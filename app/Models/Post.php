<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'description',
        'content'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
