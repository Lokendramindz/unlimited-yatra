<a href="{{ route('yatras.show', $yatra->slug) }}" class="group block bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition h-full">
    <div class="relative aspect-[4/3] overflow-hidden">
        <img src="{{ $yatra->image_url }}" alt="{{ $yatra->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 to-transparent p-4 text-white">
            <p class="text-xs">📍 {{ $yatra->destination }}</p>
        </div>
        <span class="absolute top-3 left-3 bg-saffron-600 text-white text-xs px-3 py-1 rounded-full">⏱ {{ $yatra->duration_label }}</span>
    </div>
    <div class="p-4">
        <h3 class="font-semibold text-lg leading-snug group-hover:text-saffron-600">{{ $yatra->title }}</h3>
        @if ($yatra->price)
            <p class="mt-2 text-sm text-gray-500">Starting from <span class="text-saffron-600 font-semibold text-base">₹{{ number_format($yatra->price) }}</span> / person</p>
        @endif
    </div>
</a>
