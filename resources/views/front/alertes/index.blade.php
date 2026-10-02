@extends('layouts.front.front')

@section('content')
{{-- ═══════════════════════════════════════════════════════
     PAGE ALERTES — LISTE DES BULLETINS DE VIGILANCE
═══════════════════════════════════════════════════════ --}}

<!-- Page Title (Standard Template Header Blanc) -->
<div class="page-title">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill bg-danger-subtle text-danger small fw-bold">
            <i class="bi bi-broadcast"></i>
            <span>Système d'Alerte Précoce & Surveillance Canicule</span>
          </div>
          <h1 class="heading-title" style="color: #1e3a5f; font-weight: 700;">Alertes Météo & Vigilance Canicule</h1>
          <p class="mb-0 text-muted" style="font-size: 15px;">
            Suivi en temps réel des bulletins météorologiques d'urgence, des vagues de chaleur et des indices de risque thermique en Tunisie.
          </p>
        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs">
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
        <div class="p-3 rounded-3" style="background: #fee2e2;">
          <div class="fs-2 fw-bold text-danger">{{ $statsNiveaux['rouge'] ?? 0 }}</div>
          <div class="small fw-bold text-danger text-uppercase" style="letter-spacing: 0.5px;">Vigilance Rouge</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="p-3 rounded-3" style="background: #ffedd5;">
          <div class="fs-2 fw-bold" style="color: #ea580c;">{{ $statsNiveaux['orange'] ?? 0 }}</div>
          <div class="small fw-bold text-uppercase" style="color: #ea580c; letter-spacing: 0.5px;">Vigilance Orange</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="p-3 rounded-3" style="background: #fef3c7;">
          <div class="fs-2 fw-bold" style="color: #d97706;">{{ $statsNiveaux['jaune'] ?? 0 }}</div>
          <div class="small fw-bold text-uppercase" style="color: #d97706; letter-spacing: 0.5px;">Vigilance Jaune</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="p-3 rounded-3" style="background: #dcfce7;">
          <div class="fs-2 fw-bold text-success">{{ $statsNiveaux['vert'] ?? 0 }}</div>
          <div class="small fw-bold text-success text-uppercase" style="letter-spacing: 0.5px;">Situation Normale</div>
        </div>
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

            <!-- Bandeau de statut supérieur -->
            <div style="height: 6px; background: {{ $cfg[0] }};"></div>

            <div class="card-body p-4 d-flex flex-column">

              <!-- En-tête de la carte : Niveau & Zone -->
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
    </div>

    <!-- Pagination -->
    @if($alertes->hasPages())
      <div class="d-flex justify-content-center mt-5">
        {{ $alertes->links('pagination::bootstrap-5') }}
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
          <h3 class="fw-bold mb-2" style="color:white">Protégez-vous Face à ces Alertes</h3>
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
