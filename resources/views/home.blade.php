@extends('layouts.app')

@section('content')

{{-- (1) Hero banner --}}
<section class="relative">
    <div class="swiper heroSwiper h-[70vh] min-h-[420px]">
        <div class="swiper-wrapper">
            @forelse ($banners as $b)
                <div class="swiper-slide relative">
                    <img src="{{ $b->image_url }}" alt="{{ $b->title }}" class="absolute inset-0 w-full h-full object-cover" @if(!$loop->first) loading="lazy" @endif>
                    <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/30 to-transparent"></div>
                    <div class="relative h-full max-w-7xl mx-auto px-4 flex items-center">
                        <div class="max-w-2xl text-white">
                            <p class="uppercase tracking-widest text-saffron-400 text-sm mb-3">{{ $site['tagline'] ?? 'Tailor made Authentic Yatras Since 2006' }}</p>
                            <h1 class="font-display text-4xl md:text-6xl font-bold leading-tight">{{ $b->title }}</h1>
                            @if ($b->subtitle)<p class="mt-4 text-lg text-gray-200">{{ $b->subtitle }}</p>@endif
                            @if ($b->button_text)
                                <a href="{{ $b->button_link ?: route('yatras.index') }}" class="inline-block mt-7 bg-saffron-600 hover:bg-saffron-700 px-8 py-3 rounded-full font-semibold">{{ $b->button_text }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="swiper-slide bg-maroon-800 flex items-center justify-center text-white text-3xl font-display">Unlimited Yatra</div>
            @endforelse
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div><div class="swiper-button-prev"></div>
    </div>
</section>

{{-- (2) About text + enquiry form --}}
<section class="bg-saffron-50 py-16" id="enquiry">
    <div class="max-w-7xl mx-auto px-4 grid lg:grid-cols-2 gap-12 items-center">
        <div>
            <p class="text-saffron-600 font-semibold uppercase tracking-wider text-sm">About us</p>
            <h2 class="font-display text-3xl md:text-4xl text-maroon-800 mt-2">{{ $site['about_title'] ?? 'Authentic spiritual journeys' }}</h2>
            <p class="mt-5 text-gray-600 leading-relaxed">{{ $site['about_text'] ?? '' }}</p>
            <div class="grid grid-cols-3 gap-4 mt-8 text-center">
                <div class="bg-white rounded-xl p-4 shadow"><p class="text-2xl font-bold text-saffron-600">2006</p><p class="text-xs text-gray-500">Since</p></div>
                <div class="bg-white rounded-xl p-4 shadow"><p class="text-2xl font-bold text-saffron-600">India</p><p class="text-xs text-gray-500">Nepal · Tibet</p></div>
                <div class="bg-white rounded-xl p-4 shadow"><p class="text-2xl font-bold text-saffron-600">100%</p><p class="text-xs text-gray-500">Tailor made</p></div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
            <h3 class="font-display text-2xl text-maroon-800 mb-4">Plan your yatra</h3>
            @include('partials.enquiry-form', ['yatraOptions' => $yatraOptions])
        </div>
    </div>
</section>

{{-- Explore by region --}}
<section class="py-14">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="font-display text-3xl text-maroon-800 text-center">Explore by Destination</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-8">
            @foreach ($categories as $cat)
                <a href="{{ route('yatras.index', ['category' => $cat->slug]) }}" class="group relative rounded-2xl overflow-hidden aspect-[4/5] shadow">
                    <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex items-end p-4">
                        <span class="text-white font-semibold">{{ $cat->name }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- (3) Popular / Featured yatras --}}
