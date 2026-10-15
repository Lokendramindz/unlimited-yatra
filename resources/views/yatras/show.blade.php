@extends('layouts.app')
@section('title', $yatra->title.' - '.($site['site_name'] ?? 'Unlimited Yatra'))
@section('description', \Illuminate\Support\Str::limit(strip_tags($yatra->short_description ?: $yatra->description), 160, ''))
@section('image', $yatra->image_url)
@php
    $tripSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'TouristTrip',
        'name' => $yatra->title,
        'description' => \Illuminate\Support\Str::limit(strip_tags($yatra->short_description ?: $yatra->description), 160, ''),
        'url' => url()->current(),
        'image' => $yatra->image_url,
        'touristType' => 'Pilgrimage travellers',
        'itinerary' => [
            '@type' => 'ItemList',
            'itemListElement' => collect($yatra->itinerary_days)->values()->map(fn ($day, $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $day['title'] ?? 'Day '.($index + 1),
                'description' => $day['content'] ?? '',
            ])->all(),
        ],
    ];
@endphp
@push('head')
<script type="application/ld+json">@json($tripSchema)</script>
@endpush
@section('content')
@include('partials.page-hero', ['heroTitle' => $yatra->title, 'heroSub' => $yatra->short_description, 'heroImage' => $yatra->image_url,
    'crumbs' => ['Yatras' => route('yatras.index'), ($yatra->category->name ?? 'Yatra') => $yatra->category ? route('yatras.index', ['category' => $yatra->category->slug]) : null, $yatra->title => null]])

