<?php

// database/seeders/UsersTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::insert([
            ['username' => 'admin', 'email' => 'admin@example.com', 'password' => bcrypt('password'), 'role_id' => 1],
            ['username' => 'customer1', 'email' => 'customer1@example.com', 'password' => bcrypt('password'), 'role_id' => 2],
            ['username' => 'guest1', 'email' => 'guest1@example.com', 'password' => bcrypt('password'), 'role_id' => 3],
        ]);
    }
}
