<?php

namespace App\Services;

use App\Models\PointFraicheur;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Service pour la gestion des Points de Fraîcheur.
 */
class PointFraicheurService
{
    /**
     * Retourne les points paginés avec filtres optionnels.
     */
    public function getPaginated(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = PointFraicheur::with('zone')->withCount('avisPoints')->withAvg('avisPoints', 'note');

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function ($q) use ($s) {
                $q->where('nom', 'like', "%$s%")
                  ->orWhere('adresse', 'like', "%$s%");
            });
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['actif']) && $filters['actif'] !== '') {
            $query->where('actif', (bool) $filters['actif']);
        }

        if (!empty($filters['zone_id'])) {
            $query->where('zone_id', $filters['zone_id']);
        }

        if (!empty($filters['pmr'])) {
            $query->where('accessible_pmr', true);
        }

        return $query->orderBy('nom')->paginate($perPage)->withQueryString();
    }

    /**
     * Crée un nouveau point de fraîcheur.
     */
    public function create(array $data): PointFraicheur
    {
        return PointFraicheur::create($data);
    }

    /**
     * Met à jour un point de fraîcheur.
     */
    public function update(PointFraicheur $point, array $data): bool
    {
        return $point->update($data);
    }

    /**
     * Supprime un point de fraîcheur.
     */
    public function delete(PointFraicheur $point): bool
    {
        return $point->delete();
    }

    /**
     * Points actifs pour select.
     */
    public function getActiveForSelect(): Collection
    {
        return PointFraicheur::where('actif', true)->orderBy('nom')->get();
    }
}
