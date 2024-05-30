<?php

// database/seeders/OrdersTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('orders')->insert([
            [
                'customer_id' => 1,
                'order_date' => now(),
                'total_amount' => 799.99,
                'is_guest' => false,
                'status' => 'completed'
            ],
            [
                'customer_id' => 2,
                'order_date' => now(),
                'total_amount' => 299.99,
                'is_guest' => true,
                'status' => 'pending'
            ],
        ]);
    }
}
