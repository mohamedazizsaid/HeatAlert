<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvisPoint extends Model
{
    use HasFactory;

    protected $table = 'avis_points';

    protected $fillable = [
        'note', 'commentaire', 'date_avis',
        'user_id', 'point_fraicheur_id',
    ];

    protected $casts = [
        'note'      => 'integer',
        'date_avis' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pointFraicheur(): BelongsTo
    {
        return $this->belongsTo(PointFraicheur::class, 'point_fraicheur_id');
    }
}
