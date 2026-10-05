<?php

namespace App\Http\Controllers;

use App\Models\AvisPoint;
use App\Models\PointFraicheur;
use App\Models\Zone;
use App\Services\PointFraicheurService;
use App\Services\AvisPointService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Contrôleur Front pour les Points de Fraîcheur (consultation + avis citoyens).
 */
class FrontPointFraicheurController extends Controller
{
    public function __construct(
        private PointFraicheurService $service,
        private AvisPointService $avisService,
    ) {}

    /**
     * Liste publique des points de fraîcheur avec recherche et filtres.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'type', 'zone_id', 'pmr']);
        $points  = $this->service->getPaginated(array_merge($filters, ['actif' => 1]), 12);
        $zones   = Zone::where('actif', true)->orderBy('nom')->get();

        return view('front.points_fraicheur.index', compact('points', 'zones', 'filters'));
    }

    /**
     * Fiche détail d'un point (avec ses avis).
     */
    public function show(PointFraicheur $pointFraicheur): View
    {
        $point = $pointFraicheur->load('zone');
        $point->loadCount('avisPoints');
        $avis = $point->avisPoints()->with('user')->orderByDesc('date_avis')->paginate(6);

        $monAvis = null;
        if (auth()->check()) {
            $monAvis = $this->avisService->getUserAvis(auth()->id(), $point->id);
        }

        return view('front.points_fraicheur.show', compact('point', 'avis', 'monAvis'));
    }

    /**
     * Enregistrer un avis sur un point.
     */
    public function storeAvis(Request $request, PointFraicheur $pointFraicheur): RedirectResponse
    {
        // Un user ne peut laisser qu'un seul avis par point
        if ($this->avisService->userHasAvis(auth()->id(), $pointFraicheur->id)) {
            return back()->with('error', 'Vous avez déjà laissé un avis sur ce point.');
        }

        $validated = $request->validate([
            'note'        => ['required', 'integer', 'min:1', 'max:5'],
            'commentaire' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->avisService->create([
            'note'              => $validated['note'],
            'commentaire'       => $validated['commentaire'] ?? null,
            'date_avis'         => now()->toDateString(),
            'user_id'           => auth()->id(),
            'point_fraicheur_id' => $pointFraicheur->id,
        ]);

        return back()->with('success', 'Votre avis a été publié avec succès !');
    }

    /**
     * Modifier un avis existant.
     */
    public function updateAvis(Request $request, AvisPoint $avisPoint): RedirectResponse
    {
        // Seul l'auteur peut modifier
        if ($avisPoint->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'note'        => ['required', 'integer', 'min:1', 'max:5'],
            'commentaire' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->avisService->update($avisPoint, [
            'note'        => $validated['note'],
            'commentaire' => $validated['commentaire'] ?? null,
            'date_avis'   => now()->toDateString(),
        ]);

        return back()->with('success', 'Votre avis a été mis à jour.');
    }

    /**
     * Supprimer un avis (auteur uniquement).
     */
    public function destroyAvis(AvisPoint $avisPoint): RedirectResponse
    {
        if ($avisPoint->user_id !== auth()->id()) {
            abort(403);
        }

        $this->avisService->delete($avisPoint);

        return back()->with('deleted', 'Votre avis a été supprimé.');
    }
}
