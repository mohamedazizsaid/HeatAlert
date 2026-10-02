@extends('layouts.front.front')

@section('content')
{{-- ═══════════════════════════════════════════════════════
     PAGE ZONES — LISTE DES ZONES DE SURVEILLANCE
═══════════════════════════════════════════════════════ --}}

<!-- Page Title avec image de fond contextuelle et cache sombre élégant -->
<div class="page-title position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.84) 0%, rgba(30, 58, 95, 0.82) 100%), url('{{ asset('assets/front/img/health/facilities-9.webp') }}') center/cover no-repeat; padding-top: 135px; padding-bottom: 45px; border-bottom: 1px solid rgba(255,255,255,0.1);">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill bg-primary text-white small fw-bold shadow-sm">
            <i class="bi bi-geo-alt-fill"></i>
            <span>Réseau National de Surveillance Thermique</span>
          </div>
          <h1 class="heading-title text-white" style="font-weight: 800; font-size: 36px; letter-spacing: -0.5px;">
            Zones de Surveillance Météorologique
          </h1>
          <p class="mb-0" style="font-size: 16px; color: #f1f5f9; max-width: 680px; margin: 0 auto; line-height: 1.6;">
            Consultez en direct les indicateurs météo, l'état de vigilance canicule et les alertes thermiques par zone géographique en Tunisie.
          </p>


        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs mt-4" style="background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(8px); border-top: 1px solid rgba(255,255,255,0.1);">
    <div class="container">
      <ol class="mb-0">
        <li><a href="{{ route('front.home') }}" class="text-white-50">Accueil</a></li>
        <li class="current text-white fw-semibold">Zones de Surveillance</li>
      </ol>
    </div>
  </nav>
</div><!-- End Page Title -->

<!-- Barre de Recherche & Filtres 100% Smooth (Instantané côté client sans rechargement de page) -->
<section class="py-4 bg-white border-bottom shadow-sm">
  <div class="container">
    <div class="row g-3 align-items-center">

      <!-- Recherche Textuelle en direct -->
      <div class="col-lg-5 col-md-6">
        <div class="input-group">
          <span class="input-group-text bg-light border-end-0 text-muted">
            <i class="bi bi-search"></i>
          </span>
          <input type="text" id="zonesSearchInput" class="form-control bg-light border-start-0 ps-0" placeholder="Filtrer en direct (zone, ville, code postal...)" autocomplete="off">
          <button class="btn btn-light border-start-0 text-muted" type="button" id="clearZonesSearchBtn" style="display: none;" onclick="clearZonesSearch()">
            <i class="bi bi-x-circle"></i>
          </button>
        </div>
      </div>

      <!-- Filtre Gouvernorat -->
      <div class="col-lg-3 col-md-6">
        <select id="filterGouvernorat" class="form-select bg-light">
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
        <select id="filterStatutAlerte" class="form-select bg-light">
          <option value="">Tous les statuts de vigilance</option>
          <option value="avec_alertes" {{ request('statut_alerte') == 'avec_alertes' ? 'selected' : '' }}>🚨 Avec alerte(s) active(s)</option>
          <option value="calme" {{ request('statut_alerte') == 'calme' ? 'selected' : '' }}>✅ Situation calme</option>
        </select>
      </div>

      <!-- Réinitialisation instantanée -->
      <div class="col-lg-1 col-md-6 d-flex justify-content-end">
        <button type="button" class="btn btn-outline-danger w-100 rounded-pill" onclick="resetZonesFilters()" title="Effacer tous les filtres">
          <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
        </button>
      </div>

    </div>
  </div>
</section>

