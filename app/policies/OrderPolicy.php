<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('admin-page') || $user->hasRole('seller-page') || $user->hasRole('customer');
    }

    public function view(User $user, Order $order)
    {
        return $user->hasRole('admin-page') || $user->hasRole('seller-page') || $user->hasRole('customer');
    }

    public function create(User $user)
    {
        return $user->hasRole('admin-page') || $user->hasRole('seller-page') || $user->hasRole('customer');
    }

    public function update(User $user, Order $order)
    {
        return $user->hasRole('admin-page') || $user->hasRole('seller-page');
    }

    public function delete(User $user, Order $order)
    {
        return $user->hasRole('admin-page');
    }
}
