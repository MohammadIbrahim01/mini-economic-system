<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Fillable attributes for mass assignment
    protected $fillable = [
        'order_id', 'name', 'email', 'address', 'total_amount', 'status'
    ];

    /**
     * Define the relationship with the OrderItem model
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class); // One order can have many items
    }
}
