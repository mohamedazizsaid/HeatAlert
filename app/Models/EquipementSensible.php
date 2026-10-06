<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipementSensible extends Model
{
    use HasFactory;

    public const NIVEAUX = ['faible', 'moyen', 'eleve'];
    public const TYPES = ['frigo', 'medicament', 'ventilateur', 'appareil_medical'];

    protected $table = 'equipements_sensibles';

    protected $fillable = [
        'nom', 'type', 'description', 'adresse', 'zone_id', 'user_id',
        'niveau_sensibilite', 'actif',
    ];

    protected $casts = ['actif' => 'boolean'];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function module4Notifications(): HasMany
    {
        return $this->hasMany(Module4Notification::class);
    }
}
