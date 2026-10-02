<?php

namespace App\Http\Controllers;

use App\Models\AlerteMeteo;
use App\Models\Zone;

class HomeController extends Controller
{
    /**
     * Page d'accueil publique avec splash screen.
     */
    public function index()
    {
        // Statistiques pour la page d'accueil
        $stats = [
            'zones'   => Zone::where('actif', true)->count(),
            'alertes' => AlerteMeteo::where('statut', 'active')->count(),
            'rouge'   => AlerteMeteo::where('statut', 'active')->where('niveau', 'rouge')->count(),
        ];

        $alertesActives = AlerteMeteo::with('zone')
            ->where('statut', 'active')
            ->orderByDesc('date_debut')
            ->limit(3)
            ->get();

        return view('home', compact('stats', 'alertesActives'));
    }
}
