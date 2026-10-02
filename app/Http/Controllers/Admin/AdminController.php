<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlerteMeteo;
use App\Models\Conseil;
use App\Models\Zone;

class AdminController extends Controller
{
    /**
     * Dashboard de l'administration avec statistiques.
     */
    public function index()
    {
        $stats = [
            'zones'          => Zone::count(),
            'zones_actives'  => Zone::where('actif', true)->count(),
            'alertes'        => AlerteMeteo::count(),
            'alertes_active' => AlerteMeteo::where('statut', 'active')->count(),
            'alertes_rouge'  => AlerteMeteo::where('niveau', 'rouge')->where('statut', 'active')->count(),
            'conseils'       => Conseil::count(),
            'conseils_actifs' => Conseil::where('actif', true)->count(),
        ];

        $dernieresAlertes = AlerteMeteo::with('zone')
            ->where('statut', 'active')
            ->orderByDesc('date_debut')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'dernieresAlertes'));
    }
}
