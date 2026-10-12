@extends('layouts.app')
@section('title', $title.' - '.($site['site_name'] ?? 'Unlimited Yatra'))
@section('description', $intro)
@section('content')
@include('partials.page-hero', ['heroTitle' => $title, 'heroSub' => $intro, 'heroImage' => $banner, 'crumbs' => ['Yatras' => route('yatras.index'), $title => null]])

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
            <button class="w-full bg-saffron-600 hover:bg-saffron-700 text-white py-2 rounded-lg text-sm">Apply</button>
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
            <a href="{{ route('contact') }}" class="inline-block mt-4 bg-saffron-600 px-5 py-2 rounded-full text-sm">Enquire Now</a>
            @if (!empty($site['whatsapp']))<a href="https://wa.me/{{ $site['whatsapp'] }}" target="_blank" class="block mt-3 text-sm underline">or WhatsApp us</a>@endif
        </div>
    </aside>

    {{-- Results --}}
    <div class="lg:col-span-3">
        <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
            <p class="text-sm text-gray-500">{{ $yatras->total() }} yatra(s) found</p>
            <form method="GET" action="{{ route('yatras.index') }}" class="text-sm">
                @foreach (request()->except(['sort', 'page']) as $k => $v)<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endforeach
                <select name="sort" onchange="this.form.submit()" class="border rounded-lg px-3 py-2">
                    <option value="">Sort: Recommended</option>
                    <option value="price_low" @selected(request('sort') === 'price_low')>Price: Low to High</option>
                    <option value="price_high" @selected(request('sort') === 'price_high')>Price: High to Low</option>
                    <option value="duration" @selected(request('sort') === 'duration')>Duration: Shortest</option>
                </select>
            </form>
        </div>
        <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse ($yatras as $yatra)
                @include('partials.yatra-card', ['yatra' => $yatra])
            @empty
                <p class="col-span-full text-center text-gray-500 py-10">No yatras found. Try different filters or <a class="text-saffron-600 underline" href="{{ route('contact') }}">send us an enquiry</a> - we tailor make yatras.</p>
            @endforelse
        </div>
        <div class="mt-10">{{ $yatras->links() }}</div>
    </div>
</section>
@endsection
