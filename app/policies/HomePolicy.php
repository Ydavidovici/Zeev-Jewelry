<?php

namespace App\policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HomePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the home page.
     */
    public function view(User $user)
    {
        return true; // All users can view the home page
    }
}
