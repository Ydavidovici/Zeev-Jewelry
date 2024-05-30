<?php

// database/seeders/ShippingTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('shipping')->insert([
            [
                'order_id' => 1,
                'shipping_type' => 'Standard',
                'shipping_cost' => 9.99,
                'shipping_status' => 'shipped'
            ],
            [
                'order_id' => 2,
                'shipping_type' => 'Express',
                'shipping_cost' => 19.99,
                'shipping_status' => 'pending'
            ],
        ]);
    }
}
