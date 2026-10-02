<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Conseil extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'contenu',
        'categorie',
        'niveau_alerte_cible',
        'icone',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function alertes(): BelongsToMany
    {
        return $this->belongsToMany(AlerteMeteo::class, 'alerte_conseil')->withTimestamps();
    }
}