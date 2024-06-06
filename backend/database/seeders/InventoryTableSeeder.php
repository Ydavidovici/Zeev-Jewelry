<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventoryTableSeeder extends Seeder
{
    public function run()
    {
        Inventory::insert([
            ['location' => 'Warehouse 1', 'product_id' => 1, 'quantity' => 100],
            ['location' => 'Warehouse 2', 'product_id' => 2, 'quantity' => 50],
            ['location' => 'Warehouse 3', 'product_id' => 3, 'quantity' => 200],
        ]);
    }
}
