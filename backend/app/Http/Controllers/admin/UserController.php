<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        if (Gate::denies('manage-users')) {
            abort(403);
        }

        Log::channel('custom')->info('Admin accessing users index');

        $users = User::all();

        Log::channel('custom')->info('Users data retrieved', compact('users'));

        return response()->json(['users' => $users]);
    }

    public function store(Request $request): JsonResponse
    {
        if (Gate::denies('manage-users')) {
            abort(403);
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

        $role = Role::where('name', $validatedData['role_name'])
            ->where('guard_name', 'api')
            ->firstOrFail();

        $user->assignRole($role);

        Log::channel('custom')->info('User created', ['user' => $user]);

        return response()->json(['user' => $user], 201);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        if (Gate::denies('manage-users')) {
            abort(403);
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

        $role = Role::where('name', $validatedData['role_name'])
            ->where('guard_name', 'api')
            ->firstOrFail();

        $user->syncRoles([$role]);

        Log::channel('custom')->info('User updated', ['user' => $user]);

        return response()->json(['user' => $user]);
    }

    public function destroy(User $user): JsonResponse
    {
        if (Gate::denies('manage-users')) {
            abort(403);
        }

        Log::channel('custom')->info('User deleted', ['user' => $user]);

        $user->delete();

        return response()->json(null, 204);
    }
}
