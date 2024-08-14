<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Assign a role to the user.
     *
     * @param string $role
     * @return void
     */
    public function assignUserRole(string $role)
    {
        $this->assignRole($role);
    }

    /**
     * Determine if the user has the admin role.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Determine if the user has the customer role.
     *
     * @return bool
     */
    public function isCustomer()
    {
        return $this->hasRole('customer');
    }

    /**
     * Determine if the user has the seller role.
     *
     * @return bool
     */
    public function isSeller()
    {
        return $this->hasRole('seller');
    }
}
