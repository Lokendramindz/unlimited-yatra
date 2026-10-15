<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    @php
        $metaTitle = trim($__env->yieldContent('title')) ?: (($site['site_name'] ?? 'Unlimited Yatra').' - '.($site['tagline'] ?? 'Tailor made Authentic Yatras Since 2006'));
        $metaDescription = trim($__env->yieldContent('description')) ?: 'Tailor made authentic spiritual yatras across India, Nepal and Tibet since 2006.';
        $metaDescription = \Illuminate\Support\Str::limit(preg_replace('/\s+/', ' ', strip_tags($metaDescription)), 160, '');
        $metaImage = trim($__env->yieldContent('image'));
        if ($metaImage !== '' && ! preg_match('~^https?://~i', $metaImage)) {
            $metaImage = asset(ltrim($metaImage, '/'));
        }
        $canonicalUrl = trim($__env->yieldContent('canonical_url')) ?: url()->current();
        $businessSchema = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'TravelAgency',
            'name' => $site['site_name'] ?? 'Unlimited Yatra',
            'url' => url('/'),
            'description' => $site['tagline'] ?? 'Tailor made Authentic Yatras Since 2006',
            'telephone' => $site['phone'] ?? null,
            'email' => $site['email'] ?? null,
            'address' => $site['address'] ?? null,
            'sameAs' => collect(['facebook', 'instagram', 'youtube', 'twitter', 'linkedin'])
                ->map(fn ($key) => $site[$key] ?? null)
                ->filter(fn ($url) => is_string($url) && filter_var($url, FILTER_VALIDATE_URL))
                ->values()->all(),
        ], fn ($value) => $value !== null && $value !== '');
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="@yield('robots', 'index,follow')">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $site['site_name'] ?? 'Unlimited Yatra' }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    @if ($metaImage !== '')<meta property="og:image" content="{{ $metaImage }}">@endif
    <meta name="twitter:card" content="{{ $metaImage !== '' ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    @if ($metaImage !== '')<meta name="twitter:image" content="{{ $metaImage }}">@endif
    <script type="application/ld+json">@json($businessSchema)</script>
    @stack('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11.1.14/swiper-bundle.min.css">
    <script src="https://cdn.tailwindcss.com/3.4.17?plugins=typography"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: { saffron: { 50:'#fff7ed',100:'#ffedd5',400:'#fb923c',500:'#f97316',600:'#ea580c',700:'#c2410c' }, maroon: { 700:'#7f1d1d', 800:'#601414', 900:'#450a0a' } },
            fontFamily: { sans: ['Poppins','sans-serif'], display: ['"Playfair Display"','serif'] } } } }
    </script>
    <style>
        html { overflow-x:clip; }
        @keyframes marquee { from { transform: translateX(0) } to { transform: translateX(-50%) } }
        .marquee-track { display:flex; width:max-content; animation: marquee 30s linear infinite; }
        .marquee:hover .marquee-track { animation-play-state: paused; }
        .swiper-button-next, .swiper-button-prev { width:42px; height:42px; margin-top:-21px; border:1px solid rgba(23,61,77,.18); border-radius:50%; background:rgba(255,255,255,.96); color:#173d4d; box-shadow:0 5px 16px rgba(9,35,46,.2); transition:background-color .18s ease,border-color .18s ease,color .18s ease,transform .18s ease; }
        .swiper-button-next:after, .swiper-button-prev:after { font-size:14px; font-weight:700; }
        .swiper-button-next:hover, .swiper-button-prev:hover { transform:scale(1.06); border-color:#f05264; background:#f05264; color:#fff; }
        .swiper-button-next:focus-visible, .swiper-button-prev:focus-visible { outline:3px solid rgba(240,82,100,.45); outline-offset:3px; }
        .swiper-button-disabled { opacity:.45; }
        .swiper-pagination-bullet-active { background:#f05264; }
        .site-btn { display:inline-flex; min-height:44px; align-items:center; justify-content:center; gap:.5rem; padding:0 1.25rem; border:1px solid transparent; border-radius:6px; font-size:.875rem; font-weight:600; line-height:1.25; text-align:center; transition:background-color .18s ease,border-color .18s ease,color .18s ease; cursor:pointer; }
        .hero-search-button { height:48px; min-height:48px; padding:0 1rem; border-radius:10px; }
        .site-btn:focus-visible { outline:3px solid rgba(23,61,77,.28); outline-offset:2px; }
        .site-btn-primary { background:#f05264; border-color:#f05264; color:#fff; }
        .site-btn-primary:hover { background:#d83d50; border-color:#d83d50; }
        .site-btn-secondary { background:#fff; border-color:#173d4d; color:#173d4d; }
        .site-btn-secondary:hover { background:#173d4d; color:#fff; }
        .site-btn-whatsapp { background:#16834a; border-color:#16834a; color:#fff; }
        .site-btn-whatsapp:hover { background:#11683a; border-color:#11683a; }
        .site-btn-facebook { background:#1877f2; border-color:#1877f2; color:#fff; }
        .site-btn-facebook:hover { background:#125fc4; border-color:#125fc4; }
        .site-btn-subscribe { border-top-left-radius:0; border-bottom-left-radius:0; }
        .site-fab { min-height:54px; padding:.25rem .5rem; border-radius:6px; flex-direction:column; gap:.1rem; }
        .site-fab-label { display:block; font-size:10px; line-height:1.1; }
        @media (min-width:768px) {
            .site-fab { width:56px; height:56px; min-height:56px; padding:0; border-radius:9999px; font-size:1.5rem; }
            .site-fab-label { display:none; }
        }
        @media (max-width:639px) {
            .swiper-button-next, .swiper-button-prev { width:36px; height:36px; margin-top:-18px; }
            .swiper-button-next:after, .swiper-button-prev:after { font-size:12px; }
        }
        .has-reveal [data-reveal] { opacity:0; transition:opacity .65s ease,transform .65s cubic-bezier(.2,.7,.2,1); }
        .has-reveal [data-reveal="hero"] { transform:translateY(14px); }
        .has-reveal [data-reveal="slide-left"], .has-reveal [data-reveal="slide-right"] { transform:translateY(14px); }
        .has-reveal [data-reveal="zoom"] { transform:scale(.97); }
        .has-reveal [data-reveal="stagger"] { transform:translateY(18px); }
        .has-reveal [data-reveal].is-revealed { opacity:1; transform:none; }
        .has-reveal .reveal-child { opacity:0; transform:translateY(14px); transition:opacity .5s ease,transform .5s ease; transition-delay:var(--item-delay,0ms); }
        .has-reveal [data-reveal].is-revealed .reveal-child { opacity:1; transform:none; }
        @media (prefers-reduced-motion:reduce) {
            .has-reveal [data-reveal], .has-reveal .reveal-child { opacity:1; transform:none; transition:none; }
            .site-btn { transition:none; }
        }
    </style>
</head>
<body class="font-sans text-gray-800 bg-white antialiased pb-20 md:pb-0">

{{-- Utility bar --}}
<div class="bg-[#173d4d] text-white text-[11px]">
    <div class="max-w-7xl mx-auto pl-4 pr-0 flex min-h-10 items-stretch justify-between gap-3">
        <div class="flex flex-wrap items-center gap-x-5 gap-y-1 py-2">
            @if (!empty($site['phone']))
                <a href="tel:{{ preg_replace('/\s+/', '', $site['phone']) }}" class="inline-flex items-center gap-2 hover:text-white/75">
                    <span aria-hidden="true">☎</span>{{ $site['phone'] }}
                </a>
            @endif
            @if (!empty($site['email']))
                <a href="mailto:{{ $site['email'] }}" class="hidden sm:inline-flex items-center gap-2 hover:text-white/75">
                    <span aria-hidden="true">✉</span>{{ $site['email'] }}
                </a>
            @endif
        </div>
        <div class="flex shrink-0 items-center gap-3 bg-[#f05264] px-4 sm:px-6">
            <a href="{{ route('blog.index') }}" class="hidden sm:inline hover:text-white/75">Blog</a>
            <span class="hidden md:inline">Follow us on</span>
            @foreach (['facebook' => 'f', 'instagram' => '◎', 'twitter' => 'X', 'youtube' => '▶'] as $key => $mark)
                @if (!empty($site[$key]))
                    <a href="{{ $site[$key] }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($key) }}" title="{{ ucfirst($key) }}" class="flex h-6 min-w-5 items-center justify-center font-semibold hover:text-white/75">{{ $mark }}</a>
                @endif
            @endforeach
            @if (!empty($site['phone']))
                <a href="tel:{{ preg_replace('/\s+/', '', $site['phone']) }}" class="hidden sm:inline font-semibold uppercase">Call us now</a>
            @endif
        </div>
    </div>
</div>

{{-- Main navigation --}}
<header class="sticky top-0 z-40 border-b border-gray-100 bg-white shadow-sm" x-data="{ open:false }">
    <div class="max-w-7xl mx-auto px-4 h-[76px] flex items-center justify-between gap-6">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2 text-maroon-800">
            <span aria-hidden="true" class="inline-flex h-10 w-10 items-center justify-center bg-[#f05264] text-xs font-bold text-white">UY</span>
            <span class="font-display text-xl font-bold leading-tight sm:text-2xl">{{ $site['site_name'] ?? 'Unlimited Yatra' }}</span>
        </a>
        <nav class="hidden lg:flex items-center gap-6 text-[13px] font-semibold uppercase text-gray-700">
            <a href="{{ route('home') }}" class="transition hover:text-saffron-600">Home</a>
            <a href="{{ route('about') }}" class="transition hover:text-saffron-600">About us</a>
            <div class="group relative">
                <a href="{{ route('yatras.index') }}" class="inline-flex items-center gap-1 py-7 transition hover:text-saffron-600">
                    Destinations
                    <svg class="h-3.5 w-3.5 shrink-0 transition-transform duration-200 group-hover:rotate-180" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="m5 7.5 5 5 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <div class="invisible absolute left-0 top-full min-w-52 translate-y-2 border-t-2 border-saffron-500 bg-white py-2 opacity-0 shadow-xl transition group-hover:visible group-hover:translate-y-0 group-hover:opacity-100 group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:opacity-100">
                    @foreach (['east' => 'East India', 'west' => 'West India', 'north' => 'North India', 'south' => 'South India'] as $region => $label)
                        <a href="{{ route('yatras.index', ['region' => $region]) }}" class="block px-5 py-2.5 normal-case hover:bg-orange-50 hover:text-saffron-700">{{ $label }}</a>
                    @endforeach
                    <a href="{{ route('yatras.index', ['type' => 'international']) }}" class="block px-5 py-2.5 normal-case hover:bg-orange-50 hover:text-saffron-700">International</a>
                </div>
            </div>
            <a href="{{ route('yatras.index', ['special' => 1]) }}" class="transition hover:text-saffron-600">Special yatras</a>
            <a href="{{ route('fleet') }}" class="transition hover:text-saffron-600">Transport Rental</a>
            <a href="{{ route('blog.index') }}" class="transition hover:text-saffron-600">Blog</a>
            <a href="{{ route('contact') }}" class="transition hover:text-saffron-600">Contact us</a>
            <a href="{{ route('contact') }}" class="site-btn site-btn-primary text-xs">
                <span aria-hidden="true">✣</span> Plan your yatra
            </a>
        </nav>
        <button type="button" class="inline-flex h-10 w-10 items-center justify-center border border-gray-200 text-2xl text-maroon-800 lg:hidden" @click="open = !open" :aria-expanded="open" aria-label="Toggle navigation">☰</button>
    </div>
    <nav class="border-t border-gray-100 bg-white px-4 py-4 text-sm lg:hidden" x-show="open" x-cloak style="display:none">
        <div class="mx-auto max-w-7xl space-y-1">
            <a class="block py-2 font-medium" href="{{ route('home') }}">Home</a>
            <a class="block py-2 font-medium" href="{{ route('about') }}">About us</a>
            <p class="pt-2 text-[10px] font-semibold uppercase text-gray-400">Destinations</p>
            @foreach (['east' => 'East India', 'west' => 'West India', 'north' => 'North India', 'south' => 'South India'] as $region => $label)
                <a class="block py-1.5 pl-3 text-gray-600" href="{{ route('yatras.index', ['region' => $region]) }}">{{ $label }}</a>
            @endforeach
            <a class="block py-1.5 pl-3 text-gray-600" href="{{ route('yatras.index', ['type' => 'international']) }}">International</a>
            <a class="block py-2 font-medium" href="{{ route('yatras.index', ['special' => 1]) }}">Special yatras</a>
            <a class="block py-2 font-medium" href="{{ route('fleet') }}">Transport Rental</a>
            <a class="block py-2 font-medium" href="{{ route('blog.index') }}">Blog</a>
            <a class="block py-2 font-medium" href="{{ route('contact') }}">Contact us</a>
            <a class="site-btn site-btn-primary mt-2" href="{{ route('contact') }}">Plan your yatra</a>
        </div>
    </nav>
</header>

@if (session('success'))
    <div class="bg-green-600 text-white text-center py-3 text-sm">{{ session('success') }}</div>
@endif

<main>@yield('content')</main>
@include('partials.yatra-enquiry-modal')

{{-- Newsletter --}}
<section class="border-t-4 border-[#f05264] bg-[#173d4d] text-white">
    <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-5 px-4 py-9 md:flex-row md:items-center">
        <div>
            <h3 class="font-display text-2xl">Subscribe to our newsletter</h3>
            <p class="mt-1 text-sm text-slate-300">Yatra dates, offers and spiritual travel stories in your inbox.</p>
        </div>
        <form action="{{ route('subscribe') }}" method="POST" class="flex w-full max-w-md rounded-md bg-white p-1 shadow-lg md:w-auto">
            @csrf
            <input type="email" name="email" required placeholder="Your email address" class="min-w-0 flex-1 rounded-l px-4 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#f05264] md:w-72">
            <button class="site-btn site-btn-primary site-btn-subscribe">Subscribe</button>
        </form>
    </div>
    @error('email')<p class="mx-auto max-w-7xl px-4 pb-4 text-sm text-rose-200">{{ $message }}</p>@enderror
</section>

{{-- Footer --}}
<footer class="border-t-4 border-[#f05264] bg-[#102f3b] text-sm text-slate-200">
    <div class="mx-auto grid max-w-7xl grid-cols-2 gap-x-6 gap-y-8 px-4 py-10 sm:gap-x-10 sm:py-12 xl:grid-cols-4">
        <div class="col-span-2 min-w-0 xl:col-span-1">
            <h4 class="mb-4 flex items-center gap-3 font-display text-xl text-white"><span aria-hidden="true" class="inline-flex h-10 w-10 items-center justify-center rounded bg-[#f05264] text-xs font-bold font-sans">UY</span>{{ $site['site_name'] ?? 'Unlimited Yatra' }}</h4>
            <p class="max-w-xs leading-relaxed text-slate-300">{{ $site['tagline'] ?? '' }}</p>
            <div class="mt-5 flex flex-wrap gap-2">
                @foreach (['facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube', 'twitter' => 'X', 'linkedin' => 'LinkedIn', 'whatsapp' => 'WhatsApp'] as $k => $label)
                    @if (!empty($site[$k]))
                        <a href="{{ $k === 'whatsapp' ? 'https://wa.me/' . preg_replace('/\D+/', '', $site[$k]) : $site[$k] }}" target="_blank" rel="noopener" aria-label="{{ $label }}" title="{{ $label }}" class="inline-flex h-10 w-10 items-center justify-center rounded border border-white/15 text-slate-200 transition hover:border-[#f05264] hover:bg-white/10 hover:text-white">
                            @if ($k === 'facebook')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13.4 21v-8h2.7l.4-3.1h-3.1v-2c0-.9.3-1.5 1.6-1.5h1.7V3.6c-.3 0-1.3-.1-2.5-.1-2.5 0-4.2 1.5-4.2 4.3v2.1H7.3V13H10v8h3.4Z"/></svg>
                            @elseif ($k === 'instagram')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r=".8" fill="currentColor" stroke="none"/></svg>
                            @elseif ($k === 'twitter')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.9 2H22l-6.8 7.8L23.2 22h-6.3L12 14.6 5.5 22H2.4l7.3-8.4L1.9 2h6.5l4.5 6.9L18.9 2Zm-1.1 18h1.7L7.3 3.9H5.5L17.8 20Z"/></svg>
                            @elseif ($k === 'linkedin')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.45 2H3.55C2.69 2 2 2.68 2 3.52v16.96c0 .84.69 1.52 1.55 1.52h16.9c.86 0 1.55-.68 1.55-1.52V3.52c0-.84-.69-1.52-1.55-1.52ZM8.1 18.7H5.15V9.2H8.1v9.5ZM6.63 7.9a1.71 1.71 0 1 1 0-3.42 1.71 1.71 0 0 1 0 3.42Zm12.07 10.8h-2.94v-4.62c0-1.1-.02-2.52-1.54-2.52-1.54 0-1.78 1.2-1.78 2.44v4.7H9.5V9.2h2.82v1.3h.04c.39-.75 1.35-1.54 2.78-1.54 2.98 0 3.56 1.96 3.56 4.5v5.24Z"/></svg>
                            @elseif ($k === 'whatsapp')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.5 11.7a8.5 8.5 0 0 1-12.6 7.4L3 20l.9-4.7a8.5 8.5 0 1 1 16.6-3.6Z"/><path d="M8.5 8.5c.3-.7.6-.7.9-.7h.5c.2 0 .4.1.5.4l.7 1.7c.1.2.1.4-.1.6l-.5.6c-.2.2-.2.4-.1.6.6 1 1.4 1.8 2.5 2.3.2.1.4.1.6-.1l.7-.8c.2-.2.4-.3.7-.2l1.6.8c.3.1.4.3.4.5 0 .5-.3 1.2-.8 1.5-.5.4-1.2.6-2 .4-1.1-.2-2.5-.8-3.9-2.1-1.1-1-2-2.3-2.3-3.3-.3-.9-.1-1.7.3-2.2Z"/></svg>
                            @else
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.6 3.6 12 3.6 12 3.6s-7.6 0-9.4.5A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.8.5 9.4.5 9.4.5s7.6 0 9.4-.5a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8ZM9.6 15.6V8.4l6.3 3.6-6.3 3.6Z"/></svg>
                            @endif
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="min-w-0">
            <h4 class="mb-4 flex items-center gap-3 font-semibold text-white"><span aria-hidden="true" class="h-5 w-1 bg-[#f05264]"></span>Explore</h4>
            <ul class="space-y-2.5 text-slate-300">
                <li><a href="{{ route('home') }}" class="transition hover:text-amber-300">Home</a></li>
                <li><a href="{{ route('yatras.index') }}" class="transition hover:text-amber-300">All Yatras</a></li>
                <li><a href="{{ route('yatras.index', ['special' => 1]) }}" class="transition hover:text-amber-300">Special Yatras</a></li>
                <li><a href="{{ route('about') }}" class="transition hover:text-amber-300">About Us</a></li>
                <li><a href="{{ route('guides') }}" class="transition hover:text-amber-300">Our Guides</a></li>
                <li><a href="{{ route('fleet') }}" class="transition hover:text-amber-300">Transport Rental</a></li>
                <li><a href="{{ route('faqs') }}" class="transition hover:text-amber-300">FAQs</a></li>
                <li><a href="{{ route('blog.index') }}" class="transition hover:text-amber-300">News & Blog</a></li>
                <li><a href="{{ route('contact') }}" class="transition hover:text-amber-300">Contact Us</a></li>
            </ul>
        </div>
        <div class="min-w-0">
            <h4 class="mb-4 flex items-center gap-3 font-semibold text-white"><span aria-hidden="true" class="h-5 w-1 bg-[#f05264]"></span>Destinations</h4>
            <ul class="space-y-2.5 text-slate-300">
                @foreach ($menuCategories as $cat)
                    <li><a href="{{ route('yatras.index', ['category' => $cat->slug]) }}" class="transition hover:text-amber-300">{{ $cat->name }}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="col-span-2 min-w-0 xl:col-span-1">
            <h4 class="mb-4 flex items-center gap-3 font-semibold text-white"><span aria-hidden="true" class="h-5 w-1 bg-[#f05264]"></span>Get in touch</h4>
            <div class="space-y-3 text-slate-300">
                @if (!empty($site['address']))<p class="flex gap-3"><span class="text-amber-300" aria-hidden="true">⌖</span><span>{{ $site['address'] }}</span></p>@endif
                @if (!empty($site['phone']))<a class="flex gap-3 transition hover:text-amber-300" href="tel:{{ preg_replace('/\s+/', '', $site['phone']) }}"><span class="text-amber-300" aria-hidden="true">☎</span>{{ $site['phone'] }}</a>@endif
                @if (!empty($site['email']))<a class="flex gap-3 transition hover:text-amber-300" href="mailto:{{ $site['email'] }}"><span class="text-amber-300" aria-hidden="true">✉</span>{{ $site['email'] }}</a>@endif
            </div>
        </div>
    </div>
    <div class="border-t border-white/10 bg-black/10">
        <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-4 text-xs text-slate-400 sm:flex-row sm:items-center sm:justify-between">
            <p>© {{ date('Y') }} {{ $site['site_name'] ?? 'Unlimited Yatra' }}. All rights reserved.</p>
            <div class="flex flex-wrap gap-x-5 gap-y-2">
                <a href="{{ route('page.show', 'privacy-policy') }}" class="transition hover:text-white">Privacy Policy</a>
                <a href="{{ route('page.show', 'terms-and-conditions') }}" class="transition hover:text-white">Terms &amp; Conditions</a>
            </div>
        </div>
    </div>
</footer>

{{-- Floating action buttons on every page: WhatsApp / Call / Enquiry --}}
<div class="fixed inset-x-0 bottom-0 z-50 grid grid-cols-3 gap-2 border-t border-slate-200 bg-white/95 p-2 shadow-[0_-4px_18px_rgba(15,23,42,.1)] backdrop-blur md:inset-x-auto md:bottom-5 md:right-4 md:flex md:flex-col md:gap-3 md:border-0 md:bg-transparent md:p-0 md:shadow-none">
    @if (!empty($site['whatsapp']))
        <a href="https://wa.me/{{ $site['whatsapp'] }}?text={{ urlencode('Namaste, I want to know about yatra packages.') }}" target="_blank" rel="noopener"
           class="site-btn site-btn-whatsapp site-fab shadow-sm" title="WhatsApp us"><span aria-hidden="true">💬</span><span class="site-fab-label">WhatsApp</span></a>
    @endif
    <a href="tel:{{ preg_replace('/\s+/', '', $site['phone'] ?? '') }}" class="site-btn site-btn-secondary site-fab shadow-sm" title="Call us"><span aria-hidden="true">☎</span><span class="site-fab-label">Call</span></a>
    <a href="{{ route('contact') }}" class="site-btn site-btn-primary site-fab shadow-sm" title="Send an enquiry"><span aria-hidden="true">✉</span><span class="site-fab-label">Enquire</span></a>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11.1.14/swiper-bundle.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
@stack('scripts')
<script>
    (() => {
        const sections = document.querySelectorAll('[data-reveal]');
        if (!sections.length) return;

        const revealAll = () => sections.forEach((section) => section.classList.add('is-revealed'));
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) {
            revealAll();
            return;
        }

        document.documentElement.classList.add('has-reveal');
        sections.forEach((section) => {
            section.querySelectorAll('.swiper-slide, .grid > *').forEach((child, index) => {
                child.classList.add('reveal-child');
                child.style.setProperty('--item-delay', `${Math.min(index * 65, 325)}ms`);
            });
        });

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-revealed');
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.12 });
        sections.forEach((section) => observer.observe(section));
    })();
</script>
</body>
</html>
