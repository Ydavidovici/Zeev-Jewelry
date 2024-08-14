<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::insert([
            [
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'Admin',
                'username' => 'admin',
            ],
            [
                'email' => 'customer1@example.com',
                'password' => Hash::make('password'),
                'role' => 'Customer',
                'username' => 'customer1',
            ],
            [
                'email' => 'guest1@example.com',
                'password' => Hash::make('password'),
                'role' => 'Guest',
                'username' => 'guest1',
            ],
            [
                'email' => 'seller1@example.com',
                'password' => Hash::make('password'),
                'role' => 'Seller',
                'username' => 'seller1',
            ],
        ]);
    }
}
