<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Models\Articulo;

class articuloCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $articulos;
    public $afterCommit = true;
    public function __construct()
    {
        $this->articulos = DB::table('articulos_tbl')
            ->leftJoin('categorias_tbl', 'articulos_tbl.categoria_id', '=', 'categorias_tbl.id')
            ->leftJoin('marcas_tbl', 'articulos_tbl.marca_id', '=', 'marcas_tbl.id')
            ->leftJoin('proveedores_tbl', 'articulos_tbl.proveedor_id', '=', 'proveedores_tbl.id')
            ->leftJoin('status_tbl', 'articulos_tbl.status_id', '=', 'status_tbl.id')
            ->leftJoin('tipos_tbl', 'articulos_tbl.tipo_id', '=', 'tipos_tbl.id')
            ->leftJoin('rack_tbl', 'articulos_tbl.rack_id', '=', 'rack_tbl.id')
            ->leftJoin('travesano_tbl', 'articulos_tbl.travesano_id', '=', 'travesano_tbl.id')
            ->select('articulos_tbl.id', 'articulos_tbl.nombre_articulo', 'articulos_tbl.cantidad_articulo', 'articulos_tbl.descripcion_articulo', 'articulos_tbl.foto_articulo', 'categorias_tbl.nombre_categoria', 'marcas_tbl.nombre_marca', 'proveedores_tbl.nombre_proveedor', 'status_tbl.nombre_status', 'tipos_tbl.nombre_tipo', 'travesano_tbl.nombre_travesano', 'rack_tbl.nombre_rack')->get()->map(
                function ($item) {
                    $item->foto_articulo = url("images/{$item->foto_articulo}");
                    return $item;
                }
            );
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('articulos');
    }
}
