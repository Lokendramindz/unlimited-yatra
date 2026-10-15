@extends('layouts.app')
@section('title', 'Transport Rental - '.($site['site_name'] ?? 'Unlimited Yatra'))
@section('description', 'Find comfortable chauffeur-driven transport for your pilgrimage. Filter vehicles by class and passenger capacity, then request a route-specific quote.')
@section('content')
@include('partials.page-hero', ['heroTitle' => 'Transport Rental', 'heroSub' => 'Choose a comfortable vehicle for your pilgrimage route, group size and travel date.', 'crumbs' => ['Transport Rental' => null]])
@php $viewMode = request('view') === 'list' ? 'list' : 'grid'; @endphp
<section class="bg-slate-50 py-12">
    <div class="max-w-7xl mx-auto px-4">
        <form method="GET" action="{{ route('fleet') }}" class="mb-8 grid gap-4 rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:grid-cols-2 lg:grid-cols-4 lg:items-end">
            <div>
                <label for="vehicle-class-filter" class="mb-1 block text-sm font-medium text-gray-700">Vehicle class</label>
                <select id="vehicle-class-filter" name="vehicle_class" class="w-full border border-slate-300 px-3 py-2.5 focus:border-[#173d4d] focus:outline-none">
                    <option value="">All classes</option>
                    @foreach ($vehicleClasses as $class)
                        <option value="{{ $class }}" @selected(request('vehicle_class') === $class)>{{ $class }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="passenger-filter" class="mb-1 block text-sm font-medium text-gray-700">Passengers</label>
                <select id="passenger-filter" name="passengers" class="w-full border border-slate-300 px-3 py-2.5 focus:border-[#173d4d] focus:outline-none">
                    <option value="">Any group size</option>
                    @foreach ([2, 4, 6, 8, 12, 17, 35] as $seats)
                        <option value="{{ $seats }}" @selected((string) request('passengers') === (string) $seats)>{{ $seats }}+ passengers</option>
                    @endforeach
                </select>
            </div>
            <label class="flex min-h-11 items-center gap-2 text-sm font-medium text-gray-700">
                <input type="checkbox" name="popular" value="1" @checked(request()->boolean('popular')) class="h-4 w-4 accent-[#f05264]">
                Popular vehicles only
            </label>
            <input type="hidden" name="view" value="{{ $viewMode }}">
            <div class="flex flex-wrap gap-2">
                <button type="submit" class="site-btn site-btn-primary">Find vehicles</button>
                @if (request()->hasAny(['vehicle_class', 'passengers', 'popular']))
                    <a href="{{ route('fleet') }}" class="site-btn site-btn-secondary">Clear</a>
                @endif
            </div>
        </form>
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-gray-500">{{ $fleets->count() }} vehicle option(s)</p>
            <nav aria-label="Vehicle result layout" class="inline-flex rounded-md border border-slate-200 bg-white p-1">
                <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" aria-label="Grid view" title="Grid view" aria-pressed="{{ $viewMode === 'grid' ? 'true' : 'false' }}" class="inline-flex h-9 w-10 items-center justify-center rounded {{ $viewMode === 'grid' ? 'bg-[#173d4d] text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M2 2h7v7H2V2Zm9 0h7v7h-7V2ZM2 11h7v7H2v-7Zm9 0h7v7h-7v-7Z"/></svg>
                </a>
                <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" aria-label="List view" title="List view" aria-pressed="{{ $viewMode === 'list' ? 'true' : 'false' }}" class="inline-flex h-9 w-10 items-center justify-center rounded {{ $viewMode === 'list' ? 'bg-[#173d4d] text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M2 3h2v2H2V3Zm4 0h12v2H6V3ZM2 9h2v2H2V9Zm4 0h12v2H6V9Zm-4 6h2v2H2v-2Zm4 0h12v2H6v-2Z"/></svg>
                </a>
            </nav>
        </div>
        <div @class(['grid gap-5 sm:grid-cols-2 xl:grid-cols-4' => $viewMode === 'grid', 'flex flex-col gap-4' => $viewMode === 'list'])>
            @forelse ($fleets as $v)
                <article class="group flex h-full {{ $viewMode === 'list' ? 'flex-col sm:flex-row' : 'flex-col' }} overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <a href="{{ route('fleet.show', $v->slug) }}" class="relative {{ $viewMode === 'list' ? 'aspect-[16/10] sm:aspect-auto sm:h-48 sm:w-64 sm:shrink-0' : '' }} block bg-slate-100 p-4">
                        @if ($v->is_popular)<span class="absolute left-4 top-4 z-10 rounded-full bg-[#172944] px-3 py-1 text-[10px] font-bold uppercase tracking-wide text-amber-300">Popular</span>@endif
                        <img src="{{ $v->image_url }}" alt="{{ $v->name }}" loading="lazy" class="aspect-[16/10] w-full object-contain mix-blend-multiply">
                    </a>
                    <div class="flex flex-1 flex-col p-4">
                        <h2 class="font-semibold text-lg text-gray-800 group-hover:text-saffron-700"><a href="{{ route('fleet.show', $v->slug) }}">{{ $v->name }}</a></h2>
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
            @empty
                <p class="col-span-full py-12 text-center text-gray-600">Transport options will be added shortly. Contact us to discuss your travel needs.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
