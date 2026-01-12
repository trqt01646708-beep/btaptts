<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'status'];

    // Relationships
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'category_post');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}