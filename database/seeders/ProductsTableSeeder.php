<?php

// database/seeders/ProductsTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        Product::insert([
            ['category_id' => 1, 'product_name' => 'Gold Necklace', 'description' => 'A beautiful gold necklace', 'price' => 499.99, 'image_url' => 'path/to/gold-necklace.jpg'],
            ['category_id' => 2, 'product_name' => 'Diamond Ring', 'description' => 'A stunning diamond ring', 'price' => 799.99, 'image_url' => 'path/to/diamond-ring.jpg'],
            ['category_id' => 3, 'product_name' => 'Pearl Earrings', 'description' => 'Elegant pearl earrings', 'price' => 299.99, 'image_url' => 'path/to/pearl-earrings.jpg'],
        ]);
    }
}
