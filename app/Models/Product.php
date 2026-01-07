<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'thumbnail',
        'regular_price',
        'sale_price',
        'quantity',
        'description',
        'content',
        'status',
        'published_at',
    ];
}
