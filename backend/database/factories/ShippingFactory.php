<?php

namespace Database\Factories;

use App\Models\Shipping;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingFactory extends Factory
{
    protected $model = Shipping::class;

    public function definition()
    {
        return [
            'order_id' => Order::factory(),
            'seller_id' => User::factory()->create(['role' => 'seller'])->id,
            'shipping_type' => $this->faker->word,
            'shipping_cost' => $this->faker->randomFloat(2, 1, 100),
            'shipping_status' => $this->faker->randomElement(['shipped', 'pending', 'delivered']),
            'tracking_number' => $this->faker->uuid,
            'shipping_address' => $this->faker->address,
            'shipping_carrier' => $this->faker->word,
            'recipient_name' => $this->faker->name,
            'estimated_delivery_date' => $this->faker->dateTime,
            'additional_notes' => $this->faker->paragraph,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'postal_code' => $this->faker->postcode,
            'country' => $this->faker->country,
            'shipping_method' => $this->faker->randomElement(['Ground', 'Air', 'Sea']),
        ];
    }
}
