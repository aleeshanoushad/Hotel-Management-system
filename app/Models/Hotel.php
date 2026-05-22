<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hotel extends Model
{
    protected $fillable = [
        'name',
        'country_id',
        'city_id',
        'rating',
        'description',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Get all rooms for this hotel
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get total rooms count
     */
    public function getTotalRoomsAttribute(): int
    {
        return $this->rooms()->sum('available_rooms');
    }
}
