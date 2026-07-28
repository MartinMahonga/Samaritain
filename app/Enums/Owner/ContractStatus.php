<?php

namespace App\Enums\Owner;

enum ContractStatus: string
{
    case DRAFT = 'draft';
    case PENDING_OWNER_SIGNATURE = 'pending_owner';
    case PENDING_TENANT_SIGNATURE = 'pending_tenant';
    case ACTIVE = 'active';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';
    case TERMINATED = 'terminated';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Brouillon',
            self::PENDING_OWNER_SIGNATURE => 'En attente propriétaire',
            self::PENDING_TENANT_SIGNATURE => 'En attente locataire',
            self::ACTIVE => 'Actif',
            self::REJECTED => 'Refusé',
            self::CANCELLED => 'Annulé',
            self::TERMINATED => 'Résilié',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::PENDING_OWNER_SIGNATURE => 'amber',
            self::PENDING_TENANT_SIGNATURE => 'orange',
            self::ACTIVE => 'emerald',
            self::REJECTED => 'red',
            self::CANCELLED => 'red',
            self::TERMINATED => 'red',
        };
    }

    public function isPendingSignature(): bool
    {
        return match ($this) {
            self::PENDING_OWNER_SIGNATURE,
            self::PENDING_TENANT_SIGNATURE => true,
            default => false,
        };
    }
}
