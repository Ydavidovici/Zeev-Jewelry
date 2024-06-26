<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryMovement;

class InventoryMovementsTableSeeder extends Seeder
{
    public function run()
    {
        InventoryMovement::insert([
            ['inventory_id' => 1, 'type' => 'addition', 'quantity_change' => 50, 'movement_date' => now()],
            ['inventory_id' => 2, 'type' => 'subtraction', 'quantity_change' => 10, 'movement_date' => now()],
            ['inventory_id' => 3, 'type' => 'addition', 'quantity_change' => 100, 'movement_date' => now()],
        ]);
    }
}
