@extends('layouts.front.front')

@push('scripts')
{{-- Leaflet.js pour la carte --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV/XN/WPdg=" crossorigin=""></script>
{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($zone->latitude && $zone->longitude)
    // ─── Leaflet Map ───
    const map = L.map('zone-map', { zoomControl: true, scrollWheelZoom: false }).setView([{{ $zone->latitude }}, {{ $zone->longitude }}], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(map);

    const hasActiveAlert = {{ $zone->alertes_actives_count > 0 ? 'true' : 'false' }};
    const markerColor = hasActiveAlert ? '#dc2626' : '#16a34a';

    const customIcon = L.divIcon({
        className: '',
        html: `<div style="width:44px;height:44px;border-radius:50%;background:${markerColor};border:3px solid #fff;box-shadow:0 4px 15px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem;">
                 <i class="bi bi-geo-alt-fill"></i>
               </div>`,
        iconSize: [44, 44],
        iconAnchor: [22, 44],
        popupAnchor: [0, -45]
    });

    L.marker([{{ $zone->latitude }}, {{ $zone->longitude }}], { icon: customIcon })
        .addTo(map)
        .bindPopup(`<div style="min-width:180px;padding:6px;">
            <strong style="font-size:1rem;color:#1e3a5f;">{{ $zone->nom }}</strong><br>
            <span style="color:#64748b;font-size:.85rem;">{{ $zone->ville }}{{ $zone->gouvernorat ? ', Gvt. '.$zone->gouvernorat : '' }}</span>
            @if($zone->alertes_actives_count > 0)
            <br><span style="color:#dc2626;font-weight:700;font-size:.82rem;"><i class="bi bi-exclamation-triangle"></i> {{ $zone->alertes_actives_count }} alerte(s) active(s)</span>
            @endif
        </div>`)
        .openPopup();

    L.circle([{{ $zone->latitude }}, {{ $zone->longitude }}], {
        radius: 12000,
        color: markerColor,
        fillColor: markerColor,
        fillOpacity: 0.08,
        weight: 2,
        dashArray: '6,4'
    }).addTo(map);
    @endif

    // ─── Chart Niveaux ───
    const ctx = document.getElementById('niveauxChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Rouge', 'Orange', 'Jaune', 'Vert'],
                datasets: [{
                    data: [{{ $niveauxData['rouge'] }}, {{ $niveauxData['orange'] }}, {{ $niveauxData['jaune'] }}, {{ $niveauxData['vert'] }}],
                    backgroundColor: ['#dc2626', '#ea580c', '#d97706', '#16a34a'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10 } },
                    tooltip: { callbacks: { label: (c) => ` ${c.label} : ${c.parsed} alerte(s)` } }
                }
            }
        });
    }
});
</script>
@endpush

@section('content')
{{-- ═══════════════════════════════════════════════════════
     PAGE ZONE — DÉTAILS
═══════════════════════════════════════════════════════ --}}

<!-- Page Title (Standard Template Header Blanc) -->
<div class="page-title">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill bg-light text-secondary border small fw-bold">
            <i class="bi bi-geo-alt-fill text-danger"></i>
            <span>Gouvernorat de {{ $zone->gouvernorat ?? 'Tunisie' }}</span>
          </div>
          <h1 class="heading-title" style="color: #1e3a5f; font-weight: 700;">Zone {{ $zone->nom }}</h1>
          <p class="mb-0 text-muted" style="font-size: 15px;">
            Données de surveillance météorologique en temps réel, cartographie des risques et historique des alertes de chaleur.
          </p>
        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs">
    <div class="container">
      <ol>
        <li><a href="{{ route('front.home') }}">Accueil</a></li>
        <li><a href="{{ route('front.zones.index') }}">Zones de Surveillance</a></li>
        <li class="current">{{ $zone->nom }}</li>
      </ol>
    </div>
  </nav>
</div><!-- End Page Title -->

