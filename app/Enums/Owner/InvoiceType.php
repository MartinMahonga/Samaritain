<?php

namespace App\Enums\Owner;

enum InvoiceType: string
{
    case WATER = 'water';
    case ELECTRICITY = 'electricity';
    case TAXES = 'taxes';
    case GARBAGE = 'garbage';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::WATER => 'Eau',
            self::ELECTRICITY => 'Électricité',
            self::TAXES => 'Taxes',
            self::GARBAGE => 'Ordures',
            self::OTHER => 'Autre',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::WATER => 'droplets',
            self::ELECTRICITY => 'zap',
            self::TAXES => 'landmark',
            self::GARBAGE => 'trash-2',
            self::OTHER => 'receipt',
        };
    }
}
