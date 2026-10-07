<?php

namespace App\Services;

use App\Models\EquipementSensible;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\User;

class EquipementSensibleService
{
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = EquipementSensible::with(['zone', 'user']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($query) use ($search) {
                $query->where('nom', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('adresse', 'like', "%{$search}%")
                    ->orWhereHas('zone', fn ($zone) => $zone->where('nom', 'like', "%{$search}%")->orWhere('ville', 'like', "%{$search}%"));
            });
        }

        foreach (['zone_id', 'type', 'niveau_sensibilite'] as $filter) {
            if (!empty($filters[$filter])) {
                $query->where($filter, $filters[$filter]);
            }
        }

        if (isset($filters['actif']) && $filters['actif'] !== '') {
            $query->where('actif', (bool) (int) $filters['actif']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function getForUserPaginated(User $user, array $filters = [], int $perPage = 8): LengthAwarePaginator
    {
        $query = EquipementSensible::with('zone')->where('user_id', $user->id);
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(fn ($q) => $q->where('nom', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%"));
        }
        if (!empty($filters['niveau_sensibilite'])) {
            $query->where('niveau_sensibilite', $filters['niveau_sensibilite']);
        }
        if (isset($filters['actif']) && $filters['actif'] !== '') {
            $query->where('actif', (bool) $filters['actif']);
        }
        return $query->orderBy('nom')->paginate($perPage)->withQueryString();
    }

    public function create(array $data): EquipementSensible
    {
        return EquipementSensible::create($data);
    }

    public function update(EquipementSensible $equipement, array $data): bool
    {
        return $equipement->update($data);
    }

    public function delete(EquipementSensible $equipement): bool
    {
        return $equipement->delete();
    }
}
