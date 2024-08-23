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
        // Assuming you have a seller user created
        $seller = User::where('role', 'Seller')->first();

        // Fetch orders dynamically
        $order1 = Order::where('customer_id', 1)->first();
        $order2 = Order::where('customer_id', 2)->first();

        if ($order1 && $order2) {
            Shipping::insert([
                [
                    'order_id' => $order1->id,
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
                ],
                [
                    'order_id' => $order2->id,
                    'shipping_status' => 'pending',
                    'shipping_type' => 'Express',
                    'shipping_cost' => 19.99,
                    'shipping_carrier' => 'FedEx',
                    'tracking_number' => '1Z999BB10123456785',
                    'estimated_delivery_date' => Carbon::now(),
                    'shipping_address' => '456 Elm St, Othertown, USA',
                    'recipient_name' => 'Jane Smith',
                    'additional_notes' => 'Handle with care.',
                    'seller_id' => $seller->id,
                ],
            ]);
        }
    }
}
