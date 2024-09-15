<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        // Create a seller user and assign the 'seller' role
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        return [
            'seller_id' => $seller->id,             // Set the seller's ID
            'name' => $this->faker->word,           // Example product name
            'description' => $this->faker->paragraph,
            'image_url' => 'path/to/default-image.jpg',  // Provide a default image path
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'category_id' => Category::factory(),   // Create category using factory
            'stock_quantity' => $this->faker->numberBetween(1, 100),
            'is_featured' => $this->faker->boolean,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
