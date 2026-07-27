<?php

namespace App\Enums\Owner;

enum ContractStatus: string
{
    case ACTIVE = 'active';
    case TERMINATED = 'terminated';
    case PENDING = 'pending';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Actif',
            self::TERMINATED => 'Résilié',
            self::PENDING => 'En attente',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'emerald',
            self::TERMINATED => 'red',
            self::PENDING => 'amber',
        };
    }
}