<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Inventory;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('seller');
    }

    public function view(User $user, Inventory $inventory)
    {
        return $user->hasRole('admin') || $user->hasRole('seller');
    }

    public function create(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('seller');
    }

    public function update(User $user, Inventory $inventory)
    {
        return $user->hasRole('admin') || $user->hasRole('seller');
    }

    public function delete(User $user, Inventory $inventory)
    {
        return $user->hasRole('admin');
    }
}
