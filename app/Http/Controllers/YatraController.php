<?php

namespace App\Http\Controllers;

use App\Models\{Category, Yatra};
use Illuminate\Http\Request;

class YatraController extends Controller
{
    public function index(Request $request)
    {
        $query = Yatra::active()->with('category')->withApprovedReviewSummary();
        $title = 'All Yatras';
        $intro = 'Tailor made authentic yatras to sacred destinations across India, Nepal and Tibet.';
        $banner = null;

        if ($request->filled('type')) {          // domestic | international
            $query->whereHas('category', fn ($q) => $q->where('type', $request->type));
            $title = $request->type === 'international' ? 'International Yatras' : 'Indian Yatras';
        }
        if ($request->filled('region')) {        // east | west | north | south
            $query->whereHas('category', fn ($q) => $q->where('region', $request->region));
            $title = ucfirst($request->region).' India Yatras';
        }
        if ($request->filled('category')) {
            $cat = Category::where('slug', $request->category)->firstOrFail();
            $query->where('category_id', $cat->id);
            $title = $cat->name;
            $intro = $cat->description ?: $intro;
            $banner = $cat->image_url;
        }
        if ($request->boolean('special')) {
            $query->where('is_special', true);
            $title = 'Special Yatras';
            $intro = 'Jain Tirth, Buddhist circuits, monasteries, Nav Durga, Vaishno Devi, Hemkund Sahib, Ganga Sagar and more.';
        }
        if ($request->filled('q')) {
            $term = '%'.$request->q.'%';
            $query->where(fn ($q) => $q->where('title', 'like', $term)->orWhere('destination', 'like', $term));
            $title = 'Search: '.$request->q;
        }
        if ($request->filled('duration')) {
            match ($request->duration) {
                'short' => $query->where('duration_days', '<=', 4),
                'medium' => $query->whereBetween('duration_days', [5, 8]),
                'long' => $query->where('duration_days', '>=', 9),
                default => null,
            };
        }

        match ($request->get('sort')) {
            'price_low' => $query->orderBy('price'),
            'price_high' => $query->orderByDesc('price'),
            'duration' => $query->orderBy('duration_days'),
            default => $query->orderBy('sort_order')->orderByDesc('id'),
        };

        return view('yatras.index', [
            'title' => $title,
            'intro' => $intro,
            'banner' => $banner,
            'categories' => Category::orderBy('sort_order')->withCount('yatras')->get(),
            'yatras' => $query->paginate(12)->withQueryString(),
        ]);
    }

    public function show(string $slug)
    {
        $yatra = Yatra::active()->with('category')->where('slug', $slug)->firstOrFail();
        $related = Yatra::active()->withApprovedReviewSummary()->where('id', '!=', $yatra->id)
            ->where('category_id', $yatra->category_id)->limit(3)->get();
        $yatraOptions = Yatra::active()->orderBy('title')->pluck('title', 'id');
        $reviewQuery = $yatra->reviews()->approved();
        $reviewCount = (clone $reviewQuery)->count();
        $reviewAverage = round((clone $reviewQuery)->avg('rating') ?? 0, 1);
        $reviews = $reviewQuery->orderByDesc('is_featured')->orderByDesc('rating')->latest()->limit(6)->get();

        return view('yatras.show', compact('yatra', 'related', 'yatraOptions', 'reviews', 'reviewCount', 'reviewAverage'));
    }
}
