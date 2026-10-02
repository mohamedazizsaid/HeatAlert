@extends('layouts.front.front')

@section('content')
{{-- ═══════════════════════════════════════════════════════
     PAGE CONSEIL SANTÉ — DÉTAILS
═══════════════════════════════════════════════════════ --}}

@php
  $catIcons = [
    'hydratation'        => ['bi-droplet-fill', '#0284c7', '#e0f2fe', 'Hydratation & Boissons', 'assets/front/img/health/consultation-4.webp'],
    'seniors'            => ['bi-person-heart', '#7c3aed', '#ede9fe', 'Personnes Âgées & Vulnérables', 'assets/front/img/health/staff-3.webp'],
    'enfants'            => ['bi-emoji-smile-fill', '#d97706', '#fef3c7', 'Nourrissons & Enfants', 'assets/front/img/health/pediatrics-4.webp'],
    'sport'              => ['bi-bicycle', '#16a34a', '#dcfce7', 'Activités Physiques & Sport', 'assets/front/img/health/orthopedics-1.webp'],
    'travail'            => ['bi-briefcase-fill', '#dc2626', '#fee2e2', 'Travailleurs en Extérieur', 'assets/front/img/health/facilities-6.webp'],
    'protection_solaire' => ['bi-sun-fill', '#ea580c', '#ffedd5', 'Protection UV & Soleil', 'assets/front/img/health/dermatology-1.webp'],
    'alimentation'       => ['bi-egg-fried', '#059669', '#d1fae5', 'Alimentation & Nutrition', 'assets/front/img/health/cardiology-2.webp'],
  ];
  $catKey = strtolower($conseil->categorie ?? 'general');
  $cfg = $catIcons[$catKey] ?? ['bi-shield-plus', '#2563eb', '#eff6ff', ucfirst($conseil->categorie ?? 'Santé'), 'assets/front/img/health/emergency-1.webp'];

  $niveauColors = [
    'rouge'  => ['#dc2626', '#fee2e2', 'Vigilance Rouge — Canicule Extrême'],
    'orange' => ['#ea580c', '#ffedd5', 'Vigilance Orange — Forte Chaleur'],
    'jaune'  => ['#d97706', '#fef3c7', 'Vigilance Jaune — Chaleur Modérée'],
    'vert'   => ['#16a34a', '#dcfce7', 'Situation Normale — Prévention'],
  ];
  $nivCfg = $niveauColors[$conseil->niveau_alerte_cible] ?? ['#2563eb', '#eff6ff', 'Tous niveaux de vigilance'];
@endphp

<!-- Page Title (Standard Template Header Blanc) -->
<div class="page-title">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-9">
          <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill" style="background: {{ $cfg[2] }}; color: {{ $cfg[1] }}; font-size: 13px; font-weight: 700;">
            <i class="bi {{ $cfg[0] }}"></i>
            <span>{{ $cfg[3] }}</span>
          </div>
          <h1 class="heading-title mb-3" style="font-size: 32px; font-weight: 700; color: #1e3a5f;">
            {{ $conseil->titre }}
          </h1>
          <p class="mb-0 text-muted" style="font-size: 15px;">
            Recommandations officielles et gestes de prévention santé validés face aux vagues de chaleur en Tunisie.
          </p>
        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs">
    <div class="container">
      <ol>
        <li><a href="{{ route('front.home') }}">Accueil</a></li>
        <li><a href="{{ route('front.conseils.index') }}">Conseils Santé</a></li>
        <li class="current">{{ Str::limit($conseil->titre, 40) }}</li>
      </ol>
    </div>
  </nav>
</div><!-- End Page Title -->

