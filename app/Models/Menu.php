<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cat;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cat_id',
        'user_type',
        'price',
        'index_no',
        'to_make',
        'prep_time'
    ];

    public function category()
    {
        return $this->belongsTo(Cat::class, 'cat_id', 'id');
    }
    public function ingredients(){
        return $this->hasMany(MenuIngredient::class);
    }
}
