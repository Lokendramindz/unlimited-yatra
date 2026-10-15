@extends('admin.layout')
@php $editing = $item->exists; @endphp
@section('title', ($editing ? 'Edit ' : 'Add ').$cfg['singular'])
@section('heading', $cfg['icon'].' '.($editing ? 'Edit ' : 'Add ').$cfg['singular'])
@section('content')
<form method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow p-6 max-w-4xl space-y-5"
      action="{{ $editing ? route('admin.resource.update', [$resource, $item->id]) : route('admin.resource.store', $resource) }}">
    @csrf
    @if ($editing) @method('PUT') @endif

    @foreach ($cfg['fields'] as $f)
        @php $name = $f['name']; $value = old($name, $item->$name ?? null); @endphp
        <div>
            @if ($f['type'] === 'checkbox')
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $editing ? $item->$name : true)) class="w-4 h-4">
                    <span class="text-sm font-medium">{{ $f['label'] }}</span>
                </label>
            @else
                <label class="block text-sm font-medium mb-1">{{ $f['label'] }}</label>
                @switch($f['type'])
                    @case('textarea')
                        <textarea name="{{ $name }}" rows="{{ $f['rows'] ?? 4 }}" class="w-full border rounded-lg px-3 py-2">{{ $value }}</textarea>
                        @break
                    @case('itinerary')
                        @php
                            $days = old('itinerary_days', $editing ? $item->itinerary_days : []);
                            $days = is_array($days) && count($days) ? $days : [['title' => 'Day 1', 'content' => '']];
                        @endphp
                        <div data-itinerary-editor data-next-index="{{ count($days) }}" class="space-y-4">
                            <div data-itinerary-days class="space-y-4">
                                @foreach ($days as $index => $day)
                                    <div data-itinerary-day class="border border-gray-200 rounded-lg p-4 space-y-3">
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="text-sm font-semibold text-gray-700">Itinerary day</span>
                                            <button type="button" data-remove-day class="text-sm text-red-600 hover:underline">Remove day</button>
                                        </div>
                                        <input name="itinerary_days[{{ $index }}][title]" value="{{ old('itinerary_days.'.$index.'.title', $day['title'] ?? '') }}" placeholder="Day 1 - Arrival in Varanasi" class="w-full border rounded-lg px-3 py-2">
                                        <textarea name="itinerary_days[{{ $index }}][content]" rows="4" placeholder="Describe the day's visits, travel, meals, and activities" class="w-full border rounded-lg px-3 py-2">{{ old('itinerary_days.'.$index.'.content', $day['content'] ?? '') }}</textarea>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" data-add-day class="border border-orange-600 px-4 py-2 text-sm font-medium text-orange-700 hover:bg-orange-50">Add itinerary day</button>
                            <p class="text-xs text-gray-500">Add a title and detailed description for each day. These appear in the itinerary tab on the Yatra page.</p>
                            <template data-itinerary-template>
                                <div data-itinerary-day class="border border-gray-200 rounded-lg p-4 space-y-3">
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-sm font-semibold text-gray-700">Itinerary day</span>
                                        <button type="button" data-remove-day class="text-sm text-red-600 hover:underline">Remove day</button>
                                    </div>
                                    <input name="itinerary_days[__INDEX__][title]" value="Day __DAY__" placeholder="Day __DAY__ - Arrival in Varanasi" class="w-full border rounded-lg px-3 py-2">
                                    <textarea name="itinerary_days[__INDEX__][content]" rows="4" placeholder="Describe the day's visits, travel, meals, and activities" class="w-full border rounded-lg px-3 py-2"></textarea>
                                </div>
                            </template>
                        </div>
                        @break
                    @case('faq_list')
                        @php
                            $faqs = old('faqs', $editing ? $item->faq_list : []);
                            $faqs = is_array($faqs) && count($faqs) ? $faqs : [['question' => '', 'answer' => '']];
                        @endphp
                        <div data-faq-editor data-next-index="{{ count($faqs) }}" class="space-y-4">
                            <div data-faq-items class="space-y-4">
                                @foreach ($faqs as $index => $faq)
                                    <div data-faq-item class="space-y-3 rounded-lg border border-gray-200 p-4">
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="text-sm font-semibold text-gray-700">Vehicle question</span>
                                            <button type="button" data-remove-faq class="text-sm text-red-600 hover:underline">Remove question</button>
                                        </div>
                                        <input name="faqs[{{ $index }}][question]" value="{{ old('faqs.'.$index.'.question', $faq['question'] ?? '') }}" placeholder="Is a driver included?" class="w-full border rounded-lg px-3 py-2">
                                        <textarea name="faqs[{{ $index }}][answer]" rows="3" placeholder="Add a clear answer for customers." class="w-full border rounded-lg px-3 py-2">{{ old('faqs.'.$index.'.answer', $faq['answer'] ?? '') }}</textarea>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" data-add-faq class="border border-orange-600 px-4 py-2 text-sm font-medium text-orange-700 hover:bg-orange-50">Add FAQ</button>
                            <p class="text-xs text-gray-500">FAQs appear on this vehicle's public details page.</p>
                            <template data-faq-template>
                                <div data-faq-item class="space-y-3 rounded-lg border border-gray-200 p-4">
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-sm font-semibold text-gray-700">Vehicle question</span>
                                        <button type="button" data-remove-faq class="text-sm text-red-600 hover:underline">Remove question</button>
                                    </div>
                                    <input name="faqs[__INDEX__][question]" placeholder="Is a driver included?" class="w-full border rounded-lg px-3 py-2">
                                    <textarea name="faqs[__INDEX__][answer]" rows="3" placeholder="Add a clear answer for customers." class="w-full border rounded-lg px-3 py-2"></textarea>
                                </div>
                            </template>
                        </div>
                        @break
                    @case('select')
                        <select name="{{ $name }}" class="w-full border rounded-lg px-3 py-2">
                            @if (($f['nullable'] ?? false) || isset($f['relation']))<option value="">-- none --</option>@endif
                            @foreach (($f['options'] ?? $options[$name] ?? []) as $k => $label)
                                <option value="{{ $k }}" @selected((string) $value === (string) $k)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @break
                    @case('image')
                        @if ($editing && $item->$name)<img src="{{ $item->image_url }}" class="h-24 rounded mb-2" alt="">@endif
                        <input type="file" name="{{ $name }}" accept="image/*" class="w-full border rounded-lg px-3 py-2 bg-gray-50">
                        <p class="text-xs text-gray-400 mt-1">JPG / PNG / WebP, max 5 MB. Use sharp, high-quality photos.</p>
                        @break
                    @case('images')
                        @if ($editing && !empty($item->$name))
                            <div class="flex flex-wrap gap-3 mb-3">
                                @foreach ($item->gallery_urls as $i => $url)
                                    <label class="text-xs text-center">
                                        <img src="{{ $url }}" class="h-20 w-28 object-cover rounded mb-1" alt="">
                                        <input type="checkbox" name="remove_{{ $name }}[]" value="{{ $item->gallery[$i] }}"> remove
                                    </label>
                                @endforeach
                            </div>
                        @endif
                        <input type="file" name="{{ $name }}[]" accept="image/*" multiple class="w-full border rounded-lg px-3 py-2 bg-gray-50">
                        <p class="text-xs text-gray-400 mt-1">You can select many images at once. New images are added to the existing gallery.</p>
                        @break
                    @case('date')
                        <input type="date" name="{{ $name }}" value="{{ $value instanceof \Carbon\Carbon ? $value->format('Y-m-d') : ($value ? \Illuminate\Support\Str::substr($value, 0, 10) : '') }}" class="w-full border rounded-lg px-3 py-2">
                        @break
                    @default
                        <input type="{{ $f['type'] === 'number' ? 'number' : ($f['type'] === 'email' ? 'email' : 'text') }}" @if($f['type'] === 'number') step="any" @endif
                               name="{{ $name }}" value="{{ $value }}" class="w-full border rounded-lg px-3 py-2">
                @endswitch
            @endif
        </div>
    @endforeach

    <div class="flex gap-3 pt-2">
        <button class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg">{{ $editing ? 'Update' : 'Create' }}</button>
        <a href="{{ route('admin.resource.index', $resource) }}" class="px-6 py-2 rounded-lg border">Cancel</a>
    </div>
