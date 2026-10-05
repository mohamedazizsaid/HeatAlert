<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class SignalementCoupure extends Model
{
    use HasFactory;

    protected $table = 'signalements_coupures';

    public const STATUTS_VALIDATION = ['en_attente', 'valide', 'rejete'];

    protected $fillable = [
        'description',
        'date_signalement',
        'photo',
        'statut_validation',
        'user_id',
        'coupure_id',
    ];

    protected $casts = [
        'date_signalement' => 'datetime',
    ];

    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo) {
            return null;
        }

        return app(\App\Services\CloudinaryService::class)->resolveUrl($this->photo);
    }

    public function coupure(): BelongsTo
    {
        return $this->belongsTo(Coupure::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}