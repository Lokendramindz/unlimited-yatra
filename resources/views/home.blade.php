@extends('layouts.app')

@section('title', ($site['site_name'] ?? 'Unlimited Yatra').' | Spiritual Pilgrimage Tours in India, Nepal & Tibet')
@section('description', 'Plan tailor-made spiritual yatras across India, Nepal and Tibet. Explore pilgrimage tours, destinations, travel details and speak with our yatra experts.')
@section('image', $banners->first()?->image_url ?? '')
@php
    $homepageFaqSchema = [
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
<script type="application/ld+json">@json($homepageFaqSchema)</script>
@endpush

@section('content')

{{-- (1) Hero banner --}}
<section data-reveal="hero" class="bg-[#153d4d]">
    <div class="hero-frame relative mx-auto max-w-[1600px] overflow-hidden">
        <div class="swiper heroSwiper h-[min(76svh,760px)] min-h-[540px]">
            <div class="swiper-wrapper">
                @forelse ($banners as $b)
                    <div class="swiper-slide relative">
                        <img src="{{ $b->image_url }}" alt="{{ $b->title }}" class="absolute inset-0 h-full w-full object-cover" @if(!$loop->first) loading="lazy" @endif>
                        <div class="absolute inset-0 bg-gradient-to-r from-[#082b3b]/80 via-[#082b3b]/35 to-transparent"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#082b3b]/55 via-transparent to-[#082b3b]/10"></div>
                        <div class="relative mx-auto flex h-full max-w-7xl items-center px-6 pb-32 pt-8 sm:px-10 md:pb-36">
                            <div class="max-w-2xl text-white">
                                <p class="mb-4 text-xs font-semibold uppercase tracking-[0.16em] text-orange-200 sm:text-sm">{{ $site['tagline'] ?? 'Tailor made authentic yatras since 2006' }}</p>
                                <h1 class="max-w-xl font-display text-4xl font-bold leading-tight sm:text-5xl md:text-6xl">{{ $b->title }}</h1>
                                @if ($b->subtitle)<p class="mt-4 max-w-lg text-base text-white/85 sm:text-lg">{{ $b->subtitle }}</p>@endif
                                @if ($b->button_text)
                                    <a href="{{ $b->button_link ?: route('yatras.index') }}" class="site-btn site-btn-primary mt-7">{{ $b->button_text }} <span aria-hidden="true">→</span></a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide flex items-center justify-center bg-[#153d4d] text-3xl text-white font-display">Unlimited Yatra</div>
                @endforelse
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div><div class="swiper-button-prev"></div>
        </div>
        <form action="{{ route('yatras.index') }}" method="GET" class="absolute bottom-7 left-1/2 z-10 flex w-[calc(100%-2rem)] max-w-[600px] -translate-x-1/2 items-center gap-1.5 rounded-full border border-white/30 bg-white p-1.5 shadow-2xl sm:bottom-9">
            <label for="hero-destination" class="sr-only">Search by destination or yatra</label>
            <svg class="ml-3 h-5 w-5 shrink-0 text-gray-400" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.3" stroke="currentColor" stroke-width="1.8"/><path d="m15.5 15.5 4.2 4.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
            <input id="hero-destination" type="search" name="q" placeholder="Where would you like to go?" class="min-w-0 flex-1 bg-transparent px-3 py-3 text-sm text-gray-800 placeholder:text-gray-500 focus:outline-none" required>
            <button type="submit" class="site-btn site-btn-primary hero-search-button shrink-0">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.3" stroke="currentColor" stroke-width="2"/><path d="m15.5 15.5 4.2 4.2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                <span>Search</span>
            </button>
        </form>
    </div>
</section>

{{-- (2) About text + enquiry form --}}
<section data-reveal="slide-left" class="bg-saffron-50 py-16" id="enquiry">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="min-w-0">
            <p class="text-saffron-600 font-semibold uppercase tracking-wider text-sm">About us</p>
            <h2 class="font-display text-3xl md:text-4xl text-maroon-800 mt-2">{{ $site['about_title'] ?? 'Authentic spiritual journeys' }}</h2>
            <p class="mt-5 text-gray-600 leading-relaxed">{{ $site['about_text'] ?? '' }}</p>
            <div class="grid grid-cols-3 gap-4 mt-8 text-center">
                <div class="bg-white rounded-xl p-4 shadow"><p class="text-2xl font-bold text-saffron-600">2006</p><p class="text-xs text-gray-500">Since</p></div>
                <div class="bg-white rounded-xl p-4 shadow"><p class="text-2xl font-bold text-saffron-600">India</p><p class="text-xs text-gray-500">Nepal · Tibet</p></div>
                <div class="bg-white rounded-xl p-4 shadow"><p class="text-2xl font-bold text-saffron-600">100%</p><p class="text-xs text-gray-500">Tailor made</p></div>
            </div>
        </div>
        <div class="min-w-0 bg-white rounded-2xl shadow-xl p-6 md:p-8">
            <h3 class="font-display text-2xl text-maroon-800 mb-4">Plan your yatra</h3>
            @include('partials.enquiry-form', ['yatraOptions' => $yatraOptions])
        </div>
    </div>
</section>

{{-- Explore by region --}}
<section data-reveal="slide-right" class="py-14">
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
<section data-reveal="stagger" class="py-14 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <p class="text-center text-saffron-600 font-semibold uppercase tracking-wider text-sm">Yatras you can't miss</p>
        <h2 class="font-display text-3xl md:text-4xl text-maroon-800 text-center mt-2">Popular Yatras</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-10">
            @foreach ($featured as $yatra)
                @include('partials.yatra-card', ['yatra' => $yatra])
            @endforeach
        </div>
        <div class="mt-10 text-center"><a href="{{ route('yatras.index') }}" class="site-btn site-btn-secondary">View all yatras</a></div>
    </div>
</section>

{{-- (4) Special yatras carousel --}}
<section data-reveal="zoom" class="py-14">
    <div class="max-w-7xl mx-auto px-4">
        <p class="text-center text-saffron-600 font-semibold uppercase tracking-wider text-sm">Journeys beyond the ordinary</p>
        <h2 class="font-display text-3xl md:text-4xl text-maroon-800 text-center mt-2">Special Yatras</h2>
        <div class="swiper specialSwiper mt-10 pb-12">
            <div class="swiper-wrapper">
                @foreach ($special as $yatra)
                    <div class="swiper-slide h-auto">
                        <article class="group relative overflow-hidden rounded-2xl aspect-[3/4] shadow-lg">
                            <img src="{{ $yatra->image_url }}" alt="{{ $yatra->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/10 to-transparent"></div>
                            <span class="absolute top-3 left-3 bg-saffron-600 text-white text-xs px-3 py-1 rounded-full">⏱ {{ $yatra->duration_label }}</span>
                            <div class="absolute inset-x-0 bottom-0 p-5 text-white">
                                <p class="text-xs text-saffron-400 uppercase tracking-wide">{{ $yatra->special_type }}</p>
                                @if (($yatra->approved_reviews_count ?? 0) > 0)
                                    <p class="mt-2 text-sm" aria-label="{{ number_format($yatra->approved_reviews_avg_rating, 1) }} out of 5 from {{ $yatra->approved_reviews_count }} reviews">
                                        <span class="text-amber-300" aria-hidden="true">@for ($star = 1; $star <= 5; $star++){{ $star <= round($yatra->approved_reviews_avg_rating) ? '★' : '☆' }}@endfor</span>
                                        <span class="text-xs text-white/80">{{ number_format($yatra->approved_reviews_avg_rating, 1) }} ({{ $yatra->approved_reviews_count }})</span>
                                    </p>
                                @endif
                                <h3 class="font-display text-xl leading-snug mt-1">{{ $yatra->title }}</h3>
                                <p class="text-xs mt-1 text-gray-300">📍 {{ $yatra->destination }}</p>
                                <div class="mt-4 grid grid-cols-2 gap-2">
                                    <a href="{{ route('yatras.show', $yatra->slug) }}" class="site-btn site-btn-secondary px-2 text-xs sm:text-sm">View details</a>
                                    <button type="button" x-data="{}" data-yatra-id="{{ $yatra->id }}" data-yatra-title="{{ $yatra->title }}" x-on:click="$dispatch('open-yatra-enquiry', { id: $el.dataset.yatraId, title: $el.dataset.yatraTitle })" class="site-btn site-btn-primary px-2 text-xs sm:text-sm">Enquire</button>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div><div class="swiper-button-prev"></div>
        </div>
    </div>
</section>

{{-- (5) Guides / yatra leaders --}}
<section data-reveal="slide-left" class="py-14 bg-saffron-50">
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
<section data-reveal="slide-right" class="py-14">
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
<section data-reveal="stagger" class="py-14 bg-gray-50">
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

{{-- (8) Guest testimonials --}}
<section data-reveal="stagger" class="border-y border-slate-200 bg-[#f5f8f7] py-16">
    <div class="mx-auto max-w-7xl px-4">
        <p class="text-center text-sm font-semibold uppercase text-saffron-700">From our travellers</p>
        <h2 class="mt-2 text-center font-display text-3xl text-maroon-800 md:text-4xl">Stories from the journey</h2>
        <p class="mx-auto mt-3 max-w-2xl text-center text-sm leading-relaxed text-gray-600">Experiences shared by guests after their yatra with Unlimited Yatra.</p>
        @if ($testimonials->isNotEmpty())
            <div class="swiper testimonialSwiper mt-9 pb-12">
                <div class="swiper-wrapper">
                @foreach ($testimonials as $review)
                    <div class="swiper-slide h-auto">
                        <article class="flex h-full flex-col rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-lg text-amber-500" aria-label="{{ $review->rating }} out of 5 stars">@for ($star = 1; $star <= 5; $star++){{ $star <= $review->rating ? '★' : '☆' }}@endfor</span>
                                <span class="text-xs text-gray-500">{{ $review->created_at->format('M Y') }}</span>
                            </div>
                            @if ($review->title)<h3 class="mt-4 font-semibold text-gray-900">{{ $review->title }}</h3>@endif
                            <p class="mt-3 flex-1 text-sm leading-relaxed text-gray-600">{{ \Illuminate\Support\Str::limit($review->body, 240) }}</p>
                            <div class="mt-5 border-t border-slate-100 pt-4">
                                <p class="font-semibold text-gray-900">{{ $review->reviewer_name }}</p>
                                <p class="mt-1 text-xs text-saffron-700">{{ $review->yatra?->title ?? $review->fleet?->name ?? 'Unlimited Yatra guest' }}</p>
                            </div>
                        </article>
                    </div>
                @endforeach
                </div>
                <div class="swiper-pagination testimonial-pagination"></div>
                <div class="swiper-button-prev testimonial-prev" aria-label="Previous testimonial"></div>
                <div class="swiper-button-next testimonial-next" aria-label="Next testimonial"></div>
            </div>
        @else
            <div class="mx-auto mt-9 max-w-2xl rounded-lg border border-dashed border-slate-300 bg-white px-6 py-8 text-center">
                <p class="font-semibold text-gray-800">Guest stories are on their way</p>
                <p class="mt-2 text-sm leading-relaxed text-gray-600">Approved traveller reviews will appear here. We only publish feedback shared by our guests.</p>
                <a href="{{ route('yatras.index') }}" class="site-btn site-btn-secondary mt-5">Explore yatras</a>
            </div>
        @endif
    </div>
</section>

{{-- (9) Transport fleet --}}
<section data-reveal="zoom" class="border-y border-slate-100 bg-slate-50 py-16">
    <div class="max-w-7xl mx-auto px-4">
        <p class="text-center text-saffron-700 font-semibold uppercase tracking-wider text-sm">Your journey, comfortably handled</p>
        <h2 class="font-display text-3xl md:text-5xl text-[#172944] text-center mt-2">Travel India in Comfort &amp; Style</h2>
        <p class="mx-auto mt-4 max-w-2xl text-center leading-relaxed text-gray-600">Choose a vehicle that fits your group and route. Our fleet is maintained for safe, comfortable pilgrimage travel.</p>
        <div class="mt-7 flex flex-wrap justify-center gap-2 text-sm text-[#172944]">
            @foreach (['Sanitized vehicles', 'GPS enabled', 'Air conditioned', 'Experienced drivers', 'Flexible pickup & drop', 'Insured & safe'] as $benefit)
                <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 shadow-sm"><span class="text-emerald-600" aria-hidden="true">&#10003;</span>{{ $benefit }}</span>
            @endforeach
        </div>
        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($fleets as $v)
                <article class="group flex h-full flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <a href="{{ route('fleet.show', $v->slug) }}" class="relative block bg-slate-100 p-4">
                        @if ($v->is_popular)<span class="absolute left-4 top-4 z-10 rounded-full bg-[#172944] px-3 py-1 text-[10px] font-bold uppercase tracking-wide text-amber-300">Popular</span>@endif
                        <img src="{{ $v->image_url }}" alt="{{ $v->name }}" loading="lazy" class="aspect-[16/10] w-full object-contain mix-blend-multiply">
                    </a>
                    <div class="flex flex-1 flex-col p-4">
                        <h3 class="font-semibold text-lg text-gray-800 group-hover:text-saffron-700"><a href="{{ route('fleet.show', $v->slug) }}">{{ $v->name }}</a></h3>
                        <div class="mt-3 flex flex-wrap gap-2 text-xs font-semibold text-sky-800">
                            @if ($v->vehicle_class)<span class="rounded bg-slate-100 px-2.5 py-1.5">{{ $v->vehicle_class }}</span>@endif
                            @if ($v->seat_count)<span class="rounded bg-slate-100 px-2.5 py-1.5">{{ $v->seat_count }} seats</span>
                            @elseif ($v->capacity)<span class="rounded bg-slate-100 px-2.5 py-1.5">{{ $v->capacity }}</span>
                            @endif
                            @if ($v->baggage_capacity)<span class="rounded bg-slate-100 px-2.5 py-1.5">{{ $v->baggage_capacity }}</span>@endif
                        </div>
                        @if ($v->feature_list)
                            <ul class="mt-4 flex-1 space-y-2 border-t border-slate-100 pt-3 text-sm text-gray-600">
                                @foreach (array_slice($v->feature_list, 0, 3) as $feature)
                                    <li class="flex gap-2"><span class="text-emerald-600" aria-hidden="true">&#10003;</span><span>{{ $feature }}</span></li>
                                @endforeach
                            </ul>
                        @else
                            <p class="mt-4 flex-1 text-sm leading-relaxed text-gray-500">{{ $v->description }}</p>
                        @endif
                        <div class="mt-4 grid grid-cols-2 gap-2">
                            <a href="{{ route('fleet.show', $v->slug) }}" class="site-btn site-btn-secondary px-2 text-xs sm:text-sm">View details</a>
                            @if (!empty($site['whatsapp']))
                                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $site['whatsapp']) }}?text={{ urlencode('Namaste, I am interested in renting '.$v->name.' ('.$v->seat_count.' seats, '.$v->baggage_capacity.'). Please share availability and a quote for my route.') }}" target="_blank" rel="noopener" class="site-btn site-btn-whatsapp px-2 text-xs sm:text-sm">WhatsApp</a>
                            @endif
                        </div>
                    </div>
                </article>
                @endforeach
        </div>
        <div class="mt-10 text-center"><a href="{{ route('fleet') }}" class="site-btn site-btn-secondary">View all vehicles</a></div>
    </div>
</section>

{{-- (9) Recognition & affiliations (running logos) --}}
<section data-reveal="slide-right" class="py-10 bg-white border-y">
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
    if (document.querySelector('.testimonialSwiper')) {
        new Swiper('.testimonialSwiper', { slidesPerView: 1.15, spaceBetween: 16, loop: true, autoplay: { delay: 5000, disableOnInteraction: false, pauseOnMouseEnter: true }, pagination: { el: '.testimonialSwiper .testimonial-pagination', clickable: true }, navigation: { nextEl: '.testimonial-next', prevEl: '.testimonial-prev' }, a11y: { nextSlideMessage: 'Next testimonial', prevSlideMessage: 'Previous testimonial' }, breakpoints: { 640: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } } });
    }
</script>
@endpush
