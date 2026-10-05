<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PointFraicheur extends Model
{
    use HasFactory;

    protected $table = 'points_fraicheur';

    protected $fillable = [
        'nom', 'type', 'adresse', 'latitude', 'longitude',
        'horaires', 'accessible_pmr', 'actif', 'zone_id',
    ];

    protected $casts = [
        'latitude'       => 'decimal:7',
        'longitude'      => 'decimal:7',
        'accessible_pmr' => 'boolean',
        'actif'          => 'boolean',
    ];

    /** Labels lisibles pour le type */
    public static array $typeLabels = [
        'parc'            => 'Parc / Espace vert',
        'salle_climatisee' => 'Salle climatisée',
        'fontaine'        => 'Fontaine / Borne d\'eau',
        'piscine'         => 'Piscine municipale',
        'bibliotheque'    => 'Bibliothèque',
        'autre'           => 'Autre',
    ];

    /** Icônes FA pour chaque type */
    public static array $typeIcons = [
        'parc'            => 'fa-tree',
        'salle_climatisee' => 'fa-building',
        'fontaine'        => 'fa-droplet',
        'piscine'         => 'fa-person-swimming',
        'bibliotheque'    => 'fa-book',
        'autre'           => 'fa-location-dot',
    ];

    /** Couleurs badge pour chaque type */
    public static array $typeColors = [
        'parc'            => '#16a34a',
        'salle_climatisee' => '#2563eb',
        'fontaine'        => '#0ea5e9',
        'piscine'         => '#06b6d4',
        'bibliotheque'    => '#7c3aed',
        'autre'           => '#64748b',
    ];

    public function getTypeLabelAttribute(): string
    {
        return self::$typeLabels[$this->type] ?? ucfirst($this->type);
    }

    public function getTypeIconAttribute(): string
    {
        return self::$typeIcons[$this->type] ?? 'fa-location-dot';
    }

    public function getTypeColorAttribute(): string
    {
        return self::$typeColors[$this->type] ?? '#64748b';
    }

    public function getMoyenneNotesAttribute(): float
    {
        return round($this->avisPoints()->avg('note') ?? 0, 1);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function avisPoints(): HasMany
    {
        return $this->hasMany(AvisPoint::class, 'point_fraicheur_id');
    }
}
