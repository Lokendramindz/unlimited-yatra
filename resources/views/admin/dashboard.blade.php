@extends('admin.layout')
@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('content')
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
    @foreach ($stats as $label => $n)
        <div class="bg-white rounded-xl shadow p-5"><p class="text-3xl font-bold text-orange-600">{{ $n }}</p><p class="text-sm text-gray-500 mt-1">{{ $label }}</p></div>
    @endforeach
</div>

<div class="bg-white rounded-xl shadow mt-6 overflow-x-auto">
    <div class="flex justify-between items-center px-5 py-4 border-b">
        <h2 class="font-semibold">Latest Enquiries</h2>
        <a href="{{ route('admin.resource.index', 'enquiries') }}" class="text-sm text-orange-600">View all →</a>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500"><tr><th class="p-3">Date</th><th class="p-3">Name</th><th class="p-3">Phone</th><th class="p-3">Yatra</th><th class="p-3">Status</th></tr></thead>
        <tbody>
        @forelse ($latest as $e)
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3">{{ $e->created_at->format('d M, H:i') }}</td>
                <td class="p-3"><a class="text-orange-600" href="{{ route('admin.resource.edit', ['enquiries', $e->id]) }}">{{ $e->name }}</a></td>
                <td class="p-3">{{ $e->phone }}</td>
                <td class="p-3">{{ $e->yatra?->title ?? '-' }}</td>
                <td class="p-3"><span class="px-2 py-1 rounded text-xs bg-orange-100 text-orange-700">{{ ucfirst($e->status) }}</span></td>
            </tr>
        @empty
            <tr><td colspan="5" class="p-6 text-center text-gray-400">No enquiries yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
