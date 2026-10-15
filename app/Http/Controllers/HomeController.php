<?php

namespace App\Http\Controllers;

use App\Models\{Affiliation, Banner, Category, Faq, Feature, Fleet, Guide, Post, Review, Yatra};

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'banners' => Banner::where('is_active', true)->orderBy('sort_order')->get(),
            'categories' => Category::orderBy('sort_order')->get(),
            'featured' => Yatra::active()->withApprovedReviewSummary()->where('is_featured', true)->orderBy('sort_order')->limit(8)->get(),
            'special' => Yatra::active()->withApprovedReviewSummary()->where('is_special', true)->orderBy('sort_order')->limit(12)->get(),
            'guides' => Guide::where('is_active', true)->orderBy('sort_order')->get(),
            'posts' => Post::where('is_active', true)->orderByDesc('published_at')->limit(3)->get(),
            'faqs' => Faq::where('is_active', true)->orderBy('sort_order')->get(),
            'features' => Feature::where('is_active', true)->orderBy('sort_order')->get(),
            'fleets' => Fleet::where('is_active', true)->orderBy('sort_order')->limit(8)->get(),
            'affiliations' => Affiliation::where('is_active', true)->orderBy('sort_order')->get(),
            'testimonials' => Review::approved()->with(['yatra:id,title', 'fleet:id,name'])->orderByDesc('is_featured')->latest()->limit(6)->get(),
            'yatraOptions' => Yatra::active()->orderBy('title')->pluck('title', 'id'),
        ]);
    }
}
