<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationCodeMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

/**
 * Gère la vérification email par code à 6 chiffres
 * envoyé lors de l'inscription.
 */
class EmailVerificationCodeController extends Controller
{
    /**
     * Affiche la page de saisie du code.
     */
    public function show(Request $request): View|RedirectResponse
    {
        // L'utilisateur est connecté mais son email n'est pas encore vérifié
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if ($request->user()->email_verified_at) {
            return redirect()->intended(route('front.home'));
        }

        return view('auth.verify-email-code');
    }

    /**
     * Vérifie le code saisi par l'utilisateur.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ], [
            'code.required' => 'Veuillez saisir le code de vérification.',
            'code.size'     => 'Le code doit contenir exactement 6 chiffres.',
        ]);

        /** @var User $user */
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->isEmailVerificationCodeValid($request->code)) {
            return back()->withErrors([
                'code' => 'Code invalide ou expiré. Veuillez en demander un nouveau.',
            ]);
        }

        // Valider l'email
        $user->update([
            'email_verified_at'             => now(),
            'email_verification_code'        => null,
            'email_verification_expires_at'  => null,
        ]);

        return redirect()->route('front.home')->with('status', 'email-verified');
    }

    /**
     * Renvoie un nouveau code par email.
     */
    public function resend(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->email_verified_at) {
            return redirect()->intended(route('front.home'));
        }

        $user->generateEmailVerificationCode();
        Mail::to($user->email)->send(new EmailVerificationCodeMail($user));

        return back()->with('status', 'code-resent');
    }
}
