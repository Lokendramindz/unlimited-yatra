@extends('layouts.app')
@section('title', 'News & Blog - '.($site['site_name'] ?? 'Unlimited Yatra'))
@section('content')
@include('partials.page-hero', ['heroTitle' => 'News & Blog', 'heroSub' => 'Yatra guides, travel tips and spiritual stories.', 'crumbs' => ['Blog' => null]])
<section class="max-w-7xl mx-auto px-4 py-14">
    <div class="grid md:grid-cols-3 gap-6">
        @foreach ($posts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="group bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition">
                <img src="{{ $post->image_url }}" alt="{{ $post->title }}" loading="lazy" class="w-full aspect-[16/10] object-cover">
                <div class="p-5"><p class="text-xs text-gray-400">{{ optional($post->published_at)->format('d M Y') }}</p>
                    <h2 class="font-semibold text-lg mt-1 group-hover:text-saffron-600">{{ $post->title }}</h2>
                    <p class="text-sm text-gray-500 mt-2">{{ $post->excerpt }}</p><span class="inline-block mt-3 text-saffron-600 text-sm font-semibold">Read more →</span></div>
            </a>
        @endforeach
    </div>
    <div class="mt-10">{{ $posts->links() }}</div>
</section>
@endsection
