<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('viewAny', User::class);
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('create', User::class);
        return view('users.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'email' => 'required|email|max:255',
        ]);

        User::create($request->all());

        return redirect()->route('users.index');
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'email' => 'required|email|max:255',
        ]);

        $data = $request->all();
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();

        return redirect()->route('users.index');
    }
}
