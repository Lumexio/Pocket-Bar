<?php

namespace App\Enums;

enum TicketItemStatus: string
{
    case Standby = "En espera";
    case InPreparation = "En preparacion";
    case Prepared = "Preparado";
    case Received = "Recibido";
    case Canceled = "Cancelado";
}
