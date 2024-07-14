<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('seller') || $user->hasRole('customer');
    }

    public function view(User $user, Product $product)
    {
        return $user->hasRole('admin') || $user->hasRole('seller') || $user->hasRole('customer');
    }

    public function create(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('seller');
    }

    public function update(User $user, Product $product)
    {
        return $user->hasRole('admin') || $user->hasRole('seller');
    }

    public function delete(User $user, Product $product)
    {
        return $user->hasRole('admin');
    }
}
