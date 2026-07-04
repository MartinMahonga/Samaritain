<?php

namespace App\Models;

use App\Enums\PropertyStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'created_by',
        'title',
        'slug',
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

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Property $property) {
            if (empty($property->slug)) {
                $property->slug = static::generateUniqueSlug($property->title);
            }
        });

        static::updating(function (Property $property) {
            if ($property->isDirty('title') && ! $property->isDirty('slug')) {
                $property->slug = static::generateUniqueSlug($property->title, $property->id);
            }
        });
    }

    /**
     * Generate a unique slug for a property.
     */
    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 1;

        $query = static::withTrashed()->where('slug', $slug);

        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
            $query = static::withTrashed()->where('slug', $slug);
            if ($ignoreId !== null) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }

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

    public function agencyContacts()
    {
        return $this->morphMany(AgencyContact::class, 'contactable');
    }
}
