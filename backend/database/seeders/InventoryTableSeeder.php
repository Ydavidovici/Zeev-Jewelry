<?php

// database/seeders/InventoryTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('inventory')->insert([
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
