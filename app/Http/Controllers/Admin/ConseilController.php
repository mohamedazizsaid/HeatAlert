<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlerteMeteo;
use App\Models\Conseil;
use App\Services\ConseilService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ConseilController extends Controller
{
    private static array $CATEGORIES   = ['hydratation', 'energie', 'equipements', 'sante', 'habitat', 'deplacement'];
    private static array $PUBLICS      = ['tous', 'enfants', 'personnes_agees', 'malades_chroniques', 'sportifs'];

    public function __construct(private ConseilService $conseilService) {}

    /**
     * Liste des conseils.
     */
    public function index(Request $request): View
    {
        $query = Conseil::query();

        // Recherche texte
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%$search%")
                  ->orWhere('contenu', 'like', "%$search%");
            });
        }

        // Filtres
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->get('categorie'));
        }
        if ($request->filled('niveau_alerte_cible')) {
            $query->where('niveau_alerte_cible', $request->get('niveau_alerte_cible'));
        }
        if ($request->filled('actif')) {
            $query->where('actif', $request->get('actif'));
        }

        $conseils = $query->orderBy('categorie')->orderBy('titre')->paginate(12)->withQueryString();
        return view('admin.conseils.index', compact('conseils'));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        $categories      = self::$CATEGORIES;
        $niveaux         = AlerteMeteo::NIVEAUX;
        $publics         = self::$PUBLICS;
        $alertes         = AlerteMeteo::with('zone')->orderByDesc('date_debut')->get();
        $selectedAlertes = old('alerte_ids', []);

        return view('admin.conseils.create', compact('categories', 'niveaux', 'publics', 'alertes', 'selectedAlertes'));
    }

    /**
     * Enregistre un nouveau conseil.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateConseil($request);
        $validated['actif'] = $request->boolean('actif');
        $this->conseilService->create($validated);

        return redirect()->route('admin.conseils.index')
            ->with('success', 'Conseil créé avec succès.');
    }

    /**
     * Fiche détaillée d'un conseil.
     */
    public function show(Conseil $conseil): View
    {
        $conseil->load('alertes.zone');
        return view('admin.conseils.show', compact('conseil'));
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(Conseil $conseil): View
    {
        $categories      = self::$CATEGORIES;
        $niveaux         = AlerteMeteo::NIVEAUX;
        $publics         = self::$PUBLICS;
        $alertes         = AlerteMeteo::with('zone')->orderByDesc('date_debut')->get();
        $selectedAlertes = old('alerte_ids', $conseil->alertes->pluck('id')->toArray());

        return view('admin.conseils.edit', compact('conseil', 'categories', 'niveaux', 'publics', 'alertes', 'selectedAlertes'));
    }

    /**
     * Mise à jour d'un conseil.
     */
    public function update(Request $request, Conseil $conseil): RedirectResponse
    {
        $validated = $this->validateConseil($request);
        $validated['actif'] = $request->boolean('actif');
        $this->conseilService->update($conseil, $validated);

        return redirect()->route('admin.conseils.index')
            ->with('success', 'Conseil mis à jour avec succès.');
    }

    /**
     * Suppression d'un conseil.
     */
    public function destroy(Conseil $conseil): RedirectResponse
    {
        $titre = $conseil->titre;
        $this->conseilService->delete($conseil);

        return redirect()->route('admin.conseils.index')
            ->with('deleted', "Le conseil « {$titre} » a été supprimé.");
    }

    /**
     * Règles de validation communes.
     */
    private function validateConseil(Request $request): array
    {
        return $request->validate([
            'titre'               => ['required', 'string', 'max:150'],
            'contenu'             => ['required', 'string', 'min:10'],
            'categorie'           => ['required', Rule::in(self::$CATEGORIES)],
            'niveau_alerte_cible' => ['nullable', Rule::in(AlerteMeteo::NIVEAUX)],
            'icone'               => ['nullable', 'string', 'max:60'],
            'actif'               => ['nullable', 'boolean'],
            'alerte_ids'          => ['nullable', 'array'],
            'alerte_ids.*'        => ['exists:alertes_meteo,id'],
        ]);
    }
}
