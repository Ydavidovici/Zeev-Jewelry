<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $table = 'inventory_movements';

    protected $fillable = [
        'inventory_id',
        'quantity',
        'movement_type',
        'created_at',
        'updated_at'
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
