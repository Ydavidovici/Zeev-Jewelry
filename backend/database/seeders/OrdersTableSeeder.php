<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        // Get a user with the 'customer' role
        $customer = User::role('customer')->first();

        // Get a user with the 'seller' role
        $seller = User::role('seller')->first();

        // Check if both customer and seller exist
        if ($customer && $seller) {
            Order::insert([
                [
                    'customer_id' => $customer->id, // Reference the correct user's ID
                    'is_guest' => false,
                    'order_date' => now(),
                    'payment_intent_id' => null, // Use appropriate value if needed
                    'seller_id' => $seller->id,
                    'status' => 'completed',
                    'total_amount' => 799.99,
                ],
                [
                    'customer_id' => $customer->id, // Reference the correct user's ID
                    'is_guest' => true,
                    'order_date' => now(),
                    'payment_intent_id' => null, // Use appropriate value if needed
                    'seller_id' => $seller->id,
                    'status' => 'pending',
                    'total_amount' => 299.99,
                ],
            ]);

            $this->command->info('Orders seeded successfully.');
        } else {
            $this->command->info('Customer or seller not found, skipping orders seeder.');
        }
    }
}
