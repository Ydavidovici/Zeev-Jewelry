<?php

// database/seeders/OrderDetailsTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderDetail;

class OrderDetailsTableSeeder extends Seeder
{
    public function run()
    {
        OrderDetail::insert([
            ['order_id' => 1, 'product_id' => 1, 'quantity' => 2, 'price' => 50],
            ['order_id' => 2, 'product_id' => 2, 'quantity' => 1, 'price' => 50],
        ]);
    }
}
