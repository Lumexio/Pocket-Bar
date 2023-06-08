<?php

namespace App\Enums;


enum Rol: int
{
    case Administrativo = 1;
    case Gerencia = 2;
    case Cajero = 3;
    case Mesero = 4;
    case Bartender = 5;
    case Guardia = 6;

    public static function toArray(): array
    {
        return [
            'Administrativo' => self::Administrativo,
            'Gerencia' => self::Gerencia,
            'Cajero' => self::Cajero,
            'Mesero' => self::Mesero,
            'Bartender' => self::Bartender,
            'Guardia' => self::Guardia,
        ];
    }
}
