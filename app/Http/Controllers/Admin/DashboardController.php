<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Enquiry, Post, Subscriber, Yatra};

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'stats' => [
                'Total Yatras' => Yatra::count(),
                'New Enquiries' => Enquiry::where('status', 'new')->count(),
                'All Enquiries' => Enquiry::count(),
                'Blog Posts' => Post::count(),
                'Subscribers' => Subscriber::count(),
            ],
            'latest' => Enquiry::with('yatra')->latest()->limit(8)->get(),
        ]);
    }
}
