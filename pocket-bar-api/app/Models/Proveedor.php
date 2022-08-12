<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Articulo;

class Proveedor extends Model
{
    protected $table = 'proveedores_tbl';
    protected $primaryKey = 'id';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre_proveedor',
        'descripcion',
    ];
    public function articulo()
    {
        return $this->hasMany(Articulo::class);
    }
}
