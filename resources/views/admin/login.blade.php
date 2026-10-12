<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login</title><script src="https://cdn.tailwindcss.com/3.4.17"></script></head>
<body class="min-h-screen bg-gradient-to-br from-orange-600 to-red-900 flex items-center justify-center p-4">
<form method="POST" action="{{ url('admin/login') }}" class="bg-white w-full max-w-sm rounded-2xl shadow-2xl p-8 space-y-4">
    @csrf
    <h1 class="text-2xl font-bold text-center">🕉️ Unlimited Yatra</h1>
    <p class="text-center text-gray-500 text-sm">Admin Panel Login</p>
    @error('email')<p class="bg-red-100 text-red-700 text-sm rounded px-3 py-2">{{ $message }}</p>@enderror
    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus class="w-full border rounded-lg px-4 py-3">
    <input type="password" name="password" placeholder="Password" required class="w-full border rounded-lg px-4 py-3">
    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember"> Remember me</label>
    <button class="w-full bg-orange-600 hover:bg-orange-700 text-white py-3 rounded-lg font-semibold">Login</button>
</form>
</body></html>
