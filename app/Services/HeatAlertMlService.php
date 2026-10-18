<?php

namespace App\Services;

use App\Models\AlerteMeteo;
use App\Models\Coupure;
use App\Models\SignalementCoupure;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use RuntimeException;

class HeatAlertMlService
{
    public function predictForZone(Zone $zone, ?Carbon $referenceAt = null): array
    {
        if ($zone->latitude === null || $zone->longitude === null) {
            throw new RuntimeException('La zone doit avoir une latitude et une longitude pour être prédite.');
        }

        $referenceAt ??= now();
        $payload = $this->buildPayload($zone, $referenceAt);
        $url = rtrim((string) config('services.heatalert_ml.url'), '/') . '/predict';
        $timeout = (int) config('services.heatalert_ml.timeout', 5);
        $request = Http::acceptJson()->timeout($timeout);

        if ($token = config('services.heatalert_ml.token')) {
            $request = $request->withToken($token);
        }

        try {
            $response = $request->post($url, $payload);
        } catch (ConnectionException $exception) {
            Log::warning('HeatAlert ML service unavailable', ['zone_id' => $zone->id]);
            throw new RuntimeException('Le service de prédiction est indisponible.', 0, $exception);
        }

        if ($response->failed()) {
            Log::warning('HeatAlert ML service returned an HTTP error', [
                'zone_id' => $zone->id,
                'status' => $response->status(),
            ]);
            throw new RuntimeException('Le service de prédiction a retourné une erreur.');
        }

        $result = $response->json();
        $validated = Validator::make($result, [
            'probability' => ['required', 'numeric', 'between:0,1'],
            'risk_level' => ['nullable', 'string'],
            'risk_thresholds_defined' => ['required', 'boolean'],
            'target_window_hours' => ['required', 'integer', 'in:24'],
            'target_window_start' => ['required', 'date'],
            'target_window_end_exclusive' => ['required', 'date', 'after:target_window_start'],
            'model_version' => ['required', 'string', 'max:255'],
            'warnings' => ['present', 'array'],
        ])->validate();

        return [
            'response' => $validated,
            'input_snapshot' => $payload,
        ];
    }

    private function buildPayload(Zone $zone, Carbon $referenceAt): array
    {
        $alert = AlerteMeteo::query()
            ->where('zone_id', $zone->id)
            ->where('date_debut', '<=', $referenceAt)
            ->where(function ($query) use ($referenceAt) {
                $query->whereNull('date_fin')->orWhere('date_fin', '>', $referenceAt);
            })
            ->latest('date_debut')
            ->first();

        $history = Coupure::query()
            ->where('zone_id', $zone->id)
            ->where('date_debut', '<', $referenceAt);
        $lastCoupureAt = (clone $history)->max('date_debut');

        return [
            'zone_id' => $zone->id,
            'ville' => $zone->ville,
            'gouvernorat' => $zone->gouvernorat ?? '',
            'latitude' => $zone->latitude !== null ? (float) $zone->latitude : null,
            'longitude' => $zone->longitude !== null ? (float) $zone->longitude : null,
            'zone_active' => (bool) $zone->actif,
            'reference_at' => $referenceAt->toIso8601String(),
            'temperature_min' => $alert?->temperature_min,
            'temperature_max' => $alert?->temperature_max,
            'temperature_ressentie' => $alert?->temperature_ressentie,
            'humidite' => $alert?->humidite,
            'indice_uv' => $alert?->indice_uv,
            'vitesse_vent' => $alert?->vitesse_vent,
            'alerte_type' => $alert?->type,
            'alerte_niveau' => $alert?->niveau,
            'alerte_active' => $alert !== null,
            'nb_coupures_24h' => (clone $history)->where('date_debut', '>=', $referenceAt->copy()->subDay())->count(),
            'nb_coupures_7j' => (clone $history)->where('date_debut', '>=', $referenceAt->copy()->subDays(7))->count(),
            'nb_coupures_30j' => (clone $history)->where('date_debut', '>=', $referenceAt->copy()->subDays(30))->count(),
            'jours_depuis_coupure' => $lastCoupureAt ? $referenceAt->diffInSeconds(Carbon::parse($lastCoupureAt)) / 86400 : null,
            'nb_signalements_valides_7j' => SignalementCoupure::query()
                ->where('statut_validation', 'valide')
                ->where('date_signalement', '<', $referenceAt)
                ->where('date_signalement', '>=', $referenceAt->copy()->subDays(7))
                ->whereHas('coupure', fn ($query) => $query->where('zone_id', $zone->id))
                ->count(),
        ];
    }
}
