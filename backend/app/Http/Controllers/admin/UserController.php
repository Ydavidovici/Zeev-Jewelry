<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Exceptions\UnauthorizedException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        if (!auth()->user()->can('manage users')) {
            throw UnauthorizedException::forPermissions(['manage users']);
        }

        $users = User::all();
        return response()->json(['users' => $users]);
    }

    public function store(Request $request): JsonResponse
    {
        if (!auth()->user()->can('manage users')) {
            throw UnauthorizedException::forPermissions(['manage users']);
        }

        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'role_name' => 'required|string|exists:roles,name',
            'email' => 'required|email|max:255',
        ]);

        $user = User::create([
            'username' => $validatedData['username'],
            'password' => bcrypt($validatedData['password']),
            'email' => $validatedData['email'],
        ]);

        $role = Role::where('name', $validatedData['role_name'])->firstOrFail();
        $user->assignRole($role);

        return response()->json(['user' => $user], 201);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        if (!auth()->user()->can('manage users')) {
            throw UnauthorizedException::forPermissions(['manage users']);
        }

        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
            'role_name' => 'required|string|exists:roles,name',
            'email' => 'required|email|max:255',
        ]);

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        $role = Role::where('name', $validatedData['role_name'])->firstOrFail();
        $user->syncRoles([$role]);

        return response()->json(['user' => $user]);
    }

    public function destroy(User $user): JsonResponse
    {
        if (!auth()->user()->can('manage users')) {
            throw UnauthorizedException::forPermissions(['manage users']);
        }

        $user->delete();

        return response()->json(null, 204);
    }
}
