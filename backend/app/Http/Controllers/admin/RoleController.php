<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        if (Gate::denies('manage-roles')) {
            abort(403);
        }

        Log::channel('custom')->info('Admin accessing roles index');

        $roles = Role::all();

        Log::channel('custom')->info('Roles data retrieved', compact('roles'));

        return response()->json(['roles' => $roles]);
    }

    public function store(Request $request): JsonResponse
    {
        if (Gate::denies('manage-roles')) {
            abort(403);
        }

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
        if (Gate::denies('manage-roles')) {
            abort(403);
        }

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
        if (Gate::denies('manage-roles')) {
            abort(403);
        }

        Log::channel('custom')->info('Role deleted', ['role' => $role]);

        $role->delete();

        return response()->json(null, 204);
    }
}
