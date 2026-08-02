<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Inspection extends Model
{
    use HasFactory;

    protected $table = 'inspections';

    protected $fillable = [
        'property_id',
        'contract_id',
        'type',
        'date',
        'inspector_name',
        'rooms_data',
        'notes',
        'photos',
        'tenant_signature',
        'owner_signature',
        'pdf_path',
    ];

    protected $casts = [
        'date' => 'date',
        'rooms_data' => 'array',
        'photos' => 'array',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
