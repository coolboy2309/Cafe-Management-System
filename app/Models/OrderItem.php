<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',       // FK to orders table
        'product_id',     // id from menu/item table
        'product_type',   // 'menu' or 'item'
        'quantity',
        'unit_price',
        'cost',
        'subtotal',
        'total_cost',
        'profit',
        'name',
        'send_to_kitchen',
        'to_make',
        'is_flagged',
        'prep_time',
        'status'
    ];

    // Relationship: belongs to an order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'product_id');
    }
}
