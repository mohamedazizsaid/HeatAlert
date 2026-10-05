<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePointFraicheurRequest;
use App\Http\Requests\UpdatePointFraicheurRequest;
use App\Http\Requests\UpdateAvisPointRequest;
use App\Models\AvisPoint;
use App\Models\PointFraicheur;
use App\Models\Zone;
use App\Services\PointFraicheurService;
use App\Services\AvisPointService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PointFraicheurController extends Controller
{
    public function __construct(
        private PointFraicheurService $service,
        private AvisPointService $avisService,
    ) {}

    /**
     * Liste des points de fraîcheur.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'type', 'actif', 'zone_id', 'pmr']);
        $perPage = (int) $request->get('per_page', 8);
        $points  = $this->service->getPaginated($filters, $perPage);
        $zones   = Zone::orderBy('nom')->get();

        return view('admin.points_fraicheur.index', compact('points', 'zones', 'filters'));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        $zones = Zone::where('actif', true)->orderBy('gouvernorat')->orderBy('nom')->get();
        $point = null;
        return view('admin.points_fraicheur.create', compact('zones', 'point'));
    }

    /**
     * Enregistrement d'un nouveau point.
     */
    public function store(StorePointFraicheurRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['accessible_pmr'] = $request->boolean('accessible_pmr');
        $validated['actif']          = $request->boolean('actif');
        $this->service->create($validated);

        return redirect()->route('admin.points_fraicheur.index')
            ->with('success', 'Point de fraîcheur créé avec succès.');
    }

    /**
     * Fiche détail d'un point.
     */
    public function show(PointFraicheur $pointsFraicheur): View
    {
        $point = $pointsFraicheur->load('zone');
        $point->loadCount('avisPoints');
        $avis = $point->avisPoints()->with('user')->orderByDesc('date_avis')->paginate(8);

        return view('admin.points_fraicheur.show', compact('point', 'avis'));
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(PointFraicheur $pointsFraicheur): View
    {
        $point = $pointsFraicheur;
        $zones = Zone::where('actif', true)->orderBy('gouvernorat')->orderBy('nom')->get();
        return view('admin.points_fraicheur.edit', compact('point', 'zones'));
    }

    /**
     * Mise à jour d'un point.
     */
    public function update(UpdatePointFraicheurRequest $request, PointFraicheur $pointsFraicheur): RedirectResponse
    {
        $validated = $request->validated();
        $validated['accessible_pmr'] = $request->boolean('accessible_pmr');
        $validated['actif']          = $request->boolean('actif');
        $this->service->update($pointsFraicheur, $validated);

        return redirect()->route('admin.points_fraicheur.index')
            ->with('success', 'Point de fraîcheur mis à jour avec succès.');
    }

    /**
     * Suppression d'un point.
     */
    public function destroy(PointFraicheur $pointsFraicheur): RedirectResponse
    {
        $nom = $pointsFraicheur->nom;
        $this->service->delete($pointsFraicheur);

        return redirect()->route('admin.points_fraicheur.index')
            ->with('deleted', "Le point « {$nom} » a été supprimé.");
    }

    // ─── Supervision des Avis ─────────────────────────────────────────────────

    /**
     * Liste globale des avis (modération admin).
     */
    public function avisIndex(Request $request): View
    {
        $filters = $request->only(['note', 'point_fraicheur_id', 'search']);
        $perPage = (int) $request->get('per_page', 5);
        $avis    = $this->avisService->getPaginated($filters, $perPage);
        $points  = PointFraicheur::orderBy('nom')->get();

        return view('admin.points_fraicheur.avis_index', compact('avis', 'points', 'filters'));
    }

    /**
     * Mise à jour d'un avis (modération admin).
     */
    public function avisUpdate(UpdateAvisPointRequest $request, AvisPoint $avisPoint): RedirectResponse
    {
        $validated = $request->validated();
        $this->avisService->update($avisPoint, $validated);

        return back()->with('success', 'L\'avis a été mis à jour avec succès.');
    }

    /**
     * Suppression d'un avis (modération).
     */
    public function avisDestroy(AvisPoint $avisPoint): RedirectResponse
    {
        $this->avisService->delete($avisPoint);

        return back()->with('deleted', 'L\'avis a été supprimé.');
    }
}
