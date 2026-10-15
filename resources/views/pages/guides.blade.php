@extends('layouts.app')
@section('title', 'Our Guides & Yatra Leaders - '.($site['site_name'] ?? 'Unlimited Yatra'))
@section('description', 'Meet the experienced yatra leaders and spiritual travel guides who accompany pilgrims across India, Nepal and Tibet.')
@section('content')
@include('partials.page-hero', ['heroTitle' => 'Tour Guides & Yatra Leaders', 'heroSub' => 'Experienced, devoted people who walk with you at every step of your yatra.', 'crumbs' => ['Our Guides' => null]])
<section class="max-w-7xl mx-auto px-4 py-14">
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach ($guides as $g)
            <div class="bg-white rounded-2xl overflow-hidden shadow-md">
                <img src="{{ $g->image_url }}" alt="{{ $g->name }}" loading="lazy" class="w-full aspect-[4/5] object-cover">
                <div class="p-5 text-center"><h2 class="font-semibold text-lg">{{ $g->name }}</h2><p class="text-saffron-600 text-sm">{{ $g->designation }}</p><p class="text-sm text-gray-500 mt-2">{{ $g->bio }}</p></div>
            </div>
        @endforeach
    </div>
</section>
@endsection
