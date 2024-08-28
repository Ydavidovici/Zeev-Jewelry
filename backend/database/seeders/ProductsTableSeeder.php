<?php

// database/seeders/ProductsTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        // Find the first user with the 'seller' role using Spatie's role checking
        $seller = User::role('seller')->first();  // Using Spatie's role query scope

        if ($seller) {
            Product::insert([
                ['category_id' => 1, 'seller_id' => $seller->id, 'product_name' => 'Gold Necklace', 'description' => 'A beautiful gold necklace', 'price' => 499.99, 'image_url' => 'path/to/gold-necklace.jpg'],
                ['category_id' => 2, 'seller_id' => $seller->id, 'product_name' => 'Diamond Ring', 'description' => 'A stunning diamond ring', 'price' => 799.99, 'image_url' => 'path/to/diamond-ring.jpg'],
            ]);
        }
    }
}
