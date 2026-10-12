@extends('layouts.app')
@section('title', 'Our Transport Fleet - '.($site['site_name'] ?? 'Unlimited Yatra'))
@section('content')
@include('partials.page-hero', ['heroTitle' => 'Our Transport Fleet', 'heroSub' => 'Clean, comfortable, well maintained vehicles with experienced drivers for every group size.', 'crumbs' => ['Our Fleet' => null]])
<section class="max-w-7xl mx-auto px-4 py-14">
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($fleets as $v)
            <div class="bg-white rounded-2xl overflow-hidden shadow-md">
                <img src="{{ $v->image_url }}" alt="{{ $v->name }}" loading="lazy" class="w-full aspect-[16/10] object-cover">
                <div class="p-5"><h2 class="font-semibold text-lg">{{ $v->name }}</h2><p class="text-saffron-600 text-sm">{{ $v->capacity }}</p><p class="text-sm text-gray-500 mt-2">{{ $v->description }}</p>
                    <a href="{{ route('contact') }}" class="inline-block mt-4 text-sm text-saffron-600 font-semibold">Enquire →</a></div>
            </div>
        @endforeach
    </div>
</section>
@endsection
