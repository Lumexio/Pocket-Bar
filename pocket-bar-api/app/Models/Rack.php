<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Articulo;

class Rack extends Model
{
    protected $table = 'rack_tbl';
    protected $primaryKey = 'id';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre_rack',
    ];
    public function articulo()
    {
        return $this->hasMany(Articulo::class);
    }
}
