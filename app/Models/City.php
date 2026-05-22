<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    protected $fillable = ['country_id', 'name', 'normalized_name'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->normalized_name = mb_strtolower($model->name);
        });

        static::updating(function ($model) {
            if ($model->isDirty('name')) {
                $model->normalized_name = mb_strtolower($model->name);
            }
        });
    }
}
