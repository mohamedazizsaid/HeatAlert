<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFrontEquipementSensibleRequest;
use App\Http\Requests\UpdateFrontEquipementSensibleRequest;
use App\Models\EquipementSensible;
use App\Services\EquipementSensibleService;
use App\Services\Module4NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FrontEquipementSensibleController extends Controller
{
    public function __construct(
        private EquipementSensibleService $service,
        private Module4NotificationService $notificationService,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'niveau_sensibilite', 'actif']);
        $equipements = $this->service->getForUserPaginated($request->user(), $filters);
        return view('front.equipements_sensibles.index', [
            'equipements' => $equipements,
            'niveaux' => EquipementSensible::NIVEAUX,
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('front.equipements_sensibles.create', ['niveaux' => EquipementSensible::NIVEAUX]);
    }

    public function store(StoreFrontEquipementSensibleRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['actif'] = $request->boolean('actif');
        $this->service->create($data);
        $this->notificationService->generateForUser($request->user());
        return redirect()->route('front.equipements_sensibles.index')->with('success', 'Équipement ajouté à votre préparation.');
    }

    public function edit(Request $request, EquipementSensible $equipementSensible): View
    {
        abort_unless($equipementSensible->user_id === $request->user()->id, 403);
        return view('front.equipements_sensibles.edit', [
            'equipement' => $equipementSensible,
            'niveaux' => EquipementSensible::NIVEAUX,
        ]);
    }

    public function update(UpdateFrontEquipementSensibleRequest $request, EquipementSensible $equipementSensible): RedirectResponse
    {
        abort_unless($equipementSensible->user_id === $request->user()->id, 403);
        $data = $request->validated();
        $data['actif'] = $request->boolean('actif');
        $this->service->update($equipementSensible, $data);
        $this->notificationService->generateForUser($request->user());
        return redirect()->route('front.equipements_sensibles.index')->with('success', 'Équipement mis à jour.');
    }

    public function destroy(Request $request, EquipementSensible $equipementSensible): RedirectResponse
    {
        abort_unless($equipementSensible->user_id === $request->user()->id, 403);
        $this->service->delete($equipementSensible);
        return back()->with('deleted', 'Équipement supprimé de votre préparation.');
    }
}
