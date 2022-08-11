<?php

namespace App\Enums;

enum TicketItemStatus: string
{
    case Standby = "En espera";
    case InPreparation = "En preparación";
    case Prepared = "Preparado";
    case Received = "Recibido";
}
