<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SignalementCoupure;
use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SignalementCoupureController extends Controller
{
    /**
     * Liste paginée des signalements avec leurs relations.
     */
    public function index(Request $request): View
    {
        $query = SignalementCoupure::with(['user', 'coupure.zone'])
            ->orderByRaw("FIELD(statut_validation, 'en_attente', 'valide', 'rejete')")
            ->orderByDesc('date_signalement');

        if ($request->filled('statut_validation')) {
            $query->where('statut_validation', $request->input('statut_validation'));
        }

        if ($request->filled('zone_id')) {
            $query->whereHas('coupure', function ($coupureQuery) use ($request) {
                $coupureQuery->where('zone_id', $request->input('zone_id'));
            });
        }

        $signalements = $query->paginate(10)->withQueryString();
        $zones = Zone::query()->orderBy('gouvernorat')->orderBy('nom')->get();

        return view('admin.signalements.index', compact('signalements', 'zones'));
    }

    /**
     * Affiche le détail d'un signalement.
     */
    public function show(SignalementCoupure $signalement): View
    {
        $signalement->load(['user', 'coupure.zone']);

        return view('admin.signalements.show', compact('signalement'));
    }

    /**
     * Valide un signalement.
     */
    public function valider(SignalementCoupure $signalement): RedirectResponse
    {
        $signalement->update(['statut_validation' => 'valide']);

        return redirect()->route('admin.signalements.index')
            ->with('success', 'Le signalement a été validé avec succès.');
    }

    /**
     * Rejette un signalement.
     */
    public function rejeter(SignalementCoupure $signalement): RedirectResponse
    {
        $signalement->update(['statut_validation' => 'rejete']);

        return redirect()->route('admin.signalements.index')
            ->with('success', 'Le signalement a été rejeté.');
    }

    /**
     * Supprime un signalement.
     */
    public function destroy(SignalementCoupure $signalement): RedirectResponse
    {
        $signalement->delete();

        return redirect()->route('admin.signalements.index')
            ->with('deleted', 'Le signalement a été supprimé.');
    }
}
