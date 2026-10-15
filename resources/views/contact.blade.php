@extends('layouts.app')
@section('title', 'Enquiry / Contact - '.($site['site_name'] ?? 'Unlimited Yatra'))
@section('description', 'Contact Unlimited Yatra to plan a tailor-made pilgrimage. Send your dates and travel requirements; our yatra team will get back to you.')
@section('content')
@include('partials.page-hero', ['heroTitle' => 'Enquiry / Contact Us', 'heroSub' => 'Tell us about your yatra plan - our expert will call you back shortly.', 'crumbs' => ['Contact' => null]])
<section class="max-w-7xl mx-auto px-4 py-14 grid grid-cols-1 lg:grid-cols-5 gap-10">
    <div class="min-w-0 lg:col-span-2 space-y-4">
        <div class="bg-saffron-50 rounded-2xl p-5 flex gap-4"><span class="text-3xl">📍</span><div><p class="font-semibold">Address</p><p class="text-sm text-gray-600">{{ $site['address'] ?? '' }}</p></div></div>
        <div class="bg-saffron-50 rounded-2xl p-5 flex gap-4"><span class="text-3xl">📞</span><div><p class="font-semibold">Call us</p><a class="text-sm text-gray-600" href="tel:{{ preg_replace('/\s+/', '', $site['phone'] ?? '') }}">{{ $site['phone'] ?? '' }}</a></div></div>
        <div class="bg-saffron-50 rounded-2xl p-5 flex gap-4"><span class="text-3xl">✉️</span><div><p class="font-semibold">Email</p><a class="text-sm text-gray-600" href="mailto:{{ $site['email'] ?? '' }}">{{ $site['email'] ?? '' }}</a></div></div>
        @if (!empty($site['whatsapp']))
            <a href="https://wa.me/{{ preg_replace('/\D+/', '', $site['whatsapp']) }}" target="_blank" rel="noopener" class="site-btn site-btn-whatsapp w-full">💬 Chat on WhatsApp</a>
        @endif
    </div>
    <div class="min-w-0 lg:col-span-3 bg-white shadow-xl rounded-2xl p-6 md:p-8 border">
        <h2 class="font-display text-2xl text-maroon-800 mb-4">Send us an enquiry</h2>
        @include('partials.enquiry-form', ['yatraOptions' => $yatraOptions])
    </div>
</section>
@if (!empty($site['map_embed']))
    <section class="h-80"><iframe src="{{ $site['map_embed'] }}" class="w-full h-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Our location"></iframe></section>
@endif
@endsection
