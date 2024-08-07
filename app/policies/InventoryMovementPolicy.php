<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InventoryMovement;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryMovementPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('admin-page') || $user->hasRole('seller-page');
    }

    public function view(User $user, InventoryMovement $inventoryMovement)
    {
        return $user->hasRole('admin-page') || $user->hasRole('seller-page');
    }

    public function create(User $user)
    {
        return $user->hasRole('admin-page') || $user->hasRole('seller-page');
    }

    public function update(User $user, InventoryMovement $inventoryMovement)
    {
        return $user->hasRole('admin-page') || $user->hasRole('seller-page');
    }

    public function delete(User $user, InventoryMovement $inventoryMovement)
    {
        return $user->hasRole('admin-page');
    }
}
