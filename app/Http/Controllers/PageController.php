<?php

namespace App\Http\Controllers;

use App\Models\{Faq, Feature, Fleet, Guide, Page};

class PageController extends Controller
{
    /** CMS pages: /page/{slug} (about-us, privacy-policy, terms ...) */
    public function show(string $slug)
    {
        $page = Page::where('is_active', true)->where('slug', $slug)->firstOrFail();

        return view('pages.show', [
            'page' => $page,
            'features' => $slug === 'about-us' ? Feature::where('is_active', true)->orderBy('sort_order')->get() : collect(),
        ]);
    }

    public function guides()
    {
        return view('pages.guides', ['guides' => Guide::where('is_active', true)->orderBy('sort_order')->get()]);
    }

    public function fleet()
    {
        return view('pages.fleet', ['fleets' => Fleet::where('is_active', true)->orderBy('sort_order')->get()]);
    }

    public function faqs()
    {
        return view('pages.faqs', ['faqs' => Faq::where('is_active', true)->orderBy('sort_order')->get()]);
    }
}
