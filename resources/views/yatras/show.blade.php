@extends('layouts.app')
@section('title', $yatra->title.' - '.($site['site_name'] ?? 'Unlimited Yatra'))
@section('description', $yatra->short_description)
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

<section class="max-w-7xl mx-auto px-4 py-12 grid lg:grid-cols-3 gap-10">
    <div class="lg:col-span-2" x-data="{ tab: 'overview', lightbox: null }">
        {{-- Tabs --}}
        <div class="flex gap-1 border-b overflow-x-auto text-sm font-medium">
            @foreach (['overview' => 'Overview', 'itinerary' => 'Itinerary', 'inclusions' => 'Inclusions', 'gallery' => 'Gallery'] as $k => $label)
                <button @click="tab = '{{ $k }}'" :class="tab === '{{ $k }}' ? 'border-saffron-600 text-saffron-600' : 'border-transparent text-gray-500'" class="px-5 py-3 border-b-2 whitespace-nowrap">{{ $label }}</button>
            @endforeach
        </div>

        <div class="pt-8">
            <div x-show="tab === 'overview'" class="space-y-8">
                @if ($yatra->description)<div class="prose max-w-none">{!! $yatra->description !!}</div>@endif
                @if ($yatra->highlight_list)
                    <div>
                        <h2 class="font-display text-2xl text-maroon-800 mb-4">Yatra Highlights</h2>
                        <ul class="grid sm:grid-cols-2 gap-3">
                            @foreach ($yatra->highlight_list as $h)<li class="flex gap-2 bg-saffron-50 rounded-lg px-4 py-3"><span class="text-saffron-600">✔</span>{{ $h }}</li>@endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div x-show="tab === 'itinerary'" x-cloak style="display:none">
                <h2 class="font-display text-2xl text-maroon-800 mb-4">Day wise Itinerary</h2>
                @forelse ($yatra->itinerary_list as $line)
                    <div class="flex gap-4 mb-4">
                        <div class="flex flex-col items-center"><span class="w-9 h-9 rounded-full bg-saffron-600 text-white flex items-center justify-center text-sm font-semibold">{{ $loop->iteration }}</span>@unless($loop->last)<span class="flex-1 w-px bg-saffron-400 mt-1"></span>@endunless</div>
                        <div class="bg-white border rounded-xl px-5 py-4 flex-1">{{ $line }}</div>
                    </div>
                @empty
                    <p class="text-gray-500">Detailed itinerary will be shared on enquiry - every yatra is tailor made.</p>
                @endforelse
            </div>

            <div x-show="tab === 'inclusions'" x-cloak style="display:none" class="grid sm:grid-cols-2 gap-6">
                <div>
                    <h2 class="font-display text-xl text-green-700 mb-3">Inclusions</h2>
                    <ul class="space-y-2 text-sm">@forelse ($yatra->inclusion_list as $i)<li class="flex gap-2"><span class="text-green-600">✔</span>{{ $i }}</li>@empty<li class="text-gray-500">Shared on enquiry.</li>@endforelse</ul>
                </div>
                <div>
                    <h2 class="font-display text-xl text-red-700 mb-3">Exclusions</h2>
                    <ul class="space-y-2 text-sm">@forelse ($yatra->exclusion_list as $i)<li class="flex gap-2"><span class="text-red-600">✘</span>{{ $i }}</li>@empty<li class="text-gray-500">Shared on enquiry.</li>@endforelse</ul>
                </div>
            </div>

            <div x-show="tab === 'gallery'" x-cloak style="display:none">
                @php $imgs = $yatra->gallery_urls ?: [$yatra->image_url]; @endphp
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach ($imgs as $u)
                        <img src="{{ $u }}" alt="{{ $yatra->title }}" loading="lazy" @click="lightbox = '{{ $u }}'" class="w-full aspect-[4/3] object-cover rounded-xl cursor-zoom-in hover:opacity-90">
                    @endforeach
                </div>
            </div>
        </div>

        <div x-show="lightbox" x-cloak style="display:none" @click="lightbox = null" @keydown.escape.window="lightbox = null"
             class="fixed inset-0 z-[60] bg-black/90 flex items-center justify-center p-4"><img :src="lightbox" class="max-h-full max-w-full rounded-lg" alt=""></div>
    </div>

    <aside class="lg:sticky lg:top-24 self-start space-y-4">
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
