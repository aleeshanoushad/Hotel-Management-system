<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = ['name', 'normalized_name'];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
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
