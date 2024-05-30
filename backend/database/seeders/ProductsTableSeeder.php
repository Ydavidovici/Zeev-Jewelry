<?php

// database/seeders/ProductsTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('products')->insert([
            [
                'product_name' => 'Gold Necklace',
                'description' => 'A beautiful gold necklace',
                'price' => 499.99,
                'category_id' => 1,
                'image_url' => 'path/to/gold-necklace.jpg'
            ],
            [
                'product_name' => 'Diamond Ring',
                'description' => 'A stunning diamond ring',
                'price' => 799.99,
                'category_id' => 2,
                'image_url' => 'path/to/diamond-ring.jpg'
            ],
            [
                'product_name' => 'Pearl Earrings',
                'description' => 'Elegant pearl earrings',
                'price' => 299.99,
                'category_id' => 3,
                'image_url' => 'path/to/pearl-earrings.jpg'
            ],
        ]);
    }
}
