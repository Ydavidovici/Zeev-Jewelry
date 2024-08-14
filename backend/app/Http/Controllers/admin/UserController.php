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
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        Log::channel('custom')->info('Admin accessing users index');

        $users = User::all();

        Log::channel('custom')->info('Users data retrieved', compact('users'));

        return response()->json(['users' => $users]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'email' => 'required|email|max:255',
        ]);

        $user = User::create($validatedData);
        $user->assignRole($request->role_id);

        Log::channel('custom')->info('User created', ['user' => $user]);

        return response()->json(['user' => $user], 201);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'email' => 'required|email|max:255',
        ]);

        if (empty($validatedData['password'])) {
            unset($validatedData['password']);
        }

        $user->update($validatedData);
        $user->syncRoles($request->role_id);

        Log::channel('custom')->info('User updated', ['user' => $user]);

        return response()->json(['user' => $user]);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        Log::channel('custom')->info('User deleted', ['user' => $user]);

        $user->delete();

        return response()->json(null, 204);
    }
}
