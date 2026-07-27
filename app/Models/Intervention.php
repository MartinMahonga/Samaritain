<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Intervention extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'artisan_id',
        'title',
        'description',
        'category',
        'urgency',
        'status',
        'cost',
        'is_renovation',
        'photos',
        'scheduled_at',
    ];

    protected $casts = [
        'cost' => 'integer',
        'is_renovation' => 'boolean',
        'photos' => 'array',
        'scheduled_at' => 'datetime',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function artisan(): BelongsTo
    {
        return $this->belongsTo(Artisan::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
