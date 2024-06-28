<?php

// app/Policies/RolePolicy.php
namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        // Define your logic for allowing a user to create a role
        return $user->hasPermission('create-role');
    }
}
