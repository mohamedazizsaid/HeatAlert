<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use App\Services\ZoneService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ZoneController extends Controller
{
    public function __construct(private ZoneService $zoneService) {}

    /**
     * Liste des zones.
     */
    public function index(Request $request): View
    {
        $query = Zone::query();

        // Recherche texte
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('ville', 'like', "%$search%")
                  ->orWhere('gouvernorat', 'like', "%$search%");
            });
        }

        // Filtres
        if ($request->filled('actif')) {
            $query->where('actif', $request->get('actif'));
        }

        $zones = $query->orderBy('gouvernorat')->orderBy('ville')->paginate(12)->withQueryString();
        return view('admin.zones.index', compact('zones'));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        return view('admin.zones.create');
    }

    /**
     * Enregistre une nouvelle zone.
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'nom'         => ['required', 'string', 'max:100'],
            'ville'       => ['required', 'string', 'max:100'],
            'gouvernorat' => ['nullable', 'string', 'max:100'],
            'code_postal' => ['nullable', 'digits:4'],
            'latitude'    => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'   => ['nullable', 'numeric', 'between:-180,180'],
            'description' => ['nullable', 'string', 'max:500'],
            'actif'       => ['nullable', 'boolean'],
        ];

        $messages = [
            'nom.required'         => 'Le nom de la zone est obligatoire.',
            'nom.max'              => 'Le nom de la zone ne peut pas dépasser 100 caractères.',
            'ville.required'       => 'La ville est obligatoire.',
            'ville.max'            => 'La ville ne peut pas dépasser 100 caractères.',
            'gouvernorat.max'      => 'Le gouvernorat ne peut pas dépasser 100 caractères.',
            'code_postal.digits'   => 'Le code postal doit être composé d\'exactement 4 chiffres.',
            'latitude.numeric'     => 'La latitude doit être une coordonnée numérique.',
            'latitude.between'     => 'La latitude doit être comprise entre -90 et 90.',
            'longitude.numeric'    => 'La longitude doit être une coordonnée numérique.',
            'longitude.between'    => 'La longitude doit être comprise entre -180 et 180.',
            'description.max'      => 'La description ne peut pas dépasser 500 caractères.',
        ];

        $validated = $request->validate($rules, $messages);

        $validated['actif'] = $request->boolean('actif');
        $this->zoneService->create($validated);

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zone créée avec succès.');
    }

    /**
     * Fiche détaillée d'une zone.
     */
    public function show(Zone $zone): View
    {
        $zone->loadCount(['users', 'alertes']);
        $alertes = $zone->alertes()->orderByDesc('date_debut')->paginate(6);
        $activeAlertesCount = $zone->alertes()->where('statut', 'active')->count();

        return view('admin.zones.show', compact('zone', 'alertes', 'activeAlertesCount'));
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(Zone $zone): View
    {
        return view('admin.zones.edit', compact('zone'));
    }

    /**
     * Mise à jour d'une zone.
     */
    public function update(Request $request, Zone $zone): RedirectResponse
    {
        $rules = [
            'nom'         => ['required', 'string', 'max:100'],
            'ville'       => ['required', 'string', 'max:100'],
            'gouvernorat' => ['nullable', 'string', 'max:100'],
            'code_postal' => ['nullable', 'digits:4'],
            'latitude'    => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'   => ['nullable', 'numeric', 'between:-180,180'],
            'description' => ['nullable', 'string', 'max:500'],
            'actif'       => ['nullable', 'boolean'],
        ];

        $messages = [
            'nom.required'         => 'Le nom de la zone est obligatoire.',
            'nom.max'              => 'Le nom de la zone ne peut pas dépasser 100 caractères.',
            'ville.required'       => 'La ville est obligatoire.',
            'ville.max'            => 'La ville ne peut pas dépasser 100 caractères.',
            'gouvernorat.max'      => 'Le gouvernorat ne peut pas dépasser 100 caractères.',
            'code_postal.digits'   => 'Le code postal doit être composé d\'exactement 4 chiffres.',
            'latitude.numeric'     => 'La latitude doit être une coordonnée numérique.',
            'latitude.between'     => 'La latitude doit être comprise entre -90 et 90.',
            'longitude.numeric'    => 'La longitude doit être une coordonnée numérique.',
            'longitude.between'    => 'La longitude doit être comprise entre -180 et 180.',
            'description.max'      => 'La description ne peut pas dépasser 500 caractères.',
        ];

        $validated = $request->validate($rules, $messages);

        $validated['actif'] = $request->boolean('actif');
        $this->zoneService->update($zone, $validated);

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zone mise à jour avec succès.');
    }

    /**
     * Suppression d'une zone.
     */
    public function destroy(Zone $zone): RedirectResponse
    {
        $this->zoneService->delete($zone);

        return redirect()->route('admin.zones.index')
            ->with('deleted', "La zone « {$zone->nom} » a été supprimée.");
    }
}
