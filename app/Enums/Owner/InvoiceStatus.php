<?php

namespace App\Enums\Owner;

enum InvoiceStatus: string
{
    case UNPAID = 'unpaid';
    case PAID = 'paid';
    case OVERDUE = 'overdue';

    public function label(): string
    {
        return match ($this) {
            self::UNPAID => 'Impayée',
            self::PAID => 'Payée',
            self::OVERDUE => 'En retard',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::UNPAID => 'amber',
            self::PAID => 'emerald',
            self::OVERDUE => 'red',
        };
    }
}
