<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone',
        'city',
        'property_category',
        'property_id',
        'preferred_date',
        'message',
    ];

    protected $casts = [
        'preferred_date' => 'string', // Car c'est un créneau horaire (Matin, Après-midi, etc.)
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
