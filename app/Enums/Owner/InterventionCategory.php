<?php

namespace App\Enums\Owner;

enum InterventionCategory: string
{
    case PLUMBING = 'plumbing';
    case PAINTING = 'painting';
    case ROOFING = 'roofing';
    case LOCKSMITH = 'locksmith';
    case GARDEN = 'garden';
    case HEATING = 'heating';
    case APPLIANCES = 'appliances';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::PLUMBING => 'Plomberie',
            self::PAINTING => 'Peinture',
            self::ROOFING => 'Toiture',
            self::LOCKSMITH => 'Serrurerie',
            self::GARDEN => 'Jardin',
            self::HEATING => 'Chauffage',
            self::APPLIANCES => 'Électroménager',
            self::OTHER => 'Autre',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::PLUMBING => 'droplets',
            self::PAINTING => 'paintbrush',
            self::ROOFING => 'building',
            self::LOCKSMITH => 'lock',
            self::GARDEN => 'flower-2',
            self::HEATING => 'thermometer',
            self::APPLIANCES => 'refrigerator',
            self::OTHER => 'wrench',
        };
    }
}
