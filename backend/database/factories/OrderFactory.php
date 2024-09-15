<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        // Create a seller user and assign the 'seller' role
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        return [
            'customer_id' => User::factory(),   // Create customer using factory
            'seller_id' => $seller->id,         // Assign seller ID
            'order_date' => $this->faker->dateTime,
            'total_amount' => $this->faker->randomFloat(2, 1, 1000),
            'is_guest' => $this->faker->boolean,
            'status' => $this->faker->randomElement(['pending', 'completed', 'shipped', 'canceled']),
        ];
    }
}
