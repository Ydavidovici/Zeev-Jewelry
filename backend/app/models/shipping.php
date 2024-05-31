<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'shipping_type', 'shipping_cost', 'shipping_status',
        'tracking_number', 'shipping_address', 'shipping_carrier',
        'recipient_name', 'estimated_delivery_date', 'additional_notes'
    ];
}
