<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'total',
        'comment', // ✅ Добавь это поле
    ];
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
