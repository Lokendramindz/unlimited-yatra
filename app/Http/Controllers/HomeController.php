<?php

namespace App\Http\Controllers;

use App\Models\{Affiliation, Banner, Category, Faq, Feature, Fleet, Guide, Post, Yatra};

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'banners' => Banner::where('is_active', true)->orderBy('sort_order')->get(),
            'categories' => Category::orderBy('sort_order')->get(),
            'featured' => Yatra::active()->where('is_featured', true)->orderBy('sort_order')->limit(8)->get(),
            'special' => Yatra::active()->where('is_special', true)->orderBy('sort_order')->limit(12)->get(),
            'guides' => Guide::where('is_active', true)->orderBy('sort_order')->get(),
            'posts' => Post::where('is_active', true)->orderByDesc('published_at')->limit(3)->get(),
            'faqs' => Faq::where('is_active', true)->orderBy('sort_order')->get(),
            'features' => Feature::where('is_active', true)->orderBy('sort_order')->get(),
            'fleets' => Fleet::where('is_active', true)->orderBy('sort_order')->get(),
            'affiliations' => Affiliation::where('is_active', true)->orderBy('sort_order')->get(),
            'yatraOptions' => Yatra::active()->orderBy('title')->pluck('title', 'id'),
        ]);
    }
}
