<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shipping;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class ShippingTableSeeder extends Seeder
{
    public function run()
    {
        // Find the first user with the 'seller' role
        $seller = User::role('seller')->first();

        // Find the first two orders dynamically
        $orders = Order::take(2)->get();

        if ($orders->count() === 2 && $seller) {
            Shipping::insert([
                [
                    'order_id' => $orders[0]->id,
                    'shipping_status' => 'shipped',
                    'shipping_type' => 'Standard',
                    'shipping_cost' => 9.99,
                    'shipping_carrier' => 'UPS',
                    'tracking_number' => '1Z999AA10123456784',
                    'estimated_delivery_date' => Carbon::now(),
                    'shipping_address' => '123 Main St, Anytown, USA',
                    'recipient_name' => 'John Doe',
                    'additional_notes' => 'Leave at the front door.',
                    'seller_id' => $seller->id,
                    'city' => 'Anytown',
                    'state' => 'NY',
                    'postal_code' => '10001',
                    'country' => 'USA',
                    'shipping_method' => 'Ground',
                ],
                [
                    'order_id' => $orders[1]->id,
                    'shipping_status' => 'pending',
                    'shipping_type' => 'Express',
                    'shipping_cost' => 19.99,
                    'shipping_carrier' => 'FedEx',
                    'tracking_number' => '1Z999BB10123456785',
                    'estimated_delivery_date' => Carbon::now()->addDays(2),
                    'shipping_address' => '456 Elm St, Othertown, USA',
                    'recipient_name' => 'Jane Smith',
                    'additional_notes' => 'Handle with care.',
                    'seller_id' => $seller->id,
                    'city' => 'Othertown',
                    'state' => 'CA',
                    'postal_code' => '90001',
                    'country' => 'USA',
                    'shipping_method' => 'Air',
                ],
            ]);
        } else {
            $this->command->info('Orders or Seller not found, skipping shipping seeder.');
        }
    }
}
