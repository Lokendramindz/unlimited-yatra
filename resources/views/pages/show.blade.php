@extends('layouts.app')
@section('title', $page->title.' - '.($site['site_name'] ?? 'Unlimited Yatra'))
@section('content')
@include('partials.page-hero', ['heroTitle' => $page->title, 'heroSub' => $page->subtitle, 'heroImage' => $page->image ? $page->image_url : null, 'crumbs' => [$page->title => null]])

<section class="max-w-4xl mx-auto px-4 py-14">
    <div class="prose max-w-none leading-relaxed text-gray-700">{!! $page->content !!}</div>
</section>

@if ($features->count())
<section class="bg-saffron-50 py-14"><div class="max-w-7xl mx-auto px-4">
    <h2 class="font-display text-3xl text-maroon-800 text-center">Why Travel With Us</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-8">
        @foreach ($features as $f)
            <div class="bg-white rounded-xl p-6 shadow-sm text-center"><div class="text-4xl">{{ $f->icon }}</div><h3 class="font-semibold mt-3">{{ $f->title }}</h3><p class="text-sm text-gray-500 mt-1">{{ $f->description }}</p></div>
        @endforeach
    </div>
</div></section>
@endif

<section class="bg-maroon-800 text-white py-12 text-center">
    <h2 class="font-display text-3xl">Ready for your yatra?</h2>
    <p class="text-orange-200 mt-2">{{ $site['tagline'] ?? '' }}</p>
    <a href="{{ route('contact') }}" class="inline-block mt-6 bg-saffron-600 hover:bg-saffron-700 px-8 py-3 rounded-full font-semibold">Plan my yatra</a>
</section>
@endsection
