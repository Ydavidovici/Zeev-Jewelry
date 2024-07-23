<?php

namespace App\policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SellerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the seller dashboard.
     */
    public function viewDashboard(User $user)
    {
        return $user->hasRole('seller');
    }

    /**
     * Determine whether the user can manage products.
     */
    public function manageProducts(User $user)
    {
        return $user->hasRole('seller');
    }

    /**
     * Determine whether the user can manage orders.
     */
    public function manageOrders(User $user)
    {
        return $user->hasRole('seller');
    }

    /**
     * Determine whether the user can manage inventory.
     */
    public function manageInventory(User $user)
    {
        return $user->hasRole('seller');
    }

    /**
     * Determine whether the user can manage shipping.
     */
    public function manageShipping(User $user)
    {
        return $user->hasRole('seller');
    }

    /**
     * Determine whether the user can manage payments.
     */
    public function managePayments(User $user)
    {
        return $user->hasRole('seller');
    }
}
