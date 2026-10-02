@extends('layouts.front.front')

@section('content')
{{-- ═══════════════════════════════════════════════════════
     PAGE ALERTES — LISTE DES BULLETINS DE VIGILANCE
═══════════════════════════════════════════════════════ --}}

<!-- Page Title avec image de fond contextuelle et cache sombre élégant -->
<div class="page-title position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.84) 0%, rgba(127, 29, 29, 0.82) 100%), url('{{ asset('assets/front/img/health/emergency-1.webp') }}') center/cover no-repeat; padding-top: 135px; padding-bottom: 45px; border-bottom: 1px solid rgba(255,255,255,0.1);">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill bg-danger text-white small fw-bold shadow-sm">
            <i class="bi bi-broadcast"></i>
            <span>Système d'Alerte Précoce & Vigilance Thermique</span>
          </div>
          <h1 class="heading-title text-white" style="font-weight: 800; font-size: 36px; letter-spacing: -0.5px;">
            Alertes Météo & Vigilance Canicule
          </h1>
          <p class="mb-0" style="font-size: 16px; color: #f1f5f9; max-width: 680px; margin: 0 auto; line-height: 1.6;">
            Suivez en temps réel les bulletins de canicule, les niveaux de vigilance par région et les prévisions de risques thermiques en Tunisie.
          </p>
        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs mt-4" style="background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(8px); border-top: 1px solid rgba(255,255,255,0.1);">
    <div class="container">
      <ol class="mb-0">
        <li><a href="{{ route('front.home') }}" class="text-white-50">Accueil</a></li>
        <li class="current text-white fw-semibold">Alertes Météo</li>
      </ol>
    </div>
  </nav>
</div><!-- End Page Title -->

<!-- Stats Niveaux de Vigilance -->
<section class="py-4 border-bottom bg-white">
  <div class="container">
    <div class="row g-3 text-center">
      <div class="col-6 col-md-3">
        <a href="javascript:void(0)" onclick="quickFilterNiveau('rouge')" class="text-decoration-none d-block">
          <div class="p-3 rounded-4 transition shadow-sm" style="background: #fee2e2; border: 1px solid #fca5a5; transition: all 0.2s;">
            <div class="fs-2 fw-bold text-danger">{{ $statsNiveaux['rouge'] ?? 0 }}</div>
            <div class="small fw-bold text-danger text-uppercase" style="letter-spacing: 0.5px;">Vigilance Rouge</div>
          </div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="javascript:void(0)" onclick="quickFilterNiveau('orange')" class="text-decoration-none d-block">
          <div class="p-3 rounded-4 transition shadow-sm" style="background: #ffedd5; border: 1px solid #fdba74; transition: all 0.2s;">
            <div class="fs-2 fw-bold" style="color: #ea580c;">{{ $statsNiveaux['orange'] ?? 0 }}</div>
            <div class="small fw-bold text-uppercase" style="color: #ea580c; letter-spacing: 0.5px;">Vigilance Orange</div>
          </div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="javascript:void(0)" onclick="quickFilterNiveau('jaune')" class="text-decoration-none d-block">
          <div class="p-3 rounded-4 transition shadow-sm" style="background: #fef3c7; border: 1px solid #fde047; transition: all 0.2s;">
            <div class="fs-2 fw-bold" style="color: #d97706;">{{ $statsNiveaux['jaune'] ?? 0 }}</div>
            <div class="small fw-bold text-uppercase" style="color: #d97706; letter-spacing: 0.5px;">Vigilance Jaune</div>
          </div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="javascript:void(0)" onclick="quickFilterNiveau('vert')" class="text-decoration-none d-block">
          <div class="p-3 rounded-4 transition shadow-sm" style="background: #dcfce7; border: 1px solid #86efac; transition: all 0.2s;">
            <div class="fs-2 fw-bold text-success">{{ $statsNiveaux['vert'] ?? 0 }}</div>
            <div class="small fw-bold text-success text-uppercase" style="letter-spacing: 0.5px;">Situation Normale</div>
          </div>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Barre de Recherche & Filtres 100% Smooth (Instantané côté client sans rechargement de page) -->
