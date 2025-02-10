<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // Fillable attributes for mass assignment
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'price'
    ];

    /**
     * Define the relationship with the Order model
     */
    public function order()
    {
        return $this->belongsTo(Order::class); // Each order item belongs to one order
    }
}
