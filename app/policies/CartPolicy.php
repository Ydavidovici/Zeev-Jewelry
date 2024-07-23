<?php

namespace App\policies;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any carts.
     */
    public function viewAny(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('customer');
    }

    /**
     * Determine whether the user can view the cart.
     */
    public function view(User $user, Cart $cart)
    {
        return $user->id === $cart->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create carts.
     */
    public function create(User $user)
    {
        return $user->hasRole('customer');
    }

    /**
     * Determine whether the user can update the cart.
     */
    public function update(User $user, Cart $cart)
    {
        return $user->id === $cart->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the cart.
     */
    public function delete(User $user, Cart $cart)
    {
        return $user->id === $cart->user_id || $user->hasRole('admin');
    }
}
