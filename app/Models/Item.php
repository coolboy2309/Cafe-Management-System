<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit_id',
        'min_stock',
        'cat_id',
        'price',
        'cost',
        'is_active',
        'index_no',
        'to_make',
        'type',
        'qty',
        'sup_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'min_stock' => 'decimal:2',
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
    public function category()
    {
        return $this->belongsTo(Cat::class, 'cat_id');
    }
    public function stock($locationId)
    {
        $in = $this->stockMovements()
            ->where('location_id', $locationId)
            ->where('direction', 'IN')
            ->sum('quantity');

        $out = $this->stockMovements()
            ->where('location_id', $locationId)
            ->where('direction', 'OUT')
            ->sum('quantity');

        return $in - $out;
    }
    public function usedInMenus(){
        return  $this->hasMany(MenuIngredient::class);
    }
}
