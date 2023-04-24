<?php

namespace App\Enums;


enum Rol: int
{
    case Administrativo = 1;
    case Gerencia = 2;
    case Cajero = 3;
    case Mesero = 4;
    case Bartender = 5;
}
