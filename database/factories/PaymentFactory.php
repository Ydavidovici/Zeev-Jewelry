<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'order_id' => Order::factory(),
            'payment_type' => $this->faker->word,
            'payment_status' => $this->faker->randomElement(['processed', 'failed', 'pending']),
        ];
    }
}
