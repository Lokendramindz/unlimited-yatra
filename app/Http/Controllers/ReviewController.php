<?php

namespace App\Http\Controllers;

use App\Models\{Fleet, Review, Yatra};
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function storeYatra(Request $request, string $slug)
    {
        $yatra = Yatra::active()->where('slug', $slug)->firstOrFail();

        return $this->store($request, ['yatra_id' => $yatra->id]);
    }

    public function storeFleet(Request $request, string $slug)
    {
        $fleet = Fleet::where('is_active', true)->where('slug', $slug)->firstOrFail();

        return $this->store($request, ['fleet_id' => $fleet->id]);
    }

    private function store(Request $request, array $subject): \Illuminate\Http\RedirectResponse
    {
        if ($request->filled('website')) {
            return back();
        }

        $data = $request->validate([
            'reviewer_name' => 'required|string|max:120',
            'email' => 'nullable|email|max:150',
            'title' => 'nullable|string|max:160',
            'rating' => 'required|integer|between:1,5',
            'body' => 'required|string|min:10|max:3000',
        ]);

        Review::create($data + $subject);

        return back()->with('review_submitted', 'Thank you. Your review has been sent for approval.');
    }
}