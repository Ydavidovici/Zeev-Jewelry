<?php

namespace App\policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any reports.
     */
    public function viewAny(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('seller');
    }

    /**
     * Determine whether the user can view the report.
     */
    public function view(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('seller');
    }

    /**
     * Determine whether the user can create reports.
     */
    public function create(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('seller');
    }
}
