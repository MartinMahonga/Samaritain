<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParcelFavorite extends Model
{
    protected $fillable = [
        'user_id',
        'parcel_id',
    ];

    /**
     * Get the user who favorited the parcel.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the favorited parcel.
     *
     * @return BelongsTo<Parcelle, $this>
     */
    public function parcel(): BelongsTo
    {
        return $this->belongsTo(Parcelle::class, 'parcel_id');
    }
}
