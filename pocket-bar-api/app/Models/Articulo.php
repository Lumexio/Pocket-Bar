<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\User;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;

class Articulo extends Model
{
    protected $table = 'articulos_tbl';
    protected $primaryKey = 'id';
    use HasFactory, LogsActivity;

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
        'foto_articulo',
        'user_id'
    ];

    //ACtivity log system
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nombre_articulo',
                'cantidad_articulo',
            ]);
        // Chain fluent methods for configuration options
        //$user = Auth::user();
        //Auth::login($user);
        activity()
            ->causedBy(Auth::id());
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
    public function rack()
    {
        return $this->belongsTo(Rack::class);
    }
}
