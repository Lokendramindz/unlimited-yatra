<?php

namespace App\Http\Controllers;

use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        return view('blog.index', [
            'posts' => Post::where('is_active', true)->orderByDesc('published_at')->paginate(9),
        ]);
    }

    public function show(string $slug)
    {
        $post = Post::where('is_active', true)->where('slug', $slug)->firstOrFail();
        $recent = Post::where('is_active', true)->where('id', '!=', $post->id)->orderByDesc('published_at')->limit(4)->get();
        $popular = \App\Models\Yatra::active()->where('is_featured', true)->limit(3)->get();

        return view('blog.show', compact('post', 'recent', 'popular'));
    }
}
