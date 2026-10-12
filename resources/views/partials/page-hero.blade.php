{{-- Inner page banner with breadcrumb. Vars: $heroTitle, $heroSub (opt), $heroImage (opt), $crumbs = [label => url|null] --}}
<section class="relative bg-maroon-800 text-white">
    @if (!empty($heroImage))
        <img src="{{ $heroImage }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-40">
    @endif
    <div class="absolute inset-0 bg-gradient-to-r from-maroon-900/80 to-transparent"></div>
    <div class="relative max-w-7xl mx-auto px-4 py-16 md:py-20">
        <nav class="text-xs text-orange-200 mb-3 flex flex-wrap gap-2">
            <a href="{{ route('home') }}" class="hover:text-white">Home</a>
            @foreach (($crumbs ?? []) as $label => $url)
                <span>/</span>
                @if ($url)<a href="{{ $url }}" class="hover:text-white">{{ $label }}</a>@else<span class="text-white">{{ $label }}</span>@endif
            @endforeach
        </nav>
        <h1 class="font-display text-3xl md:text-5xl font-bold">{{ $heroTitle }}</h1>
        @if (!empty($heroSub))<p class="mt-3 max-w-2xl text-orange-100">{{ $heroSub }}</p>@endif
    </div>
</section>
