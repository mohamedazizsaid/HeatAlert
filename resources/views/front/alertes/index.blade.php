@extends('layouts.front.front')

@section('content')
{{-- ═══════════════════════════════════════════════════════
     PAGE ALERTES — LISTE DES BULLETINS DE VIGILANCE
═══════════════════════════════════════════════════════ --}}

<!-- Page Title avec image de fond contextuelle et overlay blanc épuré -->
<div class="page-title position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.88) 55%, rgba(255,255,255,0.72) 100%), url('{{ asset('assets/front/img/health/emergency-1.webp') }}') center/cover no-repeat; padding-top: 130px; padding-bottom: 35px; border-bottom: 1px solid #e2e8f0;">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill bg-danger-subtle text-danger small fw-bold">
            <i class="bi bi-broadcast"></i>
            <span>Système d'Alerte Précoce & Vigilance Thermique</span>
          </div>
          <h1 class="heading-title" style="color: #1e3a5f; font-weight: 800; font-size: 34px;">
            Alertes Météo & Vigilance Canicule
          </h1>
          <p class="mb-0 text-muted" style="font-size: 15px; max-width: 680px; margin: 0 auto;">
            Suivez en temps réel les bulletins de canicule, les niveaux de vigilance par région et les prévisions de risques thermiques en Tunisie.
          </p>
        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs mt-3" style="background: rgba(255,255,255,0.65); backdrop-filter: blur(4px);">
    <div class="container">
      <ol>
        <li><a href="{{ route('front.home') }}">Accueil</a></li>
        <li class="current">Alertes Météo</li>
      </ol>
    </div>
  </nav>
</div><!-- End Page Title -->

<!-- Stats Niveaux de Vigilance (Clinic Style) -->
<section class="py-4 border-bottom bg-white">
  <div class="container">
    <div class="row g-3 text-center">
      <div class="col-6 col-md-3">
        <a href="{{ route('front.alertes.index', ['niveau' => 'rouge']) }}" class="text-decoration-none d-block">
          <div class="p-3 rounded-3 transition shadow-sm" style="background: #fee2e2; border: 1px solid #fca5a5;">
            <div class="fs-2 fw-bold text-danger">{{ $statsNiveaux['rouge'] ?? 0 }}</div>
            <div class="small fw-bold text-danger text-uppercase" style="letter-spacing: 0.5px;">Vigilance Rouge</div>
          </div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="{{ route('front.alertes.index', ['niveau' => 'orange']) }}" class="text-decoration-none d-block">
          <div class="p-3 rounded-3 transition shadow-sm" style="background: #ffedd5; border: 1px solid #fdba74;">
            <div class="fs-2 fw-bold" style="color: #ea580c;">{{ $statsNiveaux['orange'] ?? 0 }}</div>
            <div class="small fw-bold text-uppercase" style="color: #ea580c; letter-spacing: 0.5px;">Vigilance Orange</div>
          </div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="{{ route('front.alertes.index', ['niveau' => 'jaune']) }}" class="text-decoration-none d-block">
          <div class="p-3 rounded-3 transition shadow-sm" style="background: #fef3c7; border: 1px solid #fde047;">
            <div class="fs-2 fw-bold" style="color: #d97706;">{{ $statsNiveaux['jaune'] ?? 0 }}</div>
            <div class="small fw-bold text-uppercase" style="color: #d97706; letter-spacing: 0.5px;">Vigilance Jaune</div>
          </div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="{{ route('front.alertes.index', ['niveau' => 'vert']) }}" class="text-decoration-none d-block">
          <div class="p-3 rounded-3 transition shadow-sm" style="background: #dcfce7; border: 1px solid #86efac;">
            <div class="fs-2 fw-bold text-success">{{ $statsNiveaux['vert'] ?? 0 }}</div>
            <div class="small fw-bold text-success text-uppercase" style="letter-spacing: 0.5px;">Situation Normale</div>
          </div>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Barre de Recherche & Filtres Multi-critères -->
