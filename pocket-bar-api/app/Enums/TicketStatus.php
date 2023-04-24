<?php

namespace App\Enums;

enum TicketStatus: string
{
    case Canceled = "Cancelado";
    case Delivered = "Entregado";
    //Al colocar este parametro global como En espera, causaste problemas en la llamada de la lista tickets que utiliza el status por entregar, en espera es solo utilizado para ticketsitems no el status del ticket.
    case Standby = "Por entregar";
    case Closed = "Cerrado";
}
