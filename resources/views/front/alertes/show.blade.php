@extends('layouts.front.front')

@push('styles')
{{-- Leaflet.js CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
  #alerte-map { min-height: 350px; height: 350px; width: 100%; z-index: 10; }
  .leaflet-container { font-family: inherit; }
</style>
@endpush

@push('scripts')
{{-- Leaflet.js & Chart.js --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
(function() {
    function initAlerteComponents() {
        // ─── Radar Chart Météo ───
        const radarCtx = document.getElementById('meteoRadarChart');
        if (radarCtx && typeof Chart !== 'undefined') {
            new Chart(radarCtx, {
                type: 'radar',
                data: {
                    labels: ['Temp. Max', 'Ressentie', 'Indice UV', 'Humidité', 'Vent', 'Risque Électr.'],
                    datasets: [{
                        label: 'Indicateurs de Risque',
                        data: [
                            {{ min(100, (($alerte->temperature_max ?? 30) / 55) * 100) }},
                            {{ min(100, (($alerte->temperature_ressentie ?? 30) / 60) * 100) }},
                            {{ min(100, (($alerte->indice_uv ?? 5) / 12) * 100) }},
                            {{ $alerte->humidite ?? 40 }},
                            {{ min(100, (($alerte->vitesse_vent ?? 20) / 120) * 100) }},
                            {{ $alerte->risque_coupure ? 90 : 15 }}
                        ],
                        backgroundColor: 'rgba(220, 38, 38, 0.25)',
                        borderColor: '#dc2626',
                        borderWidth: 2,
                        pointBackgroundColor: '#dc2626',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#dc2626'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            angleLines: { color: 'rgba(0,0,0,0.1)' },
                            grid: { color: 'rgba(0,0,0,0.06)' },
                            suggestedMin: 0,
                            suggestedMax: 100,
                            ticks: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        @if($alerte->zone && $alerte->zone->latitude && $alerte->zone->longitude)
        // ─── Leaflet Map ───
        const mapContainer = document.getElementById('alerte-map');
        if (mapContainer && !mapContainer._leaflet_id && typeof L !== 'undefined') {
            const map = L.map('alerte-map', {
                zoomControl: true,
                scrollWheelZoom: false
            }).setView([{{ $alerte->zone->latitude }}, {{ $alerte->zone->longitude }}], 11);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 18
            }).addTo(map);

            const alerteNiveau = '{{ $alerte->niveau }}';
            const mapColors = { 'rouge': '#dc2626', 'orange': '#ea580c', 'jaune': '#d97706', 'vert': '#16a34a' };
            const markerColor = mapColors[alerteNiveau] || '#dc2626';

            const icon = L.divIcon({
                className: '',
                html: `<div style="width:46px;height:46px;border-radius:50%;background:${markerColor};border:3px solid #fff;box-shadow:0 4px 18px rgba(0,0,0,0.35);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.3rem;">
                         <i class="bi bi-exclamation-triangle-fill"></i>
                       </div>`,
                iconSize: [46, 46],
                iconAnchor: [23, 46],
                popupAnchor: [0, -48]
            });

            L.marker([{{ $alerte->zone->latitude }}, {{ $alerte->zone->longitude }}], { icon })
                .addTo(map)
                .bindPopup(`<strong>{{ $alerte->titre }}</strong><br><span style="color:${markerColor};font-weight:bold;">Vigilance ${alerteNiveau.toUpperCase()}</span><br><small>{{ $alerte->zone->nom }}</small>`)
                .openPopup();

            L.circle([{{ $alerte->zone->latitude }}, {{ $alerte->zone->longitude }}], {
                radius: 15000,
                color: markerColor,
                fillColor: markerColor,
                fillOpacity: 0.1,
                weight: 2,
                dashArray: '6,4'
            }).addTo(map);

            window.alerteMapInstance = map;
            setTimeout(() => { map.invalidateSize(); }, 300);
        }
        @endif
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAlerteComponents);
    } else {
        initAlerteComponents();
    }
    window.addEventListener('load', function() {
        initAlerteComponents();
        if (window.alerteMapInstance) {
            window.alerteMapInstance.invalidateSize();
        }
    });
})();
</script>
@endpush

@section('content')
{{-- ═══════════════════════════════════════════════════════
     PAGE ALERTE — BULLETIN DÉTAILLÉ
═══════════════════════════════════════════════════════ --}}

@php
  $niveauColors = [
    'rouge'  => ['#dc2626', '#fee2e2', 'Vigilance Rouge — Canicule Extrême'],
    'orange' => ['#ea580c', '#ffedd5', 'Vigilance Orange — Forte Chaleur'],
    'jaune'  => ['#d97706', '#fef3c7', 'Vigilance Jaune — Chaleur Modérée'],
    'vert'   => ['#16a34a', '#dcfce7', 'Situation Verte — Normale'],
  ];
  $cfg = $niveauColors[$alerte->niveau] ?? ['#64748b', '#f1f5f9', 'Vigilance ' . ucfirst($alerte->niveau)];
@endphp

<!-- Page Title (Standard Template Header Blanc) -->
<div class="page-title">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill text-uppercase fw-bold" style="background: {{ $cfg[1] }}; color: {{ $cfg[0] }}; font-size: 12px;">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ $cfg[2] }}</span>
          </div>
          <h1 class="heading-title" style="color: #1e3a5f; font-weight: 700;">{{ $alerte->titre }}</h1>
          <p class="mb-0 text-muted" style="font-size: 15px;">
            Bulletin météorologique officiel émis pour la zone de {{ $alerte->zone->nom ?? 'Tunisie' }}.
          </p>
        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs">
    <div class="container">
      <ol>
        <li><a href="{{ route('front.home') }}">Accueil</a></li>
        <li><a href="{{ route('front.alertes.index') }}">Alertes Météo</a></li>
        <li class="current">{{ Str::limit($alerte->titre, 35) }}</li>
      </ol>
    </div>
  </nav>
</div><!-- End Page Title -->

<!-- Department Details Section (Clinic Template Style) -->
<section id="department-details" class="department-details section py-5" style="background-color: #f8fafc;">
  <div class="container" data-aos="fade-up">

    <div class="row g-4">

      <!-- Colonne Principale -->
      <div class="col-lg-8">

        <!-- Carte Bulletin Principal -->
        <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4 bg-white mb-4">

          <!-- Métadonnées & Période -->
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pb-3 mb-4 border-bottom">
            <div class="d-flex align-items-center gap-2">
              <span class="badge px-3 py-2 rounded-pill fw-bold" style="background: {{ $cfg[1] }}; color: {{ $cfg[0] }}; font-size: 12px;">
                Vigilance {{ ucfirst($alerte->niveau) }}
              </span>
              <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill small">
                {{ ucfirst(str_replace('_',' ', $alerte->type)) }}
              </span>
              @if($alerte->statut === 'active')
                <span class="badge bg-danger rounded-pill px-3 py-2 small">
                  <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i>En cours
                </span>
              @else
                <span class="badge bg-secondary rounded-pill px-3 py-2 small">
                  {{ ucfirst($alerte->statut) }}
                </span>
              @endif
            </div>

            @if($alerte->zone)
              <a href="{{ route('front.zones.show', $alerte->zone) }}" class="text-decoration-none small fw-semibold text-secondary">
                <i class="bi bi-geo-alt-fill text-danger me-1"></i>Zone : <strong>{{ $alerte->zone->nom }}</strong> (Gvt. {{ $alerte->zone->gouvernorat ?? 'N/D' }})
              </a>
            @endif
          </div>

          <!-- Description Détaillée -->
          <h4 class="fw-bold mb-3" style="color: #1e3a5f;">Bulletin Météorologique & Risques Associés</h4>
          <div class="p-3 rounded-3 mb-4" style="background: #f8fafc; border-left: 4px solid {{ $cfg[0] }};">
            <p class="mb-0 text-secondary" style="font-size: 15px; line-height: 1.8;">
              {{ $alerte->description ?? "Alerte canicule émise suite aux conditions thermiques exceptionnelles constatées sur le gouvernorat. L'exposition prolongée aux températures élevées présente un risque majeur de déshydratation, coup de chaleur et décompensation de pathologies chroniques." }}
            </p>
          </div>

          <!-- Grille des Paramètres Météorologiques -->
          <h5 class="fw-bold mt-4 mb-3" style="color: #1e3a5f;">Paramètres & Indicateurs Mesurés</h5>
          <div class="row g-3 mb-4">
            <div class="col-6 col-md-4">
              <div class="p-3 rounded-3 border bg-light text-center h-100">
                <i class="bi bi-thermometer-sun text-danger fs-3 d-block mb-1"></i>
                <span class="text-muted small d-block">Température Max</span>
                <strong class="fs-4 text-danger">{{ $alerte->temperature_max ? $alerte->temperature_max.'°C' : 'N/D' }}</strong>
              </div>
            </div>
            <div class="col-6 col-md-4">
              <div class="p-3 rounded-3 border bg-light text-center h-100">
                <i class="bi bi-thermometer-half text-warning-emphasis fs-3 d-block mb-1"></i>
                <span class="text-muted small d-block">Température Ressentie</span>
                <strong class="fs-4 text-warning-emphasis">{{ $alerte->temperature_ressentie ? $alerte->temperature_ressentie.'°C' : 'N/D' }}</strong>
              </div>
            </div>
            <div class="col-6 col-md-4">
              <div class="p-3 rounded-3 border bg-light text-center h-100">
                <i class="bi bi-sun-fill text-warning fs-3 d-block mb-1"></i>
                <span class="text-muted small d-block">Indice UV</span>
                <strong class="fs-4 text-dark">{{ $alerte->indice_uv ? $alerte->indice_uv.' / 12' : 'N/D' }}</strong>
              </div>
            </div>
            <div class="col-6 col-md-4">
              <div class="p-3 rounded-3 border bg-light text-center h-100">
                <i class="bi bi-moisture text-primary fs-3 d-block mb-1"></i>
                <span class="text-muted small d-block">Humidité Relative</span>
                <strong class="fs-4 text-primary">{{ $alerte->humidite ? $alerte->humidite.'%' : 'N/D' }}</strong>
              </div>
            </div>
            <div class="col-6 col-md-4">
              <div class="p-3 rounded-3 border bg-light text-center h-100">
                <i class="bi bi-wind text-secondary fs-3 d-block mb-1"></i>
                <span class="text-muted small d-block">Vitesse du Vent</span>
                <strong class="fs-4 text-secondary">{{ $alerte->vitesse_vent ? $alerte->vitesse_vent.' km/h' : 'N/D' }}</strong>
              </div>
            </div>
            <div class="col-6 col-md-4">
              <div class="p-3 rounded-3 border bg-light text-center h-100">
                <i class="bi bi-lightning-charge-fill fs-3 d-block mb-1 {{ $alerte->risque_coupure ? 'text-danger' : 'text-success' }}"></i>
                <span class="text-muted small d-block">Risque Coupure</span>
                <strong class="fs-5 {{ $alerte->risque_coupure ? 'text-danger' : 'text-success' }}">
                  {{ $alerte->risque_coupure ? 'Élevé' : 'Faible' }}
                </strong>
              </div>
            </div>
          </div>

          <!-- Période et Source -->
          <div class="p-3 rounded-3 bg-light border d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
              <small class="text-muted d-block">Validité du bulletin :</small>
              <strong class="text-dark">
                Du {{ $alerte->date_debut ? \Carbon\Carbon::parse($alerte->date_debut)->format('d/m/Y à H:i') : 'Immédiat' }}
                au {{ $alerte->date_fin ? \Carbon\Carbon::parse($alerte->date_fin)->format('d/m/Y à H:i') : 'Indéterminé' }}
              </strong>
            </div>
            <div>
              <small class="text-muted d-block">Source officielle :</small>
              <span class="badge bg-secondary-subtle text-secondary">{{ $alerte->source ?? 'Institut National de la Météorologie (INM)' }}</span>
            </div>
          </div>

        </div>

        <!-- Carte Leaflet Géolocalisée (Périmètre Géographique Affecté) -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
          <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0" style="color: #1e3a5f;">
              <i class="bi bi-geo-alt-fill text-danger me-2"></i>Périmètre Géographique Affecté
            </h5>
            @if($alerte->zone)
              <span class="badge bg-light text-secondary border">{{ $alerte->zone->nom }} (Gvt. {{ $alerte->zone->gouvernorat }})</span>
            @endif
          </div>
          <div class="card-body p-0">
            @if($alerte->zone && $alerte->zone->latitude && $alerte->zone->longitude)
              <div id="alerte-map" style="height: 350px; width: 100%; min-height: 350px;"></div>
            @else
              <div class="p-5 text-center text-muted">
                <i class="bi bi-geo-alt-slash fs-1 d-block mb-2"></i>
                Coordonnées géographiques non disponibles pour la zone de cette alerte.
              </div>
            @endif
          </div>
        </div>

        <!-- Conseils de Santé Associés avec Liens Vers Le Show -->
        @if($alerte->conseils && $alerte->conseils->count() > 0)
          <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <h5 class="fw-bold mb-3" style="color: #1e3a5f;">
              <i class="bi bi-shield-plus text-primary me-2"></i>Conseils & Consignes Recommandés ({{ $alerte->conseils->count() }})
            </h5>
            <div class="row g-3">
              @foreach($alerte->conseils as $conseil)
                <div class="col-md-6">
                  <div class="p-3 rounded-3 border bg-light h-100 d-flex flex-column justify-content-between">
                    <div>
                      <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-primary-subtle text-primary small rounded-pill">
                          {{ ucfirst(str_replace('_',' ', $conseil->categorie)) }}
                        </span>
                      </div>
                      <h6 class="fw-bold text-dark mb-1" style="font-size: 15px;">{{ $conseil->titre }}</h6>
                      <p class="text-muted small mb-3">{{ Str::limit(strip_tags($conseil->contenu), 90) }}</p>
                    </div>
                    <div>
                      <a href="{{ route('front.conseils.show', $conseil) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                        Consulter la directive <i class="bi bi-arrow-right ms-1"></i>
                      </a>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif

      </div>

      <!-- Sidebar : Radar Chart & Urgences -->
      <div class="col-lg-4">

        <!-- Radar Chart Météorologique -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
          <h6 class="fw-bold mb-3" style="color: #1e3a5f;">
            <i class="bi bi-radar text-danger me-2"></i>Radar des Facteurs de Risque
          </h6>
          <div style="height: 250px; position: relative;">
            <canvas id="meteoRadarChart"></canvas>
          </div>
          <small class="text-muted text-center d-block mt-2">
            Analyse multifactorielle de l'intensité thermique
          </small>
        </div>

        <!-- Boîte d'Urgence (Clinic Template) -->
        <div class="card border-0 shadow-sm rounded-4 p-4 text-white mb-4" style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center bg-white text-danger shadow" style="width: 44px; height: 44px; font-size: 20px;">
              <i class="bi bi-telephone-outbound-fill"></i>
            </div>
            <div>
              <h5 class="text-white fw-bold mb-0">Lignes d'Urgence</h5>
              <small class="text-white-50">Secours médicaux gratuits 24h/24</small>
            </div>
          </div>
          <p class="small text-white-50 mb-3">
            En cas de malaise grave ou de symptômes de coup de chaleur dus à cette vigilance, contactez immédiatement les secours :
          </p>
          <div class="d-grid gap-2">
            <a href="tel:198" class="btn btn-light text-danger fw-bold rounded-pill shadow-sm">
              <i class="bi bi-telephone-fill me-2"></i>Protection Civile : 198
            </a>
            <a href="tel:190" class="btn btn-outline-light rounded-pill">
              <i class="bi bi-telephone me-2"></i>SAMU Tunisie : 190
            </a>
          </div>
        </div>

        <!-- Retour aux Alertes -->
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
          <a href="{{ route('front.alertes.index') }}" class="btn btn-outline-secondary rounded-pill w-100 fw-semibold">
            <i class="bi bi-arrow-left me-1"></i>Retour à la liste des alertes
          </a>
        </div>

      </div>

    </div>

  </div>
</section>
@endsection
