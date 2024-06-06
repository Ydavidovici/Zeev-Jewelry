<?php

// database/seeders/ReviewsTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewsTableSeeder extends Seeder
{
    public function run()
    {
        Review::insert([
            ['product_id' => 1, 'customer_id' => 1, 'review_text' => 'Great product!', 'rating' => 5, 'review_date' => now()],
            ['product_id' => 2, 'customer_id' => 2, 'review_text' => 'Amazing quality.', 'rating' => 4, 'review_date' => now()],
        ]);
    }
}
