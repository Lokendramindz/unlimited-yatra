<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings', [
            'fields' => config('admin.settings'),
            'values' => Setting::pluck('value', 'key'),
        ]);
    }

    public function update(Request $request)
    {
        foreach (array_keys(config('admin.settings')) as $key) {
            Setting::updateOrCreate(['key' => $key], ['value' => $request->input($key)]);
        }

        return back()->with('success', 'Settings saved.');
    }
}
