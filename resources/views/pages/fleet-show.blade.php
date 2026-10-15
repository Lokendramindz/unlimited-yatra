@extends('layouts.app')
@section('title', $fleet->name.' Transport & Booking - '.($site['site_name'] ?? 'Unlimited Yatra'))
@section('description', \Illuminate\Support\Str::limit(strip_tags($fleet->description ?: $fleet->details), 160, ''))
@section('image', $fleet->image_url)
@php
    $vehicleFaqs = $fleet->faq_list ?: [
        ['question' => 'Is a driver included?', 'answer' => $fleet->driver_included ? 'Yes, an experienced driver is included with this vehicle.' : 'Driver availability can be discussed with our transport team.'],
        ['question' => 'How many passengers and bags can it carry?', 'answer' => trim(($fleet->capacity ?: ($fleet->seat_count ? $fleet->seat_count.' passengers' : 'Passenger capacity on request')).($fleet->baggage_capacity ? ' with '.$fleet->baggage_capacity : '')).'.'],
        ['question' => 'How is the rental price calculated?', 'answer' => 'We confirm a quote for your route, date, duration and expected distance before booking.'],
        ['question' => 'Are tolls, parking and overnight charges included?', 'answer' => 'Any route-specific tolls, parking or overnight charges are explained in your final trip quote.'],
    ];
    $vehicleSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => $fleet->name.' transport service',
        'description' => strip_tags($fleet->description ?: $fleet->details),
        'image' => $fleet->image_url,
        'url' => url()->current(),
        'serviceType' => 'Pilgrimage transport and vehicle booking',
        'category' => $fleet->vehicle_class,
        'provider' => ['@type' => 'TravelAgency', 'name' => $site['site_name'] ?? 'Unlimited Yatra'],
    ];
    if ($fleet->per_day_rate) {
        $vehicleSchema['offers'] = ['@type' => 'Offer', 'price' => $fleet->per_day_rate, 'priceCurrency' => 'INR'];
    }
    $vehicleFaqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => collect($vehicleFaqs)->map(fn ($faq) => [
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
        ])->values()->all(),
    ];
@endphp
@push('head')
<script type="application/ld+json">@json($vehicleSchema)</script>
<script type="application/ld+json">@json($vehicleFaqSchema)</script>
@endpush
@section('content')
@include('partials.page-hero', ['heroTitle' => $fleet->name, 'heroSub' => $fleet->description, 'heroImage' => $fleet->image_url, 'crumbs' => ['Transport Rental' => route('fleet'), $fleet->name => null]])

