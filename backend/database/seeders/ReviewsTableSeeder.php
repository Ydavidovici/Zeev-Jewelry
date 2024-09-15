<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;

class ReviewsTableSeeder extends Seeder
{
    public function run()
    {
        // Get users with the 'customer' role
        $customer1 = User::role('customer')->find(1);
        $customer2 = User::role('customer')->find(2);

        // Get products by ID
        $product1 = Product::find(1);
        $product2 = Product::find(2);

        // Check if users and products exist before seeding
        if ($customer1 && $customer2 && $product1 && $product2) {
            Review::insert([
                [
                    'product_id' => $product1->id,
                    'customer_id' => $customer1->id,
                    'review_text' => 'Great product!',
                    'rating' => 5,
                    'review_date' => now()
                ],
                [
                    'product_id' => $product2->id,
                    'customer_id' => $customer2->id,
                    'review_text' => 'Amazing quality.',
                    'rating' => 4,
                    'review_date' => now()
                ],
            ]);
        }
    }
}
