<?php

namespace App\Enums;

enum TicketStatus: string
{
    case Canceled = "Cancelado";
    case Delivered = "Entregado";
    case Standby = "En espera";
    case Closed = "Cerrado";
}