{{-- Quick facts --}}
<section class="bg-saffron-50 border-b">
    <div class="max-w-7xl mx-auto px-4 py-5 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
        <div><p class="text-gray-500">Duration</p><p class="font-semibold">⏱ {{ $yatra->duration_label }}</p></div>
        <div><p class="text-gray-500">Destination</p><p class="font-semibold">📍 {{ $yatra->destination }}</p></div>
        <div><p class="text-gray-500">Best time</p><p class="font-semibold">🗓 {{ $yatra->best_time ?: 'All year' }}</p></div>
        <div><p class="text-gray-500">Starting from</p><p class="font-semibold text-saffron-600">{{ $yatra->price ? '₹'.number_format($yatra->price).' / person' : 'On request' }}</p></div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 lg:grid-cols-3 gap-10">
    <div class="lg:col-span-2 min-w-0" x-data="{ lightbox: null }">
        <nav aria-label="Yatra details sections" class="sticky top-[76px] z-20 -mx-4 border-b border-gray-200 bg-white/95 px-4 backdrop-blur">
            <div class="flex gap-2 overflow-x-auto text-sm font-medium">
                <a href="#overview" class="shrink-0 border-b-2 border-transparent px-3 py-4 text-gray-600 hover:border-saffron-600 hover:text-saffron-700">Overview</a>
                <a href="#itinerary" class="shrink-0 border-b-2 border-transparent px-3 py-4 text-gray-600 hover:border-saffron-600 hover:text-saffron-700">Itinerary</a>
                <a href="#inclusions" class="shrink-0 border-b-2 border-transparent px-3 py-4 text-gray-600 hover:border-saffron-600 hover:text-saffron-700">Inclusions</a>
                <a href="#gallery" class="shrink-0 border-b-2 border-transparent px-3 py-4 text-gray-600 hover:border-saffron-600 hover:text-saffron-700">Gallery</a>
            </div>
        </nav>

        <section id="overview" class="scroll-mt-36 space-y-8 py-10">
            <div>
                <h2 class="font-display text-3xl text-maroon-800 mb-5">Overview</h2>
                @if ($yatra->description)<div class="prose max-w-none">{!! $yatra->description !!}</div>@endif
            </div>
            @if ($yatra->highlight_list)
                <div>
                    <h2 class="font-display text-2xl text-maroon-800 mb-4">Yatra Highlights</h2>
                    <ul class="grid sm:grid-cols-2 gap-3">
                        @foreach ($yatra->highlight_list as $h)<li class="flex gap-2 bg-saffron-50 rounded-xl px-4 py-3"><span class="text-saffron-600">✔</span>{{ $h }}</li>@endforeach
                    </ul>
                </div>
            @endif
        </section>

        <section id="itinerary" class="scroll-mt-36 border-t border-gray-200 py-10">
            <h2 class="font-display text-3xl text-maroon-800 mb-6">Day-wise Itinerary</h2>
            <div x-data="{ openDay: 1 }" class="space-y-4">
                @forelse ($yatra->itinerary_days as $day)
                    @php
                        $dayNumber = $loop->iteration;
                        $dayTitle = preg_replace('/^Day\s*\d+\s*[-:–]?\s*/i', '', trim((string) ($day['title'] ?? '')));
                        $dayTitle = $dayTitle ?: \Illuminate\Support\Str::limit(trim((string) ($day['content'] ?? '')), 80, '...');
                    @endphp
                    <article class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                        <h3>
                            <button type="button" @click="openDay = openDay === {{ $dayNumber }} ? 0 : {{ $dayNumber }}"
                                    :aria-expanded="openDay === {{ $dayNumber }}" aria-controls="itinerary-day-{{ $dayNumber }}"
                                    class="flex min-h-20 w-full items-center gap-4 px-5 py-4 text-left hover:bg-orange-50/60">
                                <span class="shrink-0 font-semibold text-saffron-700">Day {{ sprintf('%02d', $dayNumber) }}:</span>
                                <span class="min-w-0 flex-1 font-semibold text-gray-900">{{ $dayTitle ?: 'Yatra schedule' }}</span>
                                <svg class="h-5 w-5 shrink-0 transition-transform" :class="openDay === {{ $dayNumber }} ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="m4 7 6 6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </h3>
                        <div id="itinerary-day-{{ $dayNumber }}" x-show="openDay === {{ $dayNumber }}" x-cloak style="display:none" class="border-t border-orange-100 bg-orange-50/50 px-5 py-6 sm:px-8">
                            @if (!empty($day['content']))<p class="whitespace-pre-line text-sm leading-7 text-gray-600 sm:text-base">{{ $day['content'] }}</p>@else<p class="text-sm text-gray-500">Details for this day will be shared on enquiry.</p>@endif
                        </div>
                    </article>
                @empty
                    <p class="rounded-xl border border-gray-200 bg-gray-50 px-5 py-4 text-gray-600">Detailed itinerary will be shared on enquiry. Every yatra can be tailored to your group.</p>
                @endforelse
            </div>
        </section>

        <section id="inclusions" class="scroll-mt-36 border-t border-gray-200 py-10">
            <h2 class="font-display text-3xl text-maroon-800 mb-6">Inclusions & Exclusions</h2>
            <div class="grid sm:grid-cols-2 gap-8">
                <div>
                    <h3 class="font-semibold text-green-700 mb-3">Included</h3>
                    <ul class="space-y-3 text-sm">@forelse ($yatra->inclusion_list as $i)<li class="flex gap-2"><span class="text-green-600">✔</span>{{ $i }}</li>@empty<li class="text-gray-500">Details shared on enquiry.</li>@endforelse</ul>
                </div>
                <div>
                    <h3 class="font-semibold text-red-700 mb-3">Not included</h3>
                    <ul class="space-y-3 text-sm">@forelse ($yatra->exclusion_list as $i)<li class="flex gap-2"><span class="text-red-600">✘</span>{{ $i }}</li>@empty<li class="text-gray-500">Details shared on enquiry.</li>@endforelse</ul>
                </div>
            </div>
        </section>

        <section id="gallery" class="scroll-mt-36 border-t border-gray-200 py-10">
            <h2 class="font-display text-3xl text-maroon-800 mb-6">Yatra Gallery</h2>
            @php $imgs = $yatra->gallery_urls ?: [$yatra->image_url]; @endphp
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @foreach ($imgs as $u)
                    <button type="button" @click="lightbox = '{{ $u }}'" aria-label="View {{ $yatra->title }} photo {{ $loop->iteration }}" class="overflow-hidden rounded-xl text-left">
                        <img src="{{ $u }}" alt="{{ $yatra->title }}" loading="lazy" class="aspect-[4/3] w-full object-cover transition hover:scale-105">
                    </button>
                @endforeach
            </div>
        </section>

        @include('partials.reviews', [
            'reviews' => $reviews,
            'reviewCount' => $reviewCount,
            'reviewAverage' => $reviewAverage,
            'reviewAction' => route('reviews.yatras.store', $yatra->slug),
            'reviewSubject' => 'yatra',
        ])

        <div x-show="lightbox" x-cloak style="display:none" @click="lightbox = null" @keydown.escape.window="lightbox = null"
             class="fixed inset-0 z-[60] flex items-center justify-center bg-black/90 p-4"><img :src="lightbox" class="max-h-full max-w-full rounded-lg" alt="{{ $yatra->title }}"></div>
    </div>

    <aside class="min-w-0 lg:sticky lg:top-24 self-start space-y-4">
        <div class="bg-white shadow-xl rounded-2xl p-6 border">
            @if ($yatra->price)<p class="text-sm text-gray-500">Starting from</p><p class="text-3xl font-bold text-saffron-600">₹{{ number_format($yatra->price) }}<span class="text-sm text-gray-500 font-normal"> / person</span></p>@endif
            <h3 class="font-display text-xl text-maroon-800 mt-4 mb-3">Enquire about this yatra</h3>
            @include('partials.enquiry-form', ['yatraOptions' => $yatraOptions, 'yatraId' => $yatra->id])
        </div>
        @if (!empty($site['whatsapp']))
            <a href="https://wa.me/{{ $site['whatsapp'] }}?text={{ urlencode('Namaste, I am interested in: '.$yatra->title) }}" target="_blank" rel="noopener" class="block text-center bg-green-500 hover:bg-green-600 text-white py-3 rounded-xl font-semibold">💬 Chat about this yatra</a>
        @endif
    </aside>
</section>

@if ($related->count())
<section class="bg-gray-50 py-12"><div class="max-w-7xl mx-auto px-4">
    <h2 class="font-display text-2xl text-maroon-800 mb-6">You may also like</h2>
    <div class="grid sm:grid-cols-3 gap-6">@foreach ($related as $yatra) @include('partials.yatra-card', ['yatra' => $yatra]) @endforeach</div>
</div></section>
@endif
@endsection
