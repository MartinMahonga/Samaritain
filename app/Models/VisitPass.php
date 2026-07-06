<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VisitPass extends Model
{
    use HasFactory;

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
        'payment_status',
        'status',
        'paid_at',
        'qr_code_path',
        'pdf_path',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = $model->uuid ?? (string) Str::uuid();
            $model->reference = $model->reference ?? 'VP-'.strtoupper(Str::random(8));
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
        return $this->isPaid() && $this->status === 'active';
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
}
