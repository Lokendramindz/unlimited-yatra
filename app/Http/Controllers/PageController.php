<?php

namespace App\Http\Controllers;

use App\Models\{Faq, Feature, Fleet, Guide, Page};
use Illuminate\Http\Request;

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

    public function fleet(Request $request)
    {
        $query = Fleet::where('is_active', true);

        if ($request->filled('vehicle_class')) {
            $query->where('vehicle_class', $request->string('vehicle_class')->toString());
        }
        if ($request->filled('passengers') && $request->integer('passengers') > 0) {
            $query->where('seat_count', '>=', $request->integer('passengers'));
        }
        if ($request->boolean('popular')) {
            $query->where('is_popular', true);
        }

        return view('pages.fleet', [
            'fleets' => $query->orderBy('sort_order')->orderBy('name')->get(),
            'vehicleClasses' => Fleet::where('is_active', true)->whereNotNull('vehicle_class')->distinct()->orderBy('vehicle_class')->pluck('vehicle_class'),
        ]);
    }

    public function fleetShow(string $slug)
    {
        $fleet = Fleet::where('is_active', true)->where('slug', $slug)->firstOrFail();
        $reviewQuery = $fleet->reviews()->approved();
        $reviewCount = (clone $reviewQuery)->count();
        $reviewAverage = round((clone $reviewQuery)->avg('rating') ?? 0, 1);
        $reviews = $reviewQuery->orderByDesc('is_featured')->orderByDesc('rating')->latest()->limit(6)->get();

        return view('pages.fleet-show', compact('fleet', 'reviews', 'reviewCount', 'reviewAverage'));
    }

    public function faqs()
    {
        return view('pages.faqs', ['faqs' => Faq::where('is_active', true)->orderBy('sort_order')->get()]);
    }
}
