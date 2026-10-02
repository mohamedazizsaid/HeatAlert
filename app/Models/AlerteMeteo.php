<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AlerteMeteo extends Model
{
    protected $table = 'alertes_meteo';

    public const NIVEAUX = ['vert', 'jaune', 'orange', 'rouge'];
    public const TYPES = ['canicule', 'vague_de_chaleur', 'orage', 'secheresse', 'autre'];
    public const STATUTS = ['brouillon', 'active', 'terminee', 'annulee'];

    protected $fillable = [
        'titre',
        'type',
        'niveau',
        'statut',
        'description',
        'temperature_min',
        'temperature_max',
        'temperature_ressentie',
        'humidite',
        'indice_uv',
        'vitesse_vent',
        'risque_coupure',
        'source',
        'date_debut',
        'date_fin',
        'zone_id',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'risque_coupure' => 'boolean',
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function conseils(): BelongsToMany
    {
        return $this->belongsToMany(Conseil::class, 'alerte_conseil')->withTimestamps();
    }
}