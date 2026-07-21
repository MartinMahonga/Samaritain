<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PropertyImage extends Model
{
    protected $fillable = ['image_url', 'cover_image', 'property_id'];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function getImageUrlAttribute(string $value): string
    {
        // Si l'URL est déjà absolue (http/https), on la retourne telle quelle
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return Storage::url($value);
    }
}
