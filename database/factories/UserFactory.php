<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Role;
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
            'role_id' => Role::factory(),
            'email' => $this->faker->unique()->safeEmail,
        ];
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'role_id' => Role::where('name', 'admin-page')->first()->id, // Ensure role exists in the database
            ];
        });
    }

    public function seller()
    {
        return $this->state(function (array $attributes) {
            return [
                'role_id' => Role::where('name', 'seller-page')->first()->id, // Ensure role exists in the database
            ];
        });
    }

    public function customer()
    {
        return $this->state(function (array $attributes) {
            return [
                'role_id' => Role::where('name', 'customer')->first()->id, // Ensure role exists in the database
            ];
        });
    }
}
