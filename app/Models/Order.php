<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',  // unique for receipt
        'user_id',       // waiter or staff ID
        'table_no',
        'order_type',    // dine_in / takeaway
        'total_amount',
        'is_flagged',
        'is_billed',
        'waiter_id'
    ];

    // Relationship: one order has many items
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    // Optional: get waiter/user info if you have User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function casher()
    {
        return $this->belongsTo(User::class, 'user_id'); // user_id is casher
    }
    public function waiter()
    {
        // Assuming 'user_id' is the foreign key for the waiter
        return $this->belongsTo(User::class, 'waiter_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
