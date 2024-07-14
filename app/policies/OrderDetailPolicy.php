<?php

namespace App\Policies;

use App\Models\User;
use App\Models\OrderDetail;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderDetailPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('seller') || $user->hasRole('customer');
    }

    public function view(User $user, OrderDetail $orderDetail)
    {
        return $user->hasRole('admin') || $user->hasRole('seller') || $user->hasRole('customer');
    }

    public function create(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('seller') || $user->hasRole('customer');
    }

    public function update(User $user, OrderDetail $orderDetail)
    {
        return $user->hasRole('admin') || $user->hasRole('seller');
    }

    public function delete(User $user, OrderDetail $orderDetail)
    {
        return $user->hasRole('admin');
    }
}
