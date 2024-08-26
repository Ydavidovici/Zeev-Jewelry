<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'customer_id' => Customer::factory(),
            'review_text' => $this->faker->paragraph,
            'rating' => $this->faker->numberBetween(1, 5),
            'review_date' => $this->faker->dateTime(),
        ];
    }
}
