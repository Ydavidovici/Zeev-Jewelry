<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuthPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can change their password.
     */
    public function changePassword(User $user)
    {
        return $user->isAuthenticated();
    }

    /**
     * Determine whether the user can reset passwords.
     */
    public function resetPassword(User $user)
    {
        return $user->isAuthenticated();
    }

    /**
     * Determine whether the user can register.
     */
    public function register(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can login.
     */
    public function login(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can logout.
     */
    public function logout(User $user)
    {
        return $user->isAuthenticated();
    }
}
