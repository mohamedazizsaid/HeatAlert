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
     * Crée un nouveau conseil.
     */
    public function create(array $data): Conseil
    {
        return Conseil::create($data);
    }

    /**
     * Met à jour un conseil existant.
     */
    public function update(Conseil $conseil, array $data): bool
    {
        return $conseil->update($data);
    }

    /**
     * Supprime un conseil.
     */
    public function delete(Conseil $conseil): bool
    {
        return $conseil->delete();
    }
}
