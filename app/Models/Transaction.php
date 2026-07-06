<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasUuids;

    protected $primaryKey = 'transaction_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'visit_pass_id',
        'status',
        'amount',
    ];

    // Nécessaire car ta clé primaire ne s'appelle pas "id"
    public function uniqueIds(): array
    {
        return ['transaction_id'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function visitPass()
    {
        return $this->belongsTo(VisitPass::class);
    }
}
