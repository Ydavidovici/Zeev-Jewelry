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
        // Create a user without the 'role' attribute directly and assign the role properly
        $seller = User::factory()->create();
        $seller->assignRole('seller'); // Properly assign role without setting a non-existent column

        return [
            'seller_id' => $seller->id, // Use the seller's ID after assigning the role
            'product_name' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'category_id' => Category::factory(),
            'image_url' => $this->faker->imageUrl,
        ];
    }
}
