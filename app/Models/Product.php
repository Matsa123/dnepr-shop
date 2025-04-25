<?php

namespace App\Models;
use App\Models\ProductImage;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'type',
        'description',
        'brand',
        'gender',
        'sizes',
        'color',
        'price',
        'image',
    ];

    protected $casts = [
        'sizes' => 'array',
    ];
    
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
