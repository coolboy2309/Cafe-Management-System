<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitchenStat extends Model
{
    use HasFactory;
    
    // Mass assignable fields
    protected $fillable = [
        'order_number',
        'total_items',
        'on_time_items',
        'late_items',
        'chef_name',
        'date_created',
        'finished_at'
    ];

    // Disable Laravel timestamps if you use date_created manually
    public $timestamps = false;
}
