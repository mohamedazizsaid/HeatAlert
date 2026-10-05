<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlerteMeteo;
use App\Models\AvisPoint;
use App\Models\Conseil;
use App\Models\Coupure;
use App\Models\PointFraicheur;
use App\Models\SignalementCoupure;
use App\Models\Zone;

class AdminController extends Controller
{
    /**
     * Dashboard de l'administration avec statistiques.
     */
    public function index()
    {
        Coupure::actualiserStatuts();

        $coupuresParStatut = Coupure::query()
            ->select('statut')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('statut')
            ->pluck('total', 'statut');

        $stats = [
            'zones'          => Zone::count(),
            'zones_actives'  => Zone::where('actif', true)->count(),
            'alertes'        => AlerteMeteo::count(),
            'alertes_active' => AlerteMeteo::where('statut', 'active')->count(),
            'alertes_rouge'  => AlerteMeteo::where('niveau', 'rouge')->where('statut', 'active')->count(),
            'conseils'       => Conseil::count(),
            'conseils_actifs' => Conseil::where('actif', true)->count(),
            'coupures_en_cours' => (int) ($coupuresParStatut['en_cours'] ?? 0),
            'coupures_prevues' => (int) ($coupuresParStatut['prevue'] ?? 0),
            'coupures_terminees' => (int) ($coupuresParStatut['terminee'] ?? 0),
            'signalements_en_attente' => SignalementCoupure::where('statut_validation', 'en_attente')->count(),
        ];

        $dernieresAlertes = AlerteMeteo::with('zone')
            ->where('statut', 'active')
            ->orderByDesc('date_debut')
            ->limit(5)
            ->get();

        $derniersSignalements = SignalementCoupure::with(['user', 'coupure.zone'])
            ->where('statut_validation', 'en_attente')
            ->orderByDesc('date_signalement')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'dernieresAlertes', 'derniersSignalements'));
    }

    /**
     * Page Statistiques Avancées — Analyse complète des données HeatAlert.
     */
    public function statistiques()
    {
        Coupure::actualiserStatuts();
        \App\Models\Intervention::actualiserStatuts();

        // ── Totaux Globaux ──
        $totaux = [
            'zones'               => Zone::count(),
            'zones_actives'       => Zone::where('actif', true)->count(),
            'alertes'             => AlerteMeteo::count(),
            'alertes_actives'     => AlerteMeteo::where('statut', 'active')->count(),
            'alertes_rouge'       => AlerteMeteo::where('niveau', 'rouge')->count(),
            'alertes_orange'      => AlerteMeteo::where('niveau', 'orange')->count(),
            'alertes_jaune'       => AlerteMeteo::where('niveau', 'jaune')->count(),
            'alertes_vert'        => AlerteMeteo::where('niveau', 'vert')->count(),
            'conseils'            => Conseil::count(),
            'conseils_actifs'     => Conseil::where('actif', true)->count(),
            'points_fraicheur'    => PointFraicheur::count(),
            'points_actifs'       => PointFraicheur::where('actif', true)->count(),
            'points_pmr'          => PointFraicheur::where('accessible_pmr', true)->count(),
            'total_avis'          => AvisPoint::count(),
            'note_moyenne_points' => round((float) (AvisPoint::avg('note') ?? 0), 1),
        ];

        // ── Répartition des alertes par niveau ──
        $alertesParNiveau = [
            'rouge'  => AlerteMeteo::where('niveau', 'rouge')->count(),
            'orange' => AlerteMeteo::where('niveau', 'orange')->count(),
            'jaune'  => AlerteMeteo::where('niveau', 'jaune')->count(),
            'vert'   => AlerteMeteo::where('niveau', 'vert')->count(),
        ];

        // ── Répartition des alertes par statut ──
        $alertesParStatut = [
            'active'   => AlerteMeteo::where('statut', 'active')->count(),
            'brouillon'=> AlerteMeteo::where('statut', 'brouillon')->count(),
            'terminee' => AlerteMeteo::where('statut', 'terminee')->count(),
            'annulee'  => AlerteMeteo::where('statut', 'annulee')->count(),
        ];

        // ── Répartition des alertes par type ──
        $alertesParType = AlerteMeteo::selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->orderByDesc('total')
            ->get()
            ->mapWithKeys(fn($r) => [$r->type => $r->total]);

        // ── Top 10 Zones avec le plus d'alertes ──
        $topZones = Zone::withCount('alertes')
            ->orderByDesc('alertes_count')
            ->take(10)
            ->get();

        // ── Alertes par mois (12 derniers mois) ──
        $debut12Mois = now()->subMonths(11)->startOfMonth();
        $rawAlertes = AlerteMeteo::selectRaw('DATE_FORMAT(COALESCE(date_debut, created_at), "%Y-%m") as mois, count(*) as total')
            ->where(function ($q) use ($debut12Mois) {
                $q->where('date_debut', '>=', $debut12Mois)
                  ->orWhere(function ($sub) use ($debut12Mois) {
                      $sub->whereNull('date_debut')->where('created_at', '>=', $debut12Mois);
                  });
            })
            ->groupBy('mois')
            ->pluck('total', 'mois');

        $alertesParMois = collect();
        for ($i = 11; $i >= 0; $i--) {
            $moisCle = now()->subMonths($i)->format('Y-m');
            $alertesParMois->put($moisCle, (int) ($rawAlertes[$moisCle] ?? 0));
        }

        // ── Températures moyennes des alertes ──
        $tempStats = AlerteMeteo::whereNotNull('temperature_max')->selectRaw('
            ROUND(AVG(temperature_max), 1) as temp_max_moy,
            ROUND(MAX(temperature_max), 1) as temp_max_record,
            ROUND(MIN(temperature_max), 1) as temp_max_min,
            ROUND(AVG(temperature_ressentie), 1) as ressentie_moy,
            ROUND(AVG(humidite), 1) as humidite_moy,
            ROUND(AVG(indice_uv), 1) as uv_moy,
            ROUND(MAX(indice_uv), 1) as uv_max
        ')->first();

        // ── Répartition des conseils par catégorie ──
        $conseilsParCategorie = Conseil::selectRaw('categorie, count(*) as total')
            ->whereNotNull('categorie')
            ->groupBy('categorie')
            ->orderByDesc('total')
            ->get()
            ->mapWithKeys(fn($r) => [$r->categorie => $r->total]);

        // ── Dernières alertes récentes ──
        $dernieresAlertes = AlerteMeteo::with('zone')
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        // ── Alertes Rouge actives (criticité maximale) ──
        $alertesCritiques = AlerteMeteo::with('zone')
            ->where('statut', 'active')
            ->whereIn('niveau', ['rouge', 'orange'])
            ->orderByRaw("FIELD(niveau, 'rouge', 'orange')")
            ->take(5)
            ->get();

        $coupuresParType = Coupure::query()
            ->select('type')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('type')
            ->orderByDesc('total')
            ->pluck('total', 'type');

        $debut12MoisCoupures = now()->subMonths(11)->startOfMonth();
        $coupuresBrutesParMois = Coupure::query()
            ->selectRaw('DATE_FORMAT(date_debut, "%Y-%m") as mois, COUNT(*) as total')
            ->where('date_debut', '>=', $debut12MoisCoupures)
            ->groupBy('mois')
            ->pluck('total', 'mois');

        $coupuresParMois = collect();
        for ($i = 11; $i >= 0; $i--) {
            $mois = now()->subMonths($i)->format('Y-m');
            $coupuresParMois->put($mois, (int) ($coupuresBrutesParMois[$mois] ?? 0));
        }

        $coupuresParZone = Zone::withCount('coupures')
            ->orderByDesc('coupures_count')
            ->take(10)
            ->get();

        $dureeMoyenneCoupures = (float) (Coupure::query()
            ->whereNotNull('date_debut')
            ->whereNotNull('date_fin')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, date_debut, date_fin)) as moyenne')
            ->value('moyenne') ?? 0);

        $totalSignalements = SignalementCoupure::count();
        $signalementsValides = SignalementCoupure::where('statut_validation', 'valide')->count();
        $tauxSignalementsValides = $totalSignalements > 0
            ? round(($signalementsValides / $totalSignalements) * 100, 1)
            : 0;

        // ── Statistiques Points de Fraîcheur ──
        $pointsParType = PointFraicheur::selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->orderByDesc('total')
            ->pluck('total', 'type');

        $pointsParZone = Zone::withCount('pointsFraicheur')
            ->having('points_fraicheur_count', '>', 0)
            ->orderByDesc('points_fraicheur_count')
            ->take(8)
            ->get();

        $avisParNote = [
            5 => AvisPoint::where('note', 5)->count(),
            4 => AvisPoint::where('note', 4)->count(),
            3 => AvisPoint::where('note', 3)->count(),
            2 => AvisPoint::where('note', 2)->count(),
            1 => AvisPoint::where('note', 1)->count(),
        ];

        $topPointsMieuxNotes = PointFraicheur::with('zone')
            ->withCount('avisPoints')
            ->withAvg('avisPoints', 'note')
            ->having('avis_points_count', '>', 0)
            ->orderByDesc('avis_points_avg_note')
            ->take(5)
            ->get();

        return view('admin.statistiques', compact(
            'totaux', 'alertesParNiveau', 'alertesParStatut', 'alertesParType',
            'topZones', 'alertesParMois', 'tempStats', 'conseilsParCategorie',
            'dernieresAlertes', 'alertesCritiques', 'coupuresParType',
            'coupuresParMois', 'coupuresParZone', 'dureeMoyenneCoupures',
            'totalSignalements', 'signalementsValides', 'tauxSignalementsValides',
            'pointsParType', 'pointsParZone', 'avisParNote', 'topPointsMieuxNotes'
        ));
    }

    /**
     * Profil & Sécurité de l'administrateur
     */
    public function profile()
    {
        $user = auth()->user()->load('zone');
        $zones = Zone::where('actif', true)->orderBy('gouvernorat')->orderBy('ville')->get();

        $adminStats = [
            'total_alertes'  => AlerteMeteo::count(),
            'total_zones'    => Zone::count(),
            'total_conseils' => Conseil::count(),
        ];

        return view('admin.profile', compact('user', 'zones', 'adminStats'));
    }

    /**
     * Mise à jour des informations de profil de l'administrateur
     */
    public function updateProfile(\App\Http\Requests\ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('admin.profile.edit')->with('status', 'profile-updated');
    }
}
