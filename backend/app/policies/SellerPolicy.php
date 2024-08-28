<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SellerPolicy
{
    use HandlesAuthorization;

    /**
     * Seller specific actions
     */
    public function manageProducts(User $user)
    {
        return $user->hasRole('seller');
    }

    public function manageOrders(User $user)
    {
        return $user->hasRole('seller');
    }

    public function manageInventory(User $user)
    {
        return $user->hasRole('seller');
    }

    public function manageShipping(User $user)
    {
        return $user->hasRole('seller');
    }

    public function managePayments(User $user)
    {
        return $user->hasRole('seller');
    }

    public function manageReports(User $user)
    {
        return $user->hasRole('seller');
    }

    public function uploadFiles(User $user)
    {
        return $user->hasRole('seller');
    }
}
