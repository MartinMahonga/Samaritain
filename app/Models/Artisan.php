<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Artisan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'created_by',
        'business_name',
        'slug',
        'profession',
        'bio',
        'phone',
        'whatsapp',
        'website',
        'avatar',
        'cover',
        'city',
        'arrondissement_id',
        'experience',
        'verified',
        'is_active',
        'views',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'is_active' => 'boolean',
        'experience' => 'integer',
        'views' => 'integer',
    ];

    protected $appends = [
        'average_rating',
        'reviews_count',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($artisan) {
            if (empty($artisan->slug)) {
                $artisan->slug = Str::slug($artisan->business_name);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ArtisanCategory::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ArtisanReview::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(ArtisanProject::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(ArtisanContact::class);
    }

    public function arrondissement(): BelongsTo
    {
        return $this->belongsTo(Arrondissement::class);
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, int $categoryId)
    {
        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('artisan_categories.id', $categoryId);
        });
    }

    public function scopeByCity($query, string $city)
    {
        return $query->where('city', 'like', "%{$city}%");
    }

    public function scopeByMinRating($query, int $rating)
    {
        return $query->having('average_rating', '>=', $rating);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('business_name', 'like', "%{$term}%")
                ->orWhere('profession', 'like', "%{$term}%")
                ->orWhere('city', 'like', "%{$term}%")
                ->orWhere('bio', 'like', "%{$term}%");
        });
    }
}