<section class="py-14 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <p class="text-center text-saffron-600 font-semibold uppercase tracking-wider text-sm">Yatras you can't miss</p>
        <h2 class="font-display text-3xl md:text-4xl text-maroon-800 text-center mt-2">Popular Yatras</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-10">
            @foreach ($featured as $yatra)
                @include('partials.yatra-card', ['yatra' => $yatra])
            @endforeach
        </div>
        <div class="text-center mt-10"><a href="{{ route('yatras.index') }}" class="inline-block border-2 border-saffron-600 text-saffron-600 hover:bg-saffron-600 hover:text-white px-8 py-3 rounded-full font-semibold transition">View all yatras</a></div>
    </div>
</section>

{{-- (4) Special yatras carousel --}}
<section class="py-14">
    <div class="max-w-7xl mx-auto px-4">
        <p class="text-center text-saffron-600 font-semibold uppercase tracking-wider text-sm">Journeys beyond the ordinary</p>
        <h2 class="font-display text-3xl md:text-4xl text-maroon-800 text-center mt-2">Special Yatras</h2>
        <div class="swiper specialSwiper mt-10 pb-12">
            <div class="swiper-wrapper">
                @foreach ($special as $yatra)
                    <div class="swiper-slide h-auto">
                        <a href="{{ route('yatras.show', $yatra->slug) }}" class="group relative block rounded-2xl overflow-hidden aspect-[3/4] shadow-lg">
                            <img src="{{ $yatra->image_url }}" alt="{{ $yatra->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/10 to-transparent"></div>
                            <span class="absolute top-3 left-3 bg-saffron-600 text-white text-xs px-3 py-1 rounded-full">⏱ {{ $yatra->duration_label }}</span>
                            <div class="absolute bottom-0 p-5 text-white">
                                <p class="text-xs text-saffron-400 uppercase tracking-wide">{{ $yatra->special_type }}</p>
                                <h3 class="font-display text-xl leading-snug mt-1">{{ $yatra->title }}</h3>
                                <p class="text-xs mt-1 text-gray-300">📍 {{ $yatra->destination }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div><div class="swiper-button-prev"></div>
        </div>
    </div>
</section>

{{-- (5) Guides / yatra leaders --}}
<section class="py-14 bg-saffron-50">
    <div class="max-w-7xl mx-auto px-4">
        <p class="text-center text-saffron-600 font-semibold uppercase tracking-wider text-sm">Our team</p>
        <h2 class="font-display text-3xl md:text-4xl text-maroon-800 text-center mt-2">Tour Guides & Yatra Leaders</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-10">
            @foreach ($guides as $g)
                <div class="bg-white rounded-2xl overflow-hidden shadow text-center">
                    <img src="{{ $g->image_url }}" alt="{{ $g->name }}" loading="lazy" class="w-full aspect-[4/5] object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold text-lg">{{ $g->name }}</h3>
                        <p class="text-saffron-600 text-sm">{{ $g->designation }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- (6) News & blogs --}}
<section class="py-14">
    <div class="max-w-7xl mx-auto px-4">
        <p class="text-center text-saffron-600 font-semibold uppercase tracking-wider text-sm">Latest updates</p>
        <h2 class="font-display text-3xl md:text-4xl text-maroon-800 text-center mt-2">Our News & Blogs</h2>
        <div class="grid md:grid-cols-3 gap-6 mt-10">
            @foreach ($posts as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="group bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition">
                    <img src="{{ $post->image_url }}" alt="{{ $post->title }}" loading="lazy" class="w-full aspect-[16/10] object-cover">
                    <div class="p-5">
                        <p class="text-xs text-gray-400">{{ optional($post->published_at)->format('d M Y') }}</p>
                        <h3 class="font-semibold text-lg mt-1 group-hover:text-saffron-600">{{ $post->title }}</h3>
                        <p class="text-sm text-gray-500 mt-2">{{ $post->excerpt }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- (7) FAQ (left) + Why travel with us (right) --}}
