<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'image',
        'thumbnail',
        'regular_price',
        'sale_price',
        'description',
        'content',
        'stock_quantity',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'regular_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // Helper methods
    public function getPrice()
    {
        return $this->sale_price ?? $this->regular_price;
    }

    public function isOnSale()
    {
        return $this->sale_price !== null && $this->sale_price < $this->regular_price;
    }
}