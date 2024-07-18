<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('admin-page');
    }

    public function view(User $user, Role $role)
    {
        return $user->hasRole('admin-page');
    }

    public function create(User $user)
    {
        return $user->hasRole('admin-page');
    }

    public function update(User $user, Role $role)
    {
        return $user->hasRole('admin-page');
    }

    public function delete(User $user, Role $role)
    {
        return $user->hasRole('admin-page');
    }
}
