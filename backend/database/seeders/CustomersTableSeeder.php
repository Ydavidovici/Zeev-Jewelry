<?php

// database/seeders/CustomersTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;

class CustomersTableSeeder extends Seeder
{
    public function run()
    {
        $customer1 = User::where('email', 'customer1@example.com')->first();
        $guest1 = User::where('email', 'guest1@example.com')->first();

        if ($customer1 && $guest1) {
            Customer::insert([
                ['user_id' => $customer1->id, 'address' => '123 Main St, Anytown, USA', 'email' => 'customer1@example.com', 'phone_number' => '123-456-7890', 'is_guest' => false],
                ['user_id' => $guest1->id, 'address' => '456 Elm St, Othertown, USA', 'email' => 'guest1@example.com', 'phone_number' => '987-654-3210', 'is_guest' => true],
            ]);
        } else {
            \Log::channel('custom')->error('One or more users could not be found for customer seeding.');
        }
    }
}
