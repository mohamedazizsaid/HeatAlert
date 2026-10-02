<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Models\Zone;
use App\Models\AlerteMeteo;

class ProfileController extends Controller
{
    /**
     * Display the user's profile view and edit forms.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load('zone');
        $zones = Zone::where('actif', true)->orderBy('gouvernorat')->orderBy('ville')->get();

        $alertesZone = collect();
        if ($user->zone_id) {
            $alertesZone = AlerteMeteo::where('zone_id', $user->zone_id)
                ->where('statut', 'active')
                ->orderByDesc('date_debut')
                ->take(3)
                ->get();
        }

        return view('profile.edit', compact('user', 'zones', 'alertesZone'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
