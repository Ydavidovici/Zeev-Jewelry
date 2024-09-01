<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('getCurrentSettings');
    }

    public function getCurrentSettings(): JsonResponse
    {
        $settings = Settings::all();
        return response()->json($settings);
    }

    public function index(): JsonResponse
    {
        if (Gate::denies('manage-settings')) {
            abort(403);
        }

        $settings = Settings::all();
        return response()->json($settings);
    }

    public function store(Request $request): JsonResponse
    {
        if (Gate::denies('manage-settings')) {
            abort(403);
        }

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

    public function update(Request $request, string $key): JsonResponse
    {
        if (Gate::denies('manage-settings')) {
            abort(403);
        }

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

    public function destroy(string $key): JsonResponse
    {
        if (Gate::denies('manage-settings')) {
            abort(403);
        }

        $setting = Settings::where('key', $key)->firstOrFail();
        $setting->delete();
        return response()->json(['message' => 'Setting deleted successfully.']);
    }
}
