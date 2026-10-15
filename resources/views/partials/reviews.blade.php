<section id="customer-reviews" class="border-t border-slate-200 py-10">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-saffron-700">Guest feedback</p>
            <h2 class="mt-1 font-display text-3xl text-maroon-800">Customer reviews</h2>
        </div>
        @if ($reviewCount)
            <div class="text-right">
                <p class="text-2xl font-bold text-gray-900">{{ number_format($reviewAverage, 1) }} <span class="text-amber-500" aria-hidden="true">★</span></p>
                <p class="text-xs text-gray-500">{{ $reviewCount }} approved review(s)</p>
            </div>
        @endif
    </div>

    @if (session('review_submitted'))
        <p role="status" class="mt-5 border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('review_submitted') }}</p>
    @endif

    <div class="mt-6 grid gap-8 lg:grid-cols-2">
        <div>
            @forelse ($reviews as $review)
                <article class="border-b border-slate-200 py-5 first:pt-0">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $review->reviewer_name }}</p>
                            <p class="text-xs text-gray-500">{{ $review->created_at->format('d M Y') }}</p>
                        </div>
                        <span class="text-amber-500" aria-label="{{ $review->rating }} out of 5 stars">@for ($star = 1; $star <= 5; $star++){{ $star <= $review->rating ? '★' : '☆' }}@endfor</span>
                    </div>
                    @if ($review->is_featured)<span class="mt-3 inline-block bg-amber-50 px-2 py-1 text-xs font-semibold text-amber-800">Featured review</span>@endif
                    @if ($review->title)<h3 class="mt-3 font-semibold text-gray-800">{{ $review->title }}</h3>@endif
                    <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-gray-600">{{ $review->body }}</p>
                </article>
            @empty
                <div class="border border-dashed border-slate-300 p-5 text-sm text-gray-600">No reviews published yet. Share your experience with this {{ $reviewSubject }}.</div>
            @endforelse
        </div>

        <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
            <h3 class="font-semibold text-gray-900">Share your experience</h3>
            <p class="mt-1 text-xs text-gray-500">Your review appears after our team approves it.</p>
            <form action="{{ $reviewAction }}" method="POST" class="mt-4 space-y-3">
                @csrf
                <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
                <div>
                    <label for="reviewer-name" class="mb-1 block text-sm font-medium">Your name *</label>
                    <input id="reviewer-name" name="reviewer_name" value="{{ old('reviewer_name') }}" required maxlength="120" autocomplete="name" class="w-full border border-slate-300 bg-white px-3 py-2.5 focus:border-[#173d4d] focus:outline-none">
                    @error('reviewer_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="review-email" class="mb-1 block text-sm font-medium">Email (not shown publicly)</label>
                    <input id="review-email" type="email" name="email" value="{{ old('email') }}" maxlength="150" autocomplete="email" class="w-full border border-slate-300 bg-white px-3 py-2.5 focus:border-[#173d4d] focus:outline-none">
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="review-rating" class="mb-1 block text-sm font-medium">Rating *</label>
                    <select id="review-rating" name="rating" required class="w-full border border-slate-300 bg-white px-3 py-2.5 focus:border-[#173d4d] focus:outline-none">
                        <option value="">Choose a rating</option>
                        @foreach ([5 => '5 - Excellent', 4 => '4 - Very good', 3 => '3 - Good', 2 => '2 - Fair', 1 => '1 - Poor'] as $rating => $label)
                            <option value="{{ $rating }}" @selected((string) old('rating') === (string) $rating)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('rating')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="review-title" class="mb-1 block text-sm font-medium">Review title</label>
                    <input id="review-title" name="title" value="{{ old('title') }}" maxlength="160" class="w-full border border-slate-300 bg-white px-3 py-2.5 focus:border-[#173d4d] focus:outline-none">
                    @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="review-body" class="mb-1 block text-sm font-medium">Your review *</label>
                    <textarea id="review-body" name="body" rows="4" minlength="10" maxlength="3000" required class="w-full border border-slate-300 bg-white px-3 py-2.5 focus:border-[#173d4d] focus:outline-none">{{ old('body') }}</textarea>
                    @error('body')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="site-btn site-btn-primary w-full">Submit review</button>
            </form>
        </div>
    </div>
</section>