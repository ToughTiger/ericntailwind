<?php

namespace App\Http\Middleware;

use App\Models\Post;
use App\Models\PostAnalytic;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackPostViews
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track on blog post pages
        if ($request->route() && $request->route()->getName() === 'blog.show') {
            $post = $request->route('post');
            
            if ($post instanceof Post) {
                $this->trackView($request, $post);
            }
        }

        return $response;
    }

    protected function trackView(Request $request, Post $post): void
    {
        $ipAddress = $request->ip();
        $cacheKey = "post_view_{$post->id}_{$ipAddress}";

        // Check if this IP already viewed this post in the last 24 hours
        $isUniqueVisitor = !Cache::has($cacheKey);

        if ($isUniqueVisitor) {
            Cache::put($cacheKey, true, now()->addDay());
        }

        $userAgent = $request->userAgent() ?? '';
        $deviceType = $this->detectDeviceType($userAgent);
        $browser = $this->detectBrowser($userAgent);
        $platform = $this->detectPlatform($userAgent);
        $referrer = $request->header('referer');
        $trafficSource = $this->determineTrafficSource($referrer);

        PostAnalytic::create([
            'post_id' => $post->id,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'device_type' => $deviceType,
            'browser' => $browser,
            'platform' => $platform,
            'referrer_url' => $referrer,
            'traffic_source' => $trafficSource,
            'is_unique_visitor' => $isUniqueVisitor,
            'viewed_at' => now(),
        ]);
    }

    protected function detectDeviceType(string $userAgent): string
    {
        if (preg_match('/mobile|android|iphone|ipad|ipod/i', $userAgent)) {
            return preg_match('/tablet|ipad/i', $userAgent) ? 'tablet' : 'mobile';
        }
        return 'desktop';
    }

    protected function detectBrowser(string $userAgent): string
    {
        if (preg_match('/Edge/i', $userAgent)) return 'Edge';
        if (preg_match('/Chrome/i', $userAgent)) return 'Chrome';
        if (preg_match('/Safari/i', $userAgent)) return 'Safari';
        if (preg_match('/Firefox/i', $userAgent)) return 'Firefox';
        if (preg_match('/MSIE|Trident/i', $userAgent)) return 'IE';
        return 'Other';
    }

    protected function detectPlatform(string $userAgent): string
    {
        if (preg_match('/Windows/i', $userAgent)) return 'Windows';
        if (preg_match('/Mac OS/i', $userAgent)) return 'MacOS';
        if (preg_match('/Linux/i', $userAgent)) return 'Linux';
        if (preg_match('/Android/i', $userAgent)) return 'Android';
        if (preg_match('/iOS|iPhone|iPad/i', $userAgent)) return 'iOS';
        return 'Other';
    }

    protected function determineTrafficSource(?string $referrer): string
    {
        if (!$referrer) {
            return 'direct';
        }

        $socialPlatforms = ['facebook.com', 'twitter.com', 'linkedin.com', 'instagram.com', 'youtube.com', 'pinterest.com', 'reddit.com'];
        $searchEngines = ['google.com', 'bing.com', 'yahoo.com', 'duckduckgo.com', 'baidu.com'];

        foreach ($socialPlatforms as $platform) {
            if (str_contains($referrer, $platform)) {
                return 'social';
            }
        }

        foreach ($searchEngines as $engine) {
            if (str_contains($referrer, $engine)) {
                return 'search';
            }
        }

        return 'referral';
    }
}
