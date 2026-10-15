<?php

namespace App\Services;

use App\Models\AlerteMeteo;
use App\Models\Conseil;
use App\Models\Coupure;
use App\Models\PointFraicheur;
use App\Models\SignalementCoupure;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiChatbotService
{
    protected string $apiKey;
    protected string $model;
    protected NasaFirmsService $firmsService;

    public function __construct(NasaFirmsService $firmsService)
    {
        $this->apiKey = (string) config('services.gemini.api_key', '');
        $this->model  = (string) config('services.gemini.model', 'gemini-1.5-flash');
        $this->firmsService = $firmsService;
    }

    /**
     * Traite un message utilisateur avec l'historique et le contexte dynamique de la plateforme.
     *
     * @param string $message
     * @param array $history
     * @param User|null $user
     * @return array{reply: string, chart: ?array, suggestions: array}
     */
    public function chat(string $message, array $history = [], ?User $user = null): array
    {
        // 1. Détection de zone ou ville spécifique demandée (ex: Hammamet, Tunis, Sousse, Paris...)
        $targetLocation = $this->detectTargetLocation($message);

        // 2. Collecte du knowledge global et du knowledge ciblé
        $knowledge = $this->gatherPlatformKnowledge($user, $message, $targetLocation);

        // Si la clé API Gemini n'est pas renseignée, basculer sur le moteur local intelligent
        if (empty(trim($this->apiKey))) {
            return $this->generateLocalResponse($message, $knowledge, $user, $targetLocation);
        }

        try {
            return $this->callGeminiApi($message, $history, $knowledge, $user, $targetLocation);
        } catch (\Throwable $e) {
            Log::warning('Gemini API call failed, falling back to local engine', [
                'error' => $e->getMessage(),
            ]);
            return $this->generateLocalResponse($message, $knowledge, $user, $targetLocation);
        }
    }

    /**
     * Détecte si l'utilisateur interroge une zone, ville ou région spécifique.
     *
     * @param string $message
     * @return array|null
     */
    public function detectTargetLocation(string $message): ?array
    {
        $cleanMessage = mb_strtolower($message);

        // Liste des villes et gouvernorats tunisiens connus
        $tunisianLocations = [
            'hammamet' => ['name' => 'Hammamet', 'admin1' => 'Nabeul', 'lat' => 36.4000, 'lng' => 10.6167],
            'nabeul' => ['name' => 'Nabeul', 'admin1' => 'Nabeul', 'lat' => 36.4561, 'lng' => 10.7376],
            'tunis' => ['name' => 'Tunis', 'admin1' => 'Tunis', 'lat' => 36.8065, 'lng' => 10.1815],
            'ariana' => ['name' => 'Ariana', 'admin1' => 'Ariana', 'lat' => 36.8663, 'lng' => 10.1956],
            'ben arous' => ['name' => 'Ben Arous', 'admin1' => 'Ben Arous', 'lat' => 36.7533, 'lng' => 10.2222],
            'manouba' => ['name' => 'Manouba', 'admin1' => 'Manouba', 'lat' => 36.8083, 'lng' => 10.0972],
            'bizerte' => ['name' => 'Bizerte', 'admin1' => 'Bizerte', 'lat' => 37.2744, 'lng' => 9.8739],
            'sousse' => ['name' => 'Sousse', 'admin1' => 'Sousse', 'lat' => 35.8256, 'lng' => 10.6084],
            'monastir' => ['name' => 'Monastir', 'admin1' => 'Monastir', 'lat' => 35.7780, 'lng' => 10.8262],
            'mahdia' => ['name' => 'Mahdia', 'admin1' => 'Mahdia', 'lat' => 35.5047, 'lng' => 11.0622],
            'sfax' => ['name' => 'Sfax', 'admin1' => 'Sfax', 'lat' => 34.7406, 'lng' => 10.7603],
            'kairouan' => ['name' => 'Kairouan', 'admin1' => 'Kairouan', 'lat' => 35.6781, 'lng' => 10.0963],
            'kasserine' => ['name' => 'Kasserine', 'admin1' => 'Kasserine', 'lat' => 35.1676, 'lng' => 8.8365],
            'sidi bouzid' => ['name' => 'Sidi Bouzid', 'admin1' => 'Sidi Bouzid', 'lat' => 35.0382, 'lng' => 9.4849],
            'jendouba' => ['name' => 'Jendouba', 'admin1' => 'Jendouba', 'lat' => 36.5011, 'lng' => 8.7802],
            'beja' => ['name' => 'Béja', 'admin1' => 'Béja', 'lat' => 36.7256, 'lng' => 9.1817],
            'béja' => ['name' => 'Béja', 'admin1' => 'Béja', 'lat' => 36.7256, 'lng' => 9.1817],
            'le kef' => ['name' => 'Le Kef', 'admin1' => 'Le Kef', 'lat' => 36.1742, 'lng' => 8.7049],
            'kef' => ['name' => 'Le Kef', 'admin1' => 'Le Kef', 'lat' => 36.1742, 'lng' => 8.7049],
            'siliana' => ['name' => 'Siliana', 'admin1' => 'Siliana', 'lat' => 36.0847, 'lng' => 9.3708],
            'zaghouan' => ['name' => 'Zaghouan', 'admin1' => 'Zaghouan', 'lat' => 36.4029, 'lng' => 10.1429],
            'gabes' => ['name' => 'Gabès', 'admin1' => 'Gabès', 'lat' => 33.8815, 'lng' => 10.0982],
            'gabès' => ['name' => 'Gabès', 'admin1' => 'Gabès', 'lat' => 33.8815, 'lng' => 10.0982],
            'medenine' => ['name' => 'Médenine', 'admin1' => 'Médenine', 'lat' => 33.3549, 'lng' => 10.5055],
            'médenine' => ['name' => 'Médenine', 'admin1' => 'Médenine', 'lat' => 33.3549, 'lng' => 10.5055],
            'djerba' => ['name' => 'Djerba', 'admin1' => 'Médenine', 'lat' => 33.8076, 'lng' => 10.8451],
            'tataouine' => ['name' => 'Tataouine', 'admin1' => 'Tataouine', 'lat' => 32.9297, 'lng' => 10.4518],
            'gafsa' => ['name' => 'Gafsa', 'admin1' => 'Gafsa', 'lat' => 34.4250, 'lng' => 8.7842],
            'tozeur' => ['name' => 'Tozeur', 'admin1' => 'Tozeur', 'lat' => 33.9197, 'lng' => 8.1335],
            'kebili' => ['name' => 'Kébili', 'admin1' => 'Kébili', 'lat' => 33.7044, 'lng' => 8.9690],
            'kébili' => ['name' => 'Kébili', 'admin1' => 'Kébili', 'lat' => 33.7044, 'lng' => 8.9690],
            'kelibia' => ['name' => 'Kélibia', 'admin1' => 'Nabeul', 'lat' => 36.8483, 'lng' => 11.0939],
            'kélibia' => ['name' => 'Kélibia', 'admin1' => 'Nabeul', 'lat' => 36.8483, 'lng' => 11.0939],
            'tabarka' => ['name' => 'Tabarka', 'admin1' => 'Jendouba', 'lat' => 36.9544, 'lng' => 8.7580],
            'la marsa' => ['name' => 'La Marsa', 'admin1' => 'Tunis', 'lat' => 36.8782, 'lng' => 10.3247],
            'carthage' => ['name' => 'Carthage', 'admin1' => 'Tunis', 'lat' => 36.8528, 'lng' => 10.3233],
        ];

        // 1. Recherche directe dans la liste tunisienne
        foreach ($tunisianLocations as $key => $info) {
            if (preg_match('/\b' . preg_quote($key, '/') . '\b/ui', $cleanMessage)) {
                // Trouver la zone en base correspondante
                $zoneModel = Zone::where('actif', true)
                    ->where(function($q) use ($info) {
                        $q->where('nom', 'like', "%{$info['name']}%")
                          ->orWhere('ville', 'like', "%{$info['name']}%")
                          ->orWhere('gouvernorat', 'like', "%{$info['admin1']}%");
                    })->first();

                return array_merge($info, [
                    'zone_id' => $zoneModel?->id,
                    'zone_nom' => $zoneModel?->nom,
                ]);
            }
        }

        // 2. Recherche parmi les zones enregistrées en base
        $allZones = Zone::where('actif', true)->get();
        foreach ($allZones as $z) {
            $nom = mb_strtolower($z->nom);
            $ville = mb_strtolower($z->ville ?? '');
            $gvt = mb_strtolower($z->gouvernorat ?? '');

            if ((!empty($ville) && str_contains($cleanMessage, $ville)) ||
                (!empty($gvt) && str_contains($cleanMessage, $gvt)) ||
                (!empty($nom) && str_contains($cleanMessage, str_replace('zone ', '', $nom)))) {
                return [
                    'name' => $z->ville ?: $z->nom,
                    'admin1' => $z->gouvernorat ?: $z->nom,
                    'lat' => (float) ($z->latitude ?: 36.8),
                    'lng' => (float) ($z->longitude ?: 10.18),
                    'zone_id' => $z->id,
                    'zone_nom' => $z->nom,
                ];
            }
        }

        // 3. Détection par motif linguistique de recherche météo / ville (ex: "meteo de rome", "meteo a paris")
        if (preg_match('/(?:meteo|météo|point|fraicheur|fraîcheur|temps|chaleur|ville|pays)\s+(?:de|d\'|à|a|dans|pour|sur)\s+([a-zA-ZÀ-ÿ\s\-]{3,25})/ui', $cleanMessage, $matches)) {
            $extractedCity = trim($matches[1]);
            // Exclure les faux positifs
            $excluded = ['demain', 'aujourd', 'cette', 'notre', 'mon', 'ce', 'tous', 'toutes', 'la plateforme', 'tunisie', 'monde'];
            if (!in_array($extractedCity, $excluded)) {
                return $this->geocodeLocation($extractedCity);
            }
        }

        return null;
    }

    /**
     * Géocode une ville avec l'API Open-Meteo Geocoding.
     */
    protected function geocodeLocation(string $cityName): ?array
    {
        return Cache::remember('geocode_' . md5(mb_strtolower($cityName)), 3600, function () use ($cityName) {
            try {
                $response = Http::timeout(4)->get('https://geocoding-api.open-meteo.com/v1/search', [
                    'name' => $cityName,
                    'count' => 1,
                    'language' => 'fr',
                    'format' => 'json',
                ]);

                if ($response->successful()) {
                    $results = $response->json('results');
                    if (!empty($results[0])) {
                        $r = $results[0];
                        return [
                            'name' => $r['name'],
                            'admin1' => $r['admin1'] ?? ($r['country'] ?? ''),
                            'country' => $r['country'] ?? '',
                            'lat' => (float) $r['latitude'],
                            'lng' => (float) $r['longitude'],
                            'zone_id' => null,
                            'zone_nom' => null,
                        ];
                    }
                }
            } catch (\Throwable $e) {
                Log::debug('Geocoding failed for ' . $cityName . ': ' . $e->getMessage());
            }
            return null;
        });
    }

    /**
     * Récupère la météo en direct de haute précision pour des coordonnées géographiques via Open-Meteo.
     */
    public function fetchLiveWeather(float $lat, float $lng, string $cityName = ''): ?array
    {
        $cacheKey = 'live_weather_' . round($lat, 2) . '_' . round($lng, 2);

        return Cache::remember($cacheKey, 600, function () use ($lat, $lng, $cityName) {
            try {
                $response = Http::timeout(5)->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'current' => 'temperature_2m,relative_humidity_2m,apparent_temperature,precipitation,weather_code,wind_speed_10m,uv_index',
                    'hourly' => 'temperature_2m,apparent_temperature',
                    'forecast_days' => 1,
                    'timezone' => 'auto',
                ]);

                if (!$response->successful()) {
                    return null;
                }

                $data = $response->json();
                $cur = $data['current'] ?? [];
                $hourly = $data['hourly'] ?? [];

                // Description du code météo WMO
                $wmoCode = (int) ($cur['weather_code'] ?? 0);
                $condition = $this->interpretWmoCode($wmoCode);

                // Extraction des heures clés (08h, 11h, 14h, 17h, 20h, 23h)
                $hoursLabels = ['08:00', '11:00', '14:00', '17:00', '20:00', '23:00'];
                $tempSeries = [];
                $feltSeries = [];

                if (!empty($hourly['time']) && !empty($hourly['temperature_2m'])) {
                    $indexes = [8, 11, 14, 17, 20, 23];
                    foreach ($indexes as $idx) {
                        $tempSeries[] = $hourly['temperature_2m'][$idx] ?? round($cur['temperature_2m'] ?? 25);
                        $feltSeries[] = $hourly['apparent_temperature'][$idx] ?? round($cur['apparent_temperature'] ?? 26);
                    }
                } else {
                    $tempSeries = [22, 28, 33, 31, 26, 23];
                    $feltSeries = [23, 30, 36, 33, 27, 24];
                }

                return [
                    'city' => $cityName,
                    'temperature' => round($cur['temperature_2m'] ?? 0, 1),
                    'apparent_temp' => round($cur['apparent_temperature'] ?? 0, 1),
                    'humidity' => $cur['relative_humidity_2m'] ?? 50,
                    'uv_index' => round($cur['uv_index'] ?? 0, 1),
                    'wind_speed' => round($cur['wind_speed_10m'] ?? 0, 1),
                    'condition' => $condition,
                    'chart_hours' => $hoursLabels,
                    'chart_temps' => $tempSeries,
                    'chart_felts' => $feltSeries,
                ];
            } catch (\Throwable $e) {
                Log::debug('Open-Meteo live weather failed: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Traduit le code météo WMO en texte français.
     */
    protected function interpretWmoCode(int $code): string
    {
        return match ($code) {
            0 => 'Ensoleillé / Ciel dégagé',
            1, 2 => 'Faiblement nuageux',
            3 => 'Nuageux',
            45, 48 => 'Brumeux',
            51, 53, 55 => 'Bruine légère',
            61, 63, 65 => 'Pluie',
            71, 73, 75 => 'Chutes de neige',
            80, 81, 82 => 'Averses de pluie',
            95, 96, 99 => 'Orageux',
            default => 'Ciel variable',
        };
    }

    /**
     * Appelle l'API Google Gemini Flash avec directives strictes de localisation.
     */
    protected function callGeminiApi(string $message, array $history, array $knowledge, ?User $user, ?array $targetLocation): array
    {
        $systemPrompt = $this->buildSystemPrompt($knowledge, $user, $targetLocation);

        $contents = [];

        // Injection du contexte système
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => "Voici ton contexte système et tes instructions prioritaires :\n" . $systemPrompt]],
        ];
        $contents[] = [
            'role' => 'model',
            'parts' => [['text' => "Compris ! Je suis HeatBot. J'applique une précision absolue : si une zone ou ville spécifique est demandée, je réponds UNIQUEMENT sur celle-ci sans mentionner de villes hors-sujet."]],
        ];

        // Historique récent (max 6 échanges)
        $recentHistory = array_slice($history, -6);
        foreach ($recentHistory as $turn) {
            $role = ($turn['role'] ?? 'user') === 'bot' || ($turn['role'] ?? 'user') === 'model' ? 'model' : 'user';
            $text = trim((string) ($turn['text'] ?? $turn['content'] ?? ''));
            if (!empty($text)) {
                $contents[] = [
                    'role' => $role,
                    'parts' => [['text' => $text]],
                ];
            }
        }

        // Message courant de l'utilisateur
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $message]],
        ];

        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

        $response = Http::timeout(20)->post($endpoint, [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.5,
                'topP' => 0.9,
                'maxOutputTokens' => 1200,
            ],
        ]);

        if (!$response->successful()) {
            throw new \Exception('Gemini API returned status ' . $response->status() . ': ' . $response->body());
        }

        $data = $response->json();
        $rawReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if (empty($rawReply)) {
            throw new \Exception('Empty reply from Gemini API');
        }

        return $this->parseReplyAndChart($rawReply, $knowledge, $targetLocation);
    }

    /**
     * Analyse la réponse pour en extraire le texte et un éventuel objet graphique Chart.js.
     */
    protected function parseReplyAndChart(string $rawReply, array $knowledge, ?array $targetLocation = null): array
    {
        $chart = null;
        $cleanedReply = $rawReply;

        $patterns = [
            '/```json:chart\s*(\{.*?\})\s*```/is',
            '/```chart\s*(\{.*?\})\s*```/is',
            '/\[CHART_DATA\]\s*(\{.*?\})\s*\[\/CHART_DATA\]/is',
            '/```json\s*(\{\s*"type"\s*:\s*"(?:bar|doughnut|pie|line|polarArea)".*?\})\s*```/is',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $cleanedReply, $matches)) {
                $jsonStr = $matches[1];
                $parsed = json_decode($jsonStr, true);
                if (is_array($parsed) && isset($parsed['type'], $parsed['labels'], $parsed['datasets'])) {
                    $chart = $parsed;
                    $cleanedReply = trim(str_replace($matches[0], '', $cleanedReply));
                    break;
                }
            }
        }

        // Si une ville spécifique était demandée et aucun graphique n'a été produit, on injecte la courbe de météo
        if ($targetLocation && !$chart && !empty($knowledge['target_location_weather'])) {
            $w = $knowledge['target_location_weather'];
            $chart = [
                'type' => 'line',
                'title' => "Évolution des Températures à {$targetLocation['name']} Aujourd'hui (°C)",
                'labels' => $w['chart_hours'],
                'datasets' => [
                    [
                        'label' => 'Température (°C)',
                        'data' => $w['chart_temps'],
                        'borderColor' => '#ea580c',
                        'backgroundColor' => 'rgba(234, 88, 12, 0.15)',
                        'fill' => true,
                    ],
                    [
                        'label' => 'Ressentie (°C)',
                        'data' => $w['chart_felts'],
                        'borderColor' => '#dc2626',
                        'backgroundColor' => 'rgba(220, 38, 38, 0.08)',
                        'fill' => true,
                    ],
                ],
            ];
        }

        $suggestions = $this->generateSuggestions($cleanedReply, $knowledge, $targetLocation);

        return [
            'reply' => $cleanedReply,
            'chart' => $chart,
            'suggestions' => $suggestions,
        ];
    }

    /**
     * Collecte et prépare les connaissances dynamiques depuis la base de données.
     */
    public function gatherPlatformKnowledge(?User $user = null, string $query = '', ?array $targetLocation = null): array
    {
        $cacheKey = 'chatbot_knowledge_' . ($user?->id ?? 'guest') . '_' . ($targetLocation['name'] ?? 'general');

        return Cache::remember($cacheKey, 120, function () use ($user, $targetLocation) {
            // Météo en direct pour la zone demandée (si présente)
            $targetWeather = null;
            $targetAlertes = [];
            $targetPoints = [];
            $targetCoupures = [];

            if ($targetLocation) {
                $targetWeather = $this->fetchLiveWeather(
                    $targetLocation['lat'],
                    $targetLocation['lng'],
                    $targetLocation['name']
                );

                // Alertes spécifiques à cette zone
                $locName = $targetLocation['name'];
                $locAdmin = $targetLocation['admin1'];

                $targetAlertes = AlerteMeteo::with('zone')
                    ->where('statut', 'active')
                    ->where(function($q) use ($locName, $locAdmin) {
                        $q->whereHas('zone', function($zq) use ($locName, $locAdmin) {
                            $zq->where('nom', 'like', "%{$locName}%")
                               ->orWhere('ville', 'like', "%{$locName}%")
                               ->orWhere('gouvernorat', 'like', "%{$locName}%")
                               ->orWhere('gouvernorat', 'like', "%{$locAdmin}%");
                        });
                    })->get()->map(fn($a) => [
                        'titre' => $a->titre,
                        'zone' => $a->zone?->nom,
                        'niveau' => $a->niveau,
                        'temp_max' => $a->temperature_max,
                        'ressenti' => $a->temperature_ressentie,
                        'uv' => $a->indice_uv,
                        'description' => $a->description,
                    ])->toArray();

                // Points fraîcheur spécifiques à cette zone
                $targetPoints = PointFraicheur::where('actif', true)
                    ->where(function($q) use ($locName, $locAdmin) {
                        $q->where('adresse', 'like', "%{$locName}%")
                           ->orWhere('nom', 'like', "%{$locName}%")
                           ->orWhereHas('zone', function($zq) use ($locName, $locAdmin) {
                               $zq->where('nom', 'like', "%{$locName}%")
                                  ->orWhere('ville', 'like', "%{$locName}%")
                                  ->orWhere('gouvernorat', 'like', "%{$locName}%")
                                  ->orWhere('gouvernorat', 'like', "%{$locAdmin}%");
                           });
                    })->get()->map(fn($p) => [
                        'nom' => $p->nom,
                        'type' => $p->type_label,
                        'adresse' => $p->adresse,
                        'pmr' => $p->accessible_pmr ? 'Oui' : 'Non',
                        'horaires' => $p->horaires ?? 'Libre',
                    ])->toArray();

                // Coupures de cette zone
                if (!empty($targetLocation['zone_id'])) {
                    $targetCoupures = Coupure::where('zone_id', $targetLocation['zone_id'])
                        ->whereIn('statut', ['en_cours', 'prevue'])
                        ->get()->map(fn($c) => [
                            'type' => $c->type,
                            'statut' => $c->statut,
                            'retablissement' => $c->retablissement_estime?->format('d/m/Y H:i'),
                        ])->toArray();
                }
            }

            // Statistiques globales de la plateforme
            $alertesParNiveau = [
                'rouge'  => AlerteMeteo::where('statut', 'active')->where('niveau', 'rouge')->count(),
                'orange' => AlerteMeteo::where('statut', 'active')->where('niveau', 'orange')->count(),
                'jaune'  => AlerteMeteo::where('statut', 'active')->where('niveau', 'jaune')->count(),
                'vert'   => AlerteMeteo::where('statut', 'active')->where('niveau', 'vert')->count(),
            ];

            $pointsParType = [
                'parc'            => PointFraicheur::where('actif', true)->where('type', 'parc')->count(),
                'salle_climatisee'=> PointFraicheur::where('actif', true)->where('type', 'salle_climatisee')->count(),
                'fontaine'        => PointFraicheur::where('actif', true)->where('type', 'fontaine')->count(),
                'piscine'         => PointFraicheur::where('actif', true)->where('type', 'piscine')->count(),
                'bibliotheque'    => PointFraicheur::where('actif', true)->where('type', 'bibliotheque')->count(),
            ];

            // Feux NASA FIRMS
            $feuxStats = ['total_hotspots' => 0, 'high_confidence' => 0];
            try {
                $firmsData = $this->firmsService->getHotspots(null, null, 'all', 1);
                $hotspots = $firmsData['hotspots'] ?? [];
                $feuxStats['total_hotspots'] = count($hotspots);
                foreach ($hotspots as $h) {
                    $conf = strtolower($h['confidence'] ?? '');
                    if ($conf === 'high' || (is_numeric($conf) && (int)$conf >= 80)) {
                        $feuxStats['high_confidence']++;
                    }
                }
            } catch (\Throwable $e) {}

            return [
                'target_location' => $targetLocation,
                'target_location_weather' => $targetWeather,
                'target_alertes' => $targetAlertes,
                'target_points' => $targetPoints,
                'target_coupures' => $targetCoupures,
                'stats' => [
                    'zones_total' => Zone::where('actif', true)->count(),
                    'alertes_actives_total' => AlerteMeteo::where('statut', 'active')->count(),
                    'alertes_par_niveau' => $alertesParNiveau,
                    'points_fraicheur_total' => PointFraicheur::where('actif', true)->count(),
                    'points_par_type' => $pointsParType,
                    'coupures_actives_total' => Coupure::whereIn('statut', ['en_cours', 'prevue'])->count(),
                    'signalements_total' => SignalementCoupure::count(),
                    'feux_hotspots_total' => $feuxStats['total_hotspots'],
                    'feux_high_confidence' => $feuxStats['high_confidence'],
                ],
                'date' => now()->format('d/m/Y H:i'),
            ];
        });
    }

    /**
     * Construit le prompt système pour Gemini.
     */
    protected function buildSystemPrompt(array $knowledge, ?User $user, ?array $targetLocation = null): string
    {
        $jsonKnowledge = json_encode($knowledge, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $locationDirective = "";
        if ($targetLocation) {
            $locName = $targetLocation['name'];
            $locRegion = $targetLocation['admin1'];
            $locationDirective = <<<LOC
### 🎯 DIRECTIVE MAJEURE — QUESTION CIBLÉE SUR UNE LOCALITÉ :
L'utilisateur s'intéresse spécifiquement à la localité : '{$locName}' (Région / Gouvernorat : {$locRegion}).
CONSIGNE ABSOLUE ET STRICTE :
1. Tu dois répondre UNIQUEMENT et EXCLUSIVEMENT sur '{$locName}'.
2. Ne cite JAMAIS d'autres gouvernorats ou villes hors-sujet (ne dis PAS "Voici les alertes à Tunis, Sidi Bouzid, Jendouba...").
3. Utilise les données en direct fournies dans "target_location_weather", "target_alertes", "target_points" pour '{$locName}'.
4. Si aucune alerte météo n'est active pour {$locName}, dis clairement : "✅ Aucune alerte canicule en cours pour {$locName}. Les conditions thermiques y sont stables."
5. Fournis la météo exacte (température, ressenti, UV, vent) et propose un graphique en courbe (type "line") des températures de la journée pour {$locName}.
LOC;
        }

        return <<<EOT
Tu es "HeatBot", le conseiller d'intelligence artificielle officiel de la plateforme "HeatAlert" (Système d'Alerte Canicule, Météo Mondiale & Protection Civile).

{$locationDirective}

### RÈGLES GÉNÉRALES :
1. Ton ton est professionnel, chaleureux, hautement qualifié, concis et bienveillant.
2. Structure tes réponses avec des puces élégantes, des titres clairs et des chiffres précis.
3. En cas de malaise ou risque grave, rappelle les numéros d'urgence : 198 (Protection Civile) et 190 (SAMU).
4. Lorsque des statistiques ou prévisions sont demandées, inclus un bloc JSON Chart.js strict délimité par:
```json:chart
{
  "type": "doughnut" | "bar" | "line",
  "title": "Titre explicite",
  "labels": ["..."],
  "datasets": [{"label": "...", "data": [...], "backgroundColor": [...]}]
}
```

### DONNÉES EN TEMPS RÉEL DE LA BASE & OPEN-METEO :
{$jsonKnowledge}
EOT;
    }

    /**
     * Moteur de réponse autonome local (Fallback zéro échec).
     */
    public function generateLocalResponse(string $message, array $knowledge, ?User $user = null, ?array $targetLocation = null): array
    {
        $lower = mb_strtolower($message);
        $stats = $knowledge['stats'];
        $chart = null;

        // ── CAS 1 : UNE LOCALITÉ SPÉCIFIQUE EST DEMANDÉE (ex: Hammamet, Sousse, Tunis, Paris...) ──
        if ($targetLocation) {
            $locName = $targetLocation['name'];
            $locAdmin = $targetLocation['admin1'];
            $weather = $knowledge['target_location_weather'];
            $alertes = $knowledge['target_alertes'];
            $points = $knowledge['target_points'];
            $coupures = $knowledge['target_coupures'];

            $reply = "### 📍 Situation & Météo en Direct — {$locName} ({$locAdmin})\n\n";

            // 1. Météo en direct
            if ($weather) {
                $reply .= "🌤️ **Conditions Météorologiques Actuelles :**\n";
                $reply .= "- **Ciel :** {$weather['condition']}\n";
                $reply .= "- **Température :** **{$weather['temperature']}°C** (Ressentie : **{$weather['apparent_temp']}°C**)\n";
                $reply .= "- **Indice UV :** **{$weather['uv_index']} / 11** " . ($weather['uv_index'] >= 8 ? '🔴 *(Très élevé - Protection solaire indispensable)*' : '🟢 *(Modéré)*') . "\n";
                $reply .= "- **Humidité :** {$weather['humidity']}%\n";
                $reply .= "- **Vent :** {$weather['wind_speed']} km/h\n\n";
            }

            // 2. Alertes canicule pour cette zone
            if (!empty($alertes)) {
                $a = $alertes[0];
                $niveauBadge = strtoupper($a['niveau']);
                $reply .= "⚠️ **Alerte Canicule en Cours pour {$locName} :**\n";
                $reply .= "- **Niveau :** **{$niveauBadge}** — {$a['titre']}\n";
                $reply .= "- **Température attendue :** Jusqu'à {$a['temp_max']}°C (Ressenti {$a['ressenti']}°C)\n";
                $reply .= "- **Description :** {$a['description']}\n\n";
            } else {
                $reply .= "✅ **Vigilance Canicule :** Aucune alerte active pour le secteur de **{$locName} / {$locAdmin}**. La situation thermique est sous contrôle.\n\n";
            }

            // 3. Points fraîcheur de cette localité
            if (!empty($points)) {
                $reply .= "❄️ **Points Fraîcheur Répertoriés à {$locName} :**\n";
                foreach (array_slice($points, 0, 3) as $pt) {
                    $reply .= "- **{$pt['nom']}** ({$pt['type']}) — *{$pt['adresse']}* [PMR: {$pt['pmr']}]\n";
                }
                $reply .= "\n";
            } else {
                $reply .= "❄️ **Points Fraîcheur :** Aucun espace municipal spécifique n'est actuellement labellisé à {$locName}. Consultez la carte interactive pour les zones voisines.\n\n";
            }

            // 4. Coupures éventuelles
            if (!empty($coupures)) {
                $reply .= "⚡ **Coupure signalée :** {$coupures[0]['type']} en cours, rétablissement estimé : {$coupures[0]['retablissement']}.\n\n";
            }

            $reply .= "💡 *Consultez le graphique ci-dessous pour suivre l'évolution des températures au fil des heures à {$locName}.*";

            if ($weather) {
                $chart = [
                    'type' => 'line',
                    'title' => "Évolution Thermique à {$locName} Aujourd'hui (°C)",
                    'labels' => $weather['chart_hours'],
                    'datasets' => [
                        [
                            'label' => 'Température (°C)',
                            'data' => $weather['chart_temps'],
                            'borderColor' => '#ea580c',
                            'backgroundColor' => 'rgba(234, 88, 12, 0.15)',
                            'fill' => true,
                        ],
                        [
                            'label' => 'Température Ressentie (°C)',
                            'data' => $weather['chart_felts'],
                            'borderColor' => '#dc2626',
                            'backgroundColor' => 'rgba(220, 38, 38, 0.08)',
                            'fill' => true,
                        ],
                    ],
                ];
            }

            return [
                'reply' => $reply,
                'chart' => $chart,
                'suggestions' => [
                    "Points fraîcheur proches de {$locName}",
                    "Conseils canicule",
                    "Statistiques globales",
                ],
            ];
        }

        // ── CAS 2 : DEMANDE DE STATISTIQUES GLOBALES OU GRAPHIQUE ──
        if (str_contains($lower, 'stat') || str_contains($lower, 'chart') || str_contains($lower, 'graph') || str_contains($lower, 'bilan') || str_contains($lower, 'combien')) {
            $reply = "### 📊 Bilan Statistique Global — Plateforme HeatAlert\n\n";
            $reply .= "Voici l'état actuel de la vigilance et des données en temps réel sur la Tunisie :\n\n";
            $reply .= "- 🌍 **Zones sous surveillance :** {$stats['zones_total']} gouvernorats & délégations\n";
            $reply .= "- ⚠️ **Alertes météo actives :** **{$stats['alertes_actives_total']}** (Rouge: {$stats['alertes_par_niveau']['rouge']}, Orange: {$stats['alertes_par_niveau']['orange']}, Jaune: {$stats['alertes_par_niveau']['jaune']})\n";
            $reply .= "- ❄️ **Points Fraîcheur répertoriés :** **{$stats['points_fraicheur_total']}** espaces identifiés\n";
            $reply .= "- 🔥 **Foyers d'incendies détectés (NASA FIRMS) :** **{$stats['feux_hotspots_total']}** anomalie(s) thermique(s)\n";
            $reply .= "- ⚡ **Coupures d'électricité :** {$stats['coupures_actives_total']} en cours / planifiées\n";
            $reply .= "- 📢 **Signalements citoyens :** {$stats['signalements_total']} reçus\n\n";
            $reply .= "Consultez le graphique interactif ci-dessous pour la répartition détaillée des alertes actives.";

            $chart = [
                'type' => 'doughnut',
                'title' => 'Répartition des Alertes Actives par Niveau',
                'labels' => ['Alerte Rouge (Danger)', 'Alerte Orange (Vigilance)', 'Alerte Jaune (Attention)', 'Niveau Vert (Normal)'],
                'datasets' => [
                    [
                        'label' => 'Nombre d\'alertes',
                        'data' => [
                            $stats['alertes_par_niveau']['rouge'],
                            $stats['alertes_par_niveau']['orange'],
                            $stats['alertes_par_niveau']['jaune'],
                            $stats['alertes_par_niveau']['vert'],
                        ],
                        'backgroundColor' => ['#dc2626', '#ea580c', '#eab308', '#16a34a'],
                    ],
                ],
            ];

            return [
                'reply' => $reply,
                'chart' => $chart,
                'suggestions' => [
                    'Détection des feux NASA FIRMS',
                    'Météo de Tunis',
                    'Météo de Hammamet',
                    'Conseils canicule',
                ],
            ];
        }

        // ── CAS 3 : FEUX DE FORÊTS / NASA FIRMS ──
        if (str_contains($lower, 'feu') || str_contains($lower, 'incendie') || str_contains($lower, 'firms') || str_contains($lower, 'nasa') || str_contains($lower, 'hotspot')) {
            $totalFeux = $stats['feux_hotspots_total'];
            $reply = "### 🔥 Détection Satellitaire des Départs de Feux (NASA FIRMS)\n\n";
            $reply .= "Notre module de télédétection satellitaire utilise les données en temps réel des satellites **VIIRS (Suomi NPP)** et **MODIS** de la NASA.\n\n";

            if ($totalFeux === 0) {
                $reply .= "✅ **Situation Rassurante :** Aucun foyer d'incendie majeur ni anomalie thermique critique n'est détecté en Tunisie pour les dernières 24 heures.\n\n";
            } else {
                $reply .= "⚠️ **Surveillance Active :** **{$totalFeux}** foyer(s) thermique(s) détecté(s) au cours des dernières 24h, dont **{$stats['feux_high_confidence']}** avec un niveau de confiance élevé.\n\n";
            }

            $reply .= "💡 *Les zones forestières du Nord-Ouest (Jendouba, Béja, Bizerte, Siliana) font l'objet d'une veille thermique prioritaire en période de forte canicule.*";

            $chart = [
                'type' => 'bar',
                'title' => 'Anomalies Thermiques Détectées (NASA FIRMS)',
                'labels' => ['Confiance Élevée (>80%)', 'Confiance Modérée (30-80%)'],
                'datasets' => [
                    [
                        'label' => 'Nombre de points chauds',
                        'data' => [
                            $stats['feux_high_confidence'],
                            max(0, $stats['feux_hotspots_total'] - $stats['feux_high_confidence']),
                        ],
                        'backgroundColor' => ['#ef4444', '#f97316'],
                    ],
                ],
            ];

            return [
                'reply' => $reply,
                'chart' => $chart,
                'suggestions' => [
                    'Carte des feux en direct',
                    'Alertes canicule en cours',
                    'Conseils de prévention',
                ],
            ];
        }

        // ── CAS 4 : POINTS FRAÎCHEUR GÉNÉRAUX ──
        if (str_contains($lower, 'point') || str_contains($lower, 'fraicheur') || str_contains($lower, 'fraîcheur') || str_contains($lower, 'parc') || str_contains($lower, 'climat') || str_contains($lower, 'piscine')) {
            $reply = "### ❄️ Points Fraîcheur & Refuges Thermiques\n\n";
            $reply .= "La plateforme recense **{$stats['points_fraicheur_total']} lieux rafraîchissants** en Tunisie :\n\n";
            $reply .= "- 🌳 **Parcs & Espaces Verts :** {$stats['points_par_type']['parc']}\n";
            $reply .= "- 🏢 **Salles Climatisées Publiques :** {$stats['points_par_type']['salle_climatisee']}\n";
            $reply .= "- 💧 **Fontaines & Bornes d'Eau Potable :** {$stats['points_par_type']['fontaine']}\n";
            $reply .= "- 🏊 **Piscines Municipales :** {$stats['points_par_type']['piscine']}\n";
            $reply .= "- 📚 **Médiathèques & Bibliothèques :** {$stats['points_par_type']['bibliotheque']}\n\n";
            $reply .= "💡 *Pour obtenir les points d'une ville précise, demandez par exemple : 'Points fraîcheur à Hammamet' ou 'Points fraîcheur à Tunis'.*";

            $chart = [
                'type' => 'bar',
                'title' => 'Répartition des Points Fraîcheur par Type',
                'labels' => ['Parcs', 'Salles Climatisées', 'Fontaines', 'Piscines', 'Bibliothèques'],
                'datasets' => [
                    [
                        'label' => 'Nombre d\'espaces',
                        'data' => [
                            $stats['points_par_type']['parc'],
                            $stats['points_par_type']['salle_climatisee'],
                            $stats['points_par_type']['fontaine'],
                            $stats['points_par_type']['piscine'],
                            $stats['points_par_type']['bibliotheque'],
                        ],
                        'backgroundColor' => ['#16a34a', '#2563eb', '#0ea5e9', '#06b6d4', '#7c3aed'],
                    ],
                ],
            ];

            return [
                'reply' => $reply,
                'chart' => $chart,
                'suggestions' => [
                    'Points fraîcheur à Tunis',
                    'Points fraîcheur à Hammamet',
                    'Conseils canicule',
                ],
            ];
        }

        // ── CAS 5 : CONSEILS SANTÉ ──
        if (str_contains($lower, 'conseil') || str_contains($lower, 'sante') || str_contains($lower, 'santé') || str_contains($lower, 'boire') || str_contains($lower, 'bebe') || str_contains($lower, 'proteger')) {
            $reply = "### 💡 Conseils de Santé & Gestes de Prévention Canicule\n\n";
            $reply .= "1. 💧 **Hydratation :** Buvez régulièrement 2 à 3 litres d'eau sans attendre d'avoir soif.\n";
            $reply .= "2. 🏠 **Conservation de la fraîcheur :** Fermez les volets le jour, aérez largement la nuit.\n";
            $reply .= "3. ☀️ **Exposition :** Évitez les sorties aux heures les plus chaudes (11h00 - 17h00).\n";
            $reply .= "4. 👶👵 **Personnes fragiles :** Surveillez les nourrissons et les personnes âgées.\n\n";
            $reply .= "🚨 **Numéros d'urgence :**\n";
            $reply .= "- **Protection Civile :** 198\n";
            $reply .= "- **SAMU :** 190";

            return [
                'reply' => $reply,
                'chart' => null,
                'suggestions' => [
                    'Symptômes du coup de chaleur',
                    'Météo de ma ville',
                    'Statistiques des alertes',
                ],
            ];
        }

        // ── CAS 6 : MÉTÉO GÉNÉRALE (AUCUNE VILLE PRÉCISÉE) ──
        if (str_contains($lower, 'meteo') || str_contains($lower, 'météo') || str_contains($lower, 'temperature')) {
            $reply = "### 🌤️ Météo & Vigilance Thermique\n\n";
            $reply .= "HeatAlert surveille en direct les conditions météo et canicule sur l'ensemble de la Tunisie et du monde via **Open-Meteo**.\n\n";
            $reply .= "🎯 **Pour une précision absolue, indiquez votre ville ou région !**\n";
            $reply .= "Exemples :\n";
            $reply .= "- *'Météo de Hammamet'*\n";
            $reply .= "- *'Météo de Tunis'*\n";
            $reply .= "- *'Météo de Sousse'*\n";
            $reply .= "- *'Météo de Paris'*\n\n";
            $reply .= "Je vous donnerai immédiatement la température exacte, le ressenti, l'indice UV et les prévisions heure par heure.";

            return [
                'reply' => $reply,
                'chart' => null,
                'suggestions' => [
                    'Météo de Hammamet',
                    'Météo de Tunis',
                    'Météo de Sousse',
                    'Météo de Sfax',
                ],
            ];
        }

        // Salutation générale
        $userName = $user ? " **{$user->name}**" : '';
        $reply = "Bonjour{$userName} ! Je suis **HeatBot**, votre assistant d'intelligence artificielle dédié.\n\n";
        $reply .= "Je peux vous renseigner avec précision sur n'importe quelle localité (météo, indice UV, alertes, points fraîcheur), par exemple :\n";
        $reply .= "- 📍 *'Météo de Hammamet'*\n";
        $reply .= "- 📍 *'Points fraîcheur à Nabeul'*\n";
        $reply .= "- 🔥 *'Détection des feux de forêts NASA FIRMS'*\n";
        $reply .= "- 📊 *'Statistiques globales de la plateforme'*\n\n";
        $reply .= "Que souhaitez-vous savoir ?";

        return [
            'reply' => $reply,
            'chart' => null,
            'suggestions' => [
                'Météo de Hammamet',
                '📊 Statistiques globales',
                '🔥 Feux récents (FIRMS)',
                '❄️ Points fraîcheur',
            ],
        ];
    }

    /**
     * Génère des suggestions pertinentes basées sur la réponse.
     */
    protected function generateSuggestions(string $reply, array $knowledge, ?array $targetLocation = null): array
    {
        if ($targetLocation) {
            $loc = $targetLocation['name'];
            return [
                "Points fraîcheur à {$loc}",
                "Conseils de prévention",
                "📊 Statistiques de la plateforme",
            ];
        }

        return [
            '📊 Statistiques globales',
            '🔥 Feux récents (FIRMS)',
            '🌤️ Météo de Hammamet',
            '❄️ Points Fraîcheur',
        ];
    }
}
