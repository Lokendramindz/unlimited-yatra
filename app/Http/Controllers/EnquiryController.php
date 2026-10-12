<?php

namespace App\Http\Controllers;

use App\Models\{Enquiry, Subscriber, Yatra};
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function create()
    {
        return view('contact', ['yatraOptions' => Yatra::active()->orderBy('title')->pluck('title', 'id')]);
    }

    public function store(Request $request)
    {
        // Honeypot: bots fill the hidden "website" field
        if ($request->filled('website')) {
            return back();
        }

        $data = $request->validate([
            'name' => 'required|max:120',
            'phone' => 'required|max:30',
            'email' => 'nullable|email|max:150',
            'yatra_id' => 'nullable|exists:yatras,id',
            'travellers' => 'nullable|integer|min:1|max:500',
            'travel_date' => 'nullable|date',
            'message' => 'nullable|max:2000',
        ]);

        Enquiry::create($data);

        return back()->with('success', 'Thank you! Our yatra expert will contact you shortly.');
    }

    public function subscribe(Request $request)
    {
        $data = $request->validate(['email' => 'required|email|max:150']);
        Subscriber::firstOrCreate($data);

        return back()->with('success', 'Thanks for subscribing to our newsletter!');
    }
}
