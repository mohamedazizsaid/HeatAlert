@extends('layouts.front.front')

@section('content')
{{-- ═══════════════════════════════════════════════════════
     PAGE ZONES — LISTE DES ZONES DE SURVEILLANCE
═══════════════════════════════════════════════════════ --}}

<!-- Page Title avec image de fond contextuelle et overlay blanc épuré -->
<div class="page-title position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(255,255,255,0.96) 0%, rgba(255,255,255,0.88) 60%, rgba(255,255,255,0.72) 100%), url('{{ asset('assets/front/img/health/facilities-9.webp') }}') center/cover no-repeat; padding-top: 130px; padding-bottom: 35px; border-bottom: 1px solid #e2e8f0;">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill bg-primary-subtle text-primary border small fw-bold">
            <i class="bi bi-geo-alt-fill"></i>
            <span>Réseau National de Surveillance Thermique</span>
          </div>
          <h1 class="heading-title" style="color: #1e3a5f; font-weight: 800; font-size: 34px;">
            Zones de Surveillance Météorologique
          </h1>
          <p class="mb-0 text-muted" style="font-size: 15px; max-width: 680px; margin: 0 auto;">
            Consultez en direct les indicateurs météo, l'état de vigilance canicule et les alertes thermiques par zone géographique en Tunisie.
          </p>
        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs mt-3" style="background: rgba(255,255,255,0.65); backdrop-filter: blur(4px);">
    <div class="container">
      <ol>
        <li><a href="{{ route('front.home') }}">Accueil</a></li>
        <li class="current">Zones de Surveillance</li>
      </ol>
    </div>
  </nav>
</div><!-- End Page Title -->

<!-- Barre de Recherche & Filtres Avancés -->
<section class="py-4 bg-white border-bottom shadow-sm">
  <div class="container">
    <form method="GET" action="{{ route('front.zones.index') }}" class="row g-3 align-items-center">

      <!-- Recherche Textuelle -->
      <div class="col-lg-4 col-md-6">
        <div class="input-group">
          <span class="input-group-text bg-light border-end-0 text-muted">
            <i class="bi bi-search"></i>
          </span>
          <input type="text" name="search" class="form-control bg-light border-start-0 ps-0" placeholder="Rechercher une zone, ville, code postal..." value="{{ request('search') }}">
        </div>
      </div>

      <!-- Filtre Gouvernorat -->
      <div class="col-lg-3 col-md-6">
        <select name="gouvernorat" class="form-select bg-light">
          <option value="">Tous les Gouvernorats ({{ $gouvernorats->count() }})</option>
          @foreach($gouvernorats as $gvt)
            <option value="{{ $gvt }}" {{ request('gouvernorat') == $gvt ? 'selected' : '' }}>
              {{ $gvt }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Filtre Statut Alerte -->
      <div class="col-lg-3 col-md-6">
        <select name="statut_alerte" class="form-select bg-light">
          <option value="">Tous les statuts de vigilance</option>
          <option value="avec_alertes" {{ request('statut_alerte') == 'avec_alertes' ? 'selected' : '' }}>🚨 Avec alerte(s) active(s)</option>
          <option value="calme" {{ request('statut_alerte') == 'calme' ? 'selected' : '' }}>✅ Situation calme</option>
        </select>
      </div>

      <!-- Actions : Filtrer & Réinitialiser -->
      <div class="col-lg-2 col-md-6 d-flex gap-2">
        <button type="submit" class="btn btn-danger w-100 rounded-pill fw-semibold shadow-sm">
          <i class="bi bi-funnel-fill me-1"></i>Filtrer
        </button>
        @if(request('search') || request('gouvernorat') || request('statut_alerte'))
          <a href="{{ route('front.zones.index') }}" class="btn btn-outline-secondary rounded-pill" title="Réinitialiser les filtres">
            <i class="bi bi-arrow-counterclockwise"></i>
          </a>
        @endif
      </div>

    </form>
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

            <!-- Statut Badge Icon -->
            <div class="department-icon shadow-sm" style="background: {{ $hasAlert ? '#fee2e2' : '#dcfce7' }}; color: {{ $hasAlert ? '#dc2626' : '#16a34a' }};">
              <i class="bi {{ $hasAlert ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill' }}"></i>
            </div>

            <!-- Image de couverture avec transition zoom -->
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
                    <i class="bi bi-exclamation-circle-fill me-1"></i>{{ $zone->alertes_actives_count }} alerte(s)
                  </span>
                @else
                  <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 small">
                    <i class="bi bi-check-circle-fill me-1"></i>Calme
                  </span>
                @endif
              </div>

              <h3 class="mb-2" style="font-size: 19px; font-weight: 700;">
                <a href="{{ route('front.zones.show', $zone) }}" class="text-decoration-none text-dark">
                  {{ $zone->nom }}
                </a>
              </h3>

              <p class="text-muted small mb-3 flex-grow-1" style="line-height: 1.6;">
                {{ $zone->description ?? 'Zone de surveillance météorologique reliée au réseau HeatAlert.' }}
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
            <i class="bi bi-geo-alt text-muted" style="font-size: 3rem;"></i>
            <h5 class="mt-3 text-secondary">Aucune zone ne correspond à vos critères de recherche.</h5>
            <a href="{{ route('front.zones.index') }}" class="btn btn-outline-primary rounded-pill mt-2">
              Réinitialiser la recherche
            </a>
          </div>
        </div>
      @endforelse
    </div>

    <!-- Pagination Personnalisée Sans Bug -->
    @if($zones->hasPages())
      <div class="d-flex justify-content-center mt-5">
        {{ $zones->links('front.components.pagination') }}
      </div>
    @endif

  </div>
</section>
@endsection
