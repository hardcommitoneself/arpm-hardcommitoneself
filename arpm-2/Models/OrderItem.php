<?php

namespace Models;

class OrderItem
{
    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'quantity',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
