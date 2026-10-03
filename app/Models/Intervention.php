<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;

    public const STATUTS = ['planifiee', 'en_cours', 'terminee'];

    protected $fillable = [
        'coupure_id',
        'equipe',
        'date_prevue',
        'duree_estimee_min',
        'statut',
        'notes',
    ];

    protected $casts = [
        'date_prevue' => 'datetime',
        'duree_estimee_min' => 'integer',
    ];

    public function coupure(): BelongsTo
    {
        return $this->belongsTo(Coupure::class);
    }
}
