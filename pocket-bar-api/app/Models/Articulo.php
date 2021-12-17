<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Articulo extends Model
{
    protected $table = 'articulos_tbl';
    protected $primaryKey = 'id';
    use HasFactory;





    /**
     ** The attributes that are mass assignable.
     *
     @var array
     *Aqui se especifica los campos de entrada o permitidos para llenar la tabla artículos con los campos de las tablas *foraneas
     */
    protected $fillable = [
        'nombre_articulo',
        'cantidad_articulo',
        'descripcion_articulo',
        'categoria_id',
        'tipo_id',
        'proveedor_id',
        'marca_id',
        'travesano_id',
        'rack_id',
        'status_id',
        'foto_articulo'
    ];
}
