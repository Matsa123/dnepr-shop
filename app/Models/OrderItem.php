<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'product_id',
        'product_name',
        'product_price',
        'quantity',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function product()
{
    return $this->belongsTo(\App\Models\Product::class, 'product_id');
}
}
