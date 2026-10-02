<?php

namespace App\Http\Controllers;

use App\Models\AlerteMeteo;
use App\Models\Conseil;
use App\Models\Zone;

class FrontController extends Controller
{
    /**
     * Page d'accueil du front pour les utilisateurs connectés.
     */
    public function index()
    {
        $alertesActives = AlerteMeteo::with('zone')
            ->where('statut', 'active')
            ->orderByRaw("FIELD(niveau, 'rouge','orange','jaune','vert')")
            ->take(6)
            ->get();

        $zonesActives = Zone::where('actif', true)
            ->withCount(['alertes' => fn($q) => $q->where('statut', 'active')])
            ->orderByDesc('alertes_count')
            ->take(6)
            ->get();

        $conseils = Conseil::where('actif', true)
            ->take(6)
            ->get();

        $stats = [
            'zones'   => Zone::where('actif', true)->count(),
            'alertes' => AlerteMeteo::where('statut', 'active')->count(),
            'conseils'=> Conseil::where('actif', true)->count(),
        ];

        return view('front.home', compact('alertesActives', 'zonesActives', 'conseils', 'stats'));
    }

    /**
     * Liste des zones (front).
     */
    public function zones()
    {
        $zones = Zone::where('actif', true)
            ->withCount(['alertes', 'alertes as alertes_actives_count' => fn($q) => $q->where('statut', 'active')])
            ->orderBy('gouvernorat')
            ->paginate(12);

        return view('front.zones.index', compact('zones'));
    }

    /**
     * Détails d'une zone (front).
     */
    public function zoneShow(Zone $zone)
    {
        $zone->loadCount(['alertes', 'alertes as alertes_actives_count' => fn($q) => $q->where('statut', 'active')]);
        $alertes = $zone->alertes()->with('zone')
            ->orderByRaw("FIELD(statut,'active','brouillon','terminee','annulee')")
            ->orderByRaw("FIELD(niveau,'rouge','orange','jaune','vert')")
            ->paginate(8);

        $niveauxData = [
            'rouge'  => $zone->alertes()->where('niveau', 'rouge')->count(),
            'orange' => $zone->alertes()->where('niveau', 'orange')->count(),
            'jaune'  => $zone->alertes()->where('niveau', 'jaune')->count(),
            'vert'   => $zone->alertes()->where('niveau', 'vert')->count(),
        ];

        return view('front.zones.show', compact('zone', 'alertes', 'niveauxData'));
    }

    /**
     * Liste des alertes (front).
     */
    public function alertes()
    {
        $alertes = AlerteMeteo::with('zone')
            ->where('statut', 'active')
            ->orderByRaw("FIELD(niveau,'rouge','orange','jaune','vert')")
            ->paginate(9);

        $statsNiveaux = [
            'rouge'  => AlerteMeteo::where('statut', 'active')->where('niveau', 'rouge')->count(),
            'orange' => AlerteMeteo::where('statut', 'active')->where('niveau', 'orange')->count(),
            'jaune'  => AlerteMeteo::where('statut', 'active')->where('niveau', 'jaune')->count(),
            'vert'   => AlerteMeteo::where('statut', 'active')->where('niveau', 'vert')->count(),
        ];

        return view('front.alertes.index', compact('alertes', 'statsNiveaux'));
    }

    /**
     * Détails d'une alerte (front).
     */
    public function alerteShow(AlerteMeteo $alerte)
    {
        $alerte->load(['zone', 'conseils']);
        return view('front.alertes.show', compact('alerte'));
    }

    /**
     * Liste des conseils (front).
     */
    public function conseils()
    {
        $query = Conseil::where('actif', true);

        if (request('categorie')) {
            $query->where('categorie', request('categorie'));
        }

        $conseils = $query->orderBy('categorie')->paginate(12)->appends(request()->query());

        $categories = Conseil::where('actif', true)
            ->select('categorie')
            ->whereNotNull('categorie')
            ->distinct()
            ->orderBy('categorie')
            ->pluck('categorie');

        return view('front.conseils.index', compact('conseils', 'categories'));
    }

    /**
     * Détails d'un conseil de santé (front).
     */
    public function conseilShow(Conseil $conseil)
    {
        $conseil->load(['alertes.zone']);

        $relatedConseils = Conseil::where('actif', true)
            ->where('id', '!=', $conseil->id)
            ->where('categorie', $conseil->categorie)
            ->take(3)
            ->get();

        return view('front.conseils.show', compact('conseil', 'relatedConseils'));
    }
}
