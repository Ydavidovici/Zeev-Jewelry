<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can access the admin dashboard.
     */
    public function accessDashboard(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can manage users.
     */
    public function manageUsers(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can manage roles.
     */
    public function manageRoles(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can manage permissions.
     */
    public function managePermissions(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can manage settings.
     */
    public function manageSettings(User $user)
    {
        return $user->hasRole('admin');
    }
}
