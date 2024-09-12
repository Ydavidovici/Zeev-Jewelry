<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    public function __construct()
    {
        // Ensure the user is authenticated
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        $this->authorize('manage roles');

        Log::channel('custom')->info('Admin accessing roles index', ['user_id' => auth()->id()]);

        $roles = Role::all();

        Log::channel('custom')->info('Roles data retrieved', ['roles' => $roles->pluck('name')->toArray()]);

        return response()->json(['roles' => $roles], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('manage roles');

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
        ]);

        $role = Role::create($validatedData);

        Log::channel('custom')->info('Role created', ['role' => $role]);

        return response()->json(['role' => $role], 201);
    }

    public function update(Request $request, Role $role): JsonResponse
    {
        $this->authorize('manage roles');

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        $role->update($validatedData);

        Log::channel('custom')->info('Role updated', ['role' => $role]);

        return response()->json(['role' => $role], 200);
    }

    public function destroy(Role $role): JsonResponse
    {
        $this->authorize('manage roles');

        Log::channel('custom')->info('Role deleted', ['role' => $role]);

        $role->delete();

        return response()->json(null, 204);
    }
}
