<?php

// File: app/Models/Shipping.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    protected $table = 'shipping';

    protected $fillable = [
        'order_id',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'shipping_method',
        'tracking_number',
        'status',
        'created_at',
        'updated_at'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
