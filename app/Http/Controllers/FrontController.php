<?php

namespace App\Http\Controllers;

use App\Models\AlerteMeteo;
use App\Models\Conseil;
use App\Models\Coupure;
use App\Models\SignalementCoupure;
use App\Models\Zone;
use App\Http\Requests\StoreSignalementCoupureRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

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

    /**
     * Liste les coupures visibles par l'utilisateur ou les coupures en cours pour un visiteur.
     */
    public function coupures(Request $request): View
    {
        Coupure::actualiserStatuts();

        $query = Coupure::with(['zone', 'interventions']);

        if (auth()->check()) {
            $query->where('zone_id', auth()->user()->zone_id);

            if ($request->filled('statut')) {
                $query->where('statut', $request->input('statut'));
            }
        } else {
            $query->where('statut', 'en_cours');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->input('tri', 'plus_proche') === 'plus_lointaine') {
            $query->orderByDesc('date_debut');
        } else {
            $query->orderBy('date_debut');
        }

        $coupures = $query->paginate(8)->withQueryString();

        $calendrierQuery = Coupure::with(['zone', 'interventions'])
            ->where('statut', 'prevue')
            ->whereBetween('date_debut', [now()->startOfDay(), now()->addDays(7)->endOfDay()])
            ->orderBy('date_debut');

        $calendrierEvents = $calendrierQuery->get()->map(function (Coupure $coupure): array {
            $couleur = match ($coupure->type) {
                'panne' => '#dc2626',
                'surcharge' => '#ea580c',
                default => '#d97706',
            };

            return [
                'id' => $coupure->id,
                'title' => 'Coupure ' . ucfirst($coupure->type) . ' — ' . ($coupure->zone?->nom ?? 'Zone inconnue'),
                'start' => $coupure->date_debut?->toIso8601String(),
                'end' => $coupure->retablissement_estime?->toIso8601String(),
                'extendedProps' => [
                    'zone' => $coupure->zone?->nom . ' (' . ($coupure->zone?->ville ?? '—') . ')',
                ],
                'url' => route('front.coupures.show', $coupure),
                'backgroundColor' => $couleur,
                'borderColor' => $couleur,
            ];
        })->values();

        return view('front.coupures.index', [
            'coupures' => $coupures,
            'types' => Coupure::TYPES,
            'statuts' => Coupure::STATUTS,
            'calendrierEvents' => $calendrierEvents,
        ]);
    }

    /**
     * Affiche une coupure accessible par l'utilisateur courant.
     */
    public function coupureShow(Coupure $coupure): View
    {
        Coupure::actualiserStatuts();

        $query = Coupure::query()
            ->whereKey($coupure->id)
            ->with(['zone', 'interventions'])
            ->withCount(['signalements as signalements_valides_count' => function ($query) {
                $query->where('statut_validation', 'valide');
            }]);

        if (! auth()->check()) {
            $query->where('statut', 'en_cours');
        } else {
            $query->whereIn('statut', ['prevue', 'en_cours']);
        }

        $coupure = $query->firstOrFail();

        return view('front.coupures.show', compact('coupure'));
    }

    /**
     * Affiche le formulaire de signalement pour la zone de l'utilisateur.
     */
    public function createSignalement(): View
    {
        Coupure::actualiserStatuts();

        $coupures = Coupure::where('zone_id', auth()->user()->zone_id)
            ->where('statut', 'en_cours')
            ->orderByDesc('date_debut')
            ->get();

        return view('front.coupures.signaler', compact('coupures'));
    }

    /**
     * Enregistre un signalement créé par un habitant.
     */
    public function storeSignalement(StoreSignalementCoupureRequest $request): RedirectResponse
    {
        Coupure::actualiserStatuts();

        $data = $request->validated();
        $user = $request->user();

        if (isset($data['coupure_id'])) {
            $coupure = Coupure::findOrFail($data['coupure_id']);

            abort_if($coupure->zone_id !== $user->zone_id, 403, 'Cette coupure ne concerne pas votre zone.');
            abort_if($coupure->statut !== 'en_cours', 422, 'Seule une coupure en cours peut être signalée.');

            $signalementsRecents = SignalementCoupure::query()
                ->where('coupure_id', $coupure->id)
                ->where('created_at', '>=', now()->subMinutes(30));

            if ((clone $signalementsRecents)->where('user_id', $user->id)->exists()) {
                throw ValidationException::withMessages([
                    'description' => 'Vous avez déjà signalé cette coupure au cours des 30 dernières minutes.',
                ]);
            }

            if ($user->zone_id && (clone $signalementsRecents)->whereHas('user', function ($userQuery) use ($user) {
                $userQuery->where('zone_id', $user->zone_id);
            })->exists()) {
                throw ValidationException::withMessages([
                    'description' => 'Un habitant de votre zone a déjà signalé cette coupure au cours des 30 dernières minutes.',
                ]);
            }
        }

        // ── Gestion photo : priorité au widget Cloudinary, fallback upload serveur ──
        if ($request->filled('photo_cloudinary_url') && $request->filled('photo_cloudinary_id')) {
            // Photo déjà uploadée via le Cloudinary Widget côté client
            // On stocke le public_id pour pouvoir la supprimer/transformer plus tard
            $data['photo'] = $request->input('photo_cloudinary_id');
        } elseif ($request->hasFile('photo')) {
            // Fallback : upload serveur via CloudinaryService
            try {
                $cloudinary = app(\App\Services\CloudinaryService::class);
                $result     = $cloudinary->upload($request->file('photo'), 'signalements');
                $data['photo'] = $result['public_id'];
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Cloudinary upload failed', ['error' => $e->getMessage()]);
                return back()->withInput()->withErrors(['photo' => "L'upload de la photo a échoué. Veuillez réessayer."]);
            }
        }
        // Ne pas passer ces champs au modèle
        unset($data['photo_cloudinary_url'], $data['photo_cloudinary_id']);

        $data['user_id'] = $user->id;
        $data['statut_validation'] = 'en_attente';

        SignalementCoupure::create($data);

        return redirect()->route('front.coupures.index')
            ->with('success', 'Votre signalement a été envoyé et sera vérifié par un administrateur.');
    }
}
