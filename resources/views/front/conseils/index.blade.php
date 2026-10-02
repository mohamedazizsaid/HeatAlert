@extends('layouts.front.front')

@section('content')
{{-- ═══════════════════════════════════════════════════════
     PAGE CONSEILS SANTÉ — CATALOGUE DE PRÉVENTION
═══════════════════════════════════════════════════════ --}}

<!-- Page Title avec image de fond contextuelle et cache sombre élégant -->
<div class="page-title position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.84) 0%, rgba(30, 58, 95, 0.82) 100%), url('{{ asset('assets/front/img/health/consultation-4.webp') }}') center/cover no-repeat; padding-top: 135px; padding-bottom: 45px; border-bottom: 1px solid rgba(255,255,255,0.1);">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill bg-warning text-dark small fw-bold shadow-sm">
            <i class="bi bi-lightbulb-fill"></i>
            <span>Guide Médical & Gestes Réflexes Canicule</span>
          </div>
          <h1 class="heading-title text-white" style="font-weight: 800; font-size: 36px; letter-spacing: -0.5px;">
            Conseils Santé & Prévention Canicule
          </h1>
          <p class="mb-0" style="font-size: 16px; color: #f1f5f9; max-width: 680px; margin: 0 auto; line-height: 1.6;">
            Recommandations officielles et gestes de protection pour préserver votre santé et celle de vos proches durant les épisodes de fortes chaleurs en Tunisie.
          </p>
        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs mt-4" style="background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(8px); border-top: 1px solid rgba(255,255,255,0.1);">
    <div class="container">
      <ol class="mb-0">
        <li><a href="{{ route('front.home') }}" class="text-white-50">Accueil</a></li>
        <li class="current text-white fw-semibold">Conseils Santé</li>
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
          <input type="text" id="conseilsSearchInput" class="form-control bg-light border-start-0 ps-0" placeholder="Filtrer en direct (titre, symptôme, mot-clé...)" autocomplete="off">
          <button class="btn btn-light border-start-0 text-muted" type="button" id="clearConseilsSearchBtn" style="display: none;" onclick="clearConseilsSearch()">
            <i class="bi bi-x-circle"></i>
          </button>
        </div>
      </div>

      <!-- Filtre Thématique / Catégorie -->
      <div class="col-lg-3 col-md-6">
        <select id="filterCategorie" class="form-select bg-light">
          <option value="">Toutes les thématiques ({{ $categories->count() }})</option>
          @foreach($categories as $cat)
            <option value="{{ $cat }}" {{ request('categorie') == $cat ? 'selected' : '' }}>
              {{ ucfirst(str_replace('_', ' ', $cat)) }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Filtre Niveau de Vigilance Cible -->
      <div class="col-lg-3 col-md-6">
        <select id="filterNiveauCible" class="form-select bg-light">
          <option value="">Tous niveaux d'alerte cibles</option>
          <option value="rouge" {{ request('niveau_cible') == 'rouge' ? 'selected' : '' }}>🔴 Alerte Rouge (Urgence)</option>
          <option value="orange" {{ request('niveau_cible') == 'orange' ? 'selected' : '' }}>🟠 Alerte Orange (Forte)</option>
          <option value="jaune" {{ request('niveau_cible') == 'jaune' ? 'selected' : '' }}>🟡 Alerte Jaune (Modérée)</option>
          <option value="vert" {{ request('niveau_cible') == 'vert' ? 'selected' : '' }}>🟢 Prévention Générale</option>
        </select>
      </div>

      <!-- Réinitialisation instantanée -->
      <div class="col-lg-1 col-md-6 d-flex justify-content-end">
        <button type="button" class="btn btn-outline-danger w-100 rounded-pill" onclick="resetConseilsFilters()" title="Effacer tous les filtres">
          <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
        </button>
      </div>

    </div>
  </div>
</section>

<!-- Catégories Rapides en Pilules (Filtrage instantané sans reload) -->
<section class="py-3 bg-light border-bottom">
  <div class="container">
    <div class="d-flex align-items-center gap-2 flex-wrap justify-content-center">
      <span class="small fw-bold text-muted text-uppercase me-2">Accès direct :</span>
      <button type="button" onclick="quickFilterCategory('')" class="btn btn-sm rounded-pill px-3 cat-pill-btn active-pill shadow-sm" id="pillAll">
        Tous les guides
      </button>
      @foreach($categories as $cat)
        <button type="button" onclick="quickFilterCategory('{{ $cat }}')" class="btn btn-sm rounded-pill px-3 cat-pill-btn btn-outline-secondary" id="pill-{{ $cat }}">
          {{ ucfirst(str_replace('_', ' ', $cat)) }}
        </button>
      @endforeach
    </div>
  </div>
</section>

<style>
  .cat-pill-btn.active-pill {
    background-color: #dc2626 !important;
    color: #ffffff !important;
    border-color: #dc2626 !important;
  }
</style>

<!-- Grille des Conseils (Clinic Template Service-Card Style) -->
<section id="services" class="services section py-5" style="background-color: #f8fafc;">
  <div class="container" data-aos="fade-up">

    @php
      $catConfig = [
        'hydratation'        => ['bi-droplet-fill', '#0284c7', '#e0f2fe', 'Hydratation & Boissons', 'assets/front/img/health/consultation-4.webp'],
        'seniors'            => ['bi-person-heart', '#7c3aed', '#ede9fe', 'Personnes Âgées', 'assets/front/img/health/staff-3.webp'],
        'enfants'            => ['bi-emoji-smile-fill', '#d97706', '#fef3c7', 'Nourrissons & Enfants', 'assets/front/img/health/pediatrics-4.webp'],
        'sport'              => ['bi-bicycle', '#16a34a', '#dcfce7', 'Activités Sportives', 'assets/front/img/health/orthopedics-1.webp'],
        'travail'            => ['bi-briefcase-fill', '#dc2626', '#fee2e2', 'Travailleurs en Extérieur', 'assets/front/img/health/facilities-6.webp'],
        'protection_solaire' => ['bi-sun-fill', '#ea580c', '#ffedd5', 'Protection Solaire', 'assets/front/img/health/dermatology-1.webp'],
        'alimentation'       => ['bi-egg-fried', '#059669', '#d1fae5', 'Alimentation & Repas', 'assets/front/img/health/cardiology-2.webp'],
      ];
    @endphp

    <div class="row g-4" id="conseilsContainer">
      @forelse($conseils as $conseil)
        @php
          $catKey = strtolower($conseil->categorie ?? 'general');
          $cfg = $catConfig[$catKey] ?? ['bi-shield-plus', '#2563eb', '#eff6ff', ucfirst($conseil->categorie ?? 'Santé'), 'assets/front/img/health/emergency-1.webp'];
          $searchData = strtolower($conseil->titre . ' ' . $conseil->contenu . ' ' . ($conseil->categorie ?? ''));
        @endphp
        <div class="col-lg-4 col-md-6 conseil-item"
             data-search="{{ $searchData }}"
             data-categorie="{{ strtolower($conseil->categorie ?? '') }}"
             data-niveau-cible="{{ strtolower($conseil->niveau_alerte_cible ?? '') }}"
             style="transition: all 0.25s ease;">
          <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white transition position-relative"
               style="transition: all 0.3s ease;"
               onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 12px 30px rgba(0,0,0,0.1)';"
               onmouseout="this.style.transform='';this.style.boxShadow='';">

            <!-- Image avec badge catégorie -->
            <div class="position-relative overflow-hidden" style="height: 180px;">
              <img src="{{ asset($cfg[4]) }}" alt="{{ $conseil->titre }}" class="w-100 h-100 object-fit-cover">
              <div class="position-absolute top-0 start-0 m-3">
                <span class="badge rounded-pill px-3 py-2 shadow-sm" style="background: {{ $cfg[2] }}; color: {{ $cfg[1] }}; font-weight: 700; font-size: 11px;">
                  <i class="bi {{ $cfg[0] }} me-1"></i>{{ $cfg[3] }}
                </span>
              </div>
              @if($conseil->niveau_alerte_cible)
                <div class="position-absolute bottom-0 end-0 m-2">
                  <span class="badge rounded-pill px-2 py-1 shadow-sm text-uppercase" style="background: rgba(0,0,0,0.7); color: #fff; font-size: 10px;">
                    Cible : {{ $conseil->niveau_alerte_cible }}
                  </span>
                </div>
              @endif
            </div>

            <!-- Contenu du conseil -->
            <div class="card-body p-4 d-flex flex-column">
              <h5 class="fw-bold mb-2" style="color: #1e3a5f; font-size: 17px; line-height: 1.4;">
                <a href="{{ route('front.conseils.show', $conseil) }}" class="text-decoration-none text-dark hover-danger">
                  {{ $conseil->titre }}
                </a>
              </h5>

              <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.6;">
                {{ Str::limit(strip_tags($conseil->contenu), 120) }}
              </p>

              <!-- Footer de la carte -->
              <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                <span class="text-muted small">
                  <i class="bi bi-shield-check text-success me-1"></i>Validé Médicalement
                </span>
                <a href="{{ route('front.conseils.show', $conseil) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold">
                  <span>Lire les conseils</span>
                  <i class="bi bi-arrow-right ms-1"></i>
                </a>
              </div>

            </div>

          </div>
        </div>
      @empty
        <div class="col-12 text-center py-5">
          <div class="p-5 rounded-4 bg-white shadow-sm border d-inline-block">
            <i class="bi bi-info-circle text-muted" style="font-size: 3rem;"></i>
            <h5 class="mt-3 text-secondary">Aucun conseil ne correspond à vos critères de recherche.</h5>
            <a href="{{ route('front.conseils.index') }}" class="btn btn-outline-primary rounded-pill mt-2">
              Afficher tous les conseils
            </a>
          </div>
        </div>
      @endforelse

      <!-- Message Aucun Résultat en direct -->
      <div id="noConseilsResults" class="col-12 text-center py-5" style="display: none;">
        <div class="p-5 rounded-4 bg-white shadow-sm border d-inline-block">
          <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
          <h5 class="mt-3 text-secondary">Aucun conseil ne correspond à votre recherche.</h5>
          <button type="button" class="btn btn-outline-danger rounded-pill mt-2" onclick="resetConseilsFilters()">
            Effacer la recherche
          </button>
        </div>
      </div>
    </div>

    <!-- Pagination Personnalisée -->
    @if($conseils->hasPages())
      <div class="d-flex justify-content-center mt-5" id="conseilsPaginationNav">
        {{ $conseils->links('front.components.pagination') }}
      </div>
    @endif

  </div>
</section>

<!-- Call To Action Santé & Urgence (Clinic Template CTA Style) -->
<section class="py-5 bg-white border-top">
  <div class="container text-center">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="p-4 p-md-5 rounded-4 text-white shadow-sm" style="background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);">
          <i class="bi bi-telephone-plus-fill text-danger fs-1 mb-3 d-inline-block"></i>
          <h3 class="fw-bold mb-2 text-white">Besoin d'une Assistance d'Urgence Canicule ?</h3>
          <p class="text-white-50 mb-4" style="max-width: 600px; margin: 0 auto;">
            En cas de malaise, déshydratation sévère ou perte de connaissance liée à la chaleur, contactez immédiatement les secours.
          </p>
          <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="tel:198" class="btn btn-danger btn-lg rounded-pill px-4 fw-bold">
              <i class="bi bi-telephone-fill me-2"></i>Protection Civile (198)
            </a>
            <a href="tel:190" class="btn btn-outline-light btn-lg rounded-pill px-4">
              <i class="bi bi-telephone me-2"></i>SAMU (190)
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
    const searchInput = document.getElementById('conseilsSearchInput');
    const filterCategorie = document.getElementById('filterCategorie');
    const filterNiveauCible = document.getElementById('filterNiveauCible');
    const clearBtn = document.getElementById('clearConseilsSearchBtn');

    function applyConseilsFilters() {
        const q = searchInput.value.trim().toLowerCase();
        const cat = filterCategorie.value.toLowerCase();
        const niveauCible = filterNiveauCible.value.toLowerCase();

        if (clearBtn) {
            clearBtn.style.display = q ? 'block' : 'none';
        }

        // Update pills active styling
        document.querySelectorAll('.cat-pill-btn').forEach(btn => {
            btn.classList.remove('active-pill');
            btn.classList.add('btn-outline-secondary');
        });
        if (!cat) {
            const allBtn = document.getElementById('pillAll');
            if (allBtn) {
                allBtn.classList.add('active-pill');
                allBtn.classList.remove('btn-outline-secondary');
            }
        } else {
            const activePill = document.getElementById('pill-' + cat);
            if (activePill) {
                activePill.classList.add('active-pill');
                activePill.classList.remove('btn-outline-secondary');
            }
        }

        let visibleCount = 0;
        const items = document.querySelectorAll('.conseil-item');

        items.forEach(el => {
            const text = el.getAttribute('data-search') || '';
            const elCat = el.getAttribute('data-categorie') || '';
            const elNiveau = el.getAttribute('data-niveau-cible') || '';

            const matchQ = !q || text.includes(q);
            const matchCat = !cat || elCat === cat;
            const matchNiveau = !niveauCible || elNiveau === niveauCible;

            if (matchQ && matchCat && matchNiveau) {
                el.style.display = '';
                el.style.opacity = '1';
                visibleCount++;
            } else {
                el.style.display = 'none';
            }
        });

        const noResults = document.getElementById('noConseilsResults');
        if (noResults) {
            noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        }

        const paginationNav = document.getElementById('conseilsPaginationNav');
        if (paginationNav) {
            paginationNav.style.display = (q || cat || niveauCible) ? 'none' : '';
        }
    }

    searchInput.addEventListener('input', applyConseilsFilters);
    filterCategorie.addEventListener('change', applyConseilsFilters);
    filterNiveauCible.addEventListener('change', applyConseilsFilters);

    window.clearConseilsSearch = function() {
        searchInput.value = '';
        applyConseilsFilters();
    };

    window.resetConseilsFilters = function() {
        searchInput.value = '';
        filterCategorie.value = '';
        filterNiveauCible.value = '';
        applyConseilsFilters();
    };

    window.quickFilterCategory = function(cat) {
        filterCategorie.value = cat;
        applyConseilsFilters();
    };

    // Initial check
    applyConseilsFilters();
});
</script>
@endpush
@endsection
