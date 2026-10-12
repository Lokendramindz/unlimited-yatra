<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', ($site['site_name'] ?? 'Unlimited Yatra').' - '.($site['tagline'] ?? 'Tailor made Authentic Yatras Since 2006'))</title>
    <meta name="description" content="@yield('description', 'Tailor made authentic spiritual yatras across India, Nepal and Tibet since 2006.')">
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
        @keyframes marquee { from { transform: translateX(0) } to { transform: translateX(-50%) } }
        .marquee-track { display:flex; width:max-content; animation: marquee 30s linear infinite; }
        .marquee:hover .marquee-track { animation-play-state: paused; }
        .swiper-button-next, .swiper-button-prev { color:#ea580c; }
        .swiper-pagination-bullet-active { background:#ea580c; }
    </style>
</head>
<body class="font-sans text-gray-800 bg-white antialiased">

{{-- Top bar --}}
<div class="bg-maroon-900 text-orange-100 text-xs">
    <div class="max-w-7xl mx-auto px-4 py-2 flex flex-wrap justify-between gap-2">
        <span>🙏 {{ $site['tagline'] ?? 'Tailor made Authentic Yatras Since 2006' }}</span>
        <span class="flex gap-4">
            <a href="tel:{{ preg_replace('/\s+/', '', $site['phone'] ?? '') }}">📞 {{ $site['phone'] ?? '' }}</a>
            <a href="mailto:{{ $site['email'] ?? '' }}" class="hidden sm:inline">✉️ {{ $site['email'] ?? '' }}</a>
        </span>
    </div>
</div>

{{-- Header --}}
<header class="sticky top-0 z-40 bg-white/95 backdrop-blur shadow-sm" x-data="{ open:false }">
    <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
        <a href="{{ route('home') }}" class="font-display text-2xl font-bold text-maroon-800">
            🕉️ {{ $site['site_name'] ?? 'Unlimited Yatra' }}
        </a>
        <nav class="hidden lg:flex items-center gap-7 text-sm font-medium">
            <a href="{{ route('home') }}" class="hover:text-saffron-600">Home</a>
            <a href="{{ route('about') }}" class="hover:text-saffron-600">About</a>
            <div class="relative group">
                <a href="{{ route('yatras.index', ['type' => 'domestic']) }}" class="hover:text-saffron-600">Indian Yatras ▾</a>
                <div class="absolute left-0 top-full hidden group-hover:block bg-white shadow-lg rounded-lg py-2 w-48">
                    @foreach (['east' => 'East India', 'west' => 'West India', 'north' => 'North India', 'south' => 'South India'] as $r => $label)
                        <a href="{{ route('yatras.index', ['region' => $r]) }}" class="block px-4 py-2 hover:bg-saffron-50 hover:text-saffron-600">{{ $label }}</a>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('yatras.index', ['type' => 'international']) }}" class="hover:text-saffron-600">International</a>
            <a href="{{ route('yatras.index', ['special' => 1]) }}" class="hover:text-saffron-600">Special Yatras</a>
            <a href="{{ route('blog.index') }}" class="hover:text-saffron-600">Blog</a>
            <a href="{{ route('contact') }}" class="hover:text-saffron-600">Contact</a>
            <a href="{{ route('contact') }}" class="bg-saffron-600 text-white px-5 py-2 rounded-full hover:bg-saffron-700">Enquire Now</a>
        </nav>
        <button class="lg:hidden text-2xl" @click="open = !open" aria-label="Menu">☰</button>
    </div>
    <div class="lg:hidden border-t px-4 py-3 space-y-2 text-sm" x-show="open" x-cloak style="display:none">
        <a class="block" href="{{ route('home') }}">Home</a>
        <a class="block" href="{{ route('about') }}">About Us</a>
        @foreach (['east' => 'East India', 'west' => 'West India', 'north' => 'North India', 'south' => 'South India'] as $r => $label)
            <a class="block pl-3 text-gray-600" href="{{ route('yatras.index', ['region' => $r]) }}">{{ $label }} Yatras</a>
        @endforeach
        <a class="block" href="{{ route('yatras.index', ['type' => 'international']) }}">International</a>
        <a class="block" href="{{ route('yatras.index', ['special' => 1]) }}">Special Yatras</a>
        <a class="block" href="{{ route('blog.index') }}">Blog</a>
        <a class="block" href="{{ route('contact') }}">Contact / Enquiry</a>
    </div>
</header>

@if (session('success'))
    <div class="bg-green-600 text-white text-center py-3 text-sm">{{ session('success') }}</div>
@endif

<main>@yield('content')</main>

{{-- Newsletter --}}
<section class="bg-saffron-600 text-white">
    <div class="max-w-7xl mx-auto px-4 py-10 flex flex-col md:flex-row items-center justify-between gap-5">
        <div>
            <h3 class="font-display text-2xl">Subscribe to our newsletter</h3>
            <p class="text-orange-100 text-sm">Yatra dates, offers and spiritual travel stories in your inbox.</p>
        </div>
        <form action="{{ route('subscribe') }}" method="POST" class="flex w-full md:w-auto">
            @csrf
            <input type="email" name="email" required placeholder="Your email address" class="px-4 py-3 rounded-l-full text-gray-800 w-full md:w-72 focus:outline-none">
            <button class="bg-maroon-800 px-6 rounded-r-full hover:bg-maroon-900">Subscribe</button>
        </form>
    </div>
    @error('email')<p class="text-center pb-3 text-sm">{{ $message }}</p>@enderror
</section>

{{-- Footer --}}
<footer class="bg-maroon-900 text-orange-100/80 text-sm">
    <div class="max-w-7xl mx-auto px-4 py-12 grid gap-8 md:grid-cols-4">
        <div>
            <h4 class="font-display text-xl text-white mb-3">🕉️ {{ $site['site_name'] ?? 'Unlimited Yatra' }}</h4>
            <p>{{ $site['tagline'] ?? '' }}</p>
            <div class="flex gap-3 mt-4">
                @foreach (['facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube'] as $k => $label)
                    @if (!empty($site[$k]))<a href="{{ $site[$k] }}" target="_blank" rel="noopener" class="hover:text-white text-sm underline">{{ $label }}</a>@endif
                @endforeach
            </div>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3">Important Links</h4>
            <ul class="space-y-2">
                <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                <li><a href="{{ route('yatras.index') }}" class="hover:text-white">All Yatras</a></li>
                <li><a href="{{ route('yatras.index', ['special' => 1]) }}" class="hover:text-white">Special Yatras</a></li>
                <li><a href="{{ route('about') }}" class="hover:text-white">About Us</a></li>
                <li><a href="{{ route('guides') }}" class="hover:text-white">Our Guides</a></li>
                <li><a href="{{ route('fleet') }}" class="hover:text-white">Our Fleet</a></li>
                <li><a href="{{ route('faqs') }}" class="hover:text-white">FAQs</a></li>
                <li><a href="{{ route('blog.index') }}" class="hover:text-white">News & Blog</a></li>
                <li><a href="{{ route('page.show', 'privacy-policy') }}" class="hover:text-white">Privacy Policy</a></li>
                <li><a href="{{ route('page.show', 'terms-and-conditions') }}" class="hover:text-white">Terms & Conditions</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-white">Contact Us</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3">Destinations</h4>
            <ul class="space-y-2">
                @foreach ($menuCategories as $cat)
                    <li><a href="{{ route('yatras.index', ['category' => $cat->slug]) }}" class="hover:text-white">{{ $cat->name }}</a></li>
                @endforeach
            </ul>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3">Contact</h4>
            <p>📍 {{ $site['address'] ?? '' }}</p>
            <p class="mt-2">📞 {{ $site['phone'] ?? '' }}</p>
            <p class="mt-2">✉️ {{ $site['email'] ?? '' }}</p>
        </div>
    </div>
    <div class="border-t border-white/10 text-center py-4 text-xs">
        © {{ date('Y') }} {{ $site['site_name'] ?? 'Unlimited Yatra' }}. All rights reserved.
    </div>
</footer>

{{-- Floating action buttons on every page: WhatsApp / Call / Enquiry --}}
<div class="fixed bottom-5 right-4 z-50 flex flex-col gap-3">
    @if (!empty($site['whatsapp']))
        <a href="https://wa.me/{{ $site['whatsapp'] }}?text={{ urlencode('Namaste, I want to know about yatra packages.') }}" target="_blank" rel="noopener"
           class="w-14 h-14 rounded-full bg-green-500 text-white flex items-center justify-center text-2xl shadow-lg hover:scale-110 transition" title="WhatsApp us">💬</a>
    @endif
    <a href="tel:{{ preg_replace('/\s+/', '', $site['phone'] ?? '') }}" class="w-14 h-14 rounded-full bg-blue-600 text-white flex items-center justify-center text-2xl shadow-lg hover:scale-110 transition" title="Call us">📞</a>
    <a href="{{ route('contact') }}" class="px-4 h-14 rounded-full bg-saffron-600 text-white flex items-center justify-center text-sm font-semibold shadow-lg hover:scale-105 transition">Enquire Now</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11.1.14/swiper-bundle.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
@stack('scripts')
</body>
</html>
