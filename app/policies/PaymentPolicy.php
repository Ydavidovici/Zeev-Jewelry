<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('admin-page') || $user->hasRole('seller-page') || $user->hasRole('customer');
    }

    public function view(User $user, Payment $payment)
    {
        return $user->hasRole('admin-page') || $user->hasRole('seller-page') || $user->hasRole('customer');
    }

    public function create(User $user)
    {
        return $user->hasRole('admin-page') || $user->hasRole('seller-page') || $user->hasRole('customer');
    }

    public function update(User $user, Payment $payment)
    {
        return $user->hasRole('admin-page') || $user->hasRole('seller-page');
    }

    public function delete(User $user, Payment $payment)
    {
        return $user->hasRole('admin-page');
    }
}
