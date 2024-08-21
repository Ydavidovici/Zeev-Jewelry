<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Models\User;

class SettingsController extends Controller
{
    /** @var array<string, mixed> $settings */
    protected array $settings;

    public function __construct()
    {
        // Hard-code the settings array
        $this->settings = [
            'site_name' => 'Zeev Jewelry',
            'currency' => 'USD',
            'theme_options' => ['light', 'dark'],
            'default_language' => 'en'
        ];
    }

    public function test(): JsonResponse
    {
        return response()->json(['message' => 'SettingsController is working!']);
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('manageSettings', User::class);

        Log::channel('custom')->info('Admin accessing settings index');

        // Use the hard-coded settings
        $settings = $this->settings;

        Log::channel('custom')->info('Settings data retrieved', compact('settings'));

        $theme = $request->cookie('theme', 'light'); // Default to light theme

        return response()->json(['settings' => $settings, 'theme' => $theme]);
    }

    public function update(Request $request): JsonResponse
    {
        $this->authorize('manageSettings', User::class);

        $settings = $this->settings;
        $inputSettings = $request->input('settings', []);

        if (is_array($inputSettings)) {
            foreach ($inputSettings as $key => $value) {
                if (is_string($key) && array_key_exists($key, $settings)) {
                    // Ensure the value type is consistent or expected
                    if (is_string($value) || is_int($value) || is_array($value)) {
                        $settings[$key] = $value;
                    }
                }
            }
        }

        // Update the settings with the new values
        $this->settings = $settings;

        if ($request->has('theme')) {
            $theme = $request->input('theme');
            if (is_string($theme)) {
                cookie()->queue(cookie('theme', $theme, 60 * 24 * 30)); // 30 days
            }
        }

        Log::channel('custom')->info('Settings updated', ['settings' => $settings]);

        return response()->json(['message' => 'Settings updated successfully.']);
    }
}
