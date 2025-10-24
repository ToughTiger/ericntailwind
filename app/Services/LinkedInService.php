<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class LinkedInService
{
    public function getAuthUrl(User $user): string
    {
        // $scopes = ['w_member_social', 'openid', 'profile',];
        $scopes = [
            'openid',          // OIDC id token, lets you call /userinfo
            'profile',         // OIDC profile claims
            'email',           // OIDC email claim (or use r_emailaddress + endpoint)
            'r_liteprofile',   // legacy profile endpoint /v2/me
            'r_emailaddress',  // legacy email endpoint /v2/emailAddress
            'w_member_social', // posting
            'offline_access',  // refresh token
        ];
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
    // public function handleCallback(string $code, User $user): void
    // {
    //     $redirect = config('services.linkedin.redirect');
    
    //     $token = Http::asForm()->post('https://www.linkedin.com/oauth/v2/accessToken', [
    //         'grant_type'    => 'authorization_code',
    //         'code'          => $code,
    //         'redirect_uri'  => $redirect,
    //         'client_id'     => config('services.linkedin.client_id'),
    //         'client_secret' => config('services.linkedin.client_secret'),
    //     ])->throw()->json();
    
    //     $profile = $this->getUserProfile($token['access_token']);
    
    //     $userId = $profile['sub'] ?? null;  // OIDC subject = member id
    //     $name   = $profile['name'] ?? trim(($profile['given_name'] ?? '').' '.($profile['family_name'] ?? ''));
    
    //     $user->update([
    //         'linkedin_access_token'     => $token['access_token'],
    //         'linkedin_refresh_token'    => $token['refresh_token'] ?? null,
    //         'linkedin_token_expires_at' => now()->addSeconds((int) ($token['expires_in'] ?? 0)),
    //         'linkedin_urn'              => isset($profile['sub']) ? 'urn:li:person:'.$profile['sub'] : null,
    //         'linkedin_name'             => $profile['name'] ??
    //                                         trim(($profile['given_name'] ?? '').' '.($profile['family_name'] ?? '')),
    //         // (optional) store the URN ready-to-use
    //         // 'linkedin_urn'           => $userId ? 'urn:li:person:'.$userId : null,
    //     ]);
    // }

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

    $accessToken = $token['access_token'];

    // OIDC userinfo (requires openid + profile [+ email])
    $userinfo = $this->getUserProfile($accessToken); // /v2/userinfo

    // Legacy endpoints for reliability & email
    $me    = $this->getMe($accessToken);             // /v2/me (r_liteprofile)
    $email = $this->getEmail($accessToken);          // /v2/emailAddress (r_emailaddress)

    $urnFromOidc = !empty($userinfo['sub']) ? 'urn:li:person:' . $userinfo['sub'] : null;
    $urnFromMe   = !empty($me['id'])        ? 'urn:li:person:' . $me['id']        : null;

    $displayName =
        $userinfo['name']
        ?? trim(($userinfo['given_name'] ?? '').' '.($userinfo['family_name'] ?? ''))
        ?? trim(($me['localizedFirstName'] ?? '').' '.($me['localizedLastName'] ?? ''));

    $user->update([
        'linkedin_access_token'     => $accessToken,
        'linkedin_refresh_token'    => $token['refresh_token'] ?? null, // needs offline_access
        'linkedin_token_expires_at' => now()->addSeconds((int) ($token['expires_in'] ?? 0)),
        'linkedin_urn'              => $urnFromOidc ?? $urnFromMe,
        'linkedin_name'             => $displayName ?: null,
        // optionally store email if you keep it in users table
        // 'email' => $email ?: $user->email,
        'linkedin_error_message'    => null,
    ]);
}

    public function getUserProfile(string $accessToken): array
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'X-Restli-Protocol-Version' => '2.0.0',
        ])->get('https://api.linkedin.com/v2/userinfo')->throw()->json();
    }

    protected function getMe(string $accessToken): array
{
    return Http::withHeaders([
        'Authorization' => "Bearer {$accessToken}",
        'X-Restli-Protocol-Version' => '2.0.0',
    ])->get('https://api.linkedin.com/v2/me')->throw()->json();
}

