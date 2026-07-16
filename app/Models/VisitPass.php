<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VisitPass extends Model
{
    use HasFactory;

    public const ALLOWED_VISITS = 3;

    protected $fillable = [
        'uuid',
        'user_id',
        'property_id',
        'transaction_id',
        'holder_name',
        'phone',
        'email',
        'comment',
        'reference',
        'amount',
        'allowed_visits',
        'remaining_visits',
        'payment_status',
        'status',
        'paid_at',
        'expires_at',
        'qr_code_path',
        'pdf_path',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'amount' => 'integer',
        'allowed_visits' => 'integer',
        'remaining_visits' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = $model->uuid ?? (string) Str::uuid();
            $model->reference = $model->reference ?? 'VP-'.strtoupper(Str::random(8));
            $model->allowed_visits = $model->allowed_visits ?? self::ALLOWED_VISITS;
            $model->remaining_visits = $model->remaining_visits ?? $model->allowed_visits;
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }

    public function markAsPaid(): void
    {
        $this->update([
            'payment_status' => 'paid',
            'status' => 'active',
            'paid_at' => now(),
            'expires_at' => $this->created_at ? $this->created_at->copy()->addDays(3) : now()->addDays(3),
        ]);
    }

    public function markAsPaymentFailed(): void
    {
        $this->update([
            'payment_status' => 'failed',
            'status' => 'payment_failed',
        ]);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isPendingPayment(): bool
    {
        return $this->status === 'pending_payment';
    }

    public function isPaymentFailed(): bool
    {
        return $this->status === 'payment_failed';
    }

    public function isDownloadable(): bool
    {
        return $this->isPaid() && $this->isActive();
    }

    public function isActive(): bool
    {
        return $this->status === 'active'
            && $this->payment_status === 'paid'
            && $this->expires_at
            && $this->expires_at->isFuture()
            && $this->remaining_visits > 0;
    }

    public function isExpired(): bool
    {
        if ($this->status === 'expired') {
            return true;
        }

        return $this->expires_at ? $this->expires_at->isPast() : false;
    }

    public function isUsed(): bool
    {
        return $this->remaining_visits <= 0 || $this->status === 'used';
    }

    public function updateStatus(): void
    {
        if ($this->status === 'cancelled') {
            return;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            $this->status = 'expired';
        } elseif ($this->remaining_visits <= 0) {
            $this->status = 'used';
        } elseif ($this->payment_status === 'paid') {
            $this->status = 'active';
        }

        $this->saveQuietly();
    }

    public function getExpirationDateAttribute()
    {
        return $this->expires_at;
    }

    public function getQrCodeUrl(): string
    {
        return $this->qr_code_path
            ? Storage::url($this->qr_code_path)
            : '';
    }

    public function getQrCodeBase64(): ?string
    {
        if ($this->qr_code_path && Storage::exists($this->qr_code_path)) {
            $qrContent = Storage::get($this->qr_code_path);

            return 'data:image/png;base64,'.base64_encode($qrContent);
        }

        return null;
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function scans(): HasMany
    {
        return $this->hasMany(PassScan::class, 'visit_pass_id');
    }
}
