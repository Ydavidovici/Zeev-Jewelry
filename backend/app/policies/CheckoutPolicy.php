<?php

namespace App\policies;

use App\Models\User;
use App\Models\Checkout;
use Illuminate\Auth\Access\HandlesAuthorization;

class CheckoutPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any checkouts.
     */
    public function viewAny(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('customer');
    }

    /**
     * Determine whether the user can view the checkout.
     */
    public function view(User $user, Checkout $checkout)
    {
        return $user->id === $checkout->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create checkouts.
     */
    public function create(User $user)
    {
        return $user->hasRole('customer');
    }

    /**
     * Determine whether the user can update the checkout.
     */
    public function update(User $user, Checkout $checkout)
    {
        return $user->id === $checkout->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the checkout.
     */
    public function delete(User $user, Checkout $checkout)
    {
        return $user->id === $checkout->user_id || $user->hasRole('admin');
    }
}
