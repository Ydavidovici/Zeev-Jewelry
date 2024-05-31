<?php

// database/seeders/OrderDetailsTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderDetail;

class OrderDetailsTableSeeder extends Seeder
{
    public function run()
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        OrderDetail::insert([
            [
                'order_id' => 1,
                'product_id' => 2,
                'quantity' => 1,
                'price' => 799.99
            ],
            [
                'order_id' => 2,
                'product_id' => 3,
                'quantity' => 1,
                'price' => 299.99
            ],
        ]);
    }
}