<section class="py-4 bg-white border-bottom shadow-sm">
  <div class="container">
    <div class="row g-3 align-items-center">

      <!-- Recherche Textuelle en direct -->
      <div class="col-lg-4 col-md-6">
        <div class="input-group">
          <span class="input-group-text bg-light border-end-0 text-muted">
            <i class="bi bi-search"></i>
          </span>
          <input type="text" id="alertesSearchInput" class="form-control bg-light border-start-0 ps-0" placeholder="Filtrer en direct (titre, région...)" autocomplete="off">
          <button class="btn btn-light border-start-0 text-muted" type="button" id="clearSearchBtn" style="display: none;" onclick="clearSearchInput()">
            <i class="bi bi-x-circle"></i>
          </button>
        </div>
      </div>

      <!-- Filtre Niveau de Vigilance -->
      <div class="col-lg-2 col-md-6">
        <select id="filterNiveau" class="form-select bg-light">
          <option value="">Tous Niveaux</option>
          <option value="rouge" {{ request('niveau') == 'rouge' ? 'selected' : '' }}>🔴 Rouge (Extrême)</option>
          <option value="orange" {{ request('niveau') == 'orange' ? 'selected' : '' }}>🟠 Orange (Forte)</option>
          <option value="jaune" {{ request('niveau') == 'jaune' ? 'selected' : '' }}>🟡 Jaune (Modérée)</option>
          <option value="vert" {{ request('niveau') == 'vert' ? 'selected' : '' }}>🟢 Vert (Normal)</option>
        </select>
      </div>

      <!-- Filtre Zone Géographique -->
      <div class="col-lg-3 col-md-6">
        <select id="filterZone" class="form-select bg-light">
          <option value="">Toutes les zones géographiques</option>
          @foreach($zonesList as $z)
            <option value="{{ $z->id }}" {{ request('zone_id') == $z->id ? 'selected' : '' }}>
              {{ $z->nom }} ({{ $z->gouvernorat }})
            </option>
          @endforeach
        </select>
      </div>

      <!-- Filtre Statut de l'alerte -->
      <div class="col-lg-2 col-md-6">
        <select id="filterStatut" class="form-select bg-light">
          <option value="active" {{ request('statut', 'active') == 'active' ? 'selected' : '' }}>Actives en cours</option>
          <option value="tous" {{ request('statut') == 'tous' ? 'selected' : '' }}>Toutes les alertes</option>
          <option value="terminee" {{ request('statut') == 'terminee' ? 'selected' : '' }}>Terminées</option>
        </select>
      </div>

      <!-- Réinitialisation instantanée -->
      <div class="col-lg-1 col-md-12 d-flex justify-content-end">
        <button type="button" class="btn btn-outline-danger w-100 rounded-pill" onclick="resetAllFilters()" title="Effacer tous les filtres">
          <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
        </button>
      </div>

    </div>
  </div>
</section>

