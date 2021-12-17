<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Categoria extends Model
{
    protected $table = 'categorias_tbl';
    protected $primaryKey = 'id';
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
}
