<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'price_type'];

    public function scopePropertyTypes($query)
    {
        return $query->whereIn('name', [
            'Chambre simple',
            'Chambre moderne',
            'Studio simple',
            'Studio moderne',
            'Appartement simple',
            'Appartement meublé',
            'Appartement journalier',
        ]);
    }
}
