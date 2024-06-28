<?php


// database/seeders/ShippingTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shipping;

class ShippingTableSeeder extends Seeder
{
    public function run()
    {
        Shipping::insert([
            [
                'order_id' => 1,
                'shipping_type' => 'Standard',
                'shipping_cost' => 9.99,
                'shipping_status' => 'shipped',
                'tracking_number' => '1Z999AA10123456784',
                'shipping_address' => '123 Main St, Anytown, USA',
                'shipping_carrier' => 'UPS',
                'recipient_name' => 'John Doe',
                'estimated_delivery_date' => now(),
                'additional_notes' => 'Leave at the front door.'
            ],
            [
                'order_id' => 2,
                'shipping_type' => 'Express',
                'shipping_cost' => 19.99,
                'shipping_status' => 'pending',
                'tracking_number' => '1Z999BB10123456785',
                'shipping_address' => '456 Elm St, Othertown, USA',
                'shipping_carrier' => 'FedEx',
                'recipient_name' => 'Jane Smith',
                'estimated_delivery_date' => now(),
                'additional_notes' => 'Handle with care.'
            ],
        ]);
    }
}
