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
@endsection
