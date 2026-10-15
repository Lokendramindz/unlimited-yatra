@php $yid = $yatraId ?? old('yatra_id'); @endphp
<form action="{{ route('enquiry.store') }}" method="POST" class="space-y-3">
    @csrf
    <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off"> {{-- honeypot --}}
    <div class="grid min-w-0 gap-3 sm:grid-cols-2">
        <div class="min-w-0">
            <input name="name" value="{{ old('name') }}" required placeholder="Your name *" class="w-full min-w-0 border rounded-lg px-4 py-3 focus:ring-2 focus:ring-saffron-400 focus:outline-none">
            @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="min-w-0">
            <input name="phone" value="{{ old('phone') }}" required placeholder="Phone / WhatsApp *" class="w-full min-w-0 border rounded-lg px-4 py-3 focus:ring-2 focus:ring-saffron-400 focus:outline-none">
            @error('phone')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="w-full min-w-0 border rounded-lg px-4 py-3 focus:ring-2 focus:ring-saffron-400 focus:outline-none">
        @if ($fixedYatra ?? false)
            <input type="hidden" name="yatra_id" value="{{ $yid }}" x-model="selectedYatraId">
        @else
            <select name="yatra_id" class="w-full min-w-0 border rounded-lg px-4 py-3 text-gray-600 focus:ring-2 focus:ring-saffron-400 focus:outline-none">
                <option value="">Select yatra (optional)</option>
                @foreach ($yatraOptions as $id => $title)
                    <option value="{{ $id }}" @selected($yid == $id)>{{ $title }}</option>
                @endforeach
            </select>
        @endif
        <input type="number" min="1" name="travellers" value="{{ old('travellers') }}" placeholder="No. of travellers" class="w-full min-w-0 border rounded-lg px-4 py-3 focus:ring-2 focus:ring-saffron-400 focus:outline-none">
        <input type="date" name="travel_date" value="{{ old('travel_date') }}" class="w-full min-w-0 border rounded-lg px-4 py-3 text-gray-600 focus:ring-2 focus:ring-saffron-400 focus:outline-none">
    </div>
    <textarea name="message" rows="3" placeholder="Tell us about your plan..." class="w-full min-w-0 border rounded-lg px-4 py-3 focus:ring-2 focus:ring-saffron-400 focus:outline-none">{{ old('message') }}</textarea>
    <button class="site-btn site-btn-primary w-full">Send Enquiry</button>
</form>
