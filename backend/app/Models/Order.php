<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', // this references in the migration the user's id its technichally a user id but we've named it customer_id for clarity
        'seller_id',    // Add seller_id to the fillable array
        'order_date',
        'total_amount',
        'is_guest',
        'status',
        'payment_intent_id'
    ];

    // Relationship with Payment
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Relationship with User (formerly Customer)
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // Relationship with Seller
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
