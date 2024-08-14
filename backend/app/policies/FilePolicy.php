<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can upload files.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function upload(User $user)
    {
        // Only allow users with specific roles to upload files
        return $user->hasRole('admin') || $user->hasRole('seller');
    }

    /**
     * Determine whether the user can delete files.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        // Only allow users with specific roles to delete files
        return $user->hasRole('admin');
    }
}
