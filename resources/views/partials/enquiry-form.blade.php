@php $yid = $yatraId ?? old('yatra_id'); @endphp
<form action="{{ route('enquiry.store') }}" method="POST" class="space-y-3">
    @csrf
    <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off"> {{-- honeypot --}}
    <div class="grid sm:grid-cols-2 gap-3">
        <div>
            <input name="name" value="{{ old('name') }}" required placeholder="Your name *" class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-saffron-400 focus:outline-none">
            @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <input name="phone" value="{{ old('phone') }}" required placeholder="Phone / WhatsApp *" class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-saffron-400 focus:outline-none">
            @error('phone')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="border rounded-lg px-4 py-3 focus:ring-2 focus:ring-saffron-400 focus:outline-none">
        <select name="yatra_id" class="border rounded-lg px-4 py-3 text-gray-600 focus:ring-2 focus:ring-saffron-400 focus:outline-none">
            <option value="">Select yatra (optional)</option>
            @foreach ($yatraOptions as $id => $title)
                <option value="{{ $id }}" @selected($yid == $id)>{{ $title }}</option>
            @endforeach
        </select>
        <input type="number" min="1" name="travellers" value="{{ old('travellers') }}" placeholder="No. of travellers" class="border rounded-lg px-4 py-3 focus:ring-2 focus:ring-saffron-400 focus:outline-none">
        <input type="date" name="travel_date" value="{{ old('travel_date') }}" class="border rounded-lg px-4 py-3 text-gray-600 focus:ring-2 focus:ring-saffron-400 focus:outline-none">
    </div>
    <textarea name="message" rows="3" placeholder="Tell us about your plan..." class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-saffron-400 focus:outline-none">{{ old('message') }}</textarea>
    <button class="w-full bg-saffron-600 hover:bg-saffron-700 text-white font-semibold py-3 rounded-lg transition">Send Enquiry</button>
</form>
