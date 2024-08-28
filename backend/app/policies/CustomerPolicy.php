<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    /**
     * Customer specific actions
     */
    public function manageCart(User $user)
    {
        return $user->hasRole('customer');
    }

    public function viewOrders(User $user)
    {
        return $user->hasRole('customer');
    }

    public function manageProfile(User $user)
    {
        return $user->hasRole('customer');
    }

    public function writeReview(User $user)
    {
        return $user->hasRole('customer');
    }

    public function viewCheckout(User $user)
    {
        return $user->hasRole('customer');
    }

    public function manageCheckout(User $user)
    {
        return $user->hasRole('customer');
    }

    public function viewPayments(User $user)
    {
        return $user->hasRole('customer');
    }
}
