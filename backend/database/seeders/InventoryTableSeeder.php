<?php

// database/seeders/InventoryTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\Product;

class InventoryTableSeeder extends Seeder
{
    public function run()
    {
        $product1 = Product::where('product_name', 'Gold Necklace')->first();
        $product2 = Product::where('product_name', 'Diamond Ring')->first();

        if ($product1 && $product2) {
            Inventory::insert([
                ['location' => 'Warehouse 1', 'product_id' => $product1->id, 'quantity' => 100],
                ['location' => 'Warehouse 2', 'product_id' => $product2->id, 'quantity' => 50],
            ]);
        }
    }
}
