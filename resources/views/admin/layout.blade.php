<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - Unlimited Yatra</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 text-gray-800" x-data="{ nav:false }">
<div class="flex min-h-screen">
    <aside class="fixed lg:static inset-y-0 left-0 z-40 w-64 bg-slate-900 text-slate-200 overflow-y-auto transform -translate-x-full lg:translate-x-0 transition"
           :class="{ 'translate-x-0': nav }">
        <div class="px-5 py-5 text-xl font-bold text-white border-b border-slate-700">🕉️ Unlimited Yatra</div>
        <nav class="p-3 space-y-1 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-slate-800 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800' : '' }}">📊 Dashboard</a>
            @foreach (config('admin.resources') as $key => $r)
                <a href="{{ route('admin.resource.index', $key) }}"
                   class="block px-3 py-2 rounded hover:bg-slate-800 {{ request()->route('resource') === $key ? 'bg-orange-600 text-white' : '' }}">{{ $r['icon'] }} {{ $r['label'] }}</a>
            @endforeach
            <a href="{{ route('admin.settings') }}" class="block px-3 py-2 rounded hover:bg-slate-800 {{ request()->routeIs('admin.settings') ? 'bg-orange-600 text-white' : '' }}">⚙️ Site Settings</a>
            <a href="{{ route('home') }}" target="_blank" class="block px-3 py-2 rounded hover:bg-slate-800">🌐 View website</a>
        </nav>
    </aside>

    <div class="flex-1 min-w-0">
        <header class="bg-white shadow-sm h-14 flex items-center justify-between px-4">
            <button class="lg:hidden text-2xl" @click="nav = !nav">☰</button>
            <span class="font-semibold">@yield('heading')</span>
            <form action="{{ route('admin.logout') }}" method="POST" class="flex items-center gap-3 text-sm">
                @csrf <span class="text-gray-500 hidden sm:inline">{{ auth()->user()->name }}</span>
                <button class="text-red-600 hover:underline">Logout</button>
            </form>
        </header>
        <main class="p-4 md:p-6">
            @if (session('success'))<div class="mb-4 bg-green-100 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>@endif
            @if ($errors->any())
                <div class="mb-4 bg-red-100 text-red-800 px-4 py-3 rounded text-sm"><ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
