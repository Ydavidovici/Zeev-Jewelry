<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'settings';

    // The attributes that are mass assignable.
    protected $fillable = ['key', 'value'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'array', // or 'json'
    ];
}
