@extends('admin.layout')
@section('title', 'Site Settings')
@section('heading', '⚙️ Site Settings')
@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" class="bg-white rounded-xl shadow p-6 max-w-3xl space-y-4">
    @csrf
    @foreach ($fields as $key => $label)
        <div>
            <label class="block text-sm font-medium mb-1">{{ $label }}</label>
            @if (str_contains($key, 'text') || $key === 'address')
                <textarea name="{{ $key }}" rows="4" class="w-full border rounded-lg px-3 py-2">{{ old($key, $values[$key] ?? '') }}</textarea>
            @else
                <input name="{{ $key }}" value="{{ old($key, $values[$key] ?? '') }}" class="w-full border rounded-lg px-3 py-2">
            @endif
        </div>
    @endforeach
    <button class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg">Save settings</button>
</form>
@endsection
