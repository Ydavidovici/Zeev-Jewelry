<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;  // Ensure User model is imported

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', // This assumes you're using 'customer_id' to reference a user
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
        return $this->belongsTo(User::class, 'customer_id'); // Updated to reference the User model
    }
}
