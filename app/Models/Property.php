<?php

namespace App\Models;

use App\Enums\PropertyStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'created_by',
        'title',
        'description',
        'price',
        'surface',
        'rooms',
        'bedrooms',
        'bathrooms',
        'floor',
        'furnished',
        'address',
        'category_id',
        'city_id',
        'arrondissement_id',
        'status',
        'is_verify',
        'is_active',
    ];

    protected $casts = [
        'status' => PropertyStatus::class,
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function arrondissement()
    {
        return $this->belongsTo(Arrondissement::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'amenity_property');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function getCoverImageUrlAttribute()
    {
        $coverImage = $this->images()->where('cover_image', true)->first();

        return $coverImage ? $coverImage->image_url : null;
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function isFavorited(): bool
    {
        if (! auth()->check()) {
            return false;
        }

        return auth()
            ->user()
            ->favorites()
            ->where('property_id', $this->id)
            ->exists();
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function visitRequests()
    {
        return $this->hasMany(VisitRequest::class);
    }

    public function contacts()
    {
        return $this->hasMany(PropertyContact::class);
    }
}
