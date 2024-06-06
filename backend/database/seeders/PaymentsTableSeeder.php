<?php

// database/seeders/PaymentsTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;

class PaymentsTableSeeder extends Seeder
{
    public function run()
    {
        Payment::insert([
            ['order_id' => 1, 'payment_type' => 'Credit Card', 'payment_status' => 'processed'],
            ['order_id' => 2, 'payment_type' => 'PayPal', 'payment_status' => 'pending'],
        ]);
    }
}
