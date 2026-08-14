<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class RentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'month',
        'year',
        'amount_due',
        'amount_paid',
        'due_date',
        'paid_at',
        'status',
        'transaction_id',
    ];

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
        'amount_due' => 'integer',
        'amount_paid' => 'integer',
        'due_date' => 'date',
        'paid_at' => 'date',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Mark this rent payment as fully paid.
     */
    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'amount_paid' => $this->amount_due,
            'paid_at' => now(),
        ]);
    }

    /**
     * Mark this rent payment as failed — keep it unpaid so the tenant can retry.
     */
    public function markAsPaymentFailed(): void
    {
        $this->update([
            'status' => 'unpaid',
            'paid_at' => null,
        ]);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
