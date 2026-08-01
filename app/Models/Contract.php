<?php

namespace App\Models;

use App\Enums\Owner\ContractStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'tenant_name',
        'tenant_email',
        'tenant_phone',
        'start_date',
        'end_date',
        'monthly_rent',
        'deposit',
        'status',
        'owner_signed_at',
        'tenant_signed_at',
        'activated_at',
        'cancelled_at',
        'contract_version',
        'content_hash',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'monthly_rent' => 'integer',
        'deposit' => 'integer',
        'owner_signed_at' => 'datetime',
        'tenant_signed_at' => 'datetime',
        'activated_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function rentPayments(): HasMany
    {
        return $this->hasMany(RentPayment::class);
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class);
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(ContractSignature::class);
    }

    public function conversation(): HasOne
    {
        return $this->hasOne(Conversation::class);
    }

    public function scopePendingOwnerSignature($query)
    {
        return $query->where('status', ContractStatus::PENDING_OWNER_SIGNATURE->value);
    }

    public function scopePendingTenantSignature($query)
    {
        return $query->where('status', ContractStatus::PENDING_TENANT_SIGNATURE->value);
    }

    public function scopeFullySigned($query)
    {
        return $query->whereNotNull('owner_signed_at')
            ->whereNotNull('tenant_signed_at');
    }

    public function isFullySigned(): bool
    {
        return ! is_null($this->owner_signed_at) && ! is_null($this->tenant_signed_at);
    }

    public function hasSignatureFrom(User $user): bool
    {
        return $this->signatures()->where('user_id', $user->id)->exists();
    }

    public function getOwnerSignatureAttribute()
    {
        return $this->signatures()->where('role', 'owner')->first();
    }

    public function getTenantSignatureAttribute()
    {
        return $this->signatures()->where('role', 'tenant')->first();
    }

    public function isCancelled(): bool
    {
        return $this->status === ContractStatus::CANCELLED->value;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            ContractStatus::DRAFT->value,
            ContractStatus::PENDING_OWNER_SIGNATURE->value,
            ContractStatus::PENDING_TENANT_SIGNATURE->value,
            ContractStatus::ACTIVE->value,
        ], true);
    }

    public function canBeDeleted(): bool
    {
        return in_array($this->status, [
            ContractStatus::DRAFT->value,
            ContractStatus::CANCELLED->value,
            ContractStatus::REJECTED->value,
        ], true);
    }
}
