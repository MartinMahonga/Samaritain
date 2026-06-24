<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Parcelle.php

class Parcelle extends Model
{
    protected $fillable = [
        'titre', 'description', 'localisation', 'quartier',
        'ville', 'superficie', 'prix', 'statut',
        'reference', 'viabilisee', 'titre_foncier',
    ];

    protected $casts = [
        'viabilisee' => 'boolean',
        'prix' => 'float',
        'superficie' => 'float',
    ];

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
}
