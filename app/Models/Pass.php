<?php

namespace App\Models;

use App\Helpers\QrCodeHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Pass extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'passes';

    protected $fillable = [
        'uuid', 'holder_name', 'phone', 'email', 'allowed_visits',
        'remaining_visits', 'start_date', 'expiration_date',
        'qr_code_path', 'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'expiration_date' => 'datetime',
        'allowed_visits' => 'integer',
        'remaining_visits' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = $model->uuid ?? (string) Str::uuid();
            $model->remaining_visits = $model->remaining_visits ?? $model->allowed_visits;
        });
    }

    public function scans()
    {
        return $this->hasMany(PassScan::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'actif'
            && $this->remaining_visits > 0
            && $this->expiration_date->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->expiration_date->isPast() || $this->status === 'expiré';
    }

    public function isUsed(): bool
    {
        return $this->remaining_visits <= 0 || $this->status === 'utilisé';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspendu';
    }

    public function updateStatus(): void
    {
        if ($this->isSuspended()) {
            return;
        }

        if ($this->expiration_date->isPast()) {
            $this->status = 'expiré';
        } elseif ($this->remaining_visits <= 0) {
            $this->status = 'utilisé';
        } else {
            $this->status = 'actif';
        }

        $this->saveQuietly();
    }

    public function getQrCodeUrl(): string
    {
        return $this->qr_code_path
            ? asset('storage/'.$this->qr_code_path)
            : '';
    }

    /**
     * Récupère le QR Code en base64 pour affichage direct
     */
    public function getQrCodeBase64()
    {
        if ($this->qr_code_path && Storage::disk('public')->exists($this->qr_code_path)) {
            $qrContent = Storage::disk('public')->get($this->qr_code_path);

            return 'data:image/png;base64,'.base64_encode($qrContent);
        }

        // Génération à la volée si le fichier n'existe pas
        $url = route('scan.show', $this->uuid);

        return QrCodeHelper::generate($url);
    }

    /**
     * Récupère le QR Code avec label
     */
    public function getQrCodeWithLabel(?string $label = null): string
    {
        $url = route('scan.show', $this->uuid);

        return QrCodeHelper::generate($url.'|'.($label ?? $this->holder_name));
    }
}
