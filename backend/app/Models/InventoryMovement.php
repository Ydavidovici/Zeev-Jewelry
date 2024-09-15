<?php

// File: app/Models/InventoryMovement.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $table = 'inventory_movements';

    protected $fillable = [
        'inventory_id',
        'quantity_change',
        'movement_type',
        'movement_date',
        'created_at',
        'updated_at'
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
