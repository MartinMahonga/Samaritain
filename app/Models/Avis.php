<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    protected $fillable = ['user_id', 'commentaire', 'note'];

    // Un avis appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
