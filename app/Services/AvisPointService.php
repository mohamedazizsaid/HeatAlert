<?php

namespace App\Services;

use App\Models\AvisPoint;
use App\Models\PointFraicheur;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Service pour la gestion des Avis sur les Points de Fraîcheur.
 */
class AvisPointService
{
    /**
     * Retourne les avis paginés (pour l'admin ou pour un point donné).
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = AvisPoint::with(['user', 'pointFraicheur']);

        if (!empty($filters['point_fraicheur_id'])) {
            $query->where('point_fraicheur_id', $filters['point_fraicheur_id']);
        }

        if (!empty($filters['note'])) {
            $query->where('note', $filters['note']);
        }

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function ($q) use ($s) {
                $q->where('commentaire', 'like', "%{$s}%")
                  ->orWhereHas('user', function ($sub) use ($s) {
                      $sub->where('nom', 'like', "%{$s}%")
                          ->orWhere('prenom', 'like', "%{$s}%")
                          ->orWhere('email', 'like', "%{$s}%");
                  })
                  ->orWhereHas('pointFraicheur', function ($sub) use ($s) {
                      $sub->where('nom', 'like', "%{$s}%");
                  });
            });
        }

        return $query->orderByDesc('date_avis')->paginate($perPage)->withQueryString();
    }

    /**
     * Crée un avis point.
     */
    public function create(array $data): AvisPoint
    {
        return AvisPoint::create($data);
    }

    /**
     * Met à jour un avis.
     */
    public function update(AvisPoint $avis, array $data): bool
    {
        return $avis->update($data);
    }

    /**
     * Supprime un avis.
     */
    public function delete(AvisPoint $avis): bool
    {
        return $avis->delete();
    }

    /**
     * Vérifie si un user a déjà laissé un avis sur un point.
     */
    public function userHasAvis(int $userId, int $pointId): bool
    {
        return AvisPoint::where('user_id', $userId)
            ->where('point_fraicheur_id', $pointId)
            ->exists();
    }

    /**
     * Retourne l'avis d'un user sur un point (ou null).
     */
    public function getUserAvis(int $userId, int $pointId): ?AvisPoint
    {
        return AvisPoint::where('user_id', $userId)
            ->where('point_fraicheur_id', $pointId)
            ->first();
    }
}
