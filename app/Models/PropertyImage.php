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
        return Storage::url($value);
    }
}
