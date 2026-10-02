<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlerteMeteo;
use App\Services\AlerteMeteoService;
use App\Services\ZoneService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AlerteMeteoController extends Controller
{
    public function __construct(
        private AlerteMeteoService $alerteService,
        private ZoneService $zoneService,
    ) {}

    /**
     * Liste des alertes météo.
     */
    public function index(): View
    {
        $alertes = $this->alerteService->getPaginated(12);
        return view('admin.alertes.index', compact('alertes'));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        $zones  = $this->zoneService->getActiveForSelect();
        $niveaux = AlerteMeteo::NIVEAUX;
        $types   = AlerteMeteo::TYPES;
        $statuts = AlerteMeteo::STATUTS;
        return view('admin.alertes.create', compact('zones', 'niveaux', 'types', 'statuts'));
    }

    /**
     * Enregistre une nouvelle alerte.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateAlerte($request);
        $this->alerteService->create($validated);

        return redirect()->route('admin.alertes.index')
            ->with('success', 'Alerte météo créée avec succès.');
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(AlerteMeteo $alerte): View
    {
        $zones  = $this->zoneService->getActiveForSelect();
        $niveaux = AlerteMeteo::NIVEAUX;
        $types   = AlerteMeteo::TYPES;
        $statuts = AlerteMeteo::STATUTS;
        return view('admin.alertes.edit', compact('alerte', 'zones', 'niveaux', 'types', 'statuts'));
    }

    /**
     * Mise à jour d'une alerte.
     */
    public function update(Request $request, AlerteMeteo $alerte): RedirectResponse
    {
        $validated = $this->validateAlerte($request);
        $this->alerteService->update($alerte, $validated);

        return redirect()->route('admin.alertes.index')
            ->with('success', 'Alerte météo mise à jour avec succès.');
    }

    /**
     * Suppression d'une alerte.
     */
    public function destroy(AlerteMeteo $alerte): RedirectResponse
    {
        $titre = $alerte->titre;
        $this->alerteService->delete($alerte);

        return redirect()->route('admin.alertes.index')
            ->with('deleted', "L'alerte « {$titre} » a été supprimée.");
    }

    /**
     * Règles de validation communes store/update.
     */
    private function validateAlerte(Request $request): array
    {
        return $request->validate([
            'titre'                 => ['required', 'string', 'max:150'],
            'type'                  => ['required', Rule::in(AlerteMeteo::TYPES)],
            'niveau'                => ['required', Rule::in(AlerteMeteo::NIVEAUX)],
            'statut'                => ['required', Rule::in(AlerteMeteo::STATUTS)],
            'description'           => ['required', 'string', 'min:10'],
            'temperature_min'       => ['nullable', 'numeric', 'between:-50,60'],
            'temperature_max'       => ['nullable', 'numeric', 'between:-50,60', 'gte:temperature_min'],
            'temperature_ressentie' => ['nullable', 'numeric', 'between:-50,70'],
            'humidite'              => ['nullable', 'integer', 'between:0,100'],
            'indice_uv'             => ['nullable', 'integer', 'between:0,20'],
            'vitesse_vent'          => ['nullable', 'numeric', 'min:0', 'max:300'],
            'risque_coupure'        => ['nullable', 'boolean'],
            'source'                => ['nullable', 'string', 'max:100'],
            'date_debut'            => ['required', 'date'],
            'date_fin'              => ['nullable', 'date', 'after_or_equal:date_debut'],
            'zone_id'               => ['nullable', 'exists:zones,id'],
        ]);
    }
}