<section class="py-14 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 grid lg:grid-cols-2 gap-12">
        <div>
            <h2 class="font-display text-3xl text-maroon-800 mb-6">Frequently Asked Questions</h2>
            <div class="space-y-3" x-data="{ open: 0 }">
                @foreach ($faqs as $i => $faq)
                    <div class="bg-white rounded-xl shadow-sm">
                        <button class="w-full text-left px-5 py-4 font-medium flex justify-between" @click="open = open === {{ $i + 1 }} ? 0 : {{ $i + 1 }}">
                            <span>{{ $faq->question }}</span><span x-text="open === {{ $i + 1 }} ? '−' : '+'" class="text-saffron-600 text-xl">+</span>
                        </button>
                        <div x-show="open === {{ $i + 1 }}" x-cloak style="display:none" class="px-5 pb-4 text-sm text-gray-600">{{ $faq->answer }}</div>
                    </div>
                @endforeach
            </div>
        </div>
        <div>
            <h2 class="font-display text-3xl text-maroon-800 mb-6">Why Travel With Us</h2>
            <div class="grid sm:grid-cols-2 gap-4">
                @foreach ($features as $f)
                    <div class="bg-white rounded-xl p-5 shadow-sm">
                        <div class="text-3xl">{{ $f->icon }}</div>
                        <h3 class="font-semibold mt-2">{{ $f->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $f->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- (8) Transport fleet slider --}}
<section class="py-14">
    <div class="max-w-7xl mx-auto px-4">
        <p class="text-center text-saffron-600 font-semibold uppercase tracking-wider text-sm">Travel in comfort</p>
        <h2 class="font-display text-3xl md:text-4xl text-maroon-800 text-center mt-2">Our Transport Fleet</h2>
        <div class="swiper fleetSwiper mt-10 pb-12">
            <div class="swiper-wrapper">
                @foreach ($fleets as $v)
                    <div class="swiper-slide h-auto">
                        <div class="bg-white rounded-2xl overflow-hidden shadow-md h-full">
                            <img src="{{ $v->image_url }}" alt="{{ $v->name }}" loading="lazy" class="w-full aspect-[16/10] object-cover">
                            <div class="p-5">
                                <h3 class="font-semibold text-lg">{{ $v->name }}</h3>
                                <p class="text-saffron-600 text-sm">{{ $v->capacity }}</p>
                                <p class="text-sm text-gray-500 mt-2">{{ $v->description }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

{{-- (9) Recognition & affiliations (running logos) --}}
<section class="py-10 bg-white border-y">
    <p class="text-center text-sm uppercase tracking-widest text-gray-400 mb-6">Recognition & Affiliations</p>
    <div class="marquee overflow-hidden">
        <div class="marquee-track">
            @foreach ([1, 2] as $_)
                @foreach ($affiliations as $a)
                    <img src="{{ $a->image_url }}" alt="{{ $a->name }}" loading="lazy" class="h-16 w-auto mx-8 grayscale hover:grayscale-0 transition">
                @endforeach
            @endforeach
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    new Swiper('.heroSwiper', { loop: true, autoplay: { delay: 5000 }, pagination: { el: '.heroSwiper .swiper-pagination', clickable: true }, navigation: { nextEl: '.heroSwiper .swiper-button-next', prevEl: '.heroSwiper .swiper-button-prev' } });
    new Swiper('.specialSwiper', { slidesPerView: 1.2, spaceBetween: 16, autoplay: { delay: 3500, pauseOnMouseEnter: true }, pagination: { el: '.specialSwiper .swiper-pagination', clickable: true }, navigation: { nextEl: '.specialSwiper .swiper-button-next', prevEl: '.specialSwiper .swiper-button-prev' }, breakpoints: { 640: { slidesPerView: 2 }, 1024: { slidesPerView: 3 }, 1280: { slidesPerView: 4 } } });
    new Swiper('.fleetSwiper', { slidesPerView: 1.1, spaceBetween: 16, pagination: { el: '.fleetSwiper .swiper-pagination', clickable: true }, breakpoints: { 640: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } } });
</script>
@endpush
