<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    protected $fillable = [
        'hotel_id',
        'name',
        'price_per_night',
        'max_occupancy',
        'available_rooms',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'max_occupancy' => 'integer',
        'available_rooms' => 'integer',
    ];

    /**
     * Get the hotel that owns this room
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
}
