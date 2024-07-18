<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('admin-page');
    }

    public function view(User $user, User $model)
    {
        return $user->hasRole('admin-page');
    }

    public function create(User $user)
    {
        return $user->hasRole('admin-page');
    }

    public function update(User $user, User $model)
    {
        return $user->hasRole('admin-page');
    }

    public function delete(User $user, User $model)
    {
        return $user->hasRole('admin-page');
    }
}
