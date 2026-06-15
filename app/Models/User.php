<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'provider_id', 'provider_name', 'provider_token', 'provider_refresh_token', 'profile_image', 'is_staff', 'is_active'])]
#[Hidden(['password', 'remember_token', 'provider_token', 'provider_refresh_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_staff' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function favorites()
    {
        return $this->belongsToMany(
            Property::class,
            'favorites'
        )->withTimestamps();
    }

    public function artisan()
    {
        return $this->hasOne(Artisan::class);
    }

    public function sentInvitations()
    {
        return $this->hasMany(AgencyInvitation::class, 'created_by');
    }

    public function isStaff(): bool
    {
        return $this->is_staff === true;
    }

    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    public function profileUrl(): string
    {
        return Storage::disk('public')->url($this->profile_image);
    }
}
