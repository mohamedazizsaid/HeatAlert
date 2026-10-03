<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Coupure extends Model
{
    use HasFactory;

    public const TYPES = ['delestage', 'surcharge', 'panne'];
    public const STATUTS = ['prevue', 'en_cours', 'terminee'];

    protected $fillable = [
        'type',
        'statut',
        'date_debut',
        'date_fin',
        'cause',
        'zone_id',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function signalements(): HasMany
    {
        return $this->hasMany(SignalementCoupure::class);
    }
}