<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
    ];

    public function productVariant()
    {
        return $this->hasOne(ProductVariant::class);
    }
}
