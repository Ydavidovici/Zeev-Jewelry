<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class PermissionsController extends Controller
{
    public function __construct()
    {
        // Ensure the user is authenticated
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        // Check if the authenticated user has the 'manage permissions' permission
        $this->authorize('manage permissions');

        Log::channel('custom')->info('Admin accessing permissions index', ['user_id' => auth()->id()]);

        $permissions = Permission::all();

        Log::channel('custom')->info('Permissions data retrieved', ['permissions' => $permissions->pluck('name')->toArray()]);

        return response()->json(['permissions' => $permissions], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('manage permissions');

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
        ]);

        $permission = Permission::create($validatedData);

        Log::channel('custom')->info('Permission created', ['permission' => $permission]);

        return response()->json(['permission' => $permission], 201);
    }

    public function update(Request $request, Permission $permission): JsonResponse
    {
        $this->authorize('manage permissions');

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update($validatedData);

        Log::channel('custom')->info('Permission updated', ['permission' => $permission]);

        return response()->json(['permission' => $permission], 200);
    }

    public function destroy(Permission $permission): JsonResponse
    {
        $this->authorize('manage permissions');

        Log::channel('custom')->info('Permission deleted', ['permission' => $permission]);

        $permission->delete();

        return response()->json(null, 204);
    }
}
