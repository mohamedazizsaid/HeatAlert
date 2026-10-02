<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
{
    protected $fillable = [
        'nom', 'ville', 'gouvernorat', 'code_postal',
        'latitude', 'longitude', 'description', 'actif',
    ];

    protected $casts = [
        'latitude'  => 'decimal:7',
        'longitude' => 'decimal:7',
        'actif'     => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function alertes(): HasMany
    {
        return $this->hasMany(AlerteMeteo::class);
    }
}