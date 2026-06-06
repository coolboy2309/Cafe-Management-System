<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type', // storage / consumption
    ];

    // A location has many stock movements
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
