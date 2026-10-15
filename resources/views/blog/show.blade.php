@extends('layouts.app')
@section('title', $post->title.' - '.($site['site_name'] ?? 'Unlimited Yatra'))
@section('description', \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?: $post->body), 160, ''))
@section('image', $post->image_url)
@section('og_type', 'article')
@php
    $articleSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $post->title,
        'description' => \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?: $post->body), 160, ''),
        'image' => $post->image_url,
        'datePublished' => optional($post->published_at)->toAtomString(),
        'dateModified' => optional($post->updated_at)->toAtomString(),
        'mainEntityOfPage' => url()->current(),
        'author' => ['@type' => 'Organization', 'name' => $site['site_name'] ?? 'Unlimited Yatra'],
    ];
@endphp
@push('head')
<script type="application/ld+json">@json($articleSchema)</script>
@endpush
@section('content')
@include('partials.page-hero', ['heroTitle' => $post->title, 'heroSub' => optional($post->published_at)->format('d M Y'), 'heroImage' => $post->image_url, 'crumbs' => ['Blog' => route('blog.index'), $post->title => null]])
<section class="max-w-7xl mx-auto px-4 py-14 grid lg:grid-cols-3 gap-10">
    <article class="lg:col-span-2">
        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-full rounded-2xl mb-8">
        <div class="prose max-w-none leading-relaxed text-gray-700">{!! $post->body !!}</div>
        <div class="mt-8 flex gap-3 text-sm">
            <a target="_blank" rel="noopener" class="site-btn site-btn-whatsapp text-sm" href="https://wa.me/?text={{ urlencode($post->title.' '.url()->current()) }}">Share on WhatsApp</a>
            <a target="_blank" rel="noopener" class="site-btn site-btn-facebook text-sm" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}">Share on Facebook</a>
        </div>
    </article>
    <aside class="space-y-8">
        <div class="bg-white border rounded-2xl p-5">
            <h3 class="font-semibold mb-4">Recent posts</h3>
            @foreach ($recent as $r)
                <a href="{{ route('blog.show', $r->slug) }}" class="flex gap-3 mb-4 group"><img src="{{ $r->image_url }}" class="w-20 h-16 object-cover rounded-lg" alt=""><span class="text-sm group-hover:text-saffron-600">{{ $r->title }}</span></a>
            @endforeach
        </div>
        <div class="bg-saffron-50 rounded-2xl p-5">
            <h3 class="font-semibold mb-4">Popular yatras</h3>
            @foreach ($popular as $y)
                <a href="{{ route('yatras.show', $y->slug) }}" class="block mb-3 text-sm hover:text-saffron-600">🛕 {{ $y->title }} <span class="text-gray-400">· {{ $y->duration_label }}</span></a>
            @endforeach
            <a href="{{ route('contact') }}" class="site-btn site-btn-primary mt-4 w-full text-sm">Enquire Now</a>
        </div>
    </aside>
</section>
@endsection
