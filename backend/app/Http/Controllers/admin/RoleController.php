<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Role::class);

        Log::channel('custom')->info('Admin accessing roles index');

        $roles = Role::all();

        Log::channel('custom')->info('Roles data retrieved', compact('roles'));

        return response()->json(['roles' => $roles]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Role::class);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $role = Role::create($validatedData);

        Log::channel('custom')->info('Role created', ['role' => $role]);

        return response()->json(['role' => $role], 201);
    }

    public function update(Request $request, Role $role): JsonResponse
    {
        $this->authorize('update', $role);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $role->update($validatedData);

        Log::channel('custom')->info('Role updated', ['role' => $role]);

        return response()->json(['role' => $role]);
    }

    public function destroy(Role $role): JsonResponse
    {
        $this->authorize('delete', $role);

        Log::channel('custom')->info('Role deleted', ['role' => $role]);

        $role->delete();

        return response()->json(null, 204);
    }
}
