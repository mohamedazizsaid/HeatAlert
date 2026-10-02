@extends('layouts.front.front')

@section('content')
{{-- ═══════════════════════════════════════════════════════
     DASHBOARD MÉTÉO MONDIALE EN DIRECT (OPEN-METEO API)
═══════════════════════════════════════════════════════ --}}

<!-- En-tête de page avec fond contextuel sombre et élégant -->
<div class="page-title position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.88) 0%, rgba(30, 58, 95, 0.88) 100%), url('{{ asset('assets/front/img/health/emergency-1.webp') }}') center/cover no-repeat; padding-top: 135px; padding-bottom: 45px; border-bottom: 1px solid rgba(255,255,255,0.1);">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-9">
          <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill bg-danger text-white small fw-bold shadow-sm">
            <i class="bi bi-broadcast"></i>
            <span>API Open-Meteo • Données Mondiales en Temps Réel</span>
          </div>
          <h1 class="heading-title text-white" style="font-weight: 800; font-size: 38px; letter-spacing: -0.5px;">
            Dashboard Météo & Vigilance Mondiale
          </h1>
          <p class="mb-0 text-white-50" style="font-size: 16px; max-width: 720px; margin: 0 auto; line-height: 1.6;">
            Surveillez les températures, les indices UV solaires, les risques de canicule et les prévisions détaillées pour n'importe quelle ville ou pays dans le monde.
          </p>
        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs mt-4" style="background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(8px); border-top: 1px solid rgba(255,255,255,0.1);">
    <div class="container">
      <ol class="mb-0">
        <li><a href="{{ route('front.home') }}" class="text-white-50">Accueil</a></li>
        <li><a href="{{ route('front.zones.index') }}" class="text-white-50">Zones</a></li>
        <li class="current text-white fw-semibold">Météo Mondiale Open-Meteo</li>
      </ol>
    </div>
  </nav>
</div>

