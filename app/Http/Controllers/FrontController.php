<?php

namespace App\Http\Controllers;

use App\Models\AlerteMeteo;
use App\Models\Conseil;
use App\Models\Zone;
use Illuminate\Http\Request;

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
     * Dashboard Météo Mondiale en direct avec l'API Open-Meteo.
     */
    public function globalWeather()
    {
        return view('front.weather.global');
    }

    /**
     * Liste des zones (front) avec recherche et filtres.
     */
    public function zones(Request $request)
    {
        $query = Zone::where('actif', true)
            ->withCount(['alertes', 'alertes as alertes_actives_count' => fn($q) => $q->where('statut', 'active')]);

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('ville', 'like', "%{$search}%")
                  ->orWhere('code_postal', 'like', "%{$search}%")
                  ->orWhere('gouvernorat', 'like', "%{$search}%");
            });
        }

        if ($gvt = $request->input('gouvernorat')) {
            $query->where('gouvernorat', $gvt);
        }

        if ($statutAlerte = $request->input('statut_alerte')) {
            if ($statutAlerte === 'avec_alertes') {
                $query->having('alertes_actives_count', '>', 0);
            } elseif ($statutAlerte === 'calme') {
                $query->having('alertes_actives_count', '=', 0);
            }
        }

        $zones = $query->orderBy('gouvernorat')->paginate(9)->appends($request->query());

        $gouvernorats = Zone::where('actif', true)
            ->whereNotNull('gouvernorat')
            ->distinct()
            ->orderBy('gouvernorat')
            ->pluck('gouvernorat');

        return view('front.zones.index', compact('zones', 'gouvernorats'));
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
     * Liste des alertes (front) avec recherche et filtres.
     */
    public function alertes(Request $request)
    {
        $query = AlerteMeteo::with('zone');

        // Filtre Statut : 'active' par défaut, ou selon sélection
        $selectedStatut = $request->input('statut', 'active');
        if ($selectedStatut !== 'tous' && $selectedStatut !== '') {
            $query->where('statut', $selectedStatut);
        }

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('zone', function($zq) use ($search) {
                      $zq->where('nom', 'like', "%{$search}%")
                         ->orWhere('gouvernorat', 'like', "%{$search}%");
                  });
            });
        }

        if ($niveau = $request->input('niveau')) {
            $query->where('niveau', $niveau);
        }

        if ($zoneId = $request->input('zone_id')) {
            $query->where('zone_id', $zoneId);
        }

        $alertes = $query->orderByRaw("FIELD(niveau,'rouge','orange','jaune','vert')")
            ->orderByDesc('created_at')
            ->paginate(6) // Paginer à 6 pour toujours avoir une pagination active et visible
            ->appends($request->query());

        $statsNiveaux = [
            'rouge'  => AlerteMeteo::where('statut', 'active')->where('niveau', 'rouge')->count(),
            'orange' => AlerteMeteo::where('statut', 'active')->where('niveau', 'orange')->count(),
            'jaune'  => AlerteMeteo::where('statut', 'active')->where('niveau', 'jaune')->count(),
            'vert'   => AlerteMeteo::where('statut', 'active')->where('niveau', 'vert')->count(),
        ];

        $zonesList = Zone::where('actif', true)->orderBy('nom')->get();

        return view('front.alertes.index', compact('alertes', 'statsNiveaux', 'zonesList'));
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
     * Liste des conseils (front) avec recherche et filtres.
     */
    public function conseils(Request $request)
    {
        $query = Conseil::where('actif', true);

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('contenu', 'like', "%{$search}%");
            });
        }

        if ($categorie = $request->input('categorie')) {
            $query->where('categorie', $categorie);
        }

        if ($niveauCible = $request->input('niveau_cible')) {
            $query->where('niveau_alerte_cible', $niveauCible);
        }

        $conseils = $query->orderBy('categorie')
            ->paginate(6) // Paginer à 6 pour assurer une pagination active
            ->appends($request->query());

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
