<?php

// database/seeders/OrderDetailsTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Product;

class OrderDetailsTableSeeder extends Seeder
{
    public function run()
    {
        $order1 = Order::find(1);
        $order2 = Order::find(2);

        $product1 = Product::find(1);
        $product2 = Product::find(2);

        if ($order1 && $order2 && $product1 && $product2) {
            OrderDetail::insert([
                ['order_id' => $order1->id, 'product_id' => $product1->id, 'quantity' => 2, 'price' => 50],
                ['order_id' => $order2->id, 'product_id' => $product2->id, 'quantity' => 1, 'price' => 50],
            ]);
        }
    }
}
