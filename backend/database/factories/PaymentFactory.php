<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'order_id' => Order::factory(),
            'seller_id' => User::factory()->create(['role' => 'seller'])->id,
            'payment_type' => $this->faker->word,
            'payment_status' => $this->faker->randomElement(['processed', 'failed', 'pending']),
            'amount' => $this->faker->randomFloat(2, 1, 1000),
        ];
    }
}
