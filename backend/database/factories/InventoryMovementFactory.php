<?php

namespace Database\Factories;

use App\Models\InventoryMovement;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryMovementFactory extends Factory
{
    protected $model = InventoryMovement::class;

    public function definition()
    {
        return [
            'inventory_id' => Inventory::factory(),
            'movement_type' => $this->faker->randomElement(['addition', 'subtraction']),
            'quantity_change' => $this->faker->numberBetween(1, 100),
            'movement_date' => $this->faker->dateTime,
        ];
    }
}
