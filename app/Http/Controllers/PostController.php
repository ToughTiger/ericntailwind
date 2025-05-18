<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use Share;

class PostController extends Controller
{
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
     
        $posts = Post::with(['categories', 'tags'])
            ->where('is_published', 1)
            ->orderBy('created_at', 'desc')
            ->simplePaginate(6);
        return view('blog.blogPost', ['posts' => $posts]);
    }
    public function singlePost($slug): \Illuminate\Contracts\View\View
    {     

        $post = Post::where('slug', $slug)
            ->with(['categories', 'tags', ])->first();
            // ->with(['categories', 'tags', 'comments', 'comments.replies'])->first(); for future implementation

              $share_buttons = \Share::page('https://ericsolutions.com/posts/'.$post->slug, $post->title)
            ->facebook()
            ->twitter()
            ->linkedin()
            ->whatsapp()
            ->telegram()
            ->reddit();
        $post['share_buttons'] = $share_buttons;
        $posts = Post::orderBy('published_at', 'desc')->limit(5)->get();

        // $comments = Comment::where('post_id', $post->id)->orderBy('created_at', 'desc')->get();
//        dd($comments->count());
        $tags = Tag::orderBy('created_at')->limit(5)->get();
        $categories = Category::orderBy('created_at')->limit(5)->get();
        $meta = [
            'title' => $post->title,
            'keywords' => $post->keywords,
            'description' => $post->meta_description,
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
            'author' => $post->author->name,
            'author_id' => $post->author->id,
            'image' => $post->image,
            'categories' => $post->category,

        ];
//        dd($post);

        return view('blog.singlePost', ['post' => $post, 'tags' => $tags, 'meta' =>$meta,  'categories' => $categories, 'posts' => $posts]);
    }


}
