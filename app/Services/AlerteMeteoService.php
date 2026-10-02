<?php

namespace App\Services;

use App\Models\AlerteMeteo;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service pour la gestion des Alertes Météo.
 */
class AlerteMeteoService
{
    /**
     * Retourne les alertes paginées avec la zone associée.
     */
    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return AlerteMeteo::with('zone')
            ->orderByDesc('date_debut')
            ->paginate($perPage);
    }

    /**
     * Crée une nouvelle alerte météo.
     */
    public function create(array $data): AlerteMeteo
    {
        return AlerteMeteo::create($data);
    }

    /**
     * Met à jour une alerte météo.
     */
    public function update(AlerteMeteo $alerte, array $data): bool
    {
        return $alerte->update($data);
    }

    /**
     * Supprime une alerte météo.
     */
    public function delete(AlerteMeteo $alerte): bool
    {
        return $alerte->delete();
    }
}
