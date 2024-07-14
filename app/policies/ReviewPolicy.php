<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Review;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('seller') || $user->hasRole('customer');
    }

    public function view(User $user, Review $review)
    {
        return $user->hasRole('admin') || $user->hasRole('seller') || $user->hasRole('customer');
    }

    public function create(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('customer');
    }

    public function update(User $user, Review $review)
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Review $review)
    {
        return $user->hasRole('admin');
    }
}
