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
        // Find the first seller user
        $seller = User::role('seller')->first();

        // Fetch the first two orders
        $orders = Order::take(2)->get();

        if ($orders->count() === 2 && $seller) {
            Payment::insert([
                [
                    'order_id' => $orders[0]->id,
                    'payment_status' => 'processed',
                    'payment_type' => 'Credit Card',
                    'seller_id' => $seller->id,
                    'amount' => 100.00,
                ],
                [
                    'order_id' => $orders[1]->id,
                    'payment_status' => 'pending',
                    'payment_type' => 'PayPal',
                    'seller_id' => $seller->id,
                    'amount' => 50.00,
                ],
            ]);
        } else {
            $this->command->info('Seller or orders not found, skipping payments seeder.');
        }
    }
}
