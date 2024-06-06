<?php

// database/seeders/CustomersTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomersTableSeeder extends Seeder
{
    public function run()
    {
        Customer::insert([
            ['user_id' => 2, 'address' => '123 Main St, Anytown, USA', 'email' => 'customer1@example.com', 'phone_number' => '123-456-7890', 'is_guest' => false],
            ['user_id' => 3, 'address' => '456 Elm St, Othertown, USA', 'email' => 'guest1@example.com', 'phone_number' => '987-654-3210', 'is_guest' => true],
        ]);
    }
}
