<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('seller');
    }

    public function view(User $user, Customer $customer)
    {
        return $user->hasRole('admin') || $user->hasRole('seller');
    }

    public function create(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('seller');
    }

    public function update(User $user, Customer $customer)
    {
        return $user->hasRole('admin') || $user->hasRole('seller');
    }

    public function delete(User $user, Customer $customer)
    {
        return $user->hasRole('admin');
    }
}
