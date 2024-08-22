<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct()
    {
        // Apply authentication middleware to all methods
        $this->middleware('auth:sanctum');
        // Apply admin permissions middleware to all methods
        $this->middleware('can:manageUsers,App\Models\User');
    }

    public function index(): JsonResponse
    {
        Log::channel('custom')->info('Admin accessing users index');

        $users = User::all();

        Log::channel('custom')->info('Users data retrieved', compact('users'));

        return response()->json(['users' => $users]);
    }

    public function store(Request $request): JsonResponse
    {
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

        // Assign role using the 'api' guard role
        $role = Role::where('name', $validatedData['role_name'])
            ->where('guard_name', 'api')
            ->firstOrFail();

        $user->assignRole($role);

        Log::channel('custom')->info('User created', ['user' => $user]);

        return response()->json(['user' => $user], 201);
    }

    public function update(Request $request, User $user): JsonResponse
    {
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

        // Sync roles using the 'api' guard role
        $role = Role::where('name', $validatedData['role_name'])
            ->where('guard_name', 'api')
            ->firstOrFail();

        $user->syncRoles([$role]);

        Log::channel('custom')->info('User updated', ['user' => $user]);

        return response()->json(['user' => $user]);
    }

    public function destroy(User $user): JsonResponse
    {
        Log::channel('custom')->info('User deleted', ['user' => $user]);

        $user->delete();

        return response()->json(null, 204);
    }
}
