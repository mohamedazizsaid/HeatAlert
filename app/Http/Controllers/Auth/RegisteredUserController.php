<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use App\Services\AuthService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(private AuthService $authService) {}

    /**
     * Affiche le formulaire d'inscription.
     */
    public function create(): View
    {
        $zones = Zone::where('actif', true)->orderBy('gouvernorat')->orderBy('ville')->get();
        return view('auth.register', compact('zones'));
    }

    /**
     * Traite l'inscription d'un nouvel utilisateur.
     * Le rôle est automatiquement ROLE_USER.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nom'       => ['required', 'string', 'max:60'],
            'prenom'    => ['required', 'string', 'max:60'],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'telephone' => ['nullable', 'string', 'regex:/^[+\d][\d\s\-]{7,19}$/'],
            'zone_id'   => ['nullable', 'exists:zones,id'],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = $this->authService->createUser($request->all());

        event(new Registered($user));
        Auth::login($user);

        return redirect($this->authService->getRedirectRouteForUser($user));
    }
}
