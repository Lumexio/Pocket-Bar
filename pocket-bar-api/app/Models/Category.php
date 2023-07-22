<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Articulo;

class Category extends Model
{

    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre_categoria',
        'descripcion_categoria'
    ];

    public function articulo()
    {
        return $this->hasMany(Articulo::class);
    }
}
