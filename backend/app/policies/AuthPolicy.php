<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class AuthPolicy
{
    use HandlesAuthorization;

    public function changePassword(User $user)
    {
        Log::channel('custom')->info('Checking changePassword policy', ['user' => $user->email]);
        return !is_null($user);
    }

    public function resetPassword(User $user)
    {
        Log::channel('custom')->info('Checking resetPassword policy', ['user' => $user->email]);
        return !is_null($user);
    }

    public function register(?User $user)
    {
        Log::channel('custom')->info('Checking register policy', ['user' => $user ? $user->email : 'guest']);
        return is_null($user);
    }

    public function login(?User $user)
    {
        Log::channel('custom')->info('Checking login policy', ['user' => $user ? $user->email : 'guest']);
        return is_null($user);
    }

    public function logout(User $user)
    {
        Log::channel('custom')->info('Checking logout policy', ['user' => $user->email]);
        return !is_null($user);
    }
}
