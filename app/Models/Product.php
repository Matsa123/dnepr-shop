<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'type',
        'description',
        'brand',
        'gender',
        'clothing_sizes', // размеры одежды
        'shoe_sizes',     // размеры обуви
        'color',
        'price',
        'image',
    ];

    protected $casts = [
        'clothing_sizes' => 'array',
        'shoe_sizes' => 'array',
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
