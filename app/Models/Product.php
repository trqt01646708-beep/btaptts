<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'image',
        'thumbnail'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Delete images when product is deleted
        static::deleting(function ($product) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
        });
    }
}
