@extends('layouts.app')
@section('title', $title.' - '.($site['site_name'] ?? 'Unlimited Yatra'))
@section('description', \Illuminate\Support\Str::limit($intro, 160, ''))
@section('image', $banner ?: '')
@section('content')
@include('partials.page-hero', ['heroTitle' => $title, 'heroSub' => $intro, 'heroImage' => $banner, 'crumbs' => ['Yatras' => route('yatras.index'), $title => null]])
@php $viewMode = request('view') === 'list' ? 'list' : 'grid'; @endphp

<section class="max-w-7xl mx-auto px-4 py-12 grid lg:grid-cols-4 gap-8">
    {{-- Sidebar --}}
    <aside class="lg:col-span-1 space-y-6">
        <form action="{{ route('yatras.index') }}" class="bg-white border rounded-2xl p-5 space-y-4">
            <h3 class="font-semibold">Find your yatra</h3>
            <input name="q" value="{{ request('q') }}" placeholder="Search yatra or destination" class="w-full border rounded-lg px-3 py-2 text-sm">
            <select name="duration" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Any duration</option>
                <option value="short" @selected(request('duration') === 'short')>Up to 4 days</option>
                <option value="medium" @selected(request('duration') === 'medium')>5 - 8 days</option>
                <option value="long" @selected(request('duration') === 'long')>9+ days</option>
            </select>
            @foreach (['type', 'region', 'category', 'special'] as $keep)
                @if (request()->filled($keep))<input type="hidden" name="{{ $keep }}" value="{{ request($keep) }}">@endif
            @endforeach
            <input type="hidden" name="view" value="{{ $viewMode }}">
            <button class="site-btn site-btn-primary w-full text-sm">Apply filters</button>
        </form>

        <div class="bg-white border rounded-2xl p-5">
            <h3 class="font-semibold mb-3">Destinations</h3>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('yatras.index') }}" class="hover:text-saffron-600">All Yatras</a></li>
                @foreach ($categories as $c)
                    <li class="flex justify-between"><a href="{{ route('yatras.index', ['category' => $c->slug]) }}" class="hover:text-saffron-600 {{ request('category') === $c->slug ? 'text-saffron-600 font-semibold' : '' }}">{{ $c->name }}</a><span class="text-gray-400">{{ $c->yatras_count }}</span></li>
                @endforeach
                <li><a href="{{ route('yatras.index', ['special' => 1]) }}" class="hover:text-saffron-600">⭐ Special Yatras</a></li>
            </ul>
        </div>

        <div class="bg-maroon-800 text-white rounded-2xl p-5 text-center">
            <p class="font-display text-xl">Need a tailor made yatra?</p>
            <p class="text-sm text-orange-200 mt-2">Tell us your dates and group size.</p>
            <a href="{{ route('contact') }}" class="site-btn site-btn-primary mt-4 text-sm">Enquire Now</a>
            @if (!empty($site['whatsapp']))<a href="https://wa.me/{{ $site['whatsapp'] }}" target="_blank" class="block mt-3 text-sm underline">or WhatsApp us</a>@endif
        </div>
    </aside>

    {{-- Results --}}
    <div class="lg:col-span-3">
        <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
            <p class="text-sm text-gray-500">{{ $yatras->total() }} yatra(s) found</p>
            <div class="flex flex-wrap items-center gap-3">
                <form method="GET" action="{{ route('yatras.index') }}" class="text-sm">
                    @foreach (request()->except(['sort', 'page']) as $k => $v)<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endforeach
                    <select name="sort" onchange="this.form.submit()" class="border rounded-md px-3 py-2">
                        <option value="">Sort: Recommended</option>
                        <option value="price_low" @selected(request('sort') === 'price_low')>Price: Low to High</option>
                        <option value="price_high" @selected(request('sort') === 'price_high')>Price: High to Low</option>
                        <option value="duration" @selected(request('sort') === 'duration')>Duration: Shortest</option>
                    </select>
                </form>
                <nav aria-label="Yatra result layout" class="inline-flex rounded-md border border-slate-200 bg-white p-1">
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" aria-label="Grid view" title="Grid view" aria-pressed="{{ $viewMode === 'grid' ? 'true' : 'false' }}" class="inline-flex h-9 w-10 items-center justify-center rounded {{ $viewMode === 'grid' ? 'bg-[#173d4d] text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M2 2h7v7H2V2Zm9 0h7v7h-7V2ZM2 11h7v7H2v-7Zm9 0h7v7h-7v-7Z"/></svg>
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" aria-label="List view" title="List view" aria-pressed="{{ $viewMode === 'list' ? 'true' : 'false' }}" class="inline-flex h-9 w-10 items-center justify-center rounded {{ $viewMode === 'list' ? 'bg-[#173d4d] text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M2 3h2v2H2V3Zm4 0h12v2H6V3ZM2 9h2v2H2V9Zm4 0h12v2H6V9Zm-4 6h2v2H2v-2Zm4 0h12v2H6v-2Z"/></svg>
                    </a>
                </nav>
            </div>
        </div>
        <div @class(['grid sm:grid-cols-2 xl:grid-cols-3 gap-6' => $viewMode === 'grid', 'flex flex-col gap-4' => $viewMode === 'list'])>
            @forelse ($yatras as $yatra)
                @include('partials.yatra-card', ['yatra' => $yatra, 'layout' => $viewMode])
            @empty
                <p class="col-span-full text-center text-gray-500 py-10">No yatras found. Try different filters or <a class="text-saffron-600 underline" href="{{ route('contact') }}">send us an enquiry</a> - we tailor make yatras.</p>
            @endforelse
        </div>
        <div class="mt-10">{{ $yatras->links() }}</div>
    </div>
</section>
@endsection
