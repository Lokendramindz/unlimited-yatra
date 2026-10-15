@extends('layouts.app')
@section('title', 'FAQs - '.($site['site_name'] ?? 'Unlimited Yatra'))
@section('description', 'Find answers about booking, senior-friendly travel, vegetarian meals and customised spiritual yatras with Unlimited Yatra.')
@php
    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faqs->map(fn ($faq) => [
            '@type' => 'Question',
            'name' => $faq->question,
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq->answer],
        ])->values()->all(),
    ];
@endphp
@push('head')
<script type="application/ld+json">@json($faqSchema)</script>
@endpush
@section('content')
@include('partials.page-hero', ['heroTitle' => 'Frequently Asked Questions', 'heroSub' => 'Everything you want to know before you travel with us.', 'crumbs' => ['FAQs' => null]])
<section class="max-w-3xl mx-auto px-4 py-14 space-y-3" x-data="{ open: 1 }">
    @foreach ($faqs as $i => $faq)
        <div class="bg-white rounded-xl shadow-sm border">
            <button class="w-full text-left px-5 py-4 font-medium flex justify-between" @click="open = open === {{ $i + 1 }} ? 0 : {{ $i + 1 }}">
                <span>{{ $faq->question }}</span><span x-text="open === {{ $i + 1 }} ? '−' : '+'" class="text-saffron-600 text-xl">+</span>
            </button>
            <div x-show="open === {{ $i + 1 }}" x-cloak style="display:none" class="px-5 pb-4 text-sm text-gray-600">{{ $faq->answer }}</div>
        </div>
    @endforeach
    <div class="pt-8 text-center"><p class="text-gray-500">Still have questions?</p><a href="{{ route('contact') }}" class="site-btn site-btn-primary mt-3">Ask our yatra expert</a></div>
</section>
@endsection
