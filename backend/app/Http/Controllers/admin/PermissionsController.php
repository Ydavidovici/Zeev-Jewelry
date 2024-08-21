<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class PermissionsController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(): JsonResponse
    {
        Log::channel('custom')->info('Attempting to access permissions index', [
            'user_id' => auth()->id(),
        ]);

        $this->authorize('viewAny', Permission::class);

        Log::channel('custom')->info('Admin accessing permissions index', [
            'user_id' => auth()->id(),
        ]);

        $permissions = Permission::all();

        Log::channel('custom')->info('Permissions data retrieved', [
            'permissions' => $permissions->pluck('name')->toArray(),
        ]);

        return response()->json(['permissions' => $permissions]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Permission::class);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
        ]);

        $permission = Permission::create($validatedData);

        Log::channel('custom')->info('Permission created', ['permission' => $permission]);

        return response()->json(['permission' => $permission], 201);
    }

    public function update(Request $request, Permission $permission): JsonResponse
    {
        $this->authorize('update', $permission);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update($validatedData);

        Log::channel('custom')->info('Permission updated', ['permission' => $permission]);

        return response()->json(['permission' => $permission]);
    }

    public function destroy(Permission $permission): JsonResponse
    {
        $this->authorize('delete', $permission);

        Log::channel('custom')->info('Permission deleted', ['permission' => $permission]);

        $permission->delete();

        return response()->json(null, 204);
    }
}
