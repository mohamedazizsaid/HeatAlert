<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEquipementSensibleRequest;
use App\Http\Requests\UpdateEquipementSensibleRequest;
use App\Models\EquipementSensible;
use App\Services\EquipementSensibleService;
use App\Services\ZoneService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EquipementSensibleController extends Controller
{
    public function __construct(
        private EquipementSensibleService $service,
        private ZoneService $zoneService,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'zone_id', 'niveau_sensibilite', 'actif']);
        $equipements = $this->service->getPaginated($filters, 12);
        $zones = $this->zoneService->getActiveForSelect();
        $niveaux = EquipementSensible::NIVEAUX;

        return view('admin.equipements_sensibles.index', compact('equipements', 'zones', 'niveaux'));
    }

    public function create(): View
    {
        $zones = $this->zoneService->getActiveForSelect();
        $niveaux = EquipementSensible::NIVEAUX;
        return view('admin.equipements_sensibles.create', compact('zones', 'niveaux'));
    }

    public function store(StoreEquipementSensibleRequest $request): RedirectResponse
    {
        $this->service->create($request->validated() + ['actif' => $request->boolean('actif')]);
        return redirect()->route('admin.equipements_sensibles.index')->with('success', 'Équipement sensible créé avec succès.');
    }

    public function show(EquipementSensible $equipementSensible): View
    {
        $equipementSensible->load('zone');
        return view('admin.equipements_sensibles.show', ['equipement' => $equipementSensible]);
    }

    public function edit(EquipementSensible $equipementSensible): View
    {
        $zones = $this->zoneService->getActiveForSelect();
        $niveaux = EquipementSensible::NIVEAUX;
        return view('admin.equipements_sensibles.edit', [
            'equipement' => $equipementSensible, 'zones' => $zones, 'niveaux' => $niveaux,
        ]);
    }

    public function update(UpdateEquipementSensibleRequest $request, EquipementSensible $equipementSensible): RedirectResponse
    {
        $this->service->update($equipementSensible, $request->validated() + ['actif' => $request->boolean('actif')]);
        return redirect()->route('admin.equipements_sensibles.index')->with('success', 'Équipement sensible mis à jour avec succès.');
    }

    public function destroy(EquipementSensible $equipementSensible): RedirectResponse
    {
        $this->service->delete($equipementSensible);
        return redirect()->route('admin.equipements_sensibles.index')->with('deleted', 'Équipement sensible supprimé.');
    }
}
