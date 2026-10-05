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
    public function index(Request $request): View
    {
        $query = AlerteMeteo::with('zone');

        // Recherche texte
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhereHas('zone', fn($z) => $z->where('ville', 'like', "%$search%"));
            });
        }

        // Filtres
        if ($request->filled('niveau')) {
            $query->where('niveau', $request->get('niveau'));
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->get('statut'));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        $alertes = $query->orderByDesc('date_debut')->paginate(12)->withQueryString();
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
     * Fiche détaillée d'une alerte météo.
     */
    public function show(AlerteMeteo $alerte): View
    {
        $alerte->load(['zone', 'conseils']);
        return view('admin.alertes.show', compact('alerte'));
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
        ], [
            'titre.required'                 => 'Le titre de l\'alerte est obligatoire.',
            'titre.max'                      => 'Le titre ne peut pas dépasser 150 caractères.',
            'type.required'                  => 'Le type d\'aléa est obligatoire.',
            'type.in'                        => 'Le type d\'aléa sélectionné est invalide.',
            'niveau.required'                => 'Le niveau de gravité est obligatoire.',
            'niveau.in'                      => 'Le niveau sélectionné est invalide.',
            'statut.required'                => 'Le statut de l\'alerte est obligatoire.',
            'statut.in'                      => 'Le statut sélectionné est invalide.',
            'description.required'           => 'La description de l\'alerte est obligatoire.',
            'description.min'                => 'La description doit contenir au moins 10 caractères.',
            'temperature_min.between'        => 'La température minimale doit être comprise entre -50 et 60°C.',
            'temperature_max.between'        => 'La température maximale doit être comprise entre -50 et 60°C.',
            'temperature_max.gte'            => 'La température max doit être supérieure ou égale à la température min.',
            'temperature_ressentie.between'  => 'La température ressentie doit être comprise entre -50 et 70°C.',
            'humidite.between'               => 'L\'humidité doit être un pourcentage entre 0 et 100%.',
            'indice_uv.between'              => 'L\'indice UV doit être un entier compris entre 0 et 20.',
            'vitesse_vent.min'               => 'La vitesse du vent ne peut pas être négative.',
            'vitesse_vent.max'               => 'La vitesse du vent ne peut pas dépasser 300 km/h.',
            'date_debut.required'            => 'La date de début est obligatoire.',
            'date_debut.date'                => 'La date de début doit être une date valide.',
            'date_fin.date'                  => 'La date de fin doit être une date valide.',
            'date_fin.after_or_equal'        => 'La date de fin doit être égale ou postérieure à la date de début.',
            'zone_id.exists'                 => 'La zone géographique sélectionnée est introuvable.',
        ]);
    }
}
