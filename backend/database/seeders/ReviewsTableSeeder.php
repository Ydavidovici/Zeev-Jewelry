<?php

// database/seeders/ReviewsTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Customer;
use App\Models\Product;

class ReviewsTableSeeder extends Seeder
{
    public function run()
    {
        $customer1 = Customer::find(1);
        $customer2 = Customer::find(2);
        $product1 = Product::find(1);
        $product2 = Product::find(2);

        if ($customer1 && $customer2 && $product1 && $product2) {
            Review::insert([
                ['product_id' => $product1->id, 'customer_id' => $customer1->id, 'review_text' => 'Great product!', 'rating' => 5, 'review_date' => now()],
                ['product_id' => $product2->id, 'customer_id' => $customer2->id, 'review_text' => 'Amazing quality.', 'rating' => 4, 'review_date' => now()],
            ]);
        }
    }
}
