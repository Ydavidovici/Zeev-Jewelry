<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Ensure roles exist before seeding users
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'seller', 'guard_name' => 'api']);

        if (app()->environment('local', 'testing')) {
            User::query()->delete();
        }

        // Seed users with roles
        $adminUser = User::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'username' => 'admin',
        ]);
        $adminUser->assignRole('admin');

        $customerUser = User::create([
            'email' => 'customer1@example.com',
            'password' => Hash::make('password'),
            'username' => 'customer1',
        ]);
        $customerUser->assignRole('customer');

        $sellerUser = User::create([
            'email' => 'seller1@example.com',
            'password' => Hash::make('password'),
            'username' => 'seller1',
        ]);
        $sellerUser->assignRole('seller');
    }
}
