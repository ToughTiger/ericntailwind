<?php

namespace App\Http\Controllers;

use App\Models\BulletPoint;
use Illuminate\Http\Request;
use App\Models\Featured;
use App\Models\Post;
class FeaturedController extends Controller
{
    public function index()
    {
        $featured = Featured::where('isActive', '=', 1)->get();
       
        $featured->each(function ($item) {
            $item->bulletpoints = BulletPoint::where('featured_id', $item->id)->get();
        });
        $posts = Post::orderBy('created_at', 'desc')
        ->limit(3)
        ->with(['categories', 'tags'])
        ->where('is_published', '=', 1)
        ->get();
        // dd($posts);
        // dd($featured);
        return view('index', ['featured' => $featured, 'posts' => $posts]);
    }
}
