@extends('admin.layout')
@section('title', $cfg['label'])
@section('heading', $cfg['icon'].' '.$cfg['label'])
@section('content')
<div class="flex justify-between mb-4">
    <h2 class="text-lg font-semibold">{{ $cfg['label'] }} <span class="text-gray-400 text-sm">({{ $items->total() }})</span></h2>
    @if (($cfg['can_create'] ?? true) !== false)
        <a href="{{ route('admin.resource.create', $resource) }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm">+ Add {{ $cfg['singular'] }}</a>
    @endif
</div>
<div class="bg-white rounded-xl shadow overflow-x-auto">
<table class="w-full text-sm">
    <thead class="bg-gray-50 text-left text-gray-500">
        <tr>@foreach ($cfg['columns'] as $c)<th class="p-3">{{ $c[1] }}</th>@endforeach<th class="p-3 text-right">Actions</th></tr>
    </thead>
    <tbody>
    @forelse ($items as $item)
        <tr class="border-t hover:bg-gray-50">
            @foreach ($cfg['columns'] as $c)
                @php $val = data_get($item, $c[0]); $type = $c[2] ?? 'text'; @endphp
                <td class="p-3">
                    @if ($type === 'image')
                        <img src="{{ $item->image_url }}" class="w-14 h-10 object-cover rounded" alt="">
                    @elseif ($type === 'bool')
                        <span class="px-2 py-1 rounded text-xs {{ $val ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $val ? 'Yes' : 'No' }}</span>
                    @else
                        {{ $val instanceof \Carbon\Carbon ? $val->format('d M Y') : \Illuminate\Support\Str::limit((string) $val, 60) }}
                    @endif
                </td>
            @endforeach
            <td class="p-3 text-right whitespace-nowrap">
                @if (($cfg['can_edit'] ?? true) !== false)
                    <a href="{{ route('admin.resource.edit', [$resource, $item->id]) }}" class="text-blue-600 mr-3">Edit</a>
                @endif
                <form action="{{ route('admin.resource.destroy', [$resource, $item->id]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this item?')">
                    @csrf @method('DELETE')<button class="text-red-600">Delete</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="{{ count($cfg['columns']) + 1 }}" class="p-8 text-center text-gray-400">Nothing here yet.</td></tr>
    @endforelse
    </tbody>
</table>
</div>
<div class="mt-4">{{ $items->links() }}</div>
@endsection
