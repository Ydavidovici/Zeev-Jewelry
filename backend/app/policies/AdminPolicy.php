<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Admin specific actions
     */
    public function accessDashboard(User $user)
    {
        return $user->hasRole('admin');
    }

    public function manageUsers(User $user)
    {
        return $user->hasRole('admin');
    }

    public function manageRoles(User $user)
    {
        return $user->hasRole('admin');
    }

    public function managePermissions(User $user)
    {
        return $user->hasRole('admin');
    }

    public function manageSettings(User $user)
    {
        return $user->hasRole('admin');
    }

    public function handleWebhooks(User $user)
    {
        return $user->hasRole('admin');
    }

    public function manageReports(User $user)
    {
        return $user->hasRole('admin');
    }

    public function manageShipping(User $user)
    {
        return $user->hasRole('admin');
    }

    public function manageProducts(User $user)
    {
        return $user->hasRole('admin');
    }

    public function manageInventory(User $user)
    {
        return $user->hasRole('admin');
    }

    public function managePayments(User $user)
    {
        return $user->hasRole('admin');
    }

    public function viewSensitiveData(User $user)
    {
        return $user->hasRole('admin');
    }

    public function uploadFiles(User $user)
    {
        return $user->hasRole('admin');
    }

    public function deleteFiles(User $user)
    {
        return $user->hasRole('admin');
    }
}
