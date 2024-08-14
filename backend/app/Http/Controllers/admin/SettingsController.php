<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('manageSettings', User::class);

        Log::channel('custom')->info('Admin accessing settings index');

        $settings = Setting::all();

        Log::channel('custom')->info('Settings data retrieved', compact('settings'));

        return response()->json(['settings' => $settings]);
    }

    public function update(Request $request): JsonResponse
    {
        $this->authorize('manageSettings', User::class);

        foreach ($request->settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Log::channel('custom')->info('Settings updated', ['settings' => $request->settings]);

        return response()->json(['message' => 'Settings updated successfully.']);
    }
}
