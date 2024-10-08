<?php

namespace Database\Factories;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    protected $model = Inventory::class;

    public function definition()
    {
        // Create a user and assign the 'seller' role properly
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        return [
            'product_id' => Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'location' => $this->faker->word,
            'seller_id' => $seller->id,
        ];
    }
}