<section class="py-4 bg-white border-bottom shadow-sm">
  <div class="container">
    <form method="GET" action="{{ route('front.alertes.index') }}" class="row g-3 align-items-center">

      <!-- Recherche Textuelle -->
      <div class="col-lg-3 col-md-6">
        <div class="input-group">
          <span class="input-group-text bg-light border-end-0 text-muted">
            <i class="bi bi-search"></i>
          </span>
          <input type="text" name="search" class="form-control bg-light border-start-0 ps-0" placeholder="Rechercher une alerte, mot-clé..." value="{{ request('search') }}">
        </div>
      </div>

      <!-- Filtre Niveau de Vigilance -->
      <div class="col-lg-2 col-md-6">
        <select name="niveau" class="form-select bg-light">
          <option value="">Tous Niveaux</option>
          <option value="rouge" {{ request('niveau') == 'rouge' ? 'selected' : '' }}>🔴 Rouge (Extrême)</option>
          <option value="orange" {{ request('niveau') == 'orange' ? 'selected' : '' }}>🟠 Orange (Forte)</option>
          <option value="jaune" {{ request('niveau') == 'jaune' ? 'selected' : '' }}>🟡 Jaune (Modérée)</option>
          <option value="vert" {{ request('niveau') == 'vert' ? 'selected' : '' }}>🟢 Vert (Normal)</option>
        </select>
      </div>

      <!-- Filtre Zone Géographique -->
      <div class="col-lg-3 col-md-6">
        <select name="zone_id" class="form-select bg-light">
          <option value="">Toutes les zones</option>
          @foreach($zonesList as $z)
            <option value="{{ $z->id }}" {{ request('zone_id') == $z->id ? 'selected' : '' }}>
              {{ $z->nom }} ({{ $z->gouvernorat }})
            </option>
          @endforeach
        </select>
      </div>

      <!-- Filtre Statut de l'alerte -->
      <div class="col-lg-2 col-md-6">
        <select name="statut" class="form-select bg-light">
          <option value="active" {{ request('statut', 'active') == 'active' ? 'selected' : '' }}>Actives en cours</option>
          <option value="tous" {{ request('statut') == 'tous' ? 'selected' : '' }}>Toutes les alertes</option>
          <option value="terminee" {{ request('statut') == 'terminee' ? 'selected' : '' }}>Terminées</option>
        </select>
      </div>

      <!-- Actions -->
      <div class="col-lg-2 col-md-12 d-flex gap-2">
        <button type="submit" class="btn btn-danger w-100 rounded-pill fw-semibold shadow-sm">
          <i class="bi bi-funnel-fill me-1"></i>Filtrer
        </button>
        @if(request('search') || request('niveau') || request('zone_id') || request('statut') && request('statut') !== 'active')
          <a href="{{ route('front.alertes.index') }}" class="btn btn-outline-secondary rounded-pill" title="Réinitialiser les filtres">
            <i class="bi bi-arrow-counterclockwise"></i>
          </a>
        @endif
      </div>

    </form>
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

    <div class="row g-4">
      @forelse($alertes as $alerte)
        @php
          $cfg = $niveauColors[$alerte->niveau] ?? ['#64748b', '#f1f5f9', 'Vigilance ' . ucfirst($alerte->niveau), 'bi-bell'];
        @endphp
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
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
            <h5 class="mt-3 text-secondary">Aucune alerte ne correspond à vos critères.</h5>
            <a href="{{ route('front.alertes.index') }}" class="btn btn-outline-primary rounded-pill mt-2">
              Afficher toutes les alertes
            </a>
          </div>
        </div>
      @endforelse
    </div>

    <!-- Pagination Personnalisée Sans Bug -->
    @if($alertes->hasPages())
      <div class="d-flex justify-content-center mt-5">
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
@endsection
