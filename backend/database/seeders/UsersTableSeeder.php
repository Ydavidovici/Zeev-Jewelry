<?php

// database/seeders/UsersTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role_id' => 1,
            ],
            [
                'username' => 'customer1',
                'password' => Hash::make('password'),
                'role_id' => 2,
            ],
            [
                'username' => 'guest1',
                'password' => Hash::make('password'),
                'role_id' => 3,
            ],
        ]);
    }
}
