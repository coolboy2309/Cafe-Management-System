<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuIngredient extends Model
{
    use HasFactory;
    protected $fillable = [
        'menu_id',
        'item_id',
        'quantity',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
