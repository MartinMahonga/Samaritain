<?php

namespace App\Enums\Owner;

enum InterventionStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'En attente',
            self::APPROVED => 'Approuvé',
            self::IN_PROGRESS => 'En cours',
            self::COMPLETED => 'Terminé',
            self::CANCELLED => 'Annulé',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::APPROVED => 'blue',
            self::IN_PROGRESS => 'amber',
            self::COMPLETED => 'emerald',
            self::CANCELLED => 'red',
        };
    }
}
