<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role; // ← Import du modèle Role de Spatie

class AgencyInvitation extends Model
{
    protected $table = 'agency_invitations';

    protected $fillable = [
        'email',
        'role_id',
        'token',
        'expires_at',
        'created_by',
        'accepted_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($invitation) {
            if (empty($invitation->token)) {
                $invitation->token = Str::random(64);
            }
        });
    }

    public function role(): BelongsTo
    {
        // Utilise le modèle Role de Spatie
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isAccepted(): bool
    {
        return ! is_null($this->accepted_at);
    }
}
