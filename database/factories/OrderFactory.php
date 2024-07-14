<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'customer_id' => Customer::factory(),
            'order_date' => $this->faker->dateTime,
            'total_amount' => $this->faker->randomFloat(2, 1, 1000),
            'is_guest' => $this->faker->boolean,
            'status' => $this->faker->randomElement(['pending', 'completed', 'shipped', 'canceled']),
        ];
    }
}
