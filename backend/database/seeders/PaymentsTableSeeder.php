<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Order;
use App\Models\User;

class PaymentsTableSeeder extends Seeder
{
    public function run()
    {
        // Assuming you have a seller user created
        $seller = User::where('role', 'Seller')->first();

        // Fetch orders dynamically
        $order1 = Order::where('customer_id', 1)->first();
        $order2 = Order::where('customer_id', 2)->first();

        if ($order1 && $order2) {
            Payment::insert([
                [
                    'order_id' => $order1->id,
                    'payment_status' => 'processed',
                    'payment_type' => 'Credit Card',
                    'seller_id' => $seller->id,
                    'amount' => 100.00,
                ],
                [
                    'order_id' => $order2->id,
                    'payment_status' => 'pending',
                    'payment_type' => 'PayPal',
                    'seller_id' => $seller->id,
                    'amount' => 50.00,
                ],
            ]);
        }
    }
}