<!-- Section Principale du Dashboard -->
<section class="py-5" style="background-color: #f1f5f9; min-height: 80vh;">
  <div class="container">

    <!-- ── BARRE DE RECHERCHE MONDIALE & LOCALISATION ── -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 p-4" style="background: #ffffff;">
      <div class="row g-3 align-items-center">
        <div class="col-lg-7 col-md-12 position-relative">
          <label class="form-label small fw-bold text-muted mb-1">
            <i class="bi bi-search me-1 text-danger"></i>Rechercher une ville, capitale ou pays du monde :
          </label>
          <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
            <span class="input-group-text bg-light border-0 text-danger"><i class="bi bi-geo-alt-fill"></i></span>
            <input type="text" id="globalCitySearchInput" class="form-control bg-light border-0 ps-1"
                   placeholder="Ex: Tunis, Paris, Tokyo, New York, Le Caire, Dubaï, Rome..."
                   autocomplete="off">
            <button class="btn btn-danger px-4 fw-bold" type="button" id="btnSearchCity" onclick="triggerCitySearch()">
              <i class="bi bi-search me-1"></i>Rechercher
            </button>
          </div>
          <!-- Menu déroulant d'autocomplétion -->
          <div id="searchResultsDropdown" class="list-group position-absolute w-100 shadow-lg mt-1 rounded-3 d-none"
               style="z-index: 1050; max-height: 320px; overflow-y: auto; border: 1px solid #e2e8f0; background: #fff;">
          </div>
        </div>

        <div class="col-lg-5 col-md-12 d-flex flex-wrap align-items-end justify-content-lg-end gap-2 pt-lg-4">
          <button type="button" class="btn btn-outline-primary rounded-pill px-3 py-2 fw-semibold shadow-sm d-inline-flex align-items-center gap-2" onclick="useCurrentGeolocation()">
            <i class="bi bi-crosshair text-primary"></i>
            <span>Ma Position Actuelle</span>
          </button>
          <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold shadow-sm d-inline-flex align-items-center gap-2" onclick="refreshCurrentLocationWeather()">
            <i class="bi bi-arrow-clockwise"></i>
            <span>Actualiser</span>
          </button>
        </div>
      </div>

      <!-- Villes populaires / Raccourcis mondiaux -->
      <div class="d-flex align-items-center gap-2 mt-3 pt-3 border-top flex-wrap">
        <span class="small fw-bold text-muted me-1"><i class="bi bi-stars text-warning me-1"></i>Accès Rapide :</span>
        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 quick-city-btn" onclick="selectPredefinedCity('Tunis', 36.8065, 10.1815, 'Tunisie', 'TN')">🇹🇳 Tunis</button>
        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 quick-city-btn" onclick="selectPredefinedCity('Paris', 48.8566, 2.3522, 'France', 'FR')">🇫🇷 Paris</button>
        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 quick-city-btn" onclick="selectPredefinedCity('New York', 40.7128, -74.0060, 'États-Unis', 'US')">🇺🇸 New York</button>
        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 quick-city-btn" onclick="selectPredefinedCity('Tokyo', 35.6762, 139.6503, 'Japon', 'JP')">🇯🇵 Tokyo</button>
        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 quick-city-btn" onclick="selectPredefinedCity('Dubaï', 25.2048, 55.2708, 'Émirats Arabes Unis', 'AE')">🇦🇪 Dubaï</button>
        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 quick-city-btn" onclick="selectPredefinedCity('Londres', 51.5074, -0.1278, 'Royaume-Uni', 'GB')">🇬🇧 Londres</button>
        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 quick-city-btn" onclick="selectPredefinedCity('Le Caire', 30.0444, 31.2357, 'Égypte', 'EG')">🇪🇬 Le Caire</button>
        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 quick-city-btn" onclick="selectPredefinedCity('Casablanca', 33.5731, -7.5898, 'Maroc', 'MA')">🇲🇦 Casablanca</button>
        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 quick-city-btn" onclick="selectPredefinedCity('Riyad', 24.7136, 46.6753, 'Arabie Saoudite', 'SA')">🇸🇦 Riyad</button>
        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 quick-city-btn" onclick="selectPredefinedCity('Sydney', -33.8688, 151.2093, 'Australie', 'AU')">🇦🇺 Sydney</button>
      </div>
    </div>

    <!-- Spinner de chargement -->
    <div id="weatherLoadingState" class="text-center py-5 d-none">
      <div class="spinner-border text-danger" style="width: 3.5rem; height: 3.5rem;" role="status">
        <span class="visually-hidden">Chargement des données Open-Meteo...</span>
      </div>
      <h5 class="fw-bold mt-3 text-secondary">Récupération des données Open-Meteo en direct...</h5>
      <p class="text-muted small">Interrogation des modèles météorologiques mondiaux haute résolution...</p>
    </div>

    <div id="weatherDashboardContent">

      <!-- ── HERO CARD MÉTÉO ACTUELLE & ÉTAT CANICULE ── -->
      <div id="currentWeatherCard" class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4 text-white"
           style="background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%); transition: background 0.5s ease;">
        <div class="card-body p-4 p-lg-5">
          <div class="row align-items-center g-4">

            <!-- Côté Gauche : Localisation & Température Principale -->
            <div class="col-lg-6">
              <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                <span id="locationBadgeCountry" class="badge bg-white text-dark fw-bold px-3 py-1 rounded-pill shadow-sm" style="font-size: 13px;">
                  🇹🇳 Tunisie
                </span>
                <span id="coordinatesBadge" class="badge bg-danger bg-opacity-20 text-white fw-normal px-2 py-1 rounded-pill small">
                  Lat: 36.81° | Lon: 10.18°
                </span>
                <span id="localTimeBadge" class="badge bg-warning text-dark fw-semibold px-2 py-1 rounded-pill small">
                  <i class="bi bi-clock me-1"></i>Heure locale : <span id="localTimeDisplay">--:--</span>
                </span>
              </div>

              <h2 id="cityNameDisplay" class="display-5 fw-extrabold text-white mb-1" style="font-weight: 800;">
                Tunis
              </h2>
              <p id="cityRegionDisplay" class="text-white-50 mb-4 fs-6">Gouvernorat de Tunis, Tunisie</p>

              <div class="d-flex align-items-center gap-4 flex-wrap">
                <div class="d-flex align-items-baseline">
                  <span id="currentTempDisplay" class="display-1 fw-bold text-white tracking-tight" style="font-family: 'Montserrat', sans-serif;">--</span>
                  <span class="fs-1 fw-bold text-warning">°C</span>
                </div>
                <div class="border-start border-white border-opacity-25 ps-3">
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <span id="weatherIconLarge" class="fs-1">☀️</span>
                    <span id="weatherConditionText" class="fs-5 fw-bold text-white">Ensoleillé</span>
                  </div>
                  <div class="text-white-50 small">
                    Ressenti : <strong id="apparentTempDisplay" class="text-white fw-bold">--°C</strong>
                  </div>
                </div>
              </div>

              <!-- Badge Alerte Canicule Dynamique -->
              <div class="mt-4">
                <div id="heatAlertBadge" class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill shadow-sm bg-success text-white fw-bold" style="font-size: 14px;">
                  <span class="spinner-grow spinner-grow-sm text-white" role="status"></span>
                  <span id="heatAlertText">Vigilance Normale — Aucun risque canicule</span>
                </div>
              </div>
            </div>

            <!-- Côté Droit : Statistiques Météo Complémentaires -->
            <div class="col-lg-6">
              <div class="row g-3">
                <div class="col-sm-6">
                  <div class="p-3 rounded-4 bg-white bg-opacity-10 backdrop-blur border border-white border-opacity-10">
                    <div class="text-white-50 small mb-1"><i class="bi bi-thermometer-half text-warning me-1"></i>Min / Max Aujourd'hui</div>
                    <div class="fs-4 fw-bold text-white">
                      <span id="tempMinDisplay" class="text-info">--°C</span> / <span id="tempMaxDisplay" class="text-danger">--°C</span>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="p-3 rounded-4 bg-white bg-opacity-10 backdrop-blur border border-white border-opacity-10">
                    <div class="text-white-50 small mb-1"><i class="bi bi-sun-fill text-warning me-1"></i>Indice UV Max</div>
                    <div class="fs-4 fw-bold text-white d-flex align-items-center gap-2">
                      <span id="uvIndexDisplay">--</span>
                      <span id="uvBadgeRisk" class="badge bg-warning text-dark small" style="font-size: 11px;">Modéré</span>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="p-3 rounded-4 bg-white bg-opacity-10 backdrop-blur border border-white border-opacity-10">
                    <div class="text-white-50 small mb-1"><i class="bi bi-sunrise-fill text-warning me-1"></i>Lever / Coucher du Soleil</div>
                    <div class="fs-5 fw-bold text-white">
                      <i class="bi bi-arrow-up-circle text-warning small"></i> <span id="sunriseDisplay">--:--</span>
                      <span class="mx-1 text-white-50">|</span>
                      <i class="bi bi-arrow-down-circle text-warning small"></i> <span id="sunsetDisplay">--:--</span>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="p-3 rounded-4 bg-white bg-opacity-10 backdrop-blur border border-white border-opacity-10">
                    <div class="text-white-50 small mb-1"><i class="bi bi-cloud-rain-fill text-info me-1"></i>Précipitations</div>
                    <div class="fs-4 fw-bold text-white">
                      <span id="precipitationSumDisplay">0.0 mm</span>
                      <span class="fs-6 text-white-50 fw-normal ms-1">(<span id="precipProbDisplay">0%</span>)</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- ── GRILLE DES 8 INDICATEURS DÉTAILLÉS ── -->
      <h4 class="fw-bold text-dark mb-3">
        <i class="bi bi-speedometer2 text-danger me-2"></i>Indicateurs Météorologiques & Facteurs de Risque
      </h4>
      <div class="row g-3 mb-4">

        <!-- 1. Humidité Relative -->
        <div class="col-xl-3 col-lg-6 col-md-6">
          <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-muted small fw-bold">Humidité de l'Air</span>
              <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle"><i class="bi bi-droplet-half fs-5"></i></div>
            </div>
            <div class="fs-2 fw-bold text-dark mb-1" id="metricHumidity">--%</div>
            <div class="progress mb-2" style="height: 6px;">
              <div id="metricHumidityBar" class="progress-bar bg-primary" role="progressbar" style="width: 50%;"></div>
            </div>
            <span class="text-muted small">Point de rosée : <strong id="metricDewPoint" class="text-dark">--°C</strong></span>
          </div>
        </div>

        <!-- 2. Vitesse et Direction du Vent -->
        <div class="col-xl-3 col-lg-6 col-md-6">
          <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-muted small fw-bold">Vent & Rafales</span>
              <div class="bg-info bg-opacity-10 text-info p-2 rounded-circle"><i class="bi bi-wind fs-5"></i></div>
            </div>
            <div class="d-flex align-items-baseline gap-2 mb-1">
              <span class="fs-2 fw-bold text-dark" id="metricWindSpeed">--</span>
              <span class="text-muted small fw-bold">km/h</span>
              <i id="windDirectionCompass" class="bi bi-arrow-up-circle-fill text-info ms-auto fs-4" title="Direction du vent"></i>
            </div>
            <div class="text-muted small">Rafales max : <strong id="metricWindGusts" class="text-dark">-- km/h</strong></div>
            <span class="text-muted small">Direction : <strong id="metricWindDir">Nord</strong></span>
          </div>
        </div>

        <!-- 3. Pression Atmosphérique -->
        <div class="col-xl-3 col-lg-6 col-md-6">
          <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-muted small fw-bold">Pression Barométrique</span>
              <div class="bg-secondary bg-opacity-10 text-secondary p-2 rounded-circle"><i class="bi bi-speedometer fs-5"></i></div>
            </div>
            <div class="fs-2 fw-bold text-dark mb-1" id="metricPressure">-- hPa</div>
            <div class="text-muted small" id="metricPressureTrend">Niveau normal au niveau de la mer</div>
            <span class="text-muted small mt-2 d-block">Pression sol : <strong id="metricSurfacePressure">-- hPa</strong></span>
          </div>
        </div>

        <!-- 4. Indice UV & Vigilance Solaire -->
        <div class="col-xl-3 col-lg-6 col-md-6">
          <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-muted small fw-bold">Rayonnement UV Solaire</span>
              <div class="bg-warning bg-opacity-10 text-warning p-2 rounded-circle"><i class="bi bi-brightness-high fs-5"></i></div>
            </div>
            <div class="fs-2 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
              <span id="metricUVVal">--</span>
              <span id="metricUVRiskBadge" class="badge bg-warning text-dark fs-6">Faible</span>
            </div>
            <div class="progress mb-2" style="height: 6px;">
              <div id="metricUVBar" class="progress-bar bg-warning" role="progressbar" style="width: 25%;"></div>
            </div>
            <span class="text-muted small" id="metricUVAdvice">Protection recommandée entre 11h et 16h</span>
          </div>
        </div>

        <!-- 5. Couverture Nuageuse -->
        <div class="col-xl-3 col-lg-6 col-md-6">
          <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-muted small fw-bold">Couverture Nuageuse</span>
              <div class="bg-dark bg-opacity-10 text-dark p-2 rounded-circle"><i class="bi bi-clouds fs-5"></i></div>
            </div>
            <div class="fs-2 fw-bold text-dark mb-1" id="metricCloudCover">--%</div>
            <div class="progress mb-2" style="height: 6px;">
              <div id="metricCloudBar" class="progress-bar bg-secondary" role="progressbar" style="width: 30%;"></div>
            </div>
            <span class="text-muted small" id="metricCloudDescription">Ciel dégagé</span>
          </div>
        </div>

        <!-- 6. Stress Thermique / Indice Canicule HeatAlert -->
        <div class="col-xl-3 col-lg-6 col-md-6">
          <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-muted small fw-bold">Stress Thermique (Heat Index)</span>
              <div class="bg-danger bg-opacity-10 text-danger p-2 rounded-circle"><i class="bi bi-fire fs-5"></i></div>
            </div>
            <div class="fs-2 fw-bold text-danger mb-1" id="metricHeatIndex">--°C</div>
            <div class="text-muted small fw-semibold" id="metricHeatRiskLevel">Niveau de confort standard</div>
            <span class="text-muted small mt-1 d-block" id="metricHeatAdvice">Restez hydraté(e) tout au long de la journée.</span>
          </div>
        </div>

        <!-- 7. Précipitations et Pluie -->
        <div class="col-xl-3 col-lg-6 col-md-6">
          <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-muted small fw-bold">Précipitations en Cours</span>
              <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle"><i class="bi bi-cloud-drizzle fs-5"></i></div>
            </div>
            <div class="fs-2 fw-bold text-dark mb-1" id="metricCurrentRain">0.0 mm/h</div>
            <div class="text-muted small">Probabilité de pluie : <strong id="metricPrecipProb">0%</strong></div>
            <span class="text-muted small mt-1 d-block">Cumul total estimé : <strong id="metricTotalPrecip">0.0 mm</strong></span>
          </div>
        </div>

        <!-- 8. Cycle Solaire & Climat -->
        <div class="col-xl-3 col-lg-6 col-md-6">
          <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-muted small fw-bold">Cycle Jour / Nuit</span>
              <div class="bg-warning bg-opacity-10 text-warning p-2 rounded-circle"><i class="bi bi-sun fs-5"></i></div>
            </div>
            <div class="fs-3 fw-bold text-dark mb-1" id="metricDayNight">Journée ☀️</div>
            <div class="text-muted small">Durée du jour : <strong id="metricDaylightDuration">-- h</strong></div>
            <span class="text-muted small mt-1 d-block">Fuseau : <strong id="metricTimezone">--</strong></span>
          </div>
        </div>

      </div>

      <!-- ── CARTE MONDIALE INTERACTIVE LEAFLET & CLIC GÉOGRAPHIQUE ── -->
      <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
        <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
          <div>
            <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-map text-danger me-2"></i>Carte Mondiale Interactive (Cliquez pour afficher la météo)</h5>
            <small class="text-muted">Cliquez n'importe où sur la planète pour charger instantanément les données Open-Meteo de ce point exact.</small>
          </div>
          <span class="badge bg-light text-dark border px-3 py-2 rounded-pill small">
            <i class="bi bi-cursor-fill text-danger me-1"></i>Cliquez sur un pays ou une ville
          </span>
        </div>
        <div class="card-body p-0 position-relative">
          <div id="worldWeatherMap" style="height: 420px; width: 100%; z-index: 10;"></div>
        </div>
      </div>

      <!-- ── GRAPHIQUES HAUTE RÉSOLUTION CHART.JS ── -->
      <div class="row g-4 mb-4">

        <!-- Graphique 1 : Températures Horaires 24h/48h -->
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
              <div>
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-graph-up-arrow text-danger me-2"></i>Évolution Thermique Horaire (Prochaines 24h - 48h)</h5>
                <small class="text-muted">Comparaison Température Réelle vs Ressentie (Open-Meteo 2m)</small>
              </div>
              <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-danger active" id="btnHourly24h" onclick="switchHourlyRange(24)">24 Heures</button>
                <button type="button" class="btn btn-outline-danger" id="btnHourly48h" onclick="switchHourlyRange(48)">48 Heures</button>
              </div>
            </div>
            <div style="position: relative; height: 320px;">
              <canvas id="hourlyTempChart"></canvas>
            </div>
          </div>
        </div>

        <!-- Graphique 2 : Indice UV Horaire & Risque Solaire -->
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="mb-3">
              <h5 class="fw-bold text-dark mb-0"><i class="bi bi-shield-shaded text-warning me-2"></i>Risque Solaire & UV Horaire</h5>
              <small class="text-muted">Pics d'exposition dangereux (Seuil de vigilance)</small>
            </div>
            <div style="position: relative; height: 320px;">
              <canvas id="hourlyUvChart"></canvas>
            </div>
          </div>
        </div>

        <!-- Graphique 3 : Prévisions à 7 Jours (Max/Min & Précipitations) -->
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="mb-3">
              <h5 class="fw-bold text-dark mb-0"><i class="bi bi-calendar-week text-primary me-2"></i>Trajectoire des Températures sur 7 Jours & Pluie</h5>
              <small class="text-muted">Courbes Max/Min et probabilité de précipitations journalières</small>
            </div>
            <div style="position: relative; height: 300px;">
              <canvas id="dailyForecastChart"></canvas>
            </div>
          </div>
        </div>

        <!-- Graphique 4 : Vent & Rafales de Vent (km/h) -->
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="mb-3">
              <h5 class="fw-bold text-dark mb-0"><i class="bi bi-wind text-info me-2"></i>Vent & Rafales (24h)</h5>
              <small class="text-muted">Vitesse moyenne et rafales de vent en km/h</small>
            </div>
            <div style="position: relative; height: 300px;">
              <canvas id="windChart"></canvas>
            </div>
          </div>
        </div>

      </div>

      <!-- ── PRÉVISIONS SUR 7 JOURS (CARTES JOURNALIÈRES) ── -->
      <h4 class="fw-bold text-dark mb-3">
        <i class="bi bi-calendar3 text-danger me-2"></i>Prévisions Météorologiques à 7 Jours
      </h4>
      <div class="row g-3 mb-4" id="dailyForecastCardsContainer">
        <!-- Généré dynamiquement en JS -->
      </div>

      <!-- ── RECOMMANDATIONS SANITAIRES CANICULE HEATALERT ── -->
      <div id="healthAdviceBox" class="card border-0 shadow-sm rounded-4 p-4 text-white" style="background: linear-gradient(135deg, #b91c1c 0%, #7f1d1d 100%);">
        <div class="row align-items-center g-3">
          <div class="col-lg-1 text-center d-none d-lg-block">
            <i class="bi bi-heart-pulse-fill text-warning" style="font-size: 3rem;"></i>
          </div>
          <div class="col-lg-8">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-2">Recommandations Sanitaires HeatAlert</span>
            <h4 class="fw-bold mb-1 text-white" id="adviceHeadline">Conseils de Protection Thermique pour cette Zone</h4>
            <p class="mb-0 text-white-50 small" id="adviceBody">
              En cas de fortes chaleurs, buvez au minimum 1,5 à 2 litres d'eau par jour, évitez les activités physiques aux heures les plus chaudes (11h-16h) et prenez des nouvelles régulières des personnes vulnérables ou âgées.
            </p>
          </div>
          <div class="col-lg-3 text-lg-end">
            <a href="{{ route('front.conseils.index') }}" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2 shadow-sm d-inline-flex align-items-center gap-2">
              <i class="bi bi-info-circle-fill"></i>
              <span>Tous les Conseils</span>
            </a>
          </div>
        </div>
      </div>

    </div><!-- End weatherDashboardContent -->

  </div>
