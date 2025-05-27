<?php

namespace Models;

class CartItem
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity', // Assuming you have a quantity field
        // Add other fields as necessary
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