<!-- Grille des Alertes Météo (Clinic Style) -->
<section class="services section py-5" style="background-color: #f8fafc;">
  <div class="container" data-aos="fade-up">

    @php
      $niveauColors = [
        'rouge'  => ['#dc2626', '#fee2e2', 'Vigilance Rouge', 'bi-thermometer-high'],
        'orange' => ['#ea580c', '#ffedd5', 'Vigilance Orange', 'bi-thermometer-half'],
        'jaune'  => ['#d97706', '#fef3c7', 'Vigilance Jaune', 'bi-thermometer-low'],
        'vert'   => ['#16a34a', '#dcfce7', 'Situation Verte', 'bi-check-circle'],
      ];
    @endphp

    <div class="row g-4" id="alertesContainer">
      @forelse($alertes as $alerte)
        @php
          $cfg = $niveauColors[$alerte->niveau] ?? ['#64748b', '#f1f5f9', 'Vigilance ' . ucfirst($alerte->niveau), 'bi-bell'];
          $searchData = strtolower($alerte->titre . ' ' . $alerte->description . ' ' . ($alerte->zone->nom ?? '') . ' ' . ($alerte->zone->gouvernorat ?? '') . ' ' . ($alerte->zone->ville ?? ''));
        @endphp
        <div class="col-lg-4 col-md-6 alerte-item"
             data-search="{{ $searchData }}"
             data-niveau="{{ strtolower($alerte->niveau) }}"
             data-zone="{{ $alerte->zone_id }}"
             data-statut="{{ strtolower($alerte->statut) }}"
             style="transition: all 0.25s ease;">
          <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white transition position-relative"
               style="transition: all 0.3s ease;"
               onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 12px 30px rgba(0,0,0,0.1)';"
               onmouseout="this.style.transform='';this.style.boxShadow='';">

            <!-- Bandeau de couleur de niveau supérieur -->
            <div style="height: 6px; background: {{ $cfg[0] }};"></div>

            <div class="card-body p-4 d-flex flex-column">

              <!-- En-tête : Niveau & Zone -->
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge rounded-pill px-3 py-1 text-uppercase fw-bold" style="background: {{ $cfg[1] }}; color: {{ $cfg[0] }}; font-size: 11px;">
                  <i class="bi {{ $cfg[3] }} me-1"></i>{{ $cfg[2] }}
                </span>
                @if($alerte->zone)
                  <a href="{{ route('front.zones.show', $alerte->zone) }}" class="badge rounded-pill text-decoration-none px-3 py-1 bg-light text-secondary border small">
                    <i class="bi bi-geo-alt me-1 text-danger"></i>{{ $alerte->zone->nom }}
                  </a>
                @endif
              </div>

              <!-- Titre & Type -->
              <h5 class="fw-bold mb-2" style="color: #1e3a5f; font-size: 18px; line-height: 1.4;">
                <a href="{{ route('front.alertes.show', $alerte) }}" class="text-decoration-none text-dark hover-danger">
                  {{ $alerte->titre }}
                </a>
              </h5>

              <p class="text-muted small mb-3 flex-grow-1" style="line-height: 1.6;">
                {{ Str::limit($alerte->description, 130) }}
              </p>

              <!-- Paramètres Météo en Pillules -->
              <div class="row g-2 mb-3">
                @if($alerte->temperature_max)
                  <div class="col-6">
                    <div class="p-2 rounded-3 bg-light text-center small">
                      <span class="text-muted d-block" style="font-size: 11px;">Temp. Max</span>
                      <strong class="text-danger fs-6">{{ $alerte->temperature_max }}°C</strong>
                    </div>
                  </div>
                @endif
                @if($alerte->temperature_ressentie)
                  <div class="col-6">
                    <div class="p-2 rounded-3 bg-light text-center small">
                      <span class="text-muted d-block" style="font-size: 11px;">Ressentie</span>
                      <strong class="text-warning-emphasis fs-6">{{ $alerte->temperature_ressentie }}°C</strong>
                    </div>
                  </div>
                @endif
                @if($alerte->indice_uv)
                  <div class="col-6">
                    <div class="p-2 rounded-3 bg-light text-center small">
                      <span class="text-muted d-block" style="font-size: 11px;">Indice UV</span>
                      <strong class="text-dark fs-6">{{ $alerte->indice_uv }} / 12</strong>
                    </div>
                  </div>
                @endif
                @if($alerte->humidite)
                  <div class="col-6">
                    <div class="p-2 rounded-3 bg-light text-center small">
                      <span class="text-muted d-block" style="font-size: 11px;">Humidité</span>
                      <strong class="text-primary fs-6">{{ $alerte->humidite }}%</strong>
                    </div>
                  </div>
                @endif
              </div>

              <!-- Période de validité -->
              <div class="small text-muted py-2 border-top border-bottom mb-3" style="font-size: 12px;">
                <i class="bi bi-clock me-1 text-danger"></i>
                Du {{ $alerte->date_debut ? \Carbon\Carbon::parse($alerte->date_debut)->format('d/m H:i') : 'Immédiat' }}
                au {{ $alerte->date_fin ? \Carbon\Carbon::parse($alerte->date_fin)->format('d/m H:i') : 'Indéterminé' }}
              </div>

              <!-- Bouton d'action -->
              <div class="d-flex justify-content-between align-items-center">
                <span class="small fw-semibold" style="color: {{ $alerte->statut === 'active' ? '#dc2626' : '#64748b' }};">
                  <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i>{{ ucfirst($alerte->statut) }}
                </span>
                <a href="{{ route('front.alertes.show', $alerte) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold">
                  <span>Voir le bulletin</span>
                  <i class="bi bi-arrow-right ms-1"></i>
                </a>
              </div>

            </div>

          </div>
        </div>
      @empty
        <div class="col-12 text-center py-5">
          <div class="p-5 rounded-4 bg-white shadow-sm border d-inline-block">
            <i class="bi bi-shield-check text-success" style="font-size: 3rem;"></i>
            <h5 class="mt-3 text-secondary">Aucune alerte active pour le moment.</h5>
            <p class="text-muted small">Toutes les zones surveillées sont actuellement en situation normale.</p>
          </div>
        </div>
      @endforelse

      <!-- Message Aucun Résultat en direct -->
      <div id="noResultsMessage" class="col-12 text-center py-5" style="display: none;">
        <div class="p-5 rounded-4 bg-white shadow-sm border d-inline-block">
          <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
          <h5 class="mt-3 text-secondary">Aucune alerte ne correspond à votre recherche.</h5>
          <button type="button" class="btn btn-outline-danger rounded-pill mt-2" onclick="resetAllFilters()">
            Effacer la recherche
          </button>
        </div>
      </div>
    </div>

    <!-- Pagination Personnalisée -->
    @if($alertes->hasPages())
      <div class="d-flex justify-content-center mt-5" id="paginationNav">
        {{ $alertes->links('front.components.pagination') }}
      </div>
    @endif

  </div>
