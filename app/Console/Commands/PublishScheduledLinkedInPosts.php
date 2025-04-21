<?php

namespace App\Console\Commands;

use App\Models\LinkedInPost;
use Illuminate\Console\Command;

class PublishScheduledLinkedInPosts extends Command
{
    protected $signature = 'linkedin:publish-scheduled';
    protected $description = 'Publish scheduled LinkedIn posts';

    public function handle()
    {
        $posts = LinkedInPost::where('status', 'draft')
            ->where('scheduled_for', '<=', now())
            ->get();

        foreach ($posts as $post) {
            try {
                $post->publishToLinkedIn();
                $this->info("Published post ID: {$post->id}");
            } catch (\Exception $e) {
                $this->error("Failed to publish post ID: {$post->id} - {$e->getMessage()}");
            }
        }
    }
}