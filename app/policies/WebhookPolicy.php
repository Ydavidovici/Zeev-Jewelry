<?php

namespace App\policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WebhookPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can handle webhooks.
     */
    public function handle(User $user)
    {
        return $user->hasRole('admin');
    }
}