</section>

<!-- Call to Action Conseils & Urgences (Clinic Style) -->
<section class="py-5 bg-white border-top">
  <div class="container text-center">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="p-4 p-md-5 rounded-4 text-white shadow-sm" style="background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);">
          <i class="bi bi-heart-pulse-fill text-danger fs-1 mb-3 d-inline-block"></i>
          <h3 class="fw-bold mb-2 text-white">Protégez-vous Face à ces Alertes</h3>
          <p class="text-white-50 mb-4" style="max-width: 600px; margin: 0 auto;">
            Consultez les recommandations de santé validées par nos médecins et spécialistes pour chaque niveau d'alerte météo.
          </p>
          <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('front.conseils.index') }}" class="btn btn-warning btn-lg rounded-pill px-4 fw-bold">
              <i class="bi bi-lightbulb-fill me-2"></i>Consulter les Conseils Santé
            </a>
            <a href="tel:198" class="btn btn-outline-light btn-lg rounded-pill px-4">
              <i class="bi bi-telephone me-2"></i>Urgence 198
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('alertesSearchInput');
    const filterNiveau = document.getElementById('filterNiveau');
    const filterZone = document.getElementById('filterZone');
    const filterStatut = document.getElementById('filterStatut');
    const clearBtn = document.getElementById('clearSearchBtn');

    function applyAlerteFilters() {
        const q = searchInput.value.trim().toLowerCase();
        const niveau = filterNiveau.value.toLowerCase();
        const zoneId = filterZone.value;
        const statut = filterStatut.value.toLowerCase();

        if (clearBtn) {
            clearBtn.style.display = q ? 'block' : 'none';
        }

        let visibleCount = 0;
        const items = document.querySelectorAll('.alerte-item');

        items.forEach(el => {
            const text = el.getAttribute('data-search') || '';
            const elNiveau = el.getAttribute('data-niveau') || '';
            const elZone = el.getAttribute('data-zone') || '';
            const elStatut = el.getAttribute('data-statut') || '';

            const matchQ = !q || text.includes(q);
            const matchNiveau = !niveau || elNiveau === niveau;
            const matchZone = !zoneId || elZone === zoneId;
            const matchStatut = !statut || statut === 'tous' || elStatut === statut;

            if (matchQ && matchNiveau && matchZone && matchStatut) {
                el.style.display = '';
                el.style.opacity = '1';
                visibleCount++;
            } else {
                el.style.display = 'none';
            }
        });

        const noResults = document.getElementById('noResultsMessage');
        if (noResults) {
            noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        }

        const paginationNav = document.getElementById('paginationNav');
        if (paginationNav) {
            paginationNav.style.display = (q || niveau || zoneId || (statut && statut !== 'active')) ? 'none' : '';
        }
    }

    searchInput.addEventListener('input', applyAlerteFilters);
    filterNiveau.addEventListener('change', applyAlerteFilters);
    filterZone.addEventListener('change', applyAlerteFilters);
    filterStatut.addEventListener('change', applyAlerteFilters);

    window.clearSearchInput = function() {
        searchInput.value = '';
        applyAlerteFilters();
    };

    window.resetAllFilters = function() {
        searchInput.value = '';
        filterNiveau.value = '';
        filterZone.value = '';
        filterStatut.value = 'active';
        applyAlerteFilters();
    };

    window.quickFilterNiveau = function(niveau) {
        filterNiveau.value = niveau;
        applyAlerteFilters();
        document.getElementById('alertesContainer').scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    // Initial check
    applyAlerteFilters();
});
</script>
@endpush
@endsection
