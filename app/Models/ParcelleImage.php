<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParcelleImage extends Model
{
    protected $fillable = ['parcelle_id', 'path', 'url', 'principale'];

    protected $casts = [
        'principale' => 'boolean',
    ];

    public function parcelle()
    {
        return $this->belongsTo(Parcelle::class);
    }
}