</form>
<script>
    document.querySelectorAll('[data-itinerary-editor]').forEach((editor) => {
        let nextIndex = Number(editor.dataset.nextIndex || 0);
        editor.addEventListener('click', (event) => {
            if (event.target.closest('[data-remove-day]')) {
                event.target.closest('[data-itinerary-day]').remove();
                return;
            }

            if (event.target.closest('[data-add-day]')) {
                const day = editor.querySelector('[data-itinerary-template]').innerHTML
                    .replaceAll('__INDEX__', String(nextIndex))
                    .replaceAll('__DAY__', String(nextIndex + 1));
                editor.querySelector('[data-itinerary-days]').insertAdjacentHTML('beforeend', day);
                nextIndex += 1;
            }
        });
    });
    document.querySelectorAll('[data-faq-editor]').forEach((editor) => {
        let nextIndex = Number(editor.dataset.nextIndex || 0);
        editor.addEventListener('click', (event) => {
            if (event.target.closest('[data-remove-faq]')) {
                event.target.closest('[data-faq-item]').remove();
                return;
            }

            if (event.target.closest('[data-add-faq]')) {
                const faq = editor.querySelector('[data-faq-template]').innerHTML.replaceAll('__INDEX__', String(nextIndex));
                editor.querySelector('[data-faq-items]').insertAdjacentHTML('beforeend', faq);
                nextIndex += 1;
            }
        });
    });
</script>
@endsection
