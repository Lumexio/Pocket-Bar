<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'base_product_id',
        'ingredient_product_id',
        'quantity',
        'unit',
        'user_id',
    ];

    public function baseProduct()
    {
        return $this->belongsTo(Product::class, 'base_product_id');
    }

    public function ingredientProduct()
    {
        return $this->belongsTo(Product::class, 'ingredient_product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
