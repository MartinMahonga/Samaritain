<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ArtisanProjectImage extends Model
{
    protected $fillable = [
        'artisan_project_id',
        'image_path',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(ArtisanProject::class, 'artisan_project_id');
    }

    public function getImageUrlAttribute(): string
    {
        return Storage::url($this->image_path);
    }
}
