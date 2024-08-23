<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Customer;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        // Fetch the customer records
        $customer1 = Customer::where('email', 'customer1@example.com')->first();
        $customer2 = Customer::where('email', 'guest1@example.com')->first();

        // Insert orders with the correct customer IDs
        Order::insert([
            ['customer_id' => $customer1->id, 'seller_id' => 1, 'order_date' => now(), 'total_amount' => 799.99, 'is_guest' => false, 'status' => 'completed'],
            ['customer_id' => $customer2->id, 'seller_id' => 1, 'order_date' => now(), 'total_amount' => 299.99, 'is_guest' => true, 'status' => 'pending'],
        ]);
    }
}
