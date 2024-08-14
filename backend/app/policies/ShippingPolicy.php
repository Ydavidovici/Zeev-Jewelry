<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Shipping;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShippingPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('admin-page') || $user->hasRole('seller-page');
    }

    public function view(User $user, Shipping $shipping)
    {
        return $user->hasRole('admin-page') || $user->hasRole('seller-page');
    }

    public function create(User $user)
    {
        return $user->hasRole('admin-page') || $user->hasRole('seller-page');
    }

    public function update(User $user, Shipping $shipping)
    {
        return $user->hasRole('admin-page') || $user->hasRole('seller-page');
    }

    public function delete(User $user, Shipping $shipping)
    {
        return $user->hasRole('admin-page');
    }
}
