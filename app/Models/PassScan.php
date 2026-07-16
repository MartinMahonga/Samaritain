<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PassScan extends Model
{
    use HasFactory;

    protected $table = 'pass_scans';

    protected $fillable = [
        'pass_id', 'visit_pass_id', 'user_id', 'scanned_at', 'ip_address', 'user_agent', 'device_info',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function pass(): BelongsTo
    {
        return $this->belongsTo(Pass::class);
    }

    public function visitPass(): BelongsTo
    {
        return $this->belongsTo(VisitPass::class, 'visit_pass_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
