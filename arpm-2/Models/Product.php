<?php

namespace Models;

class Product
{
    protected $fillable = [
        'name',
        'price',
        // Add other fields as necessary
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
