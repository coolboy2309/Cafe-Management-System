<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;

class Cat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',

    ];

    public function menus()
    {
        return $this->hasMany(Menu::class, 'cat_id', 'id');
    }
    public function drinkItems()
    {
        return $this->hasMany(Item::class, 'cat_id');
    }
}
