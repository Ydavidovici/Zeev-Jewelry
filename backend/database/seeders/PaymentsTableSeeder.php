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

        Payment::insert([
            [
                'order_id' => 1,
                'payment_status' => 'processed',
                'payment_type' => 'Credit Card',
                'seller_id' => $seller->id,
            ],
            [
                'order_id' => 2,
                'payment_status' => 'pending',
                'payment_type' => 'PayPal',
                'seller_id' => $seller->id,
            ],
        ]);
    }
}
