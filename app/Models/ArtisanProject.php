<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArtisanProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'artisan_id',
        'title',
        'description',
        'image',
        'views',
    ];

    public function artisan(): BelongsTo
    {
        return $this->belongsTo(Artisan::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ArtisanProjectImage::class, 'artisan_project_id');
    }
}
