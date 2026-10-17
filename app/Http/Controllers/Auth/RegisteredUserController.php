<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationCodeMail;
use App\Models\Zone;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
     * Traite l'inscription :
     * 1. Crée le compte
     * 2. Génère un code de vérification email et l'envoie
     * 3. Connecte l'utilisateur (sans email_verified_at)
     * 4. Redirige vers la page de vérification du code
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

        // Générer et envoyer le code de vérification email
        $user->generateEmailVerificationCode();
        Mail::to($user->email)->send(new EmailVerificationCodeMail($user));

        // Connecter l'utilisateur (email non encore vérifié)
        Auth::login($user);

        return redirect()->route('verification.code.show');
    }
}
