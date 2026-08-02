<?php

namespace App\Enums\Owner;

enum RentPaymentStatus: string
{
    case UNPAID = 'unpaid';
    case PARTIAL = 'partial';
    case PAID = 'paid';
    case LATE = 'late';

    public function label(): string
    {
        return match ($this) {
            self::UNPAID => 'Non payé',
            self::PARTIAL => 'Partiel',
            self::PAID => 'Payé',
            self::LATE => 'En retard',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::UNPAID => 'gray',
            self::PARTIAL => 'amber',
            self::PAID => 'emerald',
            self::LATE => 'red',
        };
    }
}
