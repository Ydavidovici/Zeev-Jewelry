<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Exceptions\UnauthorizedException;

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
        if (!auth()->user()->can('manage settings')) {
            throw UnauthorizedException::forPermissions(['manage settings']);
        }

        $settings = Settings::all();
        return response()->json($settings);
    }

    public function store(Request $request): JsonResponse
    {
        if (!auth()->user()->can('manage settings')) {
            throw UnauthorizedException::forPermissions(['manage settings']);
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
        if (!auth()->user()->can('manage settings')) {
            throw UnauthorizedException::forPermissions(['manage settings']);
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
        if (!auth()->user()->can('manage settings')) {
            throw UnauthorizedException::forPermissions(['manage settings']);
        }

        $setting = Settings::where('key', $key)->firstOrFail();
        $setting->delete();
        return response()->json(['message' => 'Setting deleted successfully.']);
    }
}
