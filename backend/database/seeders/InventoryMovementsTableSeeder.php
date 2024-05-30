<?php

// database/seeders/InventoryMovementsTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryMovementsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('inventory_movements')->insert([
            [
                'inventory_id' => 1,
                'type' => 'addition',
                'quantity_change' => 10,
                'movement_date' => now()
            ],
            [
                'inventory_id' => 2,
                'type' => 'subtraction',
                'quantity_change' => 5,
                'movement_date' => now()
            ],
        ]);
    }
}
