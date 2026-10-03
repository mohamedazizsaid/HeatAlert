<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCoupureRequest;
use App\Http\Requests\UpdateCoupureRequest;
use App\Models\Coupure;
use App\Models\Zone;
use App\Services\ZoneService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CoupureController extends Controller
{
    public function __construct(private ZoneService $zoneService) {}

    /**
     * Liste paginée des coupures.
     */
    public function index(Request $request): View
    {
        $query = Coupure::with('zone')->withCount('signalements');

        if ($search = $request->input('search')) {
            $query->where(function ($coupureQuery) use ($search) {
                $coupureQuery
                    ->where('type', 'like', "%{$search}%")
                    ->orWhere('cause', 'like', "%{$search}%")
                    ->orWhereHas('zone', function ($zoneQuery) use ($search) {
                        $zoneQuery
                            ->where('nom', 'like', "%{$search}%")
                            ->orWhere('ville', 'like', "%{$search}%")
                            ->orWhere('gouvernorat', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->input('statut'));
        }

        if ($request->filled('zone_id')) {
            $query->where('zone_id', $request->input('zone_id'));
        }

        switch ($request->input('tri', 'priorite')) {
            case 'date_debut_asc':
                $query->orderBy('date_debut')->orderByDesc('signalements_count');
                break;
            case 'date_debut_desc':
                $query->orderByDesc('date_debut')->orderByDesc('signalements_count');
                break;
            case 'signalements_desc':
                $query->orderByDesc('signalements_count')->orderByDesc('date_debut');
                break;
            default:
                $query->orderByRaw("FIELD(statut, 'en_cours', 'prevue', 'terminee')")
                    ->orderByDesc('date_debut')
                    ->orderByDesc('signalements_count');
                break;
        }

        $coupures = $query
            ->paginate(10)
            ->withQueryString();

        $zones = Zone::query()
            ->orderBy('gouvernorat')
            ->orderBy('nom')
            ->get();
        $types = Coupure::TYPES;
        $statuts = Coupure::STATUTS;

        return view('admin.coupures.index', compact('coupures', 'zones', 'types', 'statuts'));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        $zones = $this->zoneService->getActiveForSelect();
        $types = Coupure::TYPES;
        $statuts = Coupure::STATUTS;

        return view('admin.coupures.create', compact('zones', 'types', 'statuts'));
    }

    /**
     * Enregistre une nouvelle coupure.
     */
    public function store(StoreCoupureRequest $request): RedirectResponse
    {
        Coupure::create($request->validated());

        return redirect()->route('admin.coupures.index')
            ->with('success', 'Coupure créée avec succès.');
    }

    /**
     * Affiche les détails d'une coupure et ses signalements.
     */
    public function show(Coupure $coupure): View
    {
        $coupure->load(['zone', 'signalements.user']);

        return view('admin.coupures.show', compact('coupure'));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(Coupure $coupure): View
    {
        $zones = $this->zoneService->getActiveForSelect();
        $types = Coupure::TYPES;
        $statuts = Coupure::STATUTS;

        return view('admin.coupures.edit', compact('coupure', 'zones', 'types', 'statuts'));
    }

    /**
     * Met à jour une coupure existante.
     */
    public function update(UpdateCoupureRequest $request, Coupure $coupure): RedirectResponse
    {
        $coupure->update($request->validated());

        return redirect()->route('admin.coupures.index')
            ->with('success', 'Coupure mise à jour avec succès.');
    }

    /**
     * Supprime une coupure.
     */
    public function destroy(Coupure $coupure): RedirectResponse
    {
        $coupure->delete();

        return redirect()->route('admin.coupures.index')
            ->with('deleted', 'La coupure a été supprimée.');
    }
}
