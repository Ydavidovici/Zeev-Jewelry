<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Customer;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        // Get the first user with the 'customer' role
        $customer = User::role('customer')->first();
        // Get the first user with the 'seller' role
        $seller = User::role('seller')->first();

        // Find the customer model using the user ID
        $customerEntry = $customer ? Customer::where('user_id', $customer->id)->first() : null;

        // Check if both customerEntry and seller exist
        if ($customerEntry && $seller) {
            Order::insert([
                [
                    'customer_id' => $customerEntry->id, // Use customerEntry ID
                    'seller_id' => $seller->id,
                    'order_date' => now(),
                    'total_amount' => 799.99,
                    'is_guest' => false,
                    'status' => 'completed',
                ],
                [
                    'customer_id' => $customerEntry->id, // Use customerEntry ID
                    'seller_id' => $seller->id,
                    'order_date' => now(),
                    'total_amount' => 299.99,
                    'is_guest' => true,
                    'status' => 'pending',
                ],
            ]);
        } else {
            $this->command->info('Customer or seller not found, skipping orders seeder.');
        }
    }
}
