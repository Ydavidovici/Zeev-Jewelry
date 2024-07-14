<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('seller') || $user->hasRole('customer');
    }

    public function view(User $user, Category $category)
    {
        return $user->hasRole('admin') || $user->hasRole('seller') || $user->hasRole('customer');
    }

    public function create(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('seller');
    }

    public function update(User $user, Category $category)
    {
        return $user->hasRole('admin') || $user->hasRole('seller');
    }

    public function delete(User $user, Category $category)
    {
        return $user->hasRole('admin');
    }
}
