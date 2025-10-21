<?php

namespace App\Jobs;

use App\Models\LinkedInPost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\Middleware\ThrottlesExceptions;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PublishLinkedInPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int */
    public $timeout = 60; // seconds

    /** @var int */
    public $tries = 5;

    public function __construct(
        public int $postId
    ) {
        // run on a dedicated queue
        $this->onQueue('linkedin');
    }

    /**
     * Prevent two jobs from publishing the same post concurrently.
     */
    public function middleware(): array
    {
        return [
            new WithoutOverlapping("linkedin-post-{$this->postId}"),
            // Back off exponentially when LinkedIn/API/network fails
            (new ThrottlesExceptions(5, 5)), // 5 exceptions per 5 minutes
        ];
    }

    /**
     * Optional: dynamic backoff between retries.
     */
    public function backoff(): array
    {
        return [5, 15, 30, 60, 120]; // seconds
    }

    public function handle(): void
    {
        $post = LinkedInPost::with('user')->find($this->postId);

        if (!$post) {
            Log::warning('PublishLinkedInPostJob: post not found', ['post_id' => $post->postId]);
            return;
        }

        // Guard: only publish queued/draft/failed that are due
        if (!in_array($post->status, ['queued', 'draft', 'failed'], true)) {
            Log::info('PublishLinkedInPostJob: post in non-publishable status', [
                'post_id' => $post->id,
                'status'  => $post->status,
            ]);
            return;
        }

        if ($post->scheduled_for && now()->lt($post->scheduled_for)) {
            // Not due yet (another scheduler will re-dispatch when due)
            return;
        }

        $user = $post->user;
        if (!$user || !$user->linkedin_access_token) {
            $this->failWithMessage($post, 'Missing LinkedIn access token on user.');
            return;
        }

        // Build the payload for LinkedIn API.
        // NOTE: Endpoints/content type depend on the LinkedIn API you have access to.
        // This is a common "UGC Post" style payload; adjust to your app fields.
        $payload = [
            'author' => $user->linkedin_urn ?: 'urn:li:person:REPLACE_ME', // e.g. urn:li:person:abc123
            'lifecycleState' => 'PUBLISHED',
            'specificContent' => [
                'com.linkedin.ugc.ShareContent' => [
                    'shareCommentary' => [
                        'text' => $post->text ?? $post->title ?? '', // adjust to your columns
                    ],
                    'shareMediaCategory' => 'NONE', // or IMAGE, ARTICLE, etc.
                ],
            ],
            'visibility' => [
                'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC',
            ],
        ];

        try {
            // For testing in local, you might skip the real call:
            if (app()->environment('local') && config('services.linkedin.fake', true)) {
                // Simulate success
                $this->markAsPublished($post, 'fake-'.uniqid());
                return;
            }

            // Real API call (example)
            $endpoint = 'https://api.linkedin.com/v2/ugcPosts';
            $response = Http::withToken($user->linkedin_access_token)
                ->acceptJson()
                ->asJson()
                ->post($endpoint, $payload);

            if ($response->successful()) {
                $externalId = $response->json('id') ?? $response->header('x-restli-id') ?? null;
                $this->markAsPublished($post, $externalId);
                return;
            }

            if ($response->status() === 401 || $response->status() === 403) {
                // Token expired/invalid — mark as failed with message
                $this->failWithMessage($post, 'LinkedIn auth failed (invalid/expired token).');
                $this->release(60); // optional: re-try later if you plan to refresh tokens
                return;
            }

            // Rate limit / 429
            if ($response->status() === 429) {
                $retryAfter = (int)($response->header('Retry-After') ?? 60);
                Log::warning('LinkedIn rate limited', ['post_id' => $post->id, 'retry_after' => $retryAfter]);
                $this->release(max($retryAfter, 60));
                return;
            }

            // Other errors
            $this->failWithMessage($post, 'LinkedIn API error: '.$response->status().' '.$response->body());

        } catch (\Throwable $e) {
            Log::error('LinkedIn publish exception', [
                'post_id' => $post->id,
                'message' => $e->getMessage(),
            ]);
            // Will be retried according to $tries/backoff
            throw $e;
        }
    }

    protected function markAsPublished(LinkedInPost $post, ?string $externalId): void
    {
        $post->forceFill([
            'status'          => 'published',
            'published_at'    => now(),
            'external_post_id'=> $externalId,
            'last_error'      => null,
        ])->save();

        Log::info('LinkedIn post published', [
            'post_id'     => $post->id,
            'external_id' => $externalId,
        ]);
    }

    protected function failWithMessage(LinkedInPost $post, string $message): void
    {
        $post->forceFill([
            'status'     => 'failed',
            'last_error' => $message,
        ])->save();

        Log::warning('LinkedIn post failed', [
            'post_id' => $post->id,
            'error'   => $message,
        ]);
    }
}
