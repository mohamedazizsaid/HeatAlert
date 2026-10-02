@extends('layouts.front.front')

@section('content')
{{-- ═══════════════════════════════════════════════════════
     PAGE CONSEILS SANTÉ — CATALOGUE DE PRÉVENTION
═══════════════════════════════════════════════════════ --}}

<!-- Page Title avec image de fond contextuelle et overlay blanc épuré -->
<div class="page-title position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(255,255,255,0.96) 0%, rgba(255,255,255,0.88) 55%, rgba(255,255,255,0.72) 100%), url('{{ asset('assets/front/img/health/consultation-4.webp') }}') center/cover no-repeat; padding-top: 130px; padding-bottom: 35px; border-bottom: 1px solid #e2e8f0;">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill bg-warning-subtle text-dark border small fw-bold">
            <i class="bi bi-lightbulb-fill text-warning"></i>
            <span>Guide Médical & Gestes Réflexes Canicule</span>
          </div>
          <h1 class="heading-title" style="color: #1e3a5f; font-weight: 800; font-size: 34px;">
            Conseils Santé & Prévention Canicule
          </h1>
          <p class="mb-0 text-muted" style="font-size: 15px; max-width: 680px; margin: 0 auto;">
            Recommandations officielles et gestes de protection pour préserver votre santé et celle de vos proches durant les épisodes de fortes chaleurs en Tunisie.
          </p>
        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs mt-3" style="background: rgba(255,255,255,0.65); backdrop-filter: blur(4px);">
    <div class="container">
      <ol>
        <li><a href="{{ route('front.home') }}">Accueil</a></li>
        <li class="current">Conseils Santé</li>
      </ol>
    </div>
  </nav>
</div><!-- End Page Title -->

<!-- Barre de Recherche & Filtres Avancés -->
<section class="py-4 bg-white border-bottom shadow-sm">
  <div class="container">
    <form method="GET" action="{{ route('front.conseils.index') }}" class="row g-3 align-items-center">

      <!-- Recherche Textuelle -->
      <div class="col-lg-4 col-md-6">
        <div class="input-group">
          <span class="input-group-text bg-light border-end-0 text-muted">
            <i class="bi bi-search"></i>
          </span>
          <input type="text" name="search" class="form-control bg-light border-start-0 ps-0" placeholder="Rechercher un conseil, mot-clé, symptôme..." value="{{ request('search') }}">
        </div>
      </div>

      <!-- Filtre Thématique / Catégorie -->
      <div class="col-lg-3 col-md-6">
        <select name="categorie" class="form-select bg-light">
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
        <select name="niveau_cible" class="form-select bg-light">
          <option value="">Tous niveaux d'alerte cibles</option>
          <option value="rouge" {{ request('niveau_cible') == 'rouge' ? 'selected' : '' }}>🔴 Alerte Rouge (Urgence)</option>
          <option value="orange" {{ request('niveau_cible') == 'orange' ? 'selected' : '' }}>🟠 Alerte Orange (Forte)</option>
          <option value="jaune" {{ request('niveau_cible') == 'jaune' ? 'selected' : '' }}>🟡 Alerte Jaune (Modérée)</option>
          <option value="vert" {{ request('niveau_cible') == 'vert' ? 'selected' : '' }}>🟢 Prévention Générale</option>
        </select>
      </div>

      <!-- Actions -->
      <div class="col-lg-2 col-md-6 d-flex gap-2">
        <button type="submit" class="btn btn-danger w-100 rounded-pill fw-semibold shadow-sm">
          <i class="bi bi-funnel-fill me-1"></i>Filtrer
        </button>
        @if(request('search') || request('categorie') || request('niveau_cible'))
          <a href="{{ route('front.conseils.index') }}" class="btn btn-outline-secondary rounded-pill" title="Réinitialiser les filtres">
            <i class="bi bi-arrow-counterclockwise"></i>
          </a>
        @endif
      </div>

    </form>
  </div>
</section>

<!-- Catégories Rapides en Pilules -->
<section class="py-3 bg-light border-bottom">
  <div class="container">
    <div class="d-flex align-items-center gap-2 flex-wrap justify-content-center">
      <span class="small fw-bold text-muted text-uppercase me-2">Accès direct :</span>
      <a href="{{ route('front.conseils.index') }}"
         class="btn btn-sm rounded-pill px-3 {{ !request('categorie') ? 'btn-danger shadow-sm text-white' : 'btn-outline-secondary' }}">
        Tous les guides
      </a>
      @foreach($categories as $cat)
        <a href="{{ route('front.conseils.index', ['categorie' => $cat]) }}"
           class="btn btn-sm rounded-pill px-3 {{ request('categorie') === $cat ? 'btn-danger shadow-sm text-white' : 'btn-outline-secondary' }}">
          {{ ucfirst(str_replace('_', ' ', $cat)) }}
        </a>
      @endforeach
    </div>
  </div>
</section>

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

    <div class="row g-4">
      @forelse($conseils as $conseil)
        @php
          $catKey = strtolower($conseil->categorie ?? 'general');
          $cfg = $catConfig[$catKey] ?? ['bi-shield-plus', '#2563eb', '#eff6ff', ucfirst($conseil->categorie ?? 'Santé'), 'assets/front/img/health/emergency-1.webp'];
        @endphp
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
          <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white transition"
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
    </div>

    <!-- Pagination Personnalisée Sans Bug -->
    @if($conseils->hasPages())
      <div class="d-flex justify-content-center mt-5">
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
@endsection
