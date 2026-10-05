@extends('layouts.admin.admin')

@section('title', 'Statistiques Avancées')

@section('content')
<div class="container-fluid px-4">

  {{-- ── EN-TÊTE DE LA PAGE ── --}}
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
      <h1 class="fw-bold mb-0" style="font-size: 1.7rem; color: #1e293b; font-family: 'Inter', sans-serif;">
        <i class="fa-solid fa-chart-line text-danger me-2"></i>Tableau de Bord Analytique
      </h1>
      <p class="text-muted mb-0 small">Visualisation complète des données HeatAlert Tunisie en temps réel</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill fw-semibold" style="font-size: 12px;">
        <i class="fa-solid fa-circle-dot fa-fade me-1"></i>Données en direct
      </span>
      <a href="{{ route('admin.dashboard') }}" class="m-btn m-btn--ghost">
        <i class="fa-solid fa-arrow-left me-2"></i>Dashboard
      </a>
    </div>
  </div>

  {{-- ── LIGNE 1 : KPI CARDS ANIMÉES ── --}}
  <div class="row g-3 mb-4">

    {{-- Total Alertes --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
      <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100" style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);">
        <div class="card-body p-4 text-white position-relative">
          <div class="position-absolute end-0 top-0 p-3 opacity-20" style="font-size: 4rem;"><i class="fa-solid fa-triangle-exclamation"></i></div>
          <div class="small fw-bold text-white-50 mb-2 text-uppercase" style="letter-spacing: 1px;">Total Alertes</div>
          <div class="display-5 fw-black mb-1" style="font-weight: 900;">{{ $totaux['alertes'] }}</div>
          <div class="d-flex align-items-center gap-3 mt-2">
            <div class="small"><span class="fw-bold text-warning">{{ $totaux['alertes_actives'] }}</span> <span class="text-white-50">actives</span></div>
            <div class="small"><span class="fw-bold">{{ $totaux['alertes_rouge'] }}</span> <span class="text-white-50">rouges</span></div>
          </div>
        </div>
        <div class="px-4 pb-3">
          <div class="progress" style="height: 4px; background: rgba(255,255,255,0.2);">
            @php $pctActif = $totaux['alertes'] > 0 ? round(($totaux['alertes_actives'] / $totaux['alertes']) * 100) : 0; @endphp
            <div class="progress-bar bg-warning" style="width: {{ $pctActif }}%;"></div>
          </div>
          <div class="text-white-50 small mt-1">{{ $pctActif }}% actives sur le total</div>
        </div>
      </div>
    </div>

    {{-- Zones Actives --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
      <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100" style="background: linear-gradient(135deg, #0284c7 0%, #075985 100%);">
        <div class="card-body p-4 text-white position-relative">
          <div class="position-absolute end-0 top-0 p-3 opacity-20" style="font-size: 4rem;"><i class="fa-solid fa-map-location-dot"></i></div>
          <div class="small fw-bold text-white-50 mb-2 text-uppercase" style="letter-spacing: 1px;">Zones Couvertes</div>
          <div class="display-5 fw-black mb-1" style="font-weight: 900;">{{ $totaux['zones'] }}</div>
          <div class="d-flex align-items-center gap-3 mt-2">
            <div class="small"><span class="fw-bold text-success" style="color: #4ade80 !important;">{{ $totaux['zones_actives'] }}</span> <span class="text-white-50">actives</span></div>
            <div class="small"><span class="fw-bold">{{ $totaux['zones'] - $totaux['zones_actives'] }}</span> <span class="text-white-50">inactives</span></div>
          </div>
        </div>
        <div class="px-4 pb-3">
          @php $pctZones = $totaux['zones'] > 0 ? round(($totaux['zones_actives'] / $totaux['zones']) * 100) : 0; @endphp
          <div class="progress" style="height: 4px; background: rgba(255,255,255,0.2);">
            <div class="progress-bar bg-success" style="width: {{ $pctZones }}%;"></div>
          </div>
          <div class="text-white-50 small mt-1">{{ $pctZones }}% de zones actives</div>
        </div>
      </div>
    </div>

    {{-- Conseils Santé --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
      <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100" style="background: linear-gradient(135deg, #059669 0%, #065f46 100%);">
        <div class="card-body p-4 text-white position-relative">
          <div class="position-absolute end-0 top-0 p-3 opacity-20" style="font-size: 4rem;"><i class="fa-solid fa-lightbulb"></i></div>
          <div class="small fw-bold text-white-50 mb-2 text-uppercase" style="letter-spacing: 1px;">Conseils Santé</div>
          <div class="display-5 fw-black mb-1" style="font-weight: 900;">{{ $totaux['conseils'] }}</div>
          <div class="d-flex align-items-center gap-3 mt-2">
            <div class="small"><span class="fw-bold text-warning">{{ $totaux['conseils_actifs'] }}</span> <span class="text-white-50">publiés</span></div>
          </div>
        </div>
        <div class="px-4 pb-3">
          @php $pctConseils = $totaux['conseils'] > 0 ? round(($totaux['conseils_actifs'] / $totaux['conseils']) * 100) : 0; @endphp
          <div class="progress" style="height: 4px; background: rgba(255,255,255,0.2);">
            <div class="progress-bar bg-warning" style="width: {{ $pctConseils }}%;"></div>
          </div>
          <div class="text-white-50 small mt-1">{{ $pctConseils }}% publiés</div>
        </div>
      </div>
    </div>

    {{-- Score de Criticité --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
      @php
        $score = ($totaux['alertes_rouge'] * 4) + ($alertesParNiveau['orange'] * 2) + ($alertesParNiveau['jaune'] * 1);
        $scoreMax = max($totaux['alertes'] * 4, 1);
        $scoreClass = $score > 20 ? 'danger' : ($score > 8 ? 'warning' : 'success');
        $scoreBg = $score > 20 ? 'linear-gradient(135deg, #b91c1c 0%, #7f1d1d 100%)' : ($score > 8 ? 'linear-gradient(135deg, #d97706 0%, #92400e 100%)' : 'linear-gradient(135deg, #059669 0%, #064e3b 100%)');
      @endphp
      <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100" style="background: {{ $scoreBg }};">
        <div class="card-body p-4 text-white position-relative">
          <div class="position-absolute end-0 top-0 p-3 opacity-20" style="font-size: 4rem;"><i class="fa-solid fa-shield-halved"></i></div>
          <div class="small fw-bold text-white-50 mb-2 text-uppercase" style="letter-spacing: 1px;">Score de Criticité</div>
          <div class="display-5 fw-black mb-1" style="font-weight: 900;">{{ $score }}<span class="fs-4 fw-normal text-white-50">/{{ $scoreMax }}</span></div>
          <div class="small text-white-50 mt-2">
            @if($score > 20) <span class="text-warning fw-bold">⚠️ Criticité Élevée</span>
            @elseif($score > 8) <span class="text-warning fw-bold">🟡 Criticité Modérée</span>
            @else <span style="color: #4ade80;" class="fw-bold">✅ Situation Normale</span> @endif
          </div>
        </div>
        <div class="px-4 pb-3">
          <div class="progress" style="height: 4px; background: rgba(255,255,255,0.2);">
            <div class="progress-bar bg-warning" style="width: {{ min(100, round(($score / max($scoreMax, 1)) * 100)) }}%;"></div>
          </div>
          <div class="text-white-50 small mt-1">Index composite de vigilance</div>
        </div>
      </div>
    </div>

  </div>

  {{-- ── LIGNE 2 : GRAPHIQUES PRINCIPAUX ── --}}
  <div class="row g-4 mb-4">

    {{-- Graphique 1 : Évolution mensuelle des alertes --}}
    <div class="col-xl-8">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-0">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-chart-area text-danger me-2"></i>Évolution des Alertes (12 Derniers Mois)</h5>
              <small class="text-muted">Nombre d'alertes créées par mois</small>
            </div>
          </div>
        </div>
        <div class="card-body px-4 pt-3 pb-4">
          <div style="position: relative; height: 280px;">
            <canvas id="alertesParMoisChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    {{-- Graphique 2 : Répartition par Niveau --}}
    <div class="col-xl-4">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-0">
          <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-chart-pie text-warning me-2"></i>Répartition par Niveau</h5>
          <small class="text-muted">Distribution de toutes les alertes</small>
        </div>
        <div class="card-body px-4 pt-3 pb-2 d-flex flex-column align-items-center">
          <div style="position: relative; height: 220px; width: 220px;">
            <canvas id="niveauDonutChart"></canvas>
          </div>
          {{-- Légende personnalisée --}}
          <div class="d-flex flex-column gap-2 w-100 mt-3">
            <div class="d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center gap-2"><span class="rounded-circle d-inline-block" style="width:12px;height:12px;background:#dc2626;"></span><span class="small text-dark">Rouge</span></div>
              <span class="fw-bold text-dark small">{{ $alertesParNiveau['rouge'] }}</span>
            </div>
            <div class="d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center gap-2"><span class="rounded-circle d-inline-block" style="width:12px;height:12px;background:#ea580c;"></span><span class="small text-dark">Orange</span></div>
              <span class="fw-bold text-dark small">{{ $alertesParNiveau['orange'] }}</span>
            </div>
            <div class="d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center gap-2"><span class="rounded-circle d-inline-block" style="width:12px;height:12px;background:#eab308;"></span><span class="small text-dark">Jaune</span></div>
              <span class="fw-bold text-dark small">{{ $alertesParNiveau['jaune'] }}</span>
            </div>
            <div class="d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center gap-2"><span class="rounded-circle d-inline-block" style="width:12px;height:12px;background:#22c55e;"></span><span class="small text-dark">Vert</span></div>
              <span class="fw-bold text-dark small">{{ $alertesParNiveau['vert'] }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  {{-- ── LIGNE 3 : GRAPHIQUES SECONDAIRES ── --}}
  <div class="row g-4 mb-4">

    {{-- Graphique 3 : Top Zones --}}
    <div class="col-xl-5">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-0">
          <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-ranking-star text-primary me-2"></i>Top 10 Zones par Nombre d'Alertes</h5>
          <small class="text-muted">Zones géographiques les plus exposées</small>
        </div>
        <div class="card-body px-4 pt-3 pb-4">
          <div style="position: relative; height: 300px;">
            <canvas id="topZonesChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    {{-- Graphique 4 : Répartition par Type --}}
    <div class="col-xl-4">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-0">
          <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-tags text-info me-2"></i>Alertes par Type Météo</h5>
          <small class="text-muted">Distribution des types d'événements</small>
        </div>
        <div class="card-body px-4 pt-3 pb-4">
          <div style="position: relative; height: 300px;">
            <canvas id="typeChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    {{-- Graphique 5 : Statuts --}}
    <div class="col-xl-3">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-0">
          <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-circle-half-stroke text-secondary me-2"></i>Statuts des Alertes</h5>
          <small class="text-muted">Cycle de vie</small>
        </div>
        <div class="card-body px-4 pt-3 pb-4 d-flex flex-column align-items-center">
          <div style="position: relative; height: 200px; width: 200px;">
            <canvas id="statutChart"></canvas>
          </div>
          <div class="d-flex flex-column gap-2 w-100 mt-3">
            @php
              $statutColors = ['active' => '#22c55e', 'brouillon' => '#94a3b8', 'terminee' => '#0ea5e9', 'annulee' => '#ef4444'];
              $statutLabels = ['active' => 'Active', 'brouillon' => 'Brouillon', 'terminee' => 'Terminée', 'annulee' => 'Annulée'];
            @endphp
            @foreach($alertesParStatut as $statut => $count)
            <div class="d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center gap-2">
                <span class="rounded-circle d-inline-block" style="width:10px;height:10px;background:{{ $statutColors[$statut] ?? '#999' }};"></span>
                <span class="small text-muted">{{ $statutLabels[$statut] ?? $statut }}</span>
              </div>
              <span class="badge bg-light text-dark border fw-bold small">{{ $count }}</span>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

  </div>

  {{-- ── LIGNE 4 : ANALYSE DES COUPURES ── --}}
  <div class="row g-4 mb-4">
    <div class="col-xl-6">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-0">
          <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-bolt text-warning me-2"></i>Coupures par type</h5>
          <small class="text-muted">Répartition des pannes, surcharges et délestages</small>
        </div>
        <div class="card-body px-4 pt-3 pb-4"><div style="position:relative;height:270px;"><canvas id="coupuresParTypeChart"></canvas></div></div>
      </div>
    </div>
    <div class="col-xl-6">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-0">
          <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-chart-line text-danger me-2"></i>Coupures par mois</h5>
          <small class="text-muted">Évolution sur les 12 derniers mois</small>
        </div>
        <div class="card-body px-4 pt-3 pb-4"><div style="position:relative;height:270px;"><canvas id="coupuresParMoisChart"></canvas></div></div>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-xl-7">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-0">
          <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-map-location-dot text-primary me-2"></i>Coupures par zone</h5>
          <small class="text-muted">Top 10 des zones les plus concernées</small>
        </div>
        <div class="card-body px-4 pt-3 pb-4"><div style="position:relative;height:320px;"><canvas id="coupuresParZoneChart"></canvas></div></div>
      </div>
    </div>
    <div class="col-xl-5">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-0">
          <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-clock text-info me-2"></i>Suivi des interventions</h5>
          <small class="text-muted">Durée moyenne et signalements validés</small>
        </div>
        <div class="card-body p-4">
          <div class="row g-3 mb-3">
            <div class="col-6"><div class="p-3 rounded-3 bg-light text-center"><div class="small text-muted">Durée moyenne</div><strong class="fs-3 text-primary">{{ round($dureeMoyenneCoupures) }}</strong><div class="small text-muted">minutes</div></div></div>
            <div class="col-6"><div class="p-3 rounded-3 bg-light text-center"><div class="small text-muted">Taux validé</div><strong class="fs-3 text-success">{{ $tauxSignalementsValides }}%</strong><div class="small text-muted">{{ $signalementsValides }}/{{ $totalSignalements }} signalements</div></div></div>
          </div>
          <div style="position:relative;height:190px;"><canvas id="signalementsValidationChart"></canvas></div>
        </div>
      </div>
    </div>
  </div>

  {{-- ── LIGNE 5 : MÉTRIQUES MÉTÉO & CONSEILS ── --}}
  <div class="row g-4 mb-4">

    {{-- Statistiques Températures --}}
    <div class="col-xl-5">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-2 border-bottom">
          <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-thermometer-three-quarters text-danger me-2"></i>Statistiques Climatologiques des Alertes</h5>
          <small class="text-muted">Températures et indices enregistrés sur toutes les alertes</small>
        </div>
        <div class="card-body p-4">
          @if($tempStats && $tempStats->temp_max_moy)
          <div class="row g-3">
            <div class="col-6">
              <div class="p-3 rounded-3 text-center" style="background: linear-gradient(135deg, #fee2e2, #fecaca);">
                <div class="small text-danger fw-bold mb-1"><i class="fa-solid fa-temperature-high me-1"></i>Temp. Max Moy.</div>
                <div class="fs-3 fw-black text-danger">{{ $tempStats->temp_max_moy }}°C</div>
              </div>
            </div>
            <div class="col-6">
              <div class="p-3 rounded-3 text-center" style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
                <div class="small text-warning fw-bold mb-1"><i class="fa-solid fa-fire me-1"></i>Record Absolu</div>
                <div class="fs-3 fw-black text-warning">{{ $tempStats->temp_max_record }}°C</div>
              </div>
            </div>
            <div class="col-6">
              <div class="p-3 rounded-3 text-center" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe);">
                <div class="small text-primary fw-bold mb-1"><i class="fa-solid fa-droplet me-1"></i>Humidité Moy.</div>
                <div class="fs-3 fw-black text-primary">{{ $tempStats->humidite_moy ?? '--' }}%</div>
              </div>
            </div>
            <div class="col-6">
              <div class="p-3 rounded-3 text-center" style="background: linear-gradient(135deg, #fefce8, #fef08a);">
                <div class="small fw-bold mb-1" style="color: #d97706;"><i class="fa-solid fa-sun me-1"></i>UV Maximal</div>
                <div class="fs-3 fw-black" style="color: #d97706;">{{ $tempStats->uv_max ?? '--' }}</div>
              </div>
            </div>
          </div>
          <div class="mt-3 p-3 rounded-3 bg-light border">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <span class="small text-muted fw-semibold">Température Ressentie Moy.</span>
              <span class="fw-bold text-dark">{{ $tempStats->ressentie_moy ?? '--' }}°C</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <span class="small text-muted fw-semibold">UV Moyen</span>
              <span class="fw-bold text-dark">{{ $tempStats->uv_moy ?? '--' }}</span>
            </div>
          </div>
          @else
          <div class="text-center text-muted py-4">
            <i class="fa-solid fa-chart-simple fs-2 mb-2"></i>
            <div>Aucune donnée de température disponible</div>
          </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Graphique Conseils par Catégorie --}}
    <div class="col-xl-4">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-0">
          <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-heart-pulse text-success me-2"></i>Conseils Santé par Catégorie</h5>
          <small class="text-muted">Répartition thématique des conseils publiés</small>
        </div>
        <div class="card-body px-4 pt-3 pb-4">
          <div style="position: relative; height: 300px;">
            <canvas id="conseilsChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    {{-- Alertes Critiques --}}
    <div class="col-xl-3">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-2 border-bottom d-flex align-items-center justify-content-between">
          <h5 class="fw-bold mb-0 text-dark fs-6 text-truncate pe-2">
            <i class="fa-solid fa-fire text-danger me-2"></i>Alertes Critiques
          </h5>
          <span class="badge bg-danger rounded-pill flex-shrink-0">{{ $alertesCritiques->count() }}</span>
        </div>
        <div class="card-body p-3 d-flex flex-column gap-2" style="max-height: 310px; overflow-y: hidden; overflow-x: hidden;">
          @forelse($alertesCritiques as $a)
          @php
            $nBg = $a->niveau === 'rouge' ? '#dc2626' : '#ea580c';
          @endphp
          <a href="{{ route('admin.alertes.show', $a) }}" class="text-decoration-none p-2 px-3 rounded-3 border d-block position-relative transition-all" style="background: {{ $nBg }}08; border-color: {{ $nBg }}35 !important; transition: all 0.2s ease;">
            <div class="d-flex align-items-center justify-content-between gap-2" style="min-width: 0;">
              <div style="min-width: 0; flex: 1 1 0; overflow: hidden;">
                <div class="fw-bold text-dark small text-truncate" title="{{ $a->titre }}" style="font-size: 12.5px; line-height: 1.3;">
                  {{ \Illuminate\Support\Str::limit($a->titre, 24) }}
                </div>
                <div class="text-muted text-truncate d-flex align-items-center gap-1 mt-0.5" style="font-size: 11px;">
                  <i class="fa-solid fa-location-dot text-danger flex-shrink-0" style="font-size: 10px;"></i>
                  <span class="text-truncate">{{ \Illuminate\Support\Str::limit($a->zone->nom ?? 'National', 18) }}</span>
                </div>
              </div>
              <span class="badge fw-bold flex-shrink-0 text-white" style="background: {{ $nBg }}; font-size: 9px; padding: 4px 7px; letter-spacing: 0.3px;">
                {{ strtoupper($a->niveau) }}
              </span>
            </div>
          </a>
          @empty
          <div class="text-center text-muted py-4">
            <i class="fa-solid fa-circle-check fs-2 text-success mb-2"></i>
            <div class="small">Aucune alerte critique active</div>
          </div>
          @endforelse
        </div>
      </div>
    </div>

  {{-- ── SECTION : MODULE POINTS DE FRAÎCHEUR & ÎLOTS URBAINS ── --}}
  <div class="d-flex align-items-center justify-content-between mb-3 mt-4">
    <div>
      <h4 class="fw-bold mb-0 text-dark">
        <i class="fa-solid fa-snowflake text-info me-2"></i>Points de Fraîcheur & Refuges Caniculaires
      </h4>
      <small class="text-muted">Cartographie, accessibilité PMR et retours citoyens des îlots de fraîcheur</small>
    </div>
    <a href="{{ route('admin.points_fraicheur.index') }}" class="m-btn m-btn--ghost m-btn--sm">
      <i class="fa-solid fa-gear me-1"></i>Gérer les points
    </a>
  </div>

  {{-- Cartes KPI Points de Fraîcheur --}}
  <div class="row g-3 mb-4">
    {{-- Total Points --}}
    <div class="col-xl-3 col-md-6">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Points Référencés</div>
            <div class="fs-2 fw-black text-dark mt-1" style="font-weight: 800;">{{ $totaux['points_fraicheur'] ?? 0 }}</div>
            <div class="small text-success mt-1">
              <i class="fa-solid fa-circle-check me-1"></i>{{ $totaux['points_actifs'] ?? 0 }} ouverts & actifs
            </div>
          </div>
          <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #e0f2fe; color: #0284c7; font-size: 20px;">
            <i class="fa-solid fa-snowflake"></i>
          </div>
        </div>
      </div>
    </div>

    {{-- Accessibilité PMR --}}
    <div class="col-xl-3 col-md-6">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Accessibilité PMR</div>
            @php
              $pmrCount = $totaux['points_pmr'] ?? 0;
              $pmrTotal = max(1, $totaux['points_fraicheur'] ?? 1);
              $pmrPct = round(($pmrCount / $pmrTotal) * 100);
            @endphp
            <div class="fs-2 fw-black text-success mt-1" style="font-weight: 800;">{{ $pmrPct }}%</div>
            <div class="small text-muted mt-1">
              <i class="fa-solid fa-wheelchair me-1 text-primary"></i>{{ $pmrCount }} points adaptés
            </div>
          </div>
          <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #dcfce7; color: #16a34a; font-size: 20px;">
            <i class="fa-solid fa-universal-access"></i>
          </div>
        </div>
      </div>
    </div>

    {{-- Avis Citoyens --}}
    <div class="col-xl-3 col-md-6">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Avis Citoyens</div>
            <div class="fs-2 fw-black text-dark mt-1" style="font-weight: 800;">{{ $totaux['total_avis'] ?? 0 }}</div>
            <div class="small text-muted mt-1">
              <a href="{{ route('admin.avis_points.index') }}" class="text-decoration-none text-primary">
                <i class="fa-solid fa-comments me-1"></i>Modérer les avis
              </a>
            </div>
          </div>
          <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #fef3c7; color: #d97706; font-size: 20px;">
            <i class="fa-solid fa-star-half-stroke"></i>
          </div>
        </div>
      </div>
    </div>

    {{-- Note Moyenne Globale --}}
    <div class="col-xl-3 col-md-6">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Satisfaction Moy.</div>
            <div class="fs-2 fw-black text-warning mt-1" style="font-weight: 800;">
              {{ $totaux['note_moyenne_points'] ?? 0 }}<span class="fs-6 text-muted fw-normal">/5</span>
            </div>
            <div class="small mt-1" style="color: #f59e0b;">
              @php $nm = round($totaux['note_moyenne_points'] ?? 0); @endphp
              @for($s=1; $s<=5; $s++)
                <i class="fa-{{ $s <= $nm ? 'solid' : 'regular' }} fa-star" style="font-size:11px;"></i>
              @endfor
            </div>
          </div>
          <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #fffbeb; color: #f59e0b; font-size: 20px;">
            <i class="fa-solid fa-medal"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Graphiques Points de Fraîcheur --}}
  <div class="row g-4 mb-4">
    {{-- Répartition par Type --}}
    <div class="col-xl-4 col-lg-6">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-0">
          <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-shapes text-info me-2"></i>Typologie des Espaces</h5>
          <small class="text-muted">Parcs, salles climatisées, fontaines, etc.</small>
        </div>
        <div class="card-body px-4 pt-3 pb-4">
          <div style="position: relative; height: 260px;">
            <canvas id="pointsParTypeChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    {{-- Évaluation Citoyenne (Notes 1 à 5) --}}
    <div class="col-xl-4 col-lg-6">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-0">
          <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-star text-warning me-2"></i>Distribution des Évaluations</h5>
          <small class="text-muted">Répartition des notes citoyennes (1 à 5 étoiles)</small>
        </div>
        <div class="card-body px-4 pt-3 pb-4">
          <div style="position: relative; height: 260px;">
            <canvas id="avisParNoteChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    {{-- Points par zone --}}
    <div class="col-xl-4 col-lg-12">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-0">
          <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-map-pin text-primary me-2"></i>Disponibilité par Zone</h5>
          <small class="text-muted">Nombre de points frais par délégation / ville</small>
        </div>
        <div class="card-body px-4 pt-3 pb-4">
          <div style="position: relative; height: 260px;">
            <canvas id="pointsParZoneChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Top Points Frais les mieux notés --}}
  <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
    <div class="card-header bg-white border-0 px-4 pt-4 pb-2 border-bottom d-flex align-items-center justify-content-between">
      <div>
        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-award text-warning me-2"></i>Top Lieux Frais Recommandés par les Citoyens</h5>
        <small class="text-muted">Points de fraîcheur ayant les meilleures notes moyennes</small>
      </div>
      <a href="{{ route('admin.points_fraicheur.index') }}" class="m-btn m-btn--ghost m-btn--sm">
        <i class="fa-solid fa-arrow-right me-1"></i>Tous les points
      </a>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
          <thead class="bg-light">
            <tr>
              <th class="px-4 py-3 text-muted fw-semibold">POINT DE FRAÎCHEUR</th>
              <th class="py-3 text-muted fw-semibold text-center">TYPE</th>
              <th class="py-3 text-muted fw-semibold text-center">ZONE</th>
              <th class="py-3 text-muted fw-semibold text-center">ACCÈS PMR</th>
              <th class="py-3 text-muted fw-semibold text-center">AVIS CITOYENS</th>
              <th class="py-3 pe-4 text-muted fw-semibold text-center">NOTE MOYENNE</th>
            </tr>
          </thead>
          <tbody>
            @forelse($topPointsMieuxNotes as $pt)
            <tr>
              <td class="px-4 py-3">
                <a href="{{ route('admin.points_fraicheur.show', $pt) }}" class="fw-bold text-dark text-decoration-none">
                  {{ $pt->nom }}
                </a>
                <div class="text-muted small">{{ Str::limit($pt->adresse, 50) }}</div>
              </td>
              <td class="py-3 text-center">
                @php
                  $icon  = \App\Models\PointFraicheur::$typeIcons[$pt->type]  ?? 'fa-location-dot';
                  $color = \App\Models\PointFraicheur::$typeColors[$pt->type] ?? '#64748b';
                  $label = \App\Models\PointFraicheur::$typeLabels[$pt->type] ?? ucfirst($pt->type);
                @endphp
                <span class="badge rounded-pill" style="background:{{ $color }}18;color:{{ $color }};border:1px solid {{ $color }}35;font-size:11px;">
                  <i class="fa-solid {{ $icon }} me-1"></i>{{ $label }}
                </span>
              </td>
              <td class="py-3 text-center text-muted">{{ $pt->zone?->nom ?? '—' }}</td>
              <td class="py-3 text-center">
                @if($pt->accessible_pmr)
                  <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1"><i class="fa-solid fa-wheelchair me-1"></i>Oui</span>
                @else
                  <span class="badge bg-light text-muted border rounded-pill px-2 py-1">Non</span>
                @endif
              </td>
              <td class="py-3 text-center">
                <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1">{{ $pt->avis_points_count }} avis</span>
              </td>
              <td class="py-3 pe-4 text-center">
                <span style="color:#f59e0b;font-weight:700;">
                  @for($s = 1; $s <= 5; $s++)
                    <i class="fa-{{ $s <= round($pt->avis_points_avg_note) ? 'solid' : 'regular' }} fa-star" style="font-size:12px;"></i>
                  @endfor
                  <span class="ms-1 text-dark fw-bold">({{ number_format($pt->avis_points_avg_note, 1) }}/5)</span>
                </span>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center py-4 text-muted">
                <i class="fa-solid fa-snowflake fa-2x mb-2 d-block text-info"></i>
                Aucun avis pour le moment sur les points de fraîcheur.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- ── LIGNE 5 : TABLEAU RÉCAPITULATIF DES DERNIÈRES ALERTES ── --}}
  <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
    <div class="card-header bg-white border-0 px-4 pt-4 pb-2 border-bottom d-flex align-items-center justify-content-between">
      <div>
        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>Historique Récent des Alertes</h5>
        <small class="text-muted">Les 8 dernières alertes créées sur la plateforme</small>
      </div>
      <a href="{{ route('admin.alertes.index') }}" class="m-btn m-btn--ghost m-btn--sm">
        <i class="fa-solid fa-list me-1"></i>Tout voir
      </a>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
          <thead class="bg-light">
            <tr>
              <th class="px-4 py-3 text-muted fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">TITRE</th>
              <th class="py-3 text-muted fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">NIVEAU</th>
              <th class="py-3 text-muted fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">STATUT</th>
              <th class="py-3 text-muted fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">ZONE</th>
              <th class="py-3 text-muted fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">TEMP. MAX</th>
              <th class="py-3 pe-4 text-muted fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">CRÉÉE LE</th>
            </tr>
          </thead>
          <tbody>
            @foreach($dernieresAlertes as $alerte)
            @php
              $niveauConfig = [
                'rouge'  => ['bg' => '#dc2626', 'text' => '#fff'],
                'orange' => ['bg' => '#ea580c', 'text' => '#fff'],
                'jaune'  => ['bg' => '#eab308', 'text' => '#000'],
                'vert'   => ['bg' => '#22c55e', 'text' => '#fff'],
              ][$alerte->niveau] ?? ['bg' => '#94a3b8', 'text' => '#fff'];
              $statutConfig = [
                'active'   => 'bg-success-subtle text-success border-success-subtle',
                'brouillon'=> 'bg-secondary-subtle text-secondary border-secondary-subtle',
                'terminee' => 'bg-info-subtle text-info border-info-subtle',
                'annulee'  => 'bg-danger-subtle text-danger border-danger-subtle',
              ][$alerte->statut] ?? 'bg-light text-dark';
            @endphp
            <tr>
              <td class="px-4 py-3">
                <div class="fw-semibold text-dark" style="max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $alerte->titre }}">
                  {{ $alerte->titre }}
                </div>
                <div class="text-muted" style="font-size: 11px;">{{ ucfirst(str_replace('_', ' ', $alerte->type)) }}</div>
              </td>
              <td class="py-3">
                <span class="badge fw-bold rounded-pill px-2 py-1 text-uppercase" style="background: {{ $niveauConfig['bg'] }}; color: {{ $niveauConfig['text'] }}; font-size: 10px; letter-spacing: 0.3px;">
                  {{ $alerte->niveau }}
                </span>
              </td>
              <td class="py-3">
                <span class="badge border {{ $statutConfig }} rounded-pill px-2 py-1" style="font-size: 10px;">{{ ucfirst($alerte->statut) }}</span>
              </td>
              <td class="py-3">
                <span class="text-muted">{{ $alerte->zone->nom ?? '—' }}</span>
              </td>
              <td class="py-3">
                @if($alerte->temperature_max)
                  <span class="fw-bold {{ $alerte->temperature_max >= 40 ? 'text-danger' : ($alerte->temperature_max >= 35 ? 'text-warning' : 'text-dark') }}">
                    {{ $alerte->temperature_max }}°C
                  </span>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>
              <td class="py-3 pe-4 text-muted">{{ $alerte->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection

@push('styles')
<style>
  .card { transition: transform 0.25s ease, box-shadow 0.25s ease; }
  .card:hover { box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
  Chart.defaults.font.family = "'Inter', 'Roboto', sans-serif";
  Chart.defaults.color = '#64748b';

  const tooltipStyle = {
    backgroundColor: '#0f172a',
    padding: 12,
    cornerRadius: 10,
    titleFont: { weight: 700 },
    callbacks: {}
  };

  // ── 1. Évolution Mensuelle des Alertes (12 Derniers Mois) ──
  const moisData = @json($alertesParMois);
  const moisLabels = Object.keys(moisData).map(m => {
    const parts = m.split('-');
    const year = parseInt(parts[0], 10);
    const month = parseInt(parts[1], 10) - 1;
    const d = new Date(year, month, 1);
    const str = d.toLocaleDateString('fr-FR', { month: 'short' });
    return (str.charAt(0).toUpperCase() + str.slice(1)).replace('.', '') + ' ' + year.toString().slice(-2);
  });
  const moisValues = Object.values(moisData).map(v => Number(v) || 0);

  const canvasMois = document.getElementById('alertesParMoisChart');
  if (canvasMois) {
    const ctx = canvasMois.getContext('2d');
    const grad = ctx.createLinearGradient(0, 0, 0, 240);
    grad.addColorStop(0, 'rgba(220, 38, 38, 0.28)');
    grad.addColorStop(1, 'rgba(220, 38, 38, 0.01)');

    new Chart(canvasMois, {
      type: 'line',
      data: {
        labels: moisLabels,
        datasets: [{
          label: 'Alertes créées',
          data: moisValues,
          borderColor: '#dc2626',
          backgroundColor: grad,
          borderWidth: 3,
          fill: true,
          tension: 0.35,
          pointRadius: 4,
          pointHoverRadius: 7,
          pointBackgroundColor: '#dc2626',
          pointBorderColor: '#ffffff',
          pointBorderWidth: 2,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            ...tooltipStyle,
            callbacks: {
              label: (c) => ` ${c.parsed.y} alerte${c.parsed.y > 1 ? 's' : ''}`
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
              precision: 0,
              callback: v => Number.isInteger(v) ? v : ''
            },
            grid: { color: 'rgba(0,0,0,0.05)' }
          },
          x: {
            grid: { display: false },
            ticks: { font: { size: 11 } }
          }
        }
      }
    });
  }

  // ── 2. Donut Niveaux ──
  new Chart(document.getElementById('niveauDonutChart'), {
    type: 'doughnut',
    data: {
      labels: ['Rouge', 'Orange', 'Jaune', 'Vert'],
      datasets: [{
        data: [{{ $alertesParNiveau['rouge'] }}, {{ $alertesParNiveau['orange'] }}, {{ $alertesParNiveau['jaune'] }}, {{ $alertesParNiveau['vert'] }}],
        backgroundColor: ['#dc2626', '#ea580c', '#eab308', '#22c55e'],
        borderWidth: 4,
        borderColor: '#fff',
        hoverOffset: 8
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      cutout: '68%',
      plugins: {
        legend: { display: false },
        tooltip: { ...tooltipStyle, callbacks: { label: (c) => ` ${c.label}: ${c.parsed} alertes` } }
      }
    }
  });

  // ── 3. Top Zones (barres horizontales) ──
  const zonesData = @json($topZones->map(fn($z) => ['nom' => $z->nom, 'count' => $z->alertes_count]));
  new Chart(document.getElementById('topZonesChart'), {
    type: 'bar',
    data: {
      labels: zonesData.map(z => z.nom),
      datasets: [{
        label: 'Nombre d\'alertes',
        data: zonesData.map(z => z.count),
        backgroundColor: zonesData.map((_, i) => `hsla(${355 - i * 15}, 75%, ${50 + i * 3}%, 0.85)`),
        borderRadius: 6,
        borderSkipped: false,
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true, maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: { ...tooltipStyle, callbacks: { label: (c) => ` ${c.parsed.x} alertes` } }
      },
      scales: {
        x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.04)' } },
        y: { grid: { display: false }, ticks: { font: { size: 11 } } }
      }
    }
  });

  // ── 4. Types d'alertes (radar) ──
  const typesData = @json($alertesParType->toArray());
  const typeLabels = Object.keys(typesData).map(t => t.replace('_', ' ').replace(/\b\w/g, c => c.toUpperCase()));
  new Chart(document.getElementById('typeChart'), {
    type: 'polarArea',
    data: {
      labels: typeLabels,
      datasets: [{
        data: Object.values(typesData),
        backgroundColor: ['rgba(220,38,38,.75)', 'rgba(234,88,12,.75)', 'rgba(234,179,8,.75)', 'rgba(34,197,94,.75)', 'rgba(99,102,241,.75)'],
        borderWidth: 2,
        borderColor: '#fff'
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: {
        legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, font: { size: 11 } } },
        tooltip: { ...tooltipStyle }
      }
    }
  });

  // ── 5. Statuts ──
  new Chart(document.getElementById('statutChart'), {
    type: 'pie',
    data: {
      labels: ['Active', 'Brouillon', 'Terminée', 'Annulée'],
      datasets: [{
        data: [{{ $alertesParStatut['active'] }}, {{ $alertesParStatut['brouillon'] }}, {{ $alertesParStatut['terminee'] }}, {{ $alertesParStatut['annulee'] }}],
        backgroundColor: ['#22c55e', '#94a3b8', '#0ea5e9', '#ef4444'],
        borderWidth: 3, borderColor: '#fff'
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: { ...tooltipStyle }
      }
    }
  });

  // ── 6. Coupures par type ──
  const coupuresTypes = @json($coupuresParType->toArray());
  new Chart(document.getElementById('coupuresParTypeChart'), {
    type: 'doughnut',
    data: {
      labels: Object.keys(coupuresTypes).map(type => type.replace('_', ' ').replace(/\b\w/g, letter => letter.toUpperCase())),
      datasets: [{
        data: Object.values(coupuresTypes),
        backgroundColor: ['#dc2626', '#f97316', '#eab308'],
        borderWidth: 4,
        borderColor: '#fff'
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { position: 'bottom' }, tooltip: { ...tooltipStyle } }
    }
  });

  // ── 7. Coupures par mois ──
  const coupuresMois = @json($coupuresParMois->toArray());
  const coupuresMoisLabels = Object.keys(coupuresMois).map(mois => {
    const [annee, numeroMois] = mois.split('-').map(Number);
    return new Date(annee, numeroMois - 1, 1).toLocaleDateString('fr-FR', { month: 'short', year: '2-digit' });
  });
  new Chart(document.getElementById('coupuresParMoisChart'), {
    type: 'line',
    data: {
      labels: coupuresMoisLabels,
      datasets: [{
        label: 'Coupures',
        data: Object.values(coupuresMois),
        borderColor: '#f97316',
        backgroundColor: 'rgba(249,115,22,.14)',
        fill: true,
        tension: .35,
        borderWidth: 3
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false }, tooltip: { ...tooltipStyle } },
      scales: { y: { beginAtZero: true, ticks: { precision: 0 } }, x: { grid: { display: false } } }
    }
  });

  // ── 8. Coupures par zone ──
  const coupuresZones = @json($coupuresParZone->map(fn ($zone) => ['nom' => $zone->nom, 'total' => $zone->coupures_count])->values());
  new Chart(document.getElementById('coupuresParZoneChart'), {
    type: 'bar',
    data: {
      labels: coupuresZones.map(zone => zone.nom),
      datasets: [{
        label: 'Coupures',
        data: coupuresZones.map(zone => zone.total),
        backgroundColor: '#2563eb',
        borderRadius: 6
      }]
    },
    options: {
      indexAxis: 'y', responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false }, tooltip: { ...tooltipStyle } },
      scales: { x: { beginAtZero: true, ticks: { precision: 0 } }, y: { grid: { display: false } } }
    }
  });

  // ── 9. Taux de signalements validés ──
  new Chart(document.getElementById('signalementsValidationChart'), {
    type: 'doughnut',
    data: {
      labels: ['Validés', 'Non validés'],
      datasets: [{
        data: [{{ $signalementsValides }}, {{ max(0, $totalSignalements - $signalementsValides) }}],
        backgroundColor: ['#16a34a', '#e2e8f0'],
        borderWidth: 3,
        borderColor: '#fff'
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false, cutout: '68%',
      plugins: { legend: { position: 'bottom' }, tooltip: { ...tooltipStyle } }
    }
  });

  // ── 10. Conseils par catégorie ──
  const conseilsData = @json($conseilsParCategorie->toArray());
  const conseilLabels = Object.keys(conseilsData);
  new Chart(document.getElementById('conseilsChart'), {
    type: 'bar',
    data: {
      labels: conseilLabels,
      datasets: [{
        label: 'Conseils',
        data: Object.values(conseilsData),
        backgroundColor: 'rgba(5, 150, 105, 0.8)',
        borderRadius: 8,
        borderSkipped: false,
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: { ...tooltipStyle, callbacks: { label: (c) => ` ${c.parsed.y} conseil(s)` } }
      },
      scales: {
        y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.04)' } },
        x: { grid: { display: false }, ticks: { font: { size: 11 } } }
      }
    }
  });

  // ── 11. Points de fraîcheur par type ──
  const pointsTypes = @json($pointsParType->toArray());
  const typeColorsMap = {
    'parc': '#16a34a',
    'salle_climatisee': '#2563eb',
    'fontaine': '#0ea5e9',
    'piscine': '#06b6d4',
    'bibliotheque': '#7c3aed',
    'autre': '#64748b'
  };
  const typeLabelsMap = {
    'parc': 'Parc / Espace vert',
    'salle_climatisee': 'Salle climatisée',
    'fontaine': 'Fontaine / Borne',
    'piscine': 'Piscine municipale',
    'bibliotheque': 'Bibliothèque',
    'autre': 'Autre'
  };

  const canvasPointsType = document.getElementById('pointsParTypeChart');
  if (canvasPointsType) {
    const pKeys = Object.keys(pointsTypes);
    new Chart(canvasPointsType, {
      type: 'doughnut',
      data: {
        labels: pKeys.map(k => typeLabelsMap[k] || k),
        datasets: [{
          data: Object.values(pointsTypes),
          backgroundColor: pKeys.map(k => typeColorsMap[k] || '#94a3b8'),
          borderWidth: 3,
          borderColor: '#fff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
          legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } },
          tooltip: { ...tooltipStyle, callbacks: { label: (c) => ` ${c.label}: ${c.parsed} point(s)` } }
        }
      }
    });
  }

  // ── 12. Répartition des Avis par Note ──
  const avisNotesData = @json($avisParNote);
  const canvasAvisNote = document.getElementById('avisParNoteChart');
  if (canvasAvisNote) {
    new Chart(canvasAvisNote, {
      type: 'bar',
      data: {
        labels: ['★★★★★ 5', '★★★★ 4', '★★★ 3', '★★ 2', '★ 1'],
        datasets: [{
          label: 'Avis',
          data: [
            avisNotesData[5] || 0,
            avisNotesData[4] || 0,
            avisNotesData[3] || 0,
            avisNotesData[2] || 0,
            avisNotesData[1] || 0
          ],
          backgroundColor: ['#22c55e', '#84cc16', '#eab308', '#f97316', '#ef4444'],
          borderRadius: 6
        }]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: { ...tooltipStyle, callbacks: { label: (c) => ` ${c.parsed.x} avis` } }
        },
        scales: {
          x: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 }, grid: { color: 'rgba(0,0,0,0.04)' } },
          y: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
      }
    });
  }

  // ── 13. Points de fraîcheur par zone ──
  const pointsZonesData = @json($pointsParZone->map(fn($z) => ['nom' => $z->nom, 'total' => $z->points_fraicheur_count])->values());
  const canvasPointsZone = document.getElementById('pointsParZoneChart');
  if (canvasPointsZone) {
    new Chart(canvasPointsZone, {
      type: 'bar',
      data: {
        labels: pointsZonesData.map(z => z.nom),
        datasets: [{
          label: 'Points de fraîcheur',
          data: pointsZonesData.map(z => z.total),
          backgroundColor: '#0ea5e9',
          borderRadius: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: { ...tooltipStyle, callbacks: { label: (c) => ` ${c.parsed.y} point(s)` } }
        },
        scales: {
          y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 }, grid: { color: 'rgba(0,0,0,0.04)' } },
          x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
      }
    });
  }

})();
</script>
@endpush