<section class="max-w-7xl mx-auto grid gap-10 px-4 py-12 lg:grid-cols-5">
    <div class="space-y-8 lg:col-span-3">
        <img src="{{ $fleet->image_url }}" alt="{{ $fleet->name }}" class="aspect-[16/10] w-full rounded-lg bg-slate-100 object-contain">
        @php
            $specifications = collect([
                'Vehicle class' => $fleet->vehicle_class,
                'Passenger seats' => $fleet->seat_count,
                'Luggage capacity' => $fleet->baggage_capacity,
                'Air conditioning' => $fleet->air_conditioning ? 'Included' : 'Not included',
                'Driver' => $fleet->driver_included ? 'Included' : 'On request',
                'Fuel type' => $fleet->fuel_type,
                'Transmission' => $fleet->transmission,
                'Daily distance' => $fleet->included_km_per_day ? number_format($fleet->included_km_per_day).' km included' : null,
            ])->filter(fn ($value) => $value !== null && $value !== '');
        @endphp
        <section aria-labelledby="vehicle-specifications-heading">
            <h2 id="vehicle-specifications-heading" class="font-display text-2xl text-maroon-800">Vehicle specifications</h2>
            <dl class="mt-4 grid grid-cols-2 gap-px overflow-hidden rounded-lg border border-slate-200 bg-slate-200 sm:grid-cols-3">
                @foreach ($specifications as $label => $value)
                    <div class="bg-white p-4"><dt class="text-xs uppercase tracking-wide text-gray-500">{{ $label }}</dt><dd class="mt-1 font-semibold text-gray-900">{{ $value }}</dd></div>
                @endforeach
            </dl>
            @if ($fleet->per_day_rate || $fleet->extra_km_rate)
                <div class="mt-4 border-l-4 border-[#f05264] bg-rose-50 p-4 text-sm text-gray-700">
                    @if ($fleet->per_day_rate)<p><strong>Indicative daily rate:</strong> ₹{{ number_format($fleet->per_day_rate, 0) }} per vehicle</p>@endif
                    @if ($fleet->extra_km_rate)<p class="mt-1"><strong>Extra distance:</strong> ₹{{ number_format($fleet->extra_km_rate, 2) }} per km beyond the included distance</p>@endif
                    <p class="mt-2 text-xs">Final pricing depends on route, dates and trip duration. Confirm the quote before booking.</p>
                </div>
            @endif
        </section>
        <div>
            <h2 class="font-display text-2xl text-maroon-800">About this vehicle</h2>
            @if ($fleet->details)<p class="mt-4 whitespace-pre-line leading-7 text-gray-600">{{ $fleet->details }}</p>@else<p class="mt-4 leading-7 text-gray-600">{{ $fleet->description }}</p>@endif
        </div>
        @if ($fleet->feature_list)
            <div>
                <h2 class="font-display text-2xl text-maroon-800">Features</h2>
                <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                    @foreach ($fleet->feature_list as $feature)
                        <li class="flex gap-3 border-b border-slate-200 py-3 text-gray-700"><span class="text-emerald-600" aria-hidden="true">&#10003;</span>{{ $feature }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section aria-labelledby="vehicle-faq-heading" x-data="{ openFaq: 1 }">
            <h2 id="vehicle-faq-heading" class="font-display text-2xl text-maroon-800">Vehicle rental FAQs</h2>
            <div class="mt-4 divide-y divide-slate-200 border-y border-slate-200">
                @foreach ($vehicleFaqs as $faq)
                    <article class="py-1">
                        <h3>
                            <button type="button" @click="openFaq = openFaq === {{ $loop->iteration }} ? 0 : {{ $loop->iteration }}" :aria-expanded="openFaq === {{ $loop->iteration }}" aria-controls="vehicle-faq-{{ $loop->iteration }}" class="flex min-h-14 w-full items-center justify-between gap-4 py-3 text-left font-semibold text-gray-800">
                                <span>{{ $faq['question'] }}</span>
                                <svg class="h-5 w-5 shrink-0 text-[#173d4d] transition-transform" :class="openFaq === {{ $loop->iteration }} ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="m4 7 6 6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </h3>
                        <div id="vehicle-faq-{{ $loop->iteration }}" x-show="openFaq === {{ $loop->iteration }}" x-cloak style="display:none" class="pb-4 pr-8 text-sm leading-relaxed text-gray-600">{{ $faq['answer'] }}</div>
                    </article>
                @endforeach
            </div>
        </section>

        @include('partials.reviews', [
            'reviews' => $reviews,
            'reviewCount' => $reviewCount,
            'reviewAverage' => $reviewAverage,
            'reviewAction' => route('reviews.fleet.store', $fleet->slug),
            'reviewSubject' => 'vehicle',
        ])
    </div>

    <aside class="self-start lg:sticky lg:top-24 lg:col-span-2">
        <div class="space-y-5 rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <div>
                <h2 class="font-display text-2xl text-maroon-800">Request a vehicle booking</h2>
                <p class="mt-2 text-sm text-gray-600">Share your route and travel date. Our transport team will confirm availability and pricing.</p>
            </div>
            <form action="{{ route('enquiry.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="fleet_id" value="{{ $fleet->id }}">
                <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
                <div>
                    <label for="transport-name" class="mb-1 block text-sm font-medium">Name *</label>
                    <input id="transport-name" name="name" value="{{ old('name') }}" required autocomplete="name" class="w-full border border-slate-300 px-3 py-2.5 focus:border-sky-700 focus:outline-none">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="transport-phone" class="mb-1 block text-sm font-medium">Phone / WhatsApp *</label>
                        <input id="transport-phone" name="phone" value="{{ old('phone') }}" required autocomplete="tel" class="w-full border border-slate-300 px-3 py-2.5 focus:border-sky-700 focus:outline-none">
                        @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="transport-email" class="mb-1 block text-sm font-medium">Email</label>
                        <input id="transport-email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" class="w-full border border-slate-300 px-3 py-2.5 focus:border-sky-700 focus:outline-none">
                        @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="transport-date" class="mb-1 block text-sm font-medium">Travel date</label>
                        <input id="transport-date" type="date" name="travel_date" min="{{ now()->toDateString() }}" value="{{ old('travel_date') }}" class="w-full border border-slate-300 px-3 py-2.5 focus:border-sky-700 focus:outline-none">
                        @error('travel_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="transport-guests" class="mb-1 block text-sm font-medium">Passengers</label>
                        <input id="transport-guests" type="number" min="1" max="500" name="travellers" value="{{ old('travellers') }}" class="w-full border border-slate-300 px-3 py-2.5 focus:border-sky-700 focus:outline-none">
                        @error('travellers')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label for="pickup-location" class="mb-1 block text-sm font-medium">Pickup location</label>
                    <input id="pickup-location" name="pickup_location" value="{{ old('pickup_location') }}" class="w-full border border-slate-300 px-3 py-2.5 focus:border-sky-700 focus:outline-none">
                    @error('pickup_location')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="dropoff-location" class="mb-1 block text-sm font-medium">Drop-off location</label>
                    <input id="dropoff-location" name="dropoff_location" value="{{ old('dropoff_location') }}" class="w-full border border-slate-300 px-3 py-2.5 focus:border-sky-700 focus:outline-none">
                    @error('dropoff_location')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="transport-message" class="mb-1 block text-sm font-medium">Additional trip details</label>
                    <textarea id="transport-message" name="message" rows="3" class="w-full border border-slate-300 px-3 py-2.5 focus:border-sky-700 focus:outline-none">{{ old('message') }}</textarea>
                    @error('message')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="site-btn site-btn-primary w-full">Send booking enquiry</button>
            </form>
            <div class="flex flex-wrap gap-4 border-t border-slate-200 pt-4 text-sm">
                @if (!empty($site['phone']))<a class="site-btn site-btn-secondary text-sm" href="tel:{{ preg_replace('/\s+/', '', $site['phone']) }}">Call us</a>@endif
                @if (!empty($site['whatsapp']))<a class="site-btn site-btn-whatsapp text-sm" href="https://wa.me/{{ preg_replace('/\D+/', '', $site['whatsapp']) }}?text={{ urlencode('Namaste, I am interested in booking '.$fleet->name) }}" target="_blank" rel="noopener">WhatsApp</a>@endif
            </div>
        </div>
    </aside>
</section>
@endsection