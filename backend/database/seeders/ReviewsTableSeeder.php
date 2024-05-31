<?php

// database/seeders/ReviewsTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Review::insert([
            [
                'product_id' => 2,
                'customer_id' => 1,
                'review_text' => 'Excellent quality!',
                'rating' => 5,
                'review_date' => now()
            ],
            [
                'product_id' => 3,
                'customer_id' => 2,
                'review_text' => 'Good value for money.',
                'rating' => 4,
                'review_date' => now()
            ],
        ]);
    }
}
