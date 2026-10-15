@php $isList = ($layout ?? 'grid') === 'list'; @endphp
<article class="group {{ $isList ? 'flex flex-col sm:flex-row' : 'flex flex-col' }} h-full overflow-hidden rounded-lg bg-white shadow-md transition hover:shadow-xl">
    <a href="{{ route('yatras.show', $yatra->slug) }}" class="relative {{ $isList ? 'aspect-[4/3] sm:aspect-auto sm:h-48 sm:w-64 sm:shrink-0' : 'aspect-[4/3]' }} block overflow-hidden">
        <img src="{{ $yatra->image_url }}" alt="{{ $yatra->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 to-transparent p-4 text-white">
            <p class="text-xs">📍 {{ $yatra->destination }}</p>
        </div>
        <span class="absolute top-3 left-3 bg-saffron-600 text-white text-xs px-3 py-1 rounded-full">⏱ {{ $yatra->duration_label }}</span>
    </a>
    <div class="flex flex-1 flex-col justify-center p-4">
        @if (($yatra->approved_reviews_count ?? 0) > 0)
            <div class="mb-1 flex items-center gap-2 text-sm" aria-label="{{ number_format($yatra->approved_reviews_avg_rating, 1) }} out of 5 from {{ $yatra->approved_reviews_count }} reviews">
                <span class="text-amber-500" aria-hidden="true">@for ($star = 1; $star <= 5; $star++){{ $star <= round($yatra->approved_reviews_avg_rating) ? '★' : '☆' }}@endfor</span>
                <span class="text-xs text-gray-500">{{ number_format($yatra->approved_reviews_avg_rating, 1) }} ({{ $yatra->approved_reviews_count }})</span>
            </div>
        @endif
        <h3 class="font-semibold text-lg leading-snug group-hover:text-saffron-600"><a href="{{ route('yatras.show', $yatra->slug) }}">{{ $yatra->title }}</a></h3>
        @if ($yatra->price)
            <p class="mt-2 text-sm text-gray-500">Starting from <span class="text-saffron-600 font-semibold text-base">₹{{ number_format($yatra->price) }}</span> / person</p>
        @endif
        <div class="mt-4 grid grid-cols-2 gap-2">
            <a href="{{ route('yatras.show', $yatra->slug) }}" class="site-btn site-btn-secondary px-2 text-xs sm:text-sm">View details</a>
            <button type="button" x-data="{}" data-yatra-id="{{ $yatra->id }}" data-yatra-title="{{ $yatra->title }}" x-on:click="$dispatch('open-yatra-enquiry', { id: $el.dataset.yatraId, title: $el.dataset.yatraTitle })" class="site-btn site-btn-primary px-2 text-xs sm:text-sm">Enquire</button>
        </div>
    </div>
</article>
