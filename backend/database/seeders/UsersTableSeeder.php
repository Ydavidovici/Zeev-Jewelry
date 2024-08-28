<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Delete all users and customers to prevent duplication
        User::query()->delete();
        Customer::query()->delete();

        // Seed users with roles and corresponding customers
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

        // Create a corresponding customer entry for the customer user
        Customer::create([
            'user_id' => $customerUser->id, // Foreign key to users table
            'address' => '123 Main St, Anytown, USA',
            'email' => 'customer1@example.com',
            'phone_number' => '123-456-7890',
            'is_guest' => false,
        ]);

        $sellerUser = User::create([
            'email' => 'seller1@example.com',
            'password' => Hash::make('password'),
            'username' => 'seller1',
        ]);
        $sellerUser->assignRole('seller');
    }
}
