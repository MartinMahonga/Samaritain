<?php

namespace App\Enums\Owner;

enum DocumentCategory: string
{
    case INVOICE = 'invoice';
    case RECEIPT = 'receipt';
    case QUOTE = 'quote';
    case INSPECTION = 'inspection';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::INVOICE => 'Facture',
            self::RECEIPT => 'Reçu',
            self::QUOTE => 'Devis',
            self::INSPECTION => 'État des lieux',
            self::OTHER => 'Autre',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::INVOICE => 'receipt',
            self::RECEIPT => 'check-square',
            self::QUOTE => 'file-edit',
            self::INSPECTION => 'clipboard-check',
            self::OTHER => 'paperclip',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::INVOICE => 'red',
            self::RECEIPT => 'emerald',
            self::QUOTE => 'blue',
            self::INSPECTION => 'purple',
            self::OTHER => 'gray',
        };
    }
}