protected function getEmail(string $accessToken): ?string
{
    $res = Http::withHeaders([
        'Authorization' => "Bearer {$accessToken}",
        'X-Restli-Protocol-Version' => '2.0.0',
    ])->get('https://api.linkedin.com/v2/emailAddress', [
        'q' => 'members',
        'projection' => '(elements*(handle~))',
    ])->throw()->json();

    return $res['elements'][0]['handle~']['emailAddress'] ?? null;
}

//     public function postContent(User $user, string $content, ?string $mediaPath = null, ?string $mediaType = null): array
// {
//     $accessToken = $user->linkedin_access_token;
//     $authorUrn   = $user->linkedin_urn;

//     if (!$accessToken || !$authorUrn) {
//         throw new \RuntimeException('Missing LinkedIn token or URN. Reconnect first.');
//     }

//     $payload = [
//         'author'         => $authorUrn,
//         'lifecycleState' => 'PUBLISHED',
//         'specificContent' => [
//             'com.linkedin.ugc.ShareContent' => [
//                 'shareCommentary'    => ['text' => $content],
//                 'shareMediaCategory' => $mediaType ? strtoupper($mediaType) : 'NONE',
//             ],
//         ],
//         'visibility' => [
//             'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC',
//         ],
//     ];

//     if ($mediaPath && $mediaType) {
//         $asset = $this->uploadMedia($user, $mediaPath, $mediaType);
//         $payload['specificContent']['com.linkedin.ugc.ShareContent']['media'] = [[
//             'status' => 'READY',
//             'media'  => $asset,
//         ]];
//     }

//     try {
//         return Http::withHeaders([
//             'Authorization' => "Bearer {$accessToken}",
//             'Content-Type'  => 'application/json',
//             'X-Restli-Protocol-Version' => '2.0.0',
//         ])->post('https://api.linkedin.com/v2/ugcPosts', $payload)->throw()->json();
//     } catch (\Illuminate\Http\Client\RequestException $e) {
//         \Log::error('LinkedIn post failed', [
//             'status' => optional($e->response)->status(),
//             'body'   => optional($e->response)->json() ?? optional($e->response)->body(),
//         ]);
//         throw $e;
//     }
// }

public function postContent(User $user, string $content, ?string $mediaPath = null, ?string $mediaType = null): array
{
    $accessToken = $user->linkedin_access_token;
    $authorUrn   = $user->linkedin_urn;

    if (!$accessToken || !$authorUrn) {
        throw new \RuntimeException('Missing LinkedIn token or URN. Reconnect first.');
    }

    $payload = [
        'author' => $authorUrn,
        'lifecycleState' => 'PUBLISHED',
        'specificContent' => [
            'com.linkedin.ugc.ShareContent' => [
                'shareCommentary'    => ['text' => $content],
                'shareMediaCategory' => $mediaType ? strtoupper($mediaType) : 'NONE',
            ],
        ],
        'visibility' => ['com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC'],
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
        $status = optional($e->response)->status();
        $body   = optional($e->response)->json() ?? optional($e->response)->body();
        $msg    = is_array($body) ? json_encode($body) : (string) $body;

        \Log::error('LinkedIn post failed', ['status' => $status, 'body' => $body]);

        // Surface a concise message to callers:
        throw new \RuntimeException("LinkedIn API error ({$status}): " . str($msg)->limit(300));
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
    // Use the same disk that is configured on FileUpload (->disk('public'))
    $disk = \Storage::disk('public');

    if (!$disk->exists($mediaPath)) {
        throw new \RuntimeException("File not found on 'public' disk: {$mediaPath}");
    }

    // $bytes = \Storage::get($mediaPath);
    $mime  = \Storage::mimeType($mediaPath) ?? 'application/octet-stream';
    $size = $disk->size($mediaPath);  // bytes
    $stream = $disk->readStream($mediaPath);

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

    Http::withHeaders(['Content-Type' => $mime, 'Content-Length' => $size])
        ->timeout(120)                    // adjust if you upload large videos
        ->withBody($stream, $mime)        // stream the file
        ->send('PUT', $uploadUrl)
        ->throw();

    return $asset;
}

}