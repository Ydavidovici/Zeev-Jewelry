<?php

// database/seeders/InventoryTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;

class InventoryTableSeeder extends Seeder
{
    public function run()
    {
        $product1 = Product::where('name', 'Gold Necklace')->first();
        $product2 = Product::where('name', 'Diamond Ring')->first();

        // Assuming 'seller1' is the seller you want to use for this inventory
        $seller = User::where('username', 'seller1')->first();

        if ($product1 && $product2 && $seller) {
            Inventory::insert([
                [
                    'location' => 'Warehouse 1',
                    'product_id' => $product1->id,
                    'quantity' => 100,
                    'seller_id' => $seller->id
                ],
                [
                    'location' => 'Warehouse 2',
                    'product_id' => $product2->id,
                    'quantity' => 50,
                    'seller_id' => $seller->id
                ],
            ]);
        }
    }
}
