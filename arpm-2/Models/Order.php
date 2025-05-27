<?php

namespace Models;

class Order
{
    protected $fillable = [
        'customer_id', // Assuming you have a foreign key relationship
        'status',
        'completed_at', // Assuming you have this field to track completion
        // Add other fields as necessary
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
