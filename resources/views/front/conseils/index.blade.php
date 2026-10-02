@extends('layouts.front.front')

@section('content')
{{-- ═══════════════════════════════════════════════════════
     PAGE CONSEILS SANTÉ — CATALOGUE DE PRÉVENTION
═══════════════════════════════════════════════════════ --}}

<!-- Page Title (Standard Template Header Blanc) -->
<div class="page-title">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <h1 class="heading-title" style="color: #1e3a5f; font-weight: 700;">Conseils Santé & Prévention Canicule</h1>
          <p class="mb-0 text-muted" style="font-size: 15px;">
            Recommandations officielles et gestes de protection pour préserver votre santé et celle de vos proches durant les épisodes de fortes chaleurs en Tunisie.
          </p>
        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs">
    <div class="container">
      <ol>
        <li><a href="{{ route('front.home') }}">Accueil</a></li>
        <li class="current">Conseils Santé</li>
      </ol>
    </div>
  </nav>
</div><!-- End Page Title -->

<!-- Stats Rapides / Compteurs PureCounter -->
<section class="py-4 border-bottom bg-white">
  <div class="container">
    <div class="row g-3 text-center">
      <div class="col-6 col-md-3">
        <div class="p-3 rounded-3 bg-light">
          <div class="fs-2 fw-bold text-primary">{{ $conseils->total() }}</div>
          <div class="small text-muted text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Guides Pratiques</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="p-3 rounded-3 bg-light">
          <div class="fs-2 fw-bold text-success">{{ $categories->count() }}</div>
          <div class="small text-muted text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Thématiques Santé</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="p-3 rounded-3 bg-light">
          <div class="fs-2 fw-bold text-danger">198 / 190</div>
          <div class="small text-muted text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Lignes d'Urgence</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="p-3 rounded-3 bg-light">
          <div class="fs-2 fw-bold text-warning">100%</div>
          <div class="small text-muted text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Validé Médicalement</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Section Filtres par Catégorie (Clinic Specialty Navigation) -->
<section class="py-4 bg-white border-bottom sticky-top" style="top: 75px; z-index: 90;">
  <div class="container">
    <div class="d-flex align-items-center gap-2 flex-wrap justify-content-center">
      <span class="small fw-bold text-muted text-uppercase me-2">Catégories :</span>
      <a href="{{ route('front.conseils.index') }}"
         class="btn btn-sm rounded-pill px-3 {{ !request('categorie') ? 'btn-danger shadow-sm text-white' : 'btn-outline-secondary' }}">
        Tous les guides ({{ \App\Models\Conseil::where('actif', true)->count() }})
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
                    Cible: {{ $conseil->niveau_alerte_cible }}
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
                  <i class="bi bi-shield-check text-success me-1"></i>Vérifié
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
            <h5 class="mt-3 text-secondary">Aucun conseil trouvé dans cette catégorie.</h5>
            <a href="{{ route('front.conseils.index') }}" class="btn btn-primary rounded-pill mt-2">
              Voir tous les conseils
            </a>
          </div>
        </div>
      @endforelse
    </div>

    <!-- Pagination -->
    @if($conseils->hasPages())
      <div class="d-flex justify-content-center mt-5">
        {{ $conseils->links('pagination::bootstrap-5') }}
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
          <h3 class="fw-bold mb-2" style="color:white">Besoin d'une Assistance d'Urgence Canicule ?</h3>
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
