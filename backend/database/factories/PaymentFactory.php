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
        // Create a user and assign the 'seller' role properly
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        return [
            'order_id' => Order::factory(),
            'seller_id' => $seller->id, // Use the seller's ID after assigning the role
            'payment_type' => $this->faker->word,
            'payment_status' => $this->faker->randomElement(['processed', 'failed', 'pending']),
            'amount' => $this->faker->randomFloat(2, 1, 1000),
        ];
    }
}