</section>
@endsection

@push('styles')
{{-- Leaflet.js CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
  .backdrop-blur { backdrop-filter: blur(10px); }
  .quick-city-btn:hover { background-color: #fee2e2 !important; border-color: #dc2626 !important; color: #dc2626 !important; font-weight: 600; }
  .daily-card { transition: transform 0.25s ease, box-shadow 0.25s ease; cursor: pointer; }
  .daily-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; border-color: #dc2626 !important; }
  .search-result-item:hover { background-color: #f8fafc; color: #dc2626; cursor: pointer; }
</style>
@endpush

@push('scripts')
{{-- Leaflet.js & Chart.js --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
(function() {
  // ─── État Global du Dashboard ───
  let currentLat = 36.8065;
  let currentLon = 10.1815;
  let currentCity = "Tunis";
  let currentCountry = "Tunisie";
  let currentCountryCode = "TN";
  let currentData = null;
  let currentHourlyRange = 24;

  let map = null;
  let mapMarker = null;

  // Instances Chart.js
  let hourlyTempChartInstance = null;
  let hourlyUvChartInstance = null;
  let dailyForecastChartInstance = null;
  let windChartInstance = null;

  // Dictionnaire des codes WMO Open-Meteo en Français avec icônes
  const WMO_CODES = {
    0:  { desc: "Ciel dégagé / Ensoleillé", icon: "☀️", color: "#f59e0b" },
    1:  { desc: "Principalement dégagé", icon: "🌤️", color: "#f59e0b" },
    2:  { desc: "Partiellement nuageux", icon: "⛅", color: "#64748b" },
    3:  { desc: "Ciel couvert", icon: "☁️", color: "#475569" },
    45: { desc: "Brouillard", icon: "🌫️", color: "#94a3b8" },
    48: { desc: "Brouillard givrant", icon: "🌫️", color: "#94a3b8" },
    51: { desc: "Bruine légère", icon: "🌦️", color: "#38bdf8" },
    53: { desc: "Bruine modérée", icon: "🌦️", color: "#0ea5e9" },
    55: { desc: "Bruine dense", icon: "🌧️", color: "#0284c7" },
    61: { desc: "Pluie faible", icon: "🌧️", color: "#38bdf8" },
    63: { desc: "Pluie modérée", icon: "🌧️", color: "#0284c7" },
    65: { desc: "Pluie forte", icon: "🌧️", color: "#0369a1" },
    71: { desc: "Chutes de neige faibles", icon: "🌨️", color: "#93c5fd" },
    73: { desc: "Chutes de neige modérées", icon: "❄️", color: "#60a5fa" },
    75: { desc: "Chutes de neige fortes", icon: "❄️", color: "#2563eb" },
    80: { desc: "Averses de pluie faibles", icon: "🌦️", color: "#38bdf8" },
    81: { desc: "Averses de pluie modérées", icon: "🌧️", color: "#0284c7" },
    82: { desc: "Averses violentes", icon: "⛈️", color: "#dc2626" },
    95: { desc: "Orage modéré", icon: "⛈️", color: "#b91c1c" },
    96: { desc: "Orage avec grêle légère", icon: "⛈️", color: "#991b1b" },
    99: { desc: "Orage violent avec forte grêle", icon: "⚡", color: "#7f1d1d" }
  };

  function getWeatherInfo(code) {
    return WMO_CODES[code] || { desc: "Temps variable", icon: "⛅", color: "#64748b" };
  }

  // ─── Initialisation ───
  document.addEventListener('DOMContentLoaded', function() {
    initLeafletMap();
    initSearchAutocomplete();
    fetchOpenMeteoData(currentLat, currentLon, currentCity, currentCountry, currentCountryCode);
  });

  // ─── 1. Carte Mondiale Leaflet ───
  function initLeafletMap() {
    const mapEl = document.getElementById('worldWeatherMap');
    if (!mapEl || map) return;

    map = L.map('worldWeatherMap', {
      zoomControl: true,
      scrollWheelZoom: true
    }).setView([currentLat, currentLon], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors | Open-Meteo',
      maxZoom: 18
    }).addTo(map);

    updateMapMarker(currentLat, currentLon, currentCity);

    // Clic sur n'importe quel point du globe
    map.on('click', function(e) {
      const lat = parseFloat(e.latlng.lat.toFixed(4));
      const lon = parseFloat(e.latlng.lng.toFixed(4));
      reverseGeocodeAndFetch(lat, lon);
    });
  }

  function updateMapMarker(lat, lon, title) {
    if (!map) return;
    if (mapMarker) {
      map.removeLayer(mapMarker);
    }

    const customIcon = L.divIcon({
      className: '',
      html: `<div style="width:42px;height:42px;border-radius:50%;background:#dc2626;border:3px solid #fff;box-shadow:0 4px 15px rgba(220,38,38,0.5);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem;">
               <i class="bi bi-geo-alt-fill"></i>
             </div>`,
      iconSize: [42, 42],
      iconAnchor: [21, 42],
      popupAnchor: [0, -42]
    });

    mapMarker = L.marker([lat, lon], { icon: customIcon }).addTo(map);
    mapMarker.bindPopup(`<strong>${title}</strong><br><small>Lat: ${lat}, Lon: ${lon}</small>`).openPopup();
    map.flyTo([lat, lon], Math.max(map.getZoom(), 6), { duration: 1 });
  }

  // ─── 2. Appel API Open-Meteo ───
  window.fetchOpenMeteoData = function(lat, lon, cityName, countryName, countryCode) {
    currentLat = lat;
    currentLon = lon;
    if (cityName) currentCity = cityName;
    if (countryName) currentCountry = countryName;
    if (countryCode) currentCountryCode = countryCode;

    const loadingEl = document.getElementById('weatherLoadingState');
    const contentEl = document.getElementById('weatherDashboardContent');

    if (loadingEl) loadingEl.classList.remove('d-none');
    if (contentEl) contentEl.style.opacity = '0.5';

    const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}` +
      `&current=temperature_2m,relative_humidity_2m,apparent_temperature,is_day,precipitation,rain,weather_code,cloud_cover,pressure_msl,surface_pressure,wind_speed_10m,wind_direction_10m,wind_gusts_10m` +
      `&hourly=temperature_2m,relative_humidity_2m,dew_point_2m,apparent_temperature,precipitation_probability,precipitation,weather_code,surface_pressure,uv_index,wind_speed_10m,wind_gusts_10m` +
      `&daily=weather_code,temperature_2m_max,temperature_2m_min,apparent_temperature_max,apparent_temperature_min,sunrise,sunset,uv_index_max,precipitation_sum,precipitation_probability_max,wind_speed_10m_max` +
      `&timezone=auto`;

    fetch(url)
      .then(res => {
        if (!res.ok) throw new Error("Erreur réseau Open-Meteo : " + res.status);
        return res.json();
      })
      .then(data => {
        currentData = data;
        renderDashboard(data);
        if (loadingEl) loadingEl.classList.add('d-none');
        if (contentEl) contentEl.style.opacity = '1';
        updateMapMarker(lat, lon, currentCity);
      })
      .catch(err => {
        console.error("Open-Meteo Fetch Error:", err);
        if (loadingEl) loadingEl.classList.add('d-none');
        if (contentEl) contentEl.style.opacity = '1';
        alert("Impossible de charger les données météorologiques pour cette position.");
      });
  };

  // ─── 3. Rendu Complet du Dashboard ───
  function renderDashboard(data) {
    const cur = data.current;
    const daily = data.daily;
    const hourly = data.hourly;
    const wInfo = getWeatherInfo(cur.weather_code);

    // Titres & Localisation
    document.getElementById('cityNameDisplay').textContent = currentCity;
    document.getElementById('cityRegionDisplay').textContent = `${currentCity}, ${currentCountry}`;
    document.getElementById('locationBadgeCountry').textContent = `${getCountryFlag(currentCountryCode)} ${currentCountry}`;
    document.getElementById('coordinatesBadge').textContent = `Lat: ${data.latitude.toFixed(2)}° | Lon: ${data.longitude.toFixed(2)}°`;
    document.getElementById('localTimeDisplay').textContent = (cur.time || '').replace('T', ' ');

    // Température & Temps
    document.getElementById('currentTempDisplay').textContent = Math.round(cur.temperature_2m);
    document.getElementById('apparentTempDisplay').textContent = Math.round(cur.apparent_temperature) + '°C';
    document.getElementById('weatherIconLarge').textContent = wInfo.icon;
    document.getElementById('weatherConditionText').textContent = wInfo.desc;

    // Min / Max Jour
    const todayMin = daily.temperature_2m_min[0] !== undefined ? Math.round(daily.temperature_2m_min[0]) : '--';
    const todayMax = daily.temperature_2m_max[0] !== undefined ? Math.round(daily.temperature_2m_max[0]) : '--';
    document.getElementById('tempMinDisplay').textContent = todayMin + '°C';
    document.getElementById('tempMaxDisplay').textContent = todayMax + '°C';

    // Sunrise / Sunset
    const sunrise = daily.sunrise[0] ? daily.sunrise[0].split('T')[1].substring(0, 5) : '--:--';
    const sunset = daily.sunset[0] ? daily.sunset[0].split('T')[1].substring(0, 5) : '--:--';
    document.getElementById('sunriseDisplay').textContent = sunrise;
    document.getElementById('sunsetDisplay').textContent = sunset;

    // UV Max
    const todayUv = daily.uv_index_max[0] !== undefined ? daily.uv_index_max[0].toFixed(1) : '--';
    document.getElementById('uvIndexDisplay').textContent = todayUv;
    updateUvBadge(parseFloat(todayUv));

    // Précipitations
    const precipSum = daily.precipitation_sum[0] !== undefined ? daily.precipitation_sum[0] : 0;
    const precipProb = daily.precipitation_probability_max[0] !== undefined ? daily.precipitation_probability_max[0] : 0;
    document.getElementById('precipitationSumDisplay').textContent = precipSum + ' mm';
    document.getElementById('precipProbDisplay').textContent = precipProb + '%';

    // ── Évaluation Vigilance Canicule & Stress Thermique HeatAlert ──
    evaluateHeatAlertStatus(cur.temperature_2m, cur.apparent_temperature, cur.relative_humidity_2m);

    // ── Remplissage des 8 Métriques Détaillées ──
    document.getElementById('metricHumidity').textContent = cur.relative_humidity_2m + '%';
    document.getElementById('metricHumidityBar').style.width = cur.relative_humidity_2m + '%';
    const curDewPoint = hourly.dew_point_2m && hourly.dew_point_2m[0] !== undefined ? Math.round(hourly.dew_point_2m[0]) : '--';
    document.getElementById('metricDewPoint').textContent = curDewPoint + '°C';

    document.getElementById('metricWindSpeed').textContent = Math.round(cur.wind_speed_10m);
    document.getElementById('metricWindGusts').textContent = Math.round(cur.wind_gusts_10m) + ' km/h';
    document.getElementById('metricWindDir').textContent = getCompassDirection(cur.wind_direction_10m);
    const compassIcon = document.getElementById('windDirectionCompass');
    if (compassIcon) {
      compassIcon.style.transform = `rotate(${cur.wind_direction_10m}deg)`;
      compassIcon.style.transition = 'transform 0.5s ease';
    }

    document.getElementById('metricPressure').textContent = Math.round(cur.pressure_msl) + ' hPa';
    document.getElementById('metricSurfacePressure').textContent = Math.round(cur.surface_pressure) + ' hPa';

    document.getElementById('metricUVVal').textContent = todayUv;
    const uvPercent = Math.min(100, Math.round((parseFloat(todayUv) / 12) * 100));
    document.getElementById('metricUVBar').style.width = uvPercent + '%';

    document.getElementById('metricCloudCover').textContent = cur.cloud_cover + '%';
    document.getElementById('metricCloudBar').style.width = cur.cloud_cover + '%';
    document.getElementById('metricCloudDescription').textContent = cur.cloud_cover < 20 ? "Ciel clair et lumineux" : (cur.cloud_cover < 70 ? "Nuages épars" : "Très nuageux à couvert");

    // Stress thermique (Heat Index simplifié)
    const heatIndex = calculateHeatIndex(cur.temperature_2m, cur.relative_humidity_2m);
    document.getElementById('metricHeatIndex').textContent = Math.round(heatIndex) + '°C';

    document.getElementById('metricCurrentRain').textContent = (cur.precipitation || 0).toFixed(1) + ' mm/h';
    document.getElementById('metricPrecipProb').textContent = precipProb + '%';
    document.getElementById('metricTotalPrecip').textContent = precipSum + ' mm';

    document.getElementById('metricDayNight').textContent = cur.is_day ? "Journée ☀️" : "Nuit 🌙";
    document.getElementById('metricTimezone').textContent = data.timezone || 'Auto';

    // ── Cartes Prévisions 7 Jours ──
    renderDailyCards(daily);

    // ── Recommandations Sanitaires Personnalisées ──
    renderHealthAdvice(cur.temperature_2m, parseFloat(todayUv), cur.weather_code);

    // ── Rendu des Graphiques Chart.js ──
    renderHourlyTempChart(hourly, currentHourlyRange);
    renderHourlyUvChart(hourly);
    renderDailyForecastChart(daily);
    renderWindChart(hourly);
  }

  // ─── 4. Calculateur de Canicule & Vigilance ───
  function evaluateHeatAlertStatus(temp, apparentTemp, humidity) {
    const cardEl = document.getElementById('currentWeatherCard');
    const badgeEl = document.getElementById('heatAlertBadge');
    const textEl = document.getElementById('heatAlertText');

    let badgeClass = 'bg-success';
    let alertMsg = 'Vigilance Normale — Aucun risque canicule';
    let bgGradient = 'linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%)';

    if (temp >= 42 || apparentTemp >= 46) {
      badgeClass = 'bg-danger';
      alertMsg = '🚨 ALERTE CANICULE ROUGE — DANGER THERMIQUE EXTRÊME';
      bgGradient = 'linear-gradient(135deg, #991b1b 0%, #450a0a 100%)';
    } else if (temp >= 38 || apparentTemp >= 41) {
      badgeClass = 'bg-danger';
      alertMsg = '⚠️ ALERTE CANICULE ORANGE — FORTE CHALEUR SÉVÈRE';
      bgGradient = 'linear-gradient(135deg, #b91c1c 0%, #1e1b4b 100%)';
    } else if (temp >= 33 || apparentTemp >= 36) {
      badgeClass = 'bg-warning text-dark';
      alertMsg = '⚡ VIGILANCE JAUNE — Chaleur Intense, Prudence';
      bgGradient = 'linear-gradient(135deg, #c2410c 0%, #1e293b 100%)';
    } else if (temp < 10) {
      badgeClass = 'bg-info text-dark';
      alertMsg = '❄️ Conditions Fraîches / Hivernales';
      bgGradient = 'linear-gradient(135deg, #0369a1 0%, #0f172a 100%)';
    }

    if (badgeEl) {
      badgeEl.className = `d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill shadow-sm fw-bold ${badgeClass}`;
    }
    if (textEl) textEl.textContent = alertMsg;
    if (cardEl) cardEl.style.background = bgGradient;
  }

  function updateUvBadge(uv) {
    const badge = document.getElementById('uvBadgeRisk');
    const advice = document.getElementById('metricUVAdvice');
    const riskBadge = document.getElementById('metricUVRiskBadge');

    let label = 'Faible';
    let cls = 'bg-success text-white';
    let adv = 'Aucun danger solaire direct.';

    if (uv >= 11) {
      label = 'Extrême (11+)';
      cls = 'bg-danger text-white';
      adv = 'Danger extrême : évitez absolument le soleil en mi-journée !';
    } else if (uv >= 8) {
      label = 'Très Élevé (8-10)';
      cls = 'bg-danger text-white';
      adv = 'Risque très élevé : écran total 50+, chapeau et lunettes requis.';
    } else if (uv >= 6) {
      label = 'Élevé (6-7)';
      cls = 'bg-warning text-dark';
      adv = 'Protection solaire recommandée entre 11h et 16h.';
    } else if (uv >= 3) {
      label = 'Modéré (3-5)';
      cls = 'bg-warning text-dark';
      adv = 'Protection modérée conseillée lors d\'expositions prolongées.';
    }

    if (badge) { badge.className = `badge ${cls} small`; badge.textContent = label; }
    if (riskBadge) { riskBadge.className = `badge ${cls} fs-6`; riskBadge.textContent = label; }
    if (advice) advice.textContent = adv;
  }

  function calculateHeatIndex(temp, rh) {
    // Formule approchée Heat Index de Rothfusz
    if (temp < 27) return temp;
    const t = temp;
    const r = rh;
    return -8.78469475556 + 1.61139411 * t + 2.33854883889 * r - 0.14611605 * t * r - 0.012308094 * t * t - 0.0164248277778 * r * r + 0.002211732 * t * t * r + 0.00072546 * t * r * r - 0.000003582 * t * t * r * r;
  }

  function getCompassDirection(deg) {
    const directions = ['Nord (N)', 'Nord-Est (NE)', 'Est (E)', 'Sud-Est (SE)', 'Sud (S)', 'Sud-Ouest (SO)', 'Ouest (O)', 'Nord-Ouest (NO)'];
    return directions[Math.round(deg / 45) % 8];
  }

  function getCountryFlag(code) {
    if (!code || code.length !== 2) return "🌐";
    const offset = 127397;
    return String.fromCodePoint(...[...code.toUpperCase()].map(c => c.charCodeAt(0) + offset));
  }

  // ─── 5. Cartes Journalières (7 Jours) ───
  function renderDailyCards(daily) {
    const container = document.getElementById('dailyForecastCardsContainer');
    if (!container) return;
    container.innerHTML = '';

    const daysCount = Math.min(7, daily.time.length);
    const dayNames = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];

    for (let i = 0; i < daysCount; i++) {
      const dateStr = daily.time[i];
      const d = new Date(dateStr);
      const isToday = i === 0;
      const dayLabel = isToday ? "Aujourd'hui" : dayNames[d.getDay()];
      const wInfo = getWeatherInfo(daily.weather_code[i]);
      const maxT = Math.round(daily.temperature_2m_max[i]);
      const minT = Math.round(daily.temperature_2m_min[i]);
      const uvMax = daily.uv_index_max[i] !== undefined ? daily.uv_index_max[i].toFixed(1) : '--';
      const rainProb = daily.precipitation_probability_max[i] || 0;

      const col = document.createElement('div');
      col.className = 'col-6 col-md-4 col-lg-3 col-xl';
      col.innerHTML = `
        <div class="card border rounded-4 shadow-sm p-3 h-100 bg-white text-center daily-card">
          <div class="small fw-bold ${isToday ? 'text-danger' : 'text-muted'} mb-1">${dayLabel}</div>
          <div class="small text-muted mb-2">${d.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' })}</div>
          <div class="display-6 my-2">${wInfo.icon}</div>
          <div class="small fw-semibold text-dark text-truncate mb-2" title="${wInfo.desc}">${wInfo.desc}</div>
          <div class="d-flex justify-content-center align-items-baseline gap-2 mb-2">
            <span class="fs-5 fw-bold text-danger">${maxT}°</span>
            <span class="fs-6 fw-bold text-info">${minT}°</span>
          </div>
          <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top small">
            <span class="text-primary" title="Probabilité de pluie"><i class="bi bi-droplet-fill me-1"></i>${rainProb}%</span>
            <span class="badge bg-warning text-dark" title="Indice UV Max">UV ${uvMax}</span>
          </div>
        </div>
      `;
      container.appendChild(col);
    }
  }

  // ─── 6. Recommandations Sanitaires Personnalisées ───
  function renderHealthAdvice(temp, uv, weatherCode) {
    const headline = document.getElementById('adviceHeadline');
    const body = document.getElementById('adviceBody');
    if (!headline || !body) return;

    if (temp >= 38) {
      headline.textContent = `Alerte Chaleur Extrême à ${currentCity} (${Math.round(temp)}°C)`;
      body.textContent = "Risque sévère d'insolation et de coup de chaleur. Fermez les volets le jour, hydratez-vous sans attendre la soif (2L/jour), évitez tout effort physique à l'extérieur. Si vertiges ou nausées apparaissent, appelez immédiatement les secours (190 / 198).";
    } else if (temp >= 32) {
      headline.textContent = `Recommandations Chaleur Importante à ${currentCity} (${Math.round(temp)}°C)`;
      body.textContent = "Les températures sont élevées. Portez des vêtements légers et amples, privilégiez les pièces fraîches et aérez tard le soir. Ne laissez aucun enfant ou animal dans un véhicule à l'arrêt.";
    } else if (uv >= 8) {
      headline.textContent = `Vigilance Indice UV Très Élevé (UV ${uv})`;
      body.textContent = "Le rayonnement ultraviolet est très agressif pour la peau et les yeux. Appliquez de la crème solaire SPF 50 toutes les 2 heures, portez un chapeau à larges bords et des lunettes certifiées anti-UV.";
    } else if ([95, 96, 99].includes(weatherCode)) {
      headline.textContent = `Vigilance Orages & Rafales de Vent à ${currentCity}`;
      body.textContent = "Des conditions orageuses instables sont signalées. Mettez-vous à l'abri, évitez de stationner sous les arbres ou structures légères et limitez vos déplacements routiers pendant les précipitations violentes.";
    } else {
      headline.textContent = `Conditions Météo Favorables à ${currentCity}`;
      body.textContent = "Les indicateurs de température et de vigilance thermique sont actuellement dans des seuils normaux. Profitez de la météo tout en maintenant une hydratation régulière.";
    }
  }

  // ─── 7. Graphiques Chart.js Impeccables ───
  window.switchHourlyRange = function(hours) {
    currentHourlyRange = hours;
    document.getElementById('btnHourly24h').classList.toggle('active', hours === 24);
    document.getElementById('btnHourly48h').classList.toggle('active', hours === 48);
    if (currentData) {
      renderHourlyTempChart(currentData.hourly, hours);
    }
  };

  function renderHourlyTempChart(hourly, count) {
    const ctx = document.getElementById('hourlyTempChart');
    if (!ctx) return;

    const times = hourly.time.slice(0, count).map(t => {
      const d = new Date(t);
      return `${d.getHours()}h`;
    });
    const temps = hourly.temperature_2m.slice(0, count);
    const apparentTemps = hourly.apparent_temperature.slice(0, count);

    if (hourlyTempChartInstance) {
      hourlyTempChartInstance.destroy();
    }

    hourlyTempChartInstance = new Chart(ctx, {
      type: 'line',
      data: {
        labels: times,
        datasets: [
          {
            label: 'Température Réelle (°C)',
            data: temps,
            borderColor: '#dc2626',
            backgroundColor: 'rgba(220, 38, 38, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointRadius: 3,
            pointHoverRadius: 6,
            pointBackgroundColor: '#dc2626'
          },
          {
            label: 'Température Ressentie (°C)',
            data: apparentTemps,
            borderColor: '#f59e0b',
            borderDash: [5, 5],
            borderWidth: 2,
            fill: false,
            tension: 0.4,
            pointRadius: 2,
            pointHoverRadius: 5,
            pointBackgroundColor: '#f59e0b'
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
          legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8, font: { weight: 600 } } },
          tooltip: {
            backgroundColor: '#0f172a',
            padding: 12,
            cornerRadius: 10,
            callbacks: {
              label: (item) => ` ${item.dataset.label}: ${item.parsed.y.toFixed(1)}°C`
            }
          }
        },
        scales: {
          y: {
            grid: { color: 'rgba(0,0,0,0.05)' },
            ticks: { callback: v => v + '°' }
          },
          x: {
            grid: { display: false }
          }
        }
      }
    });
  }

  function renderHourlyUvChart(hourly) {
    const ctx = document.getElementById('hourlyUvChart');
    if (!ctx) return;

    const times = hourly.time.slice(0, 24).map(t => `${new Date(t).getHours()}h`);
    const uvData = hourly.uv_index.slice(0, 24);

    const barColors = uvData.map(v => {
      if (v >= 8) return '#dc2626'; // Rouge
      if (v >= 6) return '#ea580c'; // Orange
      if (v >= 3) return '#eab308'; // Jaune
      return '#22c55e';             // Vert
    });

    if (hourlyUvChartInstance) {
      hourlyUvChartInstance.destroy();
    }

    hourlyUvChartInstance = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: times,
        datasets: [{
          label: 'Indice UV',
          data: uvData,
          backgroundColor: barColors,
          borderRadius: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#0f172a',
            callbacks: {
              label: (item) => ` Indice UV: ${item.parsed.y.toFixed(1)}`
            }
          }
        },
        scales: {
          y: {
            min: 0,
            max: Math.max(12, Math.ceil(Math.max(...uvData) + 1)),
            grid: { color: 'rgba(0,0,0,0.05)' }
          },
          x: {
            grid: { display: false }
          }
        }
      }
    });
  }

  function renderDailyForecastChart(daily) {
    const ctx = document.getElementById('dailyForecastChart');
    if (!ctx) return;

    const daysCount = Math.min(7, daily.time.length);
    const dayNames = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
    const labels = daily.time.slice(0, daysCount).map(t => dayNames[new Date(t).getDay()]);
    const maxTemps = daily.temperature_2m_max.slice(0, daysCount);
    const minTemps = daily.temperature_2m_min.slice(0, daysCount);
    const rainProbs = daily.precipitation_probability_max.slice(0, daysCount);

    if (dailyForecastChartInstance) {
      dailyForecastChartInstance.destroy();
    }

    dailyForecastChartInstance = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          {
            type: 'line',
            label: 'Température Max (°C)',
            data: maxTemps,
            borderColor: '#dc2626',
            backgroundColor: '#dc2626',
            borderWidth: 3,
            tension: 0.3,
            yAxisID: 'y'
          },
          {
            type: 'line',
            label: 'Température Min (°C)',
            data: minTemps,
            borderColor: '#0284c7',
            backgroundColor: '#0284c7',
            borderWidth: 2,
            borderDash: [4, 4],
            tension: 0.3,
            yAxisID: 'y'
          },
          {
            type: 'bar',
            label: 'Probabilité Pluie (%)',
            data: rainProbs,
            backgroundColor: 'rgba(56, 189, 248, 0.35)',
            borderRadius: 6,
            yAxisID: 'y1'
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8, font: { weight: 600 } } }
        },
        scales: {
          y: {
            type: 'linear',
            position: 'left',
            grid: { color: 'rgba(0,0,0,0.05)' },
            ticks: { callback: v => v + '°C' }
          },
          y1: {
            type: 'linear',
            position: 'right',
            min: 0,
            max: 100,
            grid: { display: false },
            ticks: { callback: v => v + '%' }
          },
          x: {
            grid: { display: false }
          }
        }
      }
    });
  }

  function renderWindChart(hourly) {
    const ctx = document.getElementById('windChart');
    if (!ctx) return;

    const times = hourly.time.slice(0, 24).map(t => `${new Date(t).getHours()}h`);
    const windSpeed = hourly.wind_speed_10m.slice(0, 24);
    const windGusts = hourly.wind_gusts_10m.slice(0, 24);

    if (windChartInstance) {
      windChartInstance.destroy();
    }

    windChartInstance = new Chart(ctx, {
      type: 'line',
      data: {
        labels: times,
        datasets: [
          {
            label: 'Rafales (km/h)',
            data: windGusts,
            borderColor: '#0284c7',
            backgroundColor: 'rgba(2, 132, 199, 0.15)',
            fill: true,
            tension: 0.3,
            borderWidth: 2
          },
          {
            label: 'Vent Moyen (km/h)',
            data: windSpeed,
            borderColor: '#64748b',
            borderWidth: 2,
            borderDash: [3, 3],
            fill: false,
            tension: 0.3
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8 } }
        },
        scales: {
          y: {
            grid: { color: 'rgba(0,0,0,0.05)' },
            ticks: { callback: v => v + ' km/h' }
          },
          x: { grid: { display: false } }
        }
      }
    });
  }

  // ─── 8. Autocomplétion & Recherche Mondiale Open-Meteo Geocoding ───
  function initSearchAutocomplete() {
    const searchInput = document.getElementById('globalCitySearchInput');
    const dropdown = document.getElementById('searchResultsDropdown');
    let debounceTimer = null;

    if (!searchInput || !dropdown) return;

    searchInput.addEventListener('input', function() {
      const q = this.value.trim();
      clearTimeout(debounceTimer);

      if (q.length < 2) {
        dropdown.classList.add('d-none');
        dropdown.innerHTML = '';
        return;
      }

      debounceTimer = setTimeout(() => {
        searchOpenMeteoGeocoding(q);
      }, 300);
    });

    // Clic en dehors pour fermer
    document.addEventListener('click', function(e) {
      if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.add('d-none');
      }
    });

    searchInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        triggerCitySearch();
      }
    });
  }

  function searchOpenMeteoGeocoding(query) {
    const dropdown = document.getElementById('searchResultsDropdown');
    const url = `https://geocoding-api.open-meteo.com/v1/search?name=${encodeURIComponent(query)}&count=8&language=fr&format=json`;

    fetch(url)
      .then(res => res.json())
      .then(data => {
        if (!data.results || data.results.length === 0) {
          dropdown.innerHTML = '<div class="p-3 text-muted small text-center">Aucune ville trouvée pour cette recherche.</div>';
          dropdown.classList.remove('d-none');
          return;
        }

        dropdown.innerHTML = '';
        data.results.forEach(res => {
          const item = document.createElement('a');
          item.href = '#!';
          item.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3 search-result-item border-bottom';
          const flag = getCountryFlag(res.country_code);
          const region = res.admin1 ? `${res.admin1}, ` : '';

          item.innerHTML = `
            <div>
              <strong class="text-dark">${res.name}</strong>
              <small class="text-muted ms-1">${region}${res.country || ''}</small>
            </div>
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-light text-dark border small">${flag} ${res.country_code || ''}</span>
              <small class="text-muted" style="font-size: 11px;">Lat: ${res.latitude.toFixed(2)}</small>
            </div>
          `;

          item.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('globalCitySearchInput').value = `${res.name}, ${res.country || ''}`;
            dropdown.classList.add('d-none');
            fetchOpenMeteoData(res.latitude, res.longitude, res.name, res.country || 'Monde', res.country_code || 'UN');
          });

          dropdown.appendChild(item);
        });

        dropdown.classList.remove('d-none');
      })
      .catch(err => {
        console.error("Geocoding Error:", err);
      });
  }

  window.triggerCitySearch = function() {
    const q = document.getElementById('globalCitySearchInput').value.trim();
    if (!q) return;
    searchOpenMeteoGeocoding(q);
  };

  window.selectPredefinedCity = function(cityName, lat, lon, countryName, countryCode) {
    document.getElementById('globalCitySearchInput').value = `${cityName}, ${countryName}`;
    document.getElementById('searchResultsDropdown').classList.add('d-none');
    fetchOpenMeteoData(lat, lon, cityName, countryName, countryCode);
  };

  // ─── 9. Géolocalisation GPS Utilisateur ───
  window.useCurrentGeolocation = function() {
    if (!navigator.geolocation) {
      alert("La géolocalisation n'est pas supportée par votre navigateur.");
      return;
    }

    const btn = event?.currentTarget;
    if (btn) btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Localisation...';

    navigator.geolocation.getCurrentPosition(
      pos => {
        if (btn) btn.innerHTML = '<i class="bi bi-crosshair text-primary"></i> <span>Ma Position Actuelle</span>';
        const lat = parseFloat(pos.coords.latitude.toFixed(4));
        const lon = parseFloat(pos.coords.longitude.toFixed(4));
        reverseGeocodeAndFetch(lat, lon);
      },
      err => {
        if (btn) btn.innerHTML = '<i class="bi bi-crosshair text-primary"></i> <span>Ma Position Actuelle</span>';
        alert("Impossible d'obtenir votre position GPS. Veuillez vérifier vos autorisations.");
      },
      { timeout: 8000 }
    );
  };

  window.refreshCurrentLocationWeather = function() {
    fetchOpenMeteoData(currentLat, currentLon, currentCity, currentCountry, currentCountryCode);
  };

  function reverseGeocodeAndFetch(lat, lon) {
    // Géocodage inverse OpenStreetMap Nominatim
    const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json&accept-language=fr`;
    fetch(url)
      .then(res => res.json())
      .then(data => {
        const address = data.address || {};
        const cityName = address.city || address.town || address.village || address.municipality || address.county || `Position (${lat}, ${lon})`;
        const countryName = address.country || "Monde";
        const countryCode = (address.country_code || "UN").toUpperCase();

        document.getElementById('globalCitySearchInput').value = `${cityName}, ${countryName}`;
        fetchOpenMeteoData(lat, lon, cityName, countryName, countryCode);
      })
      .catch(() => {
        fetchOpenMeteoData(lat, lon, `Coordonnées (${lat}, ${lon})`, "Monde", "UN");
      });
  }

})();
</script>
@endpush