<!-- Department Details Section (Clinic Template Style) -->
<section id="department-details" class="department-details section py-5">
  <div class="container" data-aos="fade-up">

    <div class="row gy-5">

      <!-- Contenu Principal -->
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4 bg-white mb-4">

          <!-- Badges & Métadonnées -->
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pb-3 mb-4 border-bottom">
            <div class="d-flex align-items-center gap-2 flex-wrap">
              <span class="badge px-3 py-2 rounded-pill" style="background: {{ $nivCfg[1] }}; color: {{ $nivCfg[0] }}; font-weight: 700; font-size: 12px;">
                <i class="bi bi-thermometer-high me-1"></i>{{ $nivCfg[2] }}
              </span>
              <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill" style="font-size: 12px;">
                <i class="bi bi-tag me-1"></i>{{ ucfirst(str_replace('_', ' ', $conseil->categorie)) }}
              </span>
            </div>
            <div class="text-muted small">
              <i class="bi bi-calendar-check me-1"></i>Mis à jour régulièrement
            </div>
          </div>

          <!-- Image mise en avant avec effet Clinic Template -->
          <div class="position-relative rounded-4 overflow-hidden mb-4 shadow-sm" style="max-height: 380px;">
            <img src="{{ asset($cfg[4]) }}" alt="{{ $conseil->titre }}" class="img-fluid w-100 object-fit-cover" style="height: 340px; object-fit: cover;">
            <div class="position-absolute bottom-0 start-0 end-0 p-4 text-white" style="background: linear-gradient(to top, rgba(15,23,42,0.85) 0%, rgba(15,23,42,0) 100%);">
              <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 50px; height: 50px; background: {{ $cfg[1] }}; color: #fff; font-size: 22px;">
                  <i class="bi {{ $cfg[0] }}"></i>
                </div>
                <div>
                  <h4 class="text-white mb-1 fw-bold fs-5">{{ $conseil->titre }}</h4>
                  <small class="text-white-50">Protocole préventif national HeatAlert</small>
                </div>
              </div>
            </div>
          </div>

          <!-- Corps du conseil -->
          <div class="conseil-body my-4" style="line-height: 1.85; font-size: 16px; color: #334155;">
            <div class="p-4 rounded-3 mb-4" style="background: #f8fafc; border-left: 4px solid {{ $cfg[1] }};">
              <h5 class="fw-bold mb-2" style="color: #1e3a5f;">
                <i class="bi bi-info-circle-fill me-2" style="color: {{ $cfg[1] }};"></i>Directive de Santé Publique
              </h5>
              <p class="mb-0 text-secondary" style="font-size: 15px;">
                Ce conseil fait partie intégrante du plan national canicule. Son application stricte réduit significativement le risque de coup de chaleur, de déshydratation aiguë et de complications cardiovasculaires.
              </p>
            </div>

            <h4 class="fw-bold mt-4 mb-3" style="color: #1e3a5f;">Recommandations et Mesures à Appliquer</h4>
            <div class="conseil-text" style="white-space: pre-line;">{{ $conseil->contenu }}</div>
          </div>

          <!-- Points Clés / Checklist Rapide -->
          <div class="p-4 rounded-4 mt-4" style="background: #f1f5f9; border: 1px solid #e2e8f0;">
            <h5 class="fw-bold mb-3" style="color: #1e3a5f;">
              <i class="bi bi-check2-circle me-2 text-success"></i>Récapitulatif des Gestes Essentiels
            </h5>
            <div class="row g-3">
              <div class="col-md-6">
                <div class="d-flex align-items-start gap-2">
                  <i class="bi bi-check-circle-fill text-success mt-1"></i>
                  <span class="small text-secondary">Boire régulièrement de l'eau sans attendre d'avoir soif</span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="d-flex align-items-start gap-2">
                  <i class="bi bi-check-circle-fill text-success mt-1"></i>
                  <span class="small text-secondary">Éviter les sorties aux heures les plus chaudes (11h-17h)</span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="d-flex align-items-start gap-2">
                  <i class="bi bi-check-circle-fill text-success mt-1"></i>
                  <span class="small text-secondary">Maintenir son logement frais (fermer volets le jour)</span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="d-flex align-items-start gap-2">
                  <i class="bi bi-check-circle-fill text-success mt-1"></i>
                  <span class="small text-secondary">Prendre des nouvelles régulières des proches vulnérables</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Boutons d'Action & Partage -->
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-4 mt-4 border-top">
            <a href="{{ route('front.conseils.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
              <i class="bi bi-arrow-left me-1"></i>Tous les conseils
            </a>
            <div class="d-flex gap-2">
              <button onclick="window.print()" class="btn btn-light rounded-pill border px-3" title="Imprimer cette fiche">
                <i class="bi bi-printer me-1"></i>Imprimer
              </button>
              <a href="{{ route('front.alertes.index') }}" class="btn text-white rounded-pill px-4 shadow-sm" style="background: #dc2626;">
                <i class="bi bi-bell-fill me-1"></i>Voir les alertes
              </a>
            </div>
          </div>

        </div>

        <!-- Alertes liées à ce conseil -->
        @if($conseil->alertes->count() > 0)
          <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
            <h5 class="fw-bold mb-3" style="color: #1e3a5f;">
              <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Alertes Météo Associées ({{ $conseil->alertes->count() }})
            </h5>
            <div class="list-group list-group-flush">
              @foreach($conseil->alertes as $alerte)
                <a href="{{ route('front.alertes.show', $alerte) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 px-2 border-bottom">
                  <div>
                    <strong class="d-block text-dark">{{ $alerte->titre }}</strong>
                    <small class="text-muted">
                      <i class="bi bi-geo-alt me-1"></i>{{ $alerte->zone->nom ?? 'Zone non définie' }} &nbsp;·&nbsp;
                      <span class="text-uppercase fw-semibold" style="color: {{ $alerte->niveau === 'rouge' ? '#dc2626' : ($alerte->niveau === 'orange' ? '#ea580c' : '#d97706') }};">
                        Vigilance {{ $alerte->niveau }}
                      </span>
                    </small>
                  </div>
                  <i class="bi bi-chevron-right text-muted"></i>
                </a>
              @endforeach
            </div>
          </div>
        @endif
      </div>

      <!-- Sidebar Complémentaire -->
      <div class="col-lg-4">

        <!-- Carte Numéros d'Urgence (Style Clinic) -->
        <div class="card border-0 shadow-sm rounded-4 p-4 text-white mb-4" style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center bg-white text-danger shadow" style="width: 46px; height: 46px; font-size: 20px;">
              <i class="bi bi-telephone-outbound-fill"></i>
            </div>
            <div>
              <h5 class="text-white fw-bold mb-0">En Cas d'Urgence</h5>
              <small class="text-white-50">Assistance médicale 24h/24</small>
            </div>
          </div>
          <p class="small text-white-50 mb-3">
            Si une personne présente des signes de coup de chaleur (vertiges, confusion, perte de connaissance, fièvre élevée), contactez immédiatement les secours.
          </p>
          <div class="d-grid gap-2">
            <a href="tel:198" class="btn btn-light fw-bold text-danger py-2 rounded-pill shadow-sm">
              <i class="bi bi-telephone-fill me-2"></i>Protection Civile : 198
            </a>
            <a href="tel:190" class="btn btn-outline-light py-2 rounded-pill">
              <i class="bi bi-telephone me-2"></i>SAMU Tunisie : 190
            </a>
          </div>
        </div>

        <!-- Conseils Similaires (Même catégorie) -->
        @if(isset($relatedConseils) && $relatedConseils->count() > 0)
          <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h5 class="fw-bold mb-3" style="color: #1e3a5f;">
              <i class="bi bi-lightbulb me-2 text-warning"></i>Autres Conseils {{ $cfg[3] }}
            </h5>
            <div class="d-flex flex-column gap-3">
              @foreach($relatedConseils as $rel)
                <a href="{{ route('front.conseils.show', $rel) }}" class="text-decoration-none p-3 rounded-3 border transition" style="background: #fafafa; transition: all 0.2s;"
                   onmouseover="this.style.background='#f1f5f9';this.style.borderColor='{{ $cfg[1] }}';"
                   onmouseout="this.style.background='#fafafa';this.style.borderColor='#dee2e6';">
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge rounded-pill" style="background: {{ $cfg[2] }}; color: {{ $cfg[1] }}; font-size: 11px;">
                      {{ ucfirst(str_replace('_', ' ', $rel->categorie)) }}
                    </span>
                  </div>
                  <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">{{ $rel->titre }}</h6>
                  <p class="text-muted small mb-0">{{ Str::limit(strip_tags($rel->contenu), 70) }}</p>
                </a>
              @endforeach
            </div>
          </div>
        @endif

        <!-- Carte Partenaires Officiels -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center">
          <div class="d-flex justify-content-center align-items-center gap-3 mb-2">
            <i class="bi bi-shield-check text-primary fs-3"></i>
            <h6 class="fw-bold mb-0 text-dark">Sources & Validation</h6>
          </div>
          <p class="small text-muted mb-0">
            Recommandations établies en conformité avec l'Institut National de la Météorologie (INM) et le Ministère de la Santé de Tunisie.
          </p>
        </div>

      </div>

    </div>

  </div>
</section>
@endsection
