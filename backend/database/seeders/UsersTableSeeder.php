<?php

// database/seeders/UsersTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
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
