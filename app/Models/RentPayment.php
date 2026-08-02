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

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
