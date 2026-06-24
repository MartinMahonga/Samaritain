<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Arrondissement extends Model
{
    protected $fillable = ['name', 'city_id'];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
