<?php

// database/seeders/CustomersTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('customers')->insert([
            [
                'user_id' => 2,
                'address' => '123 Main St, Anytown, USA',
                'phone_number' => '123-456-7890',
                'email' => 'customer1@example.com',
                'is_guest' => false
            ],
            [
                'user_id' => 3,
                'address' => '456 Elm St, Othertown, USA',
                'phone_number' => '987-654-3210',
                'email' => 'guest1@example.com',
                'is_guest' => true
            ],
        ]);
    }
}
