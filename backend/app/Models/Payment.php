<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'seller_id',
        'payment_intent_id',
        'payment_type',
        'payment_status',
        'amount'
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
