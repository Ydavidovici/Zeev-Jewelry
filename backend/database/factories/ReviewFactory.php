<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\Product;
use App\Models\User; // Assuming User is used here now instead of Customer
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory()->create()->id,
            'review_text' => $this->faker->sentence,
            'rating' => $this->faker->numberBetween(1, 5),
            'review_date' => $this->faker->dateTime,
        ];
    }
}
