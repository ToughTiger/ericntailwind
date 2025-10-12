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
        $authorUrn = $user->linkedin_urn;

        $postData = [
            'author' => $authorUrn,
            'lifecycleState' => 'PUBLISHED',
            'specificContent' => [
                'com.linkedin.ugc.ShareContent' => [
                    'shareCommentary' => [
                        'text' => $content,
                    ],
                    'shareMediaCategory' => $mediaType ? strtoupper($mediaType) : 'NONE',
                ],
            ],
            'visibility' => [
                'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC',
            ],
        ];

        if ($mediaPath && $mediaType) {
            $mediaAsset = $this->uploadMedia($user, $mediaPath, $mediaType);
            
            $postData['specificContent']['com.linkedin.ugc.ShareContent']['media'] = [
                [
                    'status' => 'READY',
                    'media' => $mediaAsset,
                ],
            ];
        }

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type' => 'application/json',
            'X-Restli-Protocol-Version' => '2.0.0',
        ])->post('https://api.linkedin.com/v2/ugcPosts', $postData);

        return $response->json();
    }

    protected function uploadMedia(User $user, string $mediaPath, string $mediaType): string
    {
        $accessToken = $user->linkedin_access_token;
        $fileContents = Storage::get($mediaPath);
        $mimeType = Storage::mimeType($mediaPath);

        // Register upload
        $registerResponse = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type' => 'application/json',
            'X-Restli-Protocol-Version' => '2.0.0',
        ])->post('https://api.linkedin.com/v2/assets?action=registerUpload', [
            'registerUploadRequest' => [
                'recipes' => [
                    "urn:li:digitalmediaRecipe:feedshare-{$mediaType}",
                ],
                'owner' => "urn:li:person:{$user->linkedin_user_id}",
                'serviceRelationships' => [
                    [
                        'relationshipType' => 'OWNER',
                        'identifier' => 'urn:li:userGeneratedContent',
                    ],
                ],
            ],
        ]);

        $uploadUrl = $registerResponse->json()['value']['uploadMechanism']['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest']['uploadUrl'];
        $asset = $registerResponse->json()['value']['asset'];

        // Upload file
        Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
        ])->withBody($fileContents, $mimeType)
          ->put($uploadUrl);

        return $asset;
    }
}