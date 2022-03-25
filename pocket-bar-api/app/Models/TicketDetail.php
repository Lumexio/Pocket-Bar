<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketDetail extends Model
{
    use HasFactory;

    protected $table = "ticket_details_tbl";

    protected $fillable = ["*"];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, "ticket_id");
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, "articulos_tbl_id");
    }
}
