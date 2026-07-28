<?php

namespace App\Enums\Owner;

enum InspectionType: string
{
    case CHECK_IN = 'check_in';
    case CHECK_OUT = 'check_out';

    public function label(): string
    {
        return match ($this) {
            self::CHECK_IN => "État d'entrée",
            self::CHECK_OUT => 'État de sortie',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::CHECK_IN => 'log-in',
            self::CHECK_OUT => 'log-out',
        };
    }
}
