<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('manageSettings', User::class);

        Log::channel('custom')->info('Admin accessing settings index');

        $settings = Setting::all();

        Log::channel('custom')->info('Settings data retrieved', compact('settings'));

        $theme = $request->cookie('theme', 'light'); // Default to light theme

        return response()->json(['settings' => $settings, 'theme' => $theme]);
    }

    public function update(Request $request): JsonResponse
    {
        $this->authorize('manageSettings', User::class);

        foreach ($request->settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        if ($request->has('theme')) {
            $theme = $request->input('theme');
            cookie()->queue(cookie('theme', $theme, 60 * 24 * 30)); // 30 days
        }

        Log::channel('custom')->info('Settings updated', ['settings' => $request->settings]);

        return response()->json(['message' => 'Settings updated successfully.']);
    }
}
