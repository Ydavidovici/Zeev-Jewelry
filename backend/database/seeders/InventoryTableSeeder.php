<?php

// database/seeders/InventoryTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Inventory::insert([
            [
                'product_id' => 1,
                'quantity' => 50,
                'location' => 'Warehouse 1'
            ],
            [
                'product_id' => 2,
                'quantity' => 30,
                'location' => 'Warehouse 1'
            ],
            [
                'product_id' => 3,
                'quantity' => 20,
                'location' => 'Warehouse 2'
            ],
        ]);
    }
}
