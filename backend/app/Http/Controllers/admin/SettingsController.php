<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    public function __construct()
    {
        // Apply authentication middleware to all methods except getCurrentSettings
        $this->middleware('auth:api')->except('getCurrentSettings');
        // Apply admin permissions middleware to all methods except getCurrentSettings
        $this->middleware('can:manageSettings,App\Models\User')->only(['index', 'store', 'update', 'destroy']);
    }

    /**
     * Get all settings for public access.
     *
     * @return JsonResponse
     */
    public function getCurrentSettings(): JsonResponse
    {
        $settings = Settings::all();
        return response()->json($settings);
    }

    /**
     * Display a listing of the settings (admin only).
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $settings = Settings::all();
        return response()->json($settings);
    }

    /**
     * Store a new setting (admin only).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:settings',
            'value' => 'required|string',
        ]);

        $setting = Settings::create($validated);
        return response()->json([
            'message' => 'Setting created successfully.',
            'setting' => $setting
        ], 201);
    }

    /**
     * Update an existing setting (admin only).
     *
     * @param Request $request
     * @param string $key
     * @return JsonResponse
     */
    public function update(Request $request, string $key): JsonResponse
    {
        $validated = $request->validate([
            'value' => 'required|string',
        ]);

        $setting = Settings::where('key', $key)->firstOrFail();
        $setting->update($validated);
        return response()->json([
            'message' => 'Setting updated successfully.',
            'setting' => $setting
        ]);
    }

    /**
     * Delete a setting (admin only).
     *
     * @param string $key
     * @return JsonResponse
     */
    public function destroy(string $key): JsonResponse
    {
        $setting = Settings::where('key', $key)->firstOrFail();
        $setting->delete();
        return response()->json(['message' => 'Setting deleted successfully.']);
    }
}
