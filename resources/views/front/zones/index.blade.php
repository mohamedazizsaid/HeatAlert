@extends('layouts.front.front')

@section('content')
{{-- ═══════════════════════════════════════════════════════
     PAGE ZONES — LISTE DES ZONES DE SURVEILLANCE
═══════════════════════════════════════════════════════ --}}

<!-- Page Title (Standard Template Header Blanc) -->
<div class="page-title">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <h1 class="heading-title" style="color: #1e3a5f; font-weight: 700;">Zones de Surveillance Météorologique</h1>
          <p class="mb-0 text-muted" style="font-size: 15px;">
            Réseau national de surveillance des canicules et vagues de chaleur à travers les 24 gouvernorats tunisiens.
          </p>
        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs">
    <div class="container">
      <ol>
        <li><a href="{{ route('front.home') }}">Accueil</a></li>
        <li class="current">Zones de Surveillance</li>
      </ol>
    </div>
  </nav>
</div><!-- End Page Title -->

<!-- Stats Rapides / Compteurs PureCounter (Clinic Style) -->
<section class="py-4 border-bottom bg-white">
  <div class="container">
    <div class="row g-3 text-center">
      <div class="col-6 col-md-3">
        <div class="p-3 rounded-3 bg-light">
          <div class="fs-2 fw-bold text-primary">{{ $zones->total() }}</div>
          <div class="small text-muted text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Zones Actives</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="p-3 rounded-3 bg-light">
          <div class="fs-2 fw-bold text-danger">{{ $zones->sum('alertes_actives_count') }}</div>
          <div class="small text-muted text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Alertes en Cours</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="p-3 rounded-3 bg-light">
          <div class="fs-2 fw-bold text-success">24</div>
          <div class="small text-muted text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Gouvernorats Couverts</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="p-3 rounded-3 bg-light">
          <div class="fs-2 fw-bold text-warning">24/7</div>
          <div class="small text-muted text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Veille Permanente</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Grille des Zones (Clinic Departments Section Style) -->
<section id="departments" class="departments section py-5" style="background-color: #f8fafc;">
  <div class="container" data-aos="fade-up">

    @php
      $zoneImages = [
        'assets/front/img/health/facilities-6.webp',
        'assets/front/img/health/facilities-9.webp',
        'assets/front/img/health/emergency-1.webp',
        'assets/front/img/health/consultation-4.webp',
        'assets/front/img/health/cardiology-3.webp',
        'assets/front/img/health/neurology-2.webp',
      ];
    @endphp

    <div class="row g-4">
      @forelse($zones as $index => $zone)
        @php
          $hasAlert = $zone->alertes_actives_count > 0;
          $img = $zoneImages[$index % count($zoneImages)];
        @endphp
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
          <div class="department-card h-100 shadow-sm bg-white border-0 rounded-4 overflow-hidden position-relative">

            <!-- Statut Badge -->
            <div class="department-icon shadow-sm" style="background: {{ $hasAlert ? '#fee2e2' : '#dcfce7' }}; color: {{ $hasAlert ? '#dc2626' : '#16a34a' }};">
              <i class="bi {{ $hasAlert ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill' }}"></i>
            </div>

            <!-- Image de couverture -->
            <div class="department-image" style="height: 180px; overflow: hidden;">
              <img src="{{ asset($img) }}" alt="{{ $zone->nom }}" class="w-100 h-100 object-fit-cover transition">
            </div>

            <!-- Contenu de la zone -->
            <div class="department-content p-4 d-flex flex-column flex-grow-1">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="badge rounded-pill px-3 py-1" style="background: #e2e8f0; color: #334155; font-size: 11px;">
                  <i class="bi bi-geo-alt me-1"></i>Gvt. {{ $zone->gouvernorat ?? 'Tunisie' }}
                </span>
                @if($hasAlert)
                  <span class="badge bg-danger rounded-pill px-2 py-1 small">
                    {{ $zone->alertes_actives_count }} alerte(s)
                  </span>
                @else
                  <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 small">
                    Calme
                  </span>
                @endif
              </div>

              <h3 class="mb-2" style="font-size: 19px; font-weight: 700;">
                <a href="{{ route('front.zones.show', $zone) }}" class="text-decoration-none text-dark">
                  {{ $zone->nom }}
                </a>
              </h3>

              <p class="text-muted small mb-3 flex-grow-1" style="line-height: 1.6;">
                {{ $zone->description ?? 'Zone météo sous surveillance constante du réseau HeatAlert en Tunisie.' }}
              </p>

              <!-- Métadonnées & Coordonnées -->
              <div class="py-2 px-3 rounded-3 bg-light mb-3 d-flex justify-content-between align-items-center small text-secondary">
                <span><i class="bi bi-bell me-1"></i>{{ $zone->alertes_count }} alerte(s) totale(s)</span>
                @if($zone->latitude && $zone->longitude)
                  <span><i class="bi bi-compass me-1"></i>{{ number_format($zone->latitude, 2) }}, {{ number_format($zone->longitude, 2) }}</span>
                @endif
              </div>

              <!-- Lien Détails -->
              <div class="pt-2 border-top">
                <a href="{{ route('front.zones.show', $zone) }}" class="learn-more d-inline-flex align-items-center text-decoration-none fw-bold" style="color: #dc2626;">
                  <span>Consulter la carte & alertes</span>
                  <i class="bi bi-arrow-right ms-2"></i>
                </a>
              </div>

            </div>

          </div>
        </div>
      @empty
        <div class="col-12 text-center py-5">
          <div class="p-5 rounded-4 bg-white shadow-sm border d-inline-block">
            <i class="bi bi-map text-muted" style="font-size: 3rem;"></i>
            <h5 class="mt-3 text-secondary">Aucune zone enregistrée pour le moment.</h5>
          </div>
        </div>
      @endforelse
    </div>

    <!-- Pagination -->
    @if($zones->hasPages())
      <div class="d-flex justify-content-center mt-5">
        {{ $zones->links('pagination::bootstrap-5') }}
      </div>
    @endif

  </div>
</section>
@endsection
