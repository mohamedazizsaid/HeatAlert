<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conseil extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre', 'contenu', 'categorie', 'niveau_alerte_cible', 'icone', 'actif',
    ];

    protected $casts = ['actif' => 'boolean'];

    public function alertes()
    {
        return $this->belongsToMany(AlerteMeteo::class, 'alerte_conseil')->withTimestamps();
    }
}