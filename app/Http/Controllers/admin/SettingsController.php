<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return view('admin-page.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin-page.settings.index')->with('success', 'Settings updated successfully.');
    }
}
