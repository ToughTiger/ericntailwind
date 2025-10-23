<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class LinkedInService
{
    public function getAuthUrl(User $user): string
    {
        $scopes = ['w_member_social', 'openid', 'profile',];
        $state  = (string) $user->id;

        // Use the exact value from config/services.php -> env('LINKEDIN_REDIRECT_URI')
        $redirect = config('services.linkedin.redirect');

        return sprintf(
            'https://www.linkedin.com/oauth/v2/authorization?%s',
            http_build_query([
                'response_type' => 'code',
                'client_id'     => config('services.linkedin.client_id'),
                'redirect_uri'  => $redirect,          // <— NOT route(...)
                'scope'         => implode(' ', $scopes),
                'state'         => $state,
            ])
        );
    }
    public function handleCallback(string $code, User $user): void
    {
        $redirect = config('services.linkedin.redirect');
    
        $token = Http::asForm()->post('https://www.linkedin.com/oauth/v2/accessToken', [
            'grant_type'    => 'authorization_code',
            'code'          => $code,
            'redirect_uri'  => $redirect,
            'client_id'     => config('services.linkedin.client_id'),
            'client_secret' => config('services.linkedin.client_secret'),
        ])->throw()->json();
    
        $profile = $this->getUserProfile($token['access_token']);
    
        $userId = $profile['sub'] ?? null;  // OIDC subject = member id
        $name   = $profile['name'] ?? trim(($profile['given_name'] ?? '').' '.($profile['family_name'] ?? ''));
    
        $user->update([
            'linkedin_access_token'     => $token['access_token'],
            'linkedin_refresh_token'    => $token['refresh_token'] ?? null,
            'linkedin_token_expires_at' => now()->addSeconds((int) ($token['expires_in'] ?? 0)),
            'linkedin_urn'              => isset($profile['sub']) ? 'urn:li:person:'.$profile['sub'] : null,
            'linkedin_name'             => $profile['name'] ??
                                            trim(($profile['given_name'] ?? '').' '.($profile['family_name'] ?? '')),
            // (optional) store the URN ready-to-use
            // 'linkedin_urn'           => $userId ? 'urn:li:person:'.$userId : null,
        ]);
    }

    public function getUserProfile(string $accessToken): array
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'X-Restli-Protocol-Version' => '2.0.0',
        ])->get('https://api.linkedin.com/v2/userinfo');

        return $response->json();
    }

    public function postContent(User $user, string $content, ?string $mediaPath = null, ?string $mediaType = null): array
{
    $accessToken = $user->linkedin_access_token;
    $authorUrn   = $user->linkedin_urn;

    if (!$accessToken || !$authorUrn) {
        throw new \RuntimeException('Missing LinkedIn token or URN. Reconnect first.');
    }

    $payload = [
        'author'         => $authorUrn,
        'lifecycleState' => 'PUBLISHED',
        'specificContent' => [
            'com.linkedin.ugc.ShareContent' => [
                'shareCommentary'    => ['text' => $content],
                'shareMediaCategory' => $mediaType ? strtoupper($mediaType) : 'NONE',
            ],
        ],
        'visibility' => [
            'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC',
        ],
    ];

    if ($mediaPath && $mediaType) {
        $asset = $this->uploadMedia($user, $mediaPath, $mediaType);
        $payload['specificContent']['com.linkedin.ugc.ShareContent']['media'] = [[
            'status' => 'READY',
            'media'  => $asset,
        ]];
    }

    try {
        return Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type'  => 'application/json',
            'X-Restli-Protocol-Version' => '2.0.0',
        ])->post('https://api.linkedin.com/v2/ugcPosts', $payload)->throw()->json();
    } catch (\Illuminate\Http\Client\RequestException $e) {
        \Log::error('LinkedIn post failed', [
            'status' => optional($e->response)->status(),
            'body'   => optional($e->response)->json() ?? optional($e->response)->body(),
        ]);
        throw $e;
    }
}


protected function uploadMedia(User $user, string $mediaPath, string $mediaType): string
{
    if (!$user->linkedin_urn) {
        throw new \RuntimeException('Missing linkedin_urn — reconnect to LinkedIn.');
    }

    $recipe = strtolower($mediaType); // 'image' or 'video'
    if (!in_array($recipe, ['image','video'], true)) {
        throw new \InvalidArgumentException("Unsupported mediaType '{$mediaType}'. Use 'image' or 'video'.");
    }

    $bytes = \Storage::get($mediaPath);
    $mime  = \Storage::mimeType($mediaPath) ?? 'application/octet-stream';

    $register = Http::withHeaders([
        'Authorization' => "Bearer {$user->linkedin_access_token}",
        'Content-Type'  => 'application/json',
        'X-Restli-Protocol-Version' => '2.0.0',
    ])->post('https://api.linkedin.com/v2/assets?action=registerUpload', [
        'registerUploadRequest' => [
            'recipes' => ["urn:li:digitalmediaRecipe:feedshare-{$recipe}"],
            'owner'   => $user->linkedin_urn, // use URN
            'serviceRelationships' => [[
                'relationshipType' => 'OWNER',
                'identifier'       => 'urn:li:userGeneratedContent',
            ]],
        ],
    ])->throw()->json();

    $uploadUrl = data_get($register, 'value.uploadMechanism.com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest.uploadUrl');
    $asset     = data_get($register, 'value.asset');

    if (!$uploadUrl || !$asset) {
        throw new \RuntimeException('registerUpload did not return uploadUrl/asset.');
    }

    Http::withBody($bytes, $mime)->put($uploadUrl)->throw();

    return $asset;
}

}