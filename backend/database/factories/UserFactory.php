<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'username' => $this->faker->unique()->userName,
            'password' => Hash::make('password'),
            'email' => $this->faker->unique()->safeEmail,
        ];
    }

    public function admin()
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('admin');
        });
    }

    public function seller()
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('seller');
        });
    }

    public function customer()
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('customer');
        });
    }
}
