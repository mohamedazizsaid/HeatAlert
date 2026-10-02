<?php

namespace App\Services;

use App\Models\Conseil;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service pour la gestion des Conseils.
 */
class ConseilService
{
    /**
     * Retourne les conseils paginés.
     */
    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Conseil::orderByDesc('created_at')->paginate($perPage);
    }

    /**
     * Crée un nouveau conseil et synchronise les alertes associées.
     */
    public function create(array $data): Conseil
    {
        $alerteIds = $data['alerte_ids'] ?? [];
        unset($data['alerte_ids']);

        $conseil = Conseil::create($data);

        if (!empty($alerteIds)) {
            $conseil->alertes()->sync($alerteIds);
        }

        return $conseil;
    }

    /**
     * Met à jour un conseil existant et synchronise les alertes.
     */
    public function update(Conseil $conseil, array $data): bool
    {
        $alerteIds = $data['alerte_ids'] ?? [];
        unset($data['alerte_ids']);

        $updated = $conseil->update($data);
        $conseil->alertes()->sync($alerteIds);

        return $updated;
    }

    /**
     * Supprime un conseil.
     */
    public function delete(Conseil $conseil): bool
    {
        return $conseil->delete();
    }
}