<!-- Department Details Section (Clinic Template Style) -->
<section id="department-details" class="department-details section py-5" style="background-color: #f8fafc;">
  <div class="container" data-aos="fade-up">

    <!-- Key Highlights (Clinic Template Style) -->
    <div class="row g-4 mb-5">
      <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white text-center h-100">
          <div class="text-muted small text-uppercase fw-semibold mb-1">Statut Actuel</div>
          @if($zone->alertes_actives_count > 0)
            <div class="fs-3 fw-bold text-danger">{{ $zone->alertes_actives_count }} Alerte(s)</div>
            <div class="small text-danger fw-semibold"><i class="bi bi-exclamation-triangle-fill me-1"></i>Vigilance Requise</div>
          @else
            <div class="fs-3 fw-bold text-success">Calme</div>
            <div class="small text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i>Situation Normale</div>
          @endif
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white text-center h-100">
          <div class="text-muted small text-uppercase fw-semibold mb-1">Alertes Totales</div>
          <div class="fs-3 fw-bold text-primary">{{ $zone->alertes_count }}</div>
          <div class="small text-muted">Historique complet</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white text-center h-100">
          <div class="text-muted small text-uppercase fw-semibold mb-1">Localisation</div>
          <div class="fs-4 fw-bold text-dark text-truncate">{{ $zone->ville }}</div>
          <div class="small text-muted">Code: {{ $zone->code_postal ?? 'N/D' }}</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white text-center h-100">
          <div class="text-muted small text-uppercase fw-semibold mb-1">Coordonnées GPS</div>
          @if($zone->latitude && $zone->longitude)
            <div class="fs-5 fw-bold text-secondary">{{ number_format($zone->latitude, 3) }}, {{ number_format($zone->longitude, 3) }}</div>
            <div class="small text-success"><i class="bi bi-geo-fill me-1"></i>Géolocalisé</div>
          @else
            <div class="fs-5 fw-bold text-muted">Non définies</div>
            <div class="small text-muted">Estimation régionale</div>
          @endif
        </div>
      </div>
    </div>

    <div class="row g-4">

      <!-- Carte Leaflet & Graphique -->
      <div class="col-lg-8">
        <!-- Carte Interactive -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
          <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0" style="color: #1e3a5f;">
              <i class="bi bi-map-fill text-danger me-2"></i>Carte de Surveillance en Temps Réel
            </h5>
            <span class="badge bg-light text-secondary border">Rayon de surveillance : 12 km</span>
          </div>
          <div class="card-body p-0">
            @if($zone->latitude && $zone->longitude)
              <div id="zone-map" style="height: 380px; width: 100%;"></div>
            @else
              <div class="p-5 text-center text-muted">
                <i class="bi bi-geo-alt-slash fs-1 d-block mb-2"></i>
                Coordonnées GPS non disponibles pour cette zone.
              </div>
            @endif
          </div>
        </div>

        <!-- Alertes pour cette zone -->
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
          <h5 class="fw-bold mb-3" style="color: #1e3a5f;">
            <i class="bi bi-bell-fill text-danger me-2"></i>Bulletins Météo & Alertes de la Zone ({{ $alertes->total() }})
          </h5>

          @forelse($alertes as $alerte)
            @php
              $nColors = ['rouge'=>['#dc2626','#fee2e2'],'orange'=>['#ea580c','#ffedd5'],'jaune'=>['#d97706','#fef3c7'],'vert'=>['#16a34a','#dcfce7']];
              $nc = $nColors[$alerte->niveau] ?? ['#64748b','#f1f5f9'];
            @endphp
            <div class="p-3 rounded-3 border mb-3 transition" style="background: #fafafa;"
                 onmouseover="this.style.background='#fff';this.style.boxShadow='0 4px 15px rgba(0,0,0,0.06)';"
                 onmouseout="this.style.background='#fafafa';this.style.boxShadow='';">
              <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap mb-2">
                <div class="d-flex align-items-center gap-2">
                  <span class="badge rounded-pill px-3 py-1 text-uppercase fw-bold" style="background: {{ $nc[1] }}; color: {{ $nc[0] }}; font-size: 11px;">
                    Vigilance {{ $alerte->niveau }}
                  </span>
                  <span class="badge bg-light text-secondary border small">
                    {{ ucfirst(str_replace('_',' ', $alerte->type)) }}
                  </span>
                  @if($alerte->statut === 'active')
                    <span class="badge bg-danger rounded-pill px-2 py-1 small">Active</span>
                  @endif
                </div>
                <small class="text-muted">
                  <i class="bi bi-clock me-1"></i>Du {{ $alerte->date_debut ? \Carbon\Carbon::parse($alerte->date_debut)->format('d/m/Y H:i') : 'N/D' }} au {{ $alerte->date_fin ? \Carbon\Carbon::parse($alerte->date_fin)->format('d/m/Y H:i') : 'N/D' }}
                </small>
              </div>

              <h6 class="fw-bold mb-1" style="color: #1e3a5f; font-size: 16px;">
                <a href="{{ route('front.alertes.show', $alerte) }}" class="text-decoration-none text-dark">
                  {{ $alerte->titre }}
                </a>
              </h6>
              <p class="text-muted small mb-3">{{ Str::limit($alerte->description, 130) }}</p>

              <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                <div class="d-flex gap-3 small text-secondary">
                  @if($alerte->temperature_max)
                    <span><i class="bi bi-thermometer-sun text-danger me-1"></i>Max: <strong>{{ $alerte->temperature_max }}°C</strong></span>
                  @endif
                  @if($alerte->indice_uv)
                    <span><i class="bi bi-sun text-warning me-1"></i>UV: <strong>{{ $alerte->indice_uv }}</strong></span>
                  @endif
                </div>
                <a href="{{ route('front.alertes.show', $alerte) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                  Détails du bulletin <i class="bi bi-arrow-right ms-1"></i>
                </a>
              </div>
            </div>
          @empty
            <div class="text-center py-4 text-muted">
              <i class="bi bi-shield-check text-success fs-1 d-block mb-2"></i>
              Aucune alerte enregistrée pour cette zone. La situation est calme.
            </div>
          @endforelse

          @if($alertes->hasPages())
            <div class="d-flex justify-content-center mt-3">
              {{ $alertes->links('pagination::bootstrap-5') }}
            </div>
          @endif
        </div>
      </div>

      <!-- Sidebar Donut Chart & Urgences -->
      <div class="col-lg-4">

        <!-- Répartition des Alertes (Chart.js) -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
          <h6 class="fw-bold mb-3" style="color: #1e3a5f;">
            <i class="bi bi-pie-chart-fill text-primary me-2"></i>Répartition par Niveau
          </h6>
          <div style="height: 220px; position: relative;">
            <canvas id="niveauxChart"></canvas>
          </div>
          <div class="row g-2 mt-3 text-center small">
            <div class="col-3"><span class="badge bg-danger w-100 py-1">{{ $niveauxData['rouge'] }} Rouge</span></div>
            <div class="col-3"><span class="badge w-100 py-1" style="background: #ea580c;">{{ $niveauxData['orange'] }} Oran.</span></div>
            <div class="col-3"><span class="badge bg-warning w-100 py-1 text-dark">{{ $niveauxData['jaune'] }} Jaun.</span></div>
            <div class="col-3"><span class="badge bg-success w-100 py-1">{{ $niveauxData['vert'] }} Vert</span></div>
          </div>
        </div>

        <!-- Carte d'Urgence (Clinic Template Style) -->
        <div class="card border-0 shadow-sm rounded-4 p-4 text-white mb-4" style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center bg-white text-danger shadow" style="width: 44px; height: 44px; font-size: 20px;">
              <i class="bi bi-telephone-outbound-fill"></i>
            </div>
            <div>
              <h5 class="text-white fw-bold mb-0">Assistance Médicale</h5>
              <small class="text-white-50">Intervention rapide en Tunisie</small>
            </div>
          </div>
          <p class="small text-white-50 mb-3">
            Pour tout malaise lié à la chaleur dans le gouvernorat de {{ $zone->gouvernorat ?? 'votre région' }}, appelez les numéros d'urgence :
          </p>
          <div class="d-grid gap-2">
            <a href="tel:198" class="btn btn-light text-danger fw-bold rounded-pill">
              <i class="bi bi-telephone-fill me-2"></i>Protection Civile : 198
            </a>
            <a href="tel:190" class="btn btn-outline-light rounded-pill">
              <i class="bi bi-telephone me-2"></i>SAMU : 190
            </a>
          </div>
        </div>

        <!-- Liens rapides -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
          <h6 class="fw-bold mb-3" style="color: #1e3a5f;">
            <i class="bi bi-lightbulb-fill text-warning me-2"></i>Conseils Recommandés
          </h6>
          <p class="small text-muted mb-3">
            Adoptez les bons réflexes face aux températures élevées de votre gouvernorat.
          </p>
          <a href="{{ route('front.conseils.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill w-100 fw-semibold">
            Consulter le guide des gestes de prévention <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>

      </div>

    </div>

  </div>
</section>
@endsection
