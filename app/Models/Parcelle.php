<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Parcelle extends Model
{
    protected $fillable = [
        'titre',
        'slug',
        'description',
        'localisation',
        'ville',
        'superficie',
        'prix',
        'statut',
        'reference',
        'viabilisee',
        'titre_foncier',
        'created_by',
        'conditions_accepted_at',
        'arrondissement_id',
    ];

    protected $casts = [
        // 'viabilisee' => 'boolean',
        'prix' => 'float',
        'superficie' => 'float',
        'conditions_accepted_at' => 'datetime',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function arrondissement()
    {
        return $this->belongsTo(Arrondissement::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Parcelle $parcelle) {
            if (empty($parcelle->slug)) {
                $parcelle->slug = static::generateUniqueSlug($parcelle->titre);
            }
        });

        static::updating(function (Parcelle $parcelle) {
            if ($parcelle->isDirty('titre') && ! $parcelle->isDirty('slug')) {
                $parcelle->slug = static::generateUniqueSlug($parcelle->titre, $parcelle->id);
            }
        });
    }

    /**
     * Generate a unique slug for a parcelle.
     */
    public static function generateUniqueSlug(string $titre, ?int $ignoreId = null): string
    {
        $base = Str::slug($titre);
        $slug = $base;
        $counter = 1;

        $query = static::where('slug', $slug);

        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
            $query = static::where('slug', $slug);
            if ($ignoreId !== null) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }

    // Relation avec les images
    public function images()
    {
        return $this->hasMany(ParcelleImage::class);
    }

    // Image principale
    public function imagePrincipale()
    {
        return $this->hasOne(ParcelleImage::class)->where('principale', true);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function agencyContacts()
    {
        return $this->morphMany(AgencyContact::class, 'contactable');
    }

    public function parcelFavorites()
    {
        return $this->hasMany(ParcelFavorite::class, 'parcel_id');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'parcel_favorites', 'parcel_id', 'user_id')->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'parcel_favorites', 'parcel_id', 'user_id')->withTimestamps();
    }

    public function isFavorited(): bool
    {
        if (! auth()->check()) {
            return false;
        }

        return auth()
            ->user()
            ->favoritesParcels()
            ->where('parcel_id', $this->id)
            ->exists();
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }
}
