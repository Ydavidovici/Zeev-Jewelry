<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'seller_id',
        'category_id',
        'name',
        'description',
        'image_url',
        'price',
        'stock_quantity',
        'created_at',
        'updated_at',
        'is_featured'
    ];

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship with Reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Relationship with Seller
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
