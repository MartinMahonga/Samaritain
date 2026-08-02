<?php

namespace App\Enums\Owner;

enum InterventionUrgency: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case EMERGENCY = 'emergency';

    public function label(): string
    {
        return match ($this) {
            self::LOW => 'Faible',
            self::MEDIUM => 'Moyenne',
            self::HIGH => 'Haute',
            self::EMERGENCY => 'Urgence',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::LOW => 'gray',
            self::MEDIUM => 'amber',
            self::HIGH => 'orange',
            self::EMERGENCY => 'red',
        };
    }

    public function emoji(): string
    {
        return match ($this) {
            self::LOW => '🟢',
            self::MEDIUM => '🟡',
            self::HIGH => '🔴',
            self::EMERGENCY => '🚨',
        };
    }
}
