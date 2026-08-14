<?php

namespace App\Models;

use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'transaction_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'visit_pass_id',
        'rent_payment_id',
        'type',
        'status',
        'amount',
        'deposit_id',
        'payout_id',
        'provider',
        'currency',
        'raw_response',
    ];

    protected $casts = [
        'amount' => 'integer',
        'raw_response' => 'array',
    ];

    // Nécessaire car ta clé primaire ne s'appelle pas "id"
    public function uniqueIds(): array
    {
        return ['transaction_id'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function visitPass(): BelongsTo
    {
        return $this->belongsTo(VisitPass::class);
    }

    public function rentPayment(): BelongsTo
    {
        return $this->belongsTo(RentPayment::class);
    }

    protected static function newFactory(): Factory
    {
        return TransactionFactory::new();
    }
}
