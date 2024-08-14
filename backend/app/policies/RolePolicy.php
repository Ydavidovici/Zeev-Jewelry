<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('admin-page');
    }

    public function view(User $user, SpatieRole $role)
    {
        return $user->hasRole('admin-page');
    }

    public function create(User $user)
    {
        return $user->hasRole('admin-page');
    }

    public function update(User $user, SpatieRole $role)
    {
        return $user->hasRole('admin-page');
    }

    public function delete(User $user, SpatieRole $role)
    {
        return $user->hasRole('admin-page');
    }
}
