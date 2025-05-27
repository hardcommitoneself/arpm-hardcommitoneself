<?php

namespace Models;

class Customer
{
    protected $fillable = [
        'name',
        'email', // Assuming you have an email field
        // Add other fields as necessary
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