<!-- Grille des Zones (Clinic Departments Section Style) -->
<section id="departments" class="departments section py-5" style="background-color: #f8fafc;">
  <div class="container" data-aos="fade-up">

    {{-- Bannière spéciale Météo Mondiale Open-Meteo --}}
    <div class="mb-4 p-4 rounded-4 shadow-sm position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%); color: #fff; border: 1px solid rgba(255,255,255,0.1);">
      <div class="row align-items-center g-3 position-relative" style="z-index: 2;">
        <div class="col-lg-8">
          <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-danger rounded-pill px-3 py-1 fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Nouveau Service Global</span>
            <span class="badge bg-warning text-dark rounded-pill px-2 py-1 fw-bold" style="font-size: 11px;">API Open-Meteo</span>
          </div>
          <h4 class="fw-bold mb-1 text-white">Surveillance Météo Mondiale en Temps Réel</h4>
          <p class="mb-0 text-white-50 small" style="line-height: 1.5;">
            Explorez les températures, indices UV solaires, alertes de canicule et graphiques prévisionnels détaillés pour n'importe quelle ville ou pays dans le monde via la carte interactive.
          </p>
        </div>
        <div class="col-lg-4 text-lg-end">
          <a href="{{ route('front.weather.global') }}" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2 shadow-sm d-inline-flex align-items-center gap-2" style="font-size: 14px;">
            <i class="bi bi-globe2 fs-5"></i>
            <span>Accéder au Dashboard Mondial</span>
            <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>
      <div class="position-absolute end-0 top-0 bottom-0 opacity-10 d-none d-md-flex align-items-center pe-4 pointer-events-none" style="font-size: 9rem; color: #fff;">
        <i class="bi bi-globe-americas"></i>
      </div>
    </div>

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

    <div class="row g-4" id="zonesContainer">
      @forelse($zones as $index => $zone)
        @php
          $hasAlert = $zone->alertes_actives_count > 0;
          $img = $zoneImages[$index % count($zoneImages)];
          $searchData = strtolower($zone->nom . ' ' . $zone->ville . ' ' . ($zone->gouvernorat ?? '') . ' ' . ($zone->code_postal ?? '') . ' ' . ($zone->description ?? ''));
        @endphp
        <div class="col-lg-4 col-md-6 zone-item"
             data-search="{{ $searchData }}"
             data-gouvernorat="{{ strtolower($zone->gouvernorat ?? '') }}"
             data-has-alert="{{ $hasAlert ? '1' : '0' }}"
             style="transition: all 0.25s ease;">
          <div class="department-card h-100 shadow-sm bg-white border-0 rounded-4 overflow-hidden position-relative"
               style="transition: all 0.3s ease;"
               onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 12px 30px rgba(0,0,0,0.1)';"
               onmouseout="this.style.transform='';this.style.boxShadow='';">

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
            <h5 class="mt-3 text-secondary">Aucune zone enregistrée pour le moment.</h5>
          </div>
        </div>
      @endforelse

      <!-- Message Aucun Résultat en direct -->
      <div id="noZonesResults" class="col-12 text-center py-5" style="display: none;">
        <div class="p-5 rounded-4 bg-white shadow-sm border d-inline-block">
          <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
          <h5 class="mt-3 text-secondary">Aucune zone ne correspond à votre recherche.</h5>
          <button type="button" class="btn btn-outline-danger rounded-pill mt-2" onclick="resetZonesFilters()">
            Effacer la recherche
          </button>
        </div>
      </div>
    </div>

    <!-- Pagination Personnalisée -->
    @if($zones->hasPages())
      <div class="d-flex justify-content-center mt-5" id="zonesPaginationNav">
        {{ $zones->links('front.components.pagination') }}
      </div>
    @endif

  </div>{{-- End container --}}
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('zonesSearchInput');
    const filterGouvernorat = document.getElementById('filterGouvernorat');
    const filterStatutAlerte = document.getElementById('filterStatutAlerte');
    const clearBtn = document.getElementById('clearZonesSearchBtn');

    function applyZonesFilters() {
        const q = searchInput.value.trim().toLowerCase();
        const gvt = filterGouvernorat.value.toLowerCase();
        const statut = filterStatutAlerte.value;

        if (clearBtn) {
            clearBtn.style.display = q ? 'block' : 'none';
        }

        let visibleCount = 0;
        const items = document.querySelectorAll('.zone-item');

        items.forEach(el => {
            const text = el.getAttribute('data-search') || '';
            const elGvt = el.getAttribute('data-gouvernorat') || '';
            const elHasAlert = el.getAttribute('data-has-alert') || '0';

            const matchQ = !q || text.includes(q);
            const matchGvt = !gvt || elGvt === gvt;
            let matchStatut = true;
            if (statut === 'avec_alertes') {
                matchStatut = elHasAlert === '1';
            } else if (statut === 'calme') {
                matchStatut = elHasAlert === '0';
            }

            if (matchQ && matchGvt && matchStatut) {
                el.style.display = '';
                el.style.opacity = '1';
                visibleCount++;
            } else {
                el.style.display = 'none';
            }
        });

        const noResults = document.getElementById('noZonesResults');
        if (noResults) {
            noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        }

        const paginationNav = document.getElementById('zonesPaginationNav');
        if (paginationNav) {
            paginationNav.style.display = (q || gvt || statut) ? 'none' : '';
        }
    }

    searchInput.addEventListener('input', applyZonesFilters);
    filterGouvernorat.addEventListener('change', applyZonesFilters);
    filterStatutAlerte.addEventListener('change', applyZonesFilters);

    window.clearZonesSearch = function() {
        searchInput.value = '';
        applyZonesFilters();
    };

    window.resetZonesFilters = function() {
        searchInput.value = '';
        filterGouvernorat.value = '';
        filterStatutAlerte.value = '';
        applyZonesFilters();
    };

    // Initial check
    applyZonesFilters();
});
</script>
@endpush
@endsection
