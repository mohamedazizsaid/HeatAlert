<?php

namespace App\Services;

use App\Models\Zone;
use Illuminate\Database\Eloquent\Collection;

/**
 * Service pour la gestion des Zones.
 */
class ZoneService
{
    /**
     * Retourne toutes les zones actives paginées.
     */
    public function getPaginated(int $perPage = 15)
    {
        return Zone::orderBy('gouvernorat')->orderBy('nom')->paginate($perPage);
    }

    /**
     * Crée une nouvelle zone.
     */
    public function create(array $data): Zone
    {
        return Zone::create($data);
    }

    /**
     * Met à jour une zone existante.
     */
    public function update(Zone $zone, array $data): bool
    {
        return $zone->update($data);
    }

    /**
     * Supprime une zone.
     */
    public function delete(Zone $zone): bool
    {
        return $zone->delete();
    }

    /**
     * Retourne toutes les zones actives pour les selects.
     */
    public function getActiveForSelect(): Collection
    {
        return Zone::where('actif', true)->orderBy('gouvernorat')->orderBy('nom')->get();
    }
}
