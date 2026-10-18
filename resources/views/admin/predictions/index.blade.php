@extends('layouts.admin.admin')

@section('title', 'Prédictions de coupures')

@section('content')
<div class="page-header">
  <div>
    <h1><i class="fa-solid fa-wand-magic-sparkles me-2 text-primary"></i>Prédictions de coupures</h1>
    <p class="subtitle">Dernier résultat disponible par zone, avec sa période de validité.</p>
  </div>
  <div class="page-header__actions d-flex align-items-center gap-2">
    <button type="button" id="btn-predict-all" class="btn btn-primary fw-semibold px-3 py-2 shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#predictAllConfirmModal">
      <i class="fa-solid fa-arrows-rotate"></i>
      <span>Prédire pour toutes les zones</span>
    </button>
  </div>
</div>

<div id="prediction-error" class="alert alert-danger d-none" role="alert"></div>

<div class="m-card mb-4 p-3" style="border-radius:14px;background:#ffffff;box-shadow:0 2px 10px rgba(0,0,0,0.03);">
  <form action="{{ route('admin.predictions.index') }}" method="GET">
    <div class="row g-2 align-items-center">
      <div class="col-lg-8 col-md-9">
        <div class="input-group" style="border-radius:10px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
          <span class="input-group-text bg-white border-end-0 text-muted" style="border-color:#e2e8f0;padding-left:14px;">
            <i class="fa-solid fa-magnifying-glass"></i>
          </span>
          <input type="search" name="search" class="form-control border-start-0" style="border-color:#e2e8f0;" value="{{ request('search') }}" placeholder="Rechercher une zone, une ville ou un gouvernorat…">
          <button type="submit" class="btn px-3 fw-semibold text-white" style="background:#0ea5e9;border-color:#0ea5e9;">Rechercher</button>
        </div>
      </div>
      <div class="col-lg-4 col-md-3 d-flex justify-content-md-end">
        @if(request('search'))
          <a href="{{ route('admin.predictions.index') }}" class="btn btn-outline-secondary w-100 w-md-auto">Réinitialiser</a>
        @else
          <span class="text-muted small align-self-center">{{ $zones->total() }} zone(s)</span>
        @endif
      </div>
    </div>
  </form>
</div>

<div class="row g-4">
  @forelse($zones as $zone)
    @php
      $prediction = $zone->latestPrediction;
      $isStale = $prediction && $prediction->created_at->lt(now()->subMinutes($staleAfterMinutes));
      $probability = $prediction?->probability;
      $inputSnapshot = $prediction?->input_snapshot ?? [];
      $riskClass = $prediction?->risk_level === 'high' ? 'danger' : ($prediction?->risk_level === 'medium' ? 'warning' : 'success');
      $displayVariables = [
        'temperature_max' => 'Température max',
        'temperature_ressentie' => 'Ressenti',
        'humidite' => 'Humidité',
        'vitesse_vent' => 'Vent',
        'nb_coupures_7j' => 'Coupures sur 7 jours',
        'nb_signalements_valides_7j' => 'Signalements validés sur 7 jours',
      ];
    @endphp
    <div class="col-xl-6">
      <article class="m-card h-100" style="overflow:hidden;">
        <div class="d-flex justify-content-between align-items-start gap-3 p-3 border-bottom" style="border-color:#eef2f7 !important;">
          <div>
            <div class="d-flex align-items-center gap-2">
              <span class="d-inline-flex align-items-center justify-content-center rounded-3" style="width:38px;height:38px;background:rgba(14,165,233,.1);color:#0284c7;"><i class="fa-solid fa-location-dot"></i></span>
              <div>
                <h2 class="h5 mb-0">{{ $zone->nom }}</h2>
                <div class="small text-muted">{{ $zone->ville }}{{ $zone->gouvernorat ? ' · ' . $zone->gouvernorat : '' }}</div>
              </div>
            </div>
          </div>
          @if($prediction && $isStale)
            <span class="badge bg-warning text-dark" title="Plus de 24 heures">
              <i class="fa-solid fa-clock me-1"></i>Ancienne
            </span>
          @elseif($prediction)
            <span class="badge bg-success-subtle text-success border border-success-subtle">Disponible</span>
          @else
            <span class="badge bg-light text-secondary border">Aucune donnée</span>
          @endif
        </div>

        <div class="p-3">
          @if($prediction)
            <div class="row g-3 align-items-center mb-3">
              <div class="col-sm-5">
                <div class="text-muted text-uppercase fw-bold" style="font-size:.7rem;letter-spacing:.08em;">Probabilité estimée</div>
                <div class="display-6 fw-bold text-primary">{{ number_format($probability * 100, 1, ',', ' ') }}<span class="fs-5">%</span></div>
                <small class="text-muted">Risque de coupure dans les 24h</small>
              </div>
              <div class="col-sm-7">
                <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                  <div class="small text-muted mb-1"><i class="fa-solid fa-calendar-day me-1"></i>Période de surveillance</div>
                  <strong class="d-block small">{{ $prediction->target_window_start?->format('d/m/Y H:i') }} → {{ $prediction->target_window_end?->format('d/m/Y H:i') }}</strong>

                  @if($prediction->estimated_outage_start && $prediction->estimated_outage_end)
                    <div class="mt-2 pt-2 border-top">
                      <div class="small fw-bold text-danger">
                        <i class="fa-solid fa-bolt me-1"></i>Coupure estimée :
                      </div>
                      <div class="fw-bold text-dark small">
                        {{ $prediction->estimated_outage_start->format('d/m/Y H:i') }} → {{ $prediction->estimated_outage_end->format('H:i') }}
                      </div>
                      @if($prediction->estimated_outage_cause)
                        <div class="text-muted" style="font-size:.75rem;">
                          <i class="fa-solid fa-circle-info me-1"></i>{{ $prediction->estimated_outage_cause }}
                        </div>
                      @endif
                    </div>
                  @endif

                  <div class="small text-muted mt-2" style="font-size:.75rem;">Mise à jour {{ $prediction->created_at?->diffForHumans() }}</div>
                </div>
              </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mb-3">
              @if($prediction->risk_level)
                @php
                  $riskLabel = match($prediction->risk_level) {
                    'high'   => ['class'=>'danger',  'icon'=>'fa-triangle-exclamation', 'text'=>'Risque élevé'],
                    'medium' => ['class'=>'warning',  'icon'=>'fa-circle-exclamation',  'text'=>'Risque modéré'],
                    default  => ['class'=>'success',  'icon'=>'fa-circle-check',         'text'=>'Risque faible'],
                  };
                @endphp
                <span class="badge bg-{{ $riskLabel['class'] }}"><i class="fa-solid {{ $riskLabel['icon'] }} me-1"></i>{{ $riskLabel['text'] }}</span>
              @endif
              @if($isStale)
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Actualiser recommandé</span>
              @endif
            </div>

            {{-- Variables météo & historique --}}
            @php
              $unit = [
                'temperature_max'            => ['label'=>'Temp. max',             'unit'=>'°C',  'icon'=>'fa-temperature-high'],
                'temperature_ressentie'      => ['label'=>'Ressenti',              'unit'=>'°C',  'icon'=>'fa-thermometer-half'],
                'humidite'                   => ['label'=>'Humidité',              'unit'=>'%',   'icon'=>'fa-droplet'],
                'vitesse_vent'               => ['label'=>'Vent',                  'unit'=>'km/h','icon'=>'fa-wind'],
                'nb_coupures_24h'            => ['label'=>'Coupures (24 h)',       'unit'=>'',    'icon'=>'fa-bolt'],
                'nb_coupures_7j'             => ['label'=>'Coupures (7 j)',        'unit'=>'',    'icon'=>'fa-bolt'],
                'nb_coupures_30j'            => ['label'=>'Coupures (30 j)',       'unit'=>'',    'icon'=>'fa-bolt'],
                'nb_signalements_valides_7j' => ['label'=>'Signalements (7 j)',    'unit'=>'',    'icon'=>'fa-flag'],
                'jours_depuis_coupure'       => ['label'=>'Dernière coupure',      'unit'=>'j',   'icon'=>'fa-clock-rotate-left'],
              ];
              $hasWeather = !is_null($inputSnapshot['temperature_max'] ?? null)
                         || !is_null($inputSnapshot['humidite'] ?? null);
            @endphp

            <div class="border rounded-3 p-3 mb-3" style="background:#f8fafc;">
              <div class="small fw-semibold text-secondary mb-2">
                <i class="fa-solid fa-chart-bar me-1"></i>Données utilisées pour la prédiction
              </div>

              @if(!$hasWeather)
                <div class="alert alert-info py-2 px-3 small mb-2">
                  <i class="fa-solid fa-circle-info me-1"></i>
                  Aucune alerte météo active pour cette zone — les données climatiques ne sont pas disponibles.
                </div>
              @endif

              <div class="row g-2">
                @foreach($unit as $key => $meta)
                  @if(array_key_exists($key, $inputSnapshot))
                    @php $val = $inputSnapshot[$key]; @endphp
                    <div class="col-6 col-md-4">
                      <div class="p-2 rounded-2 h-100" style="background:white;border:1px solid #e2e8f0;">
                        <div class="small text-muted mb-1">
                          <i class="fa-solid {{ $meta['icon'] }} me-1 text-primary" style="font-size:.7rem;"></i>{{ $meta['label'] }}
                        </div>
                        @if($val === null)
                          <span class="small text-muted fst-italic">—</span>
                        @else
                          <strong class="small">{{ is_float($val) ? number_format($val, 1, ',', ' ') : $val }}{{ $meta['unit'] ? ' '.$meta['unit'] : '' }}</strong>
                        @endif
                      </div>
                    </div>
                  @endif
                @endforeach
              </div>
            </div>
          @else
            <div class="text-center py-4">
              <span class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:54px;height:54px;background:#f1f5f9;color:#64748b;font-size:1.25rem;"><i class="fa-solid fa-chart-line"></i></span>
              <h3 class="h6">Aucune prédiction disponible</h3>
              <p class="text-muted small mb-0">Lance une première estimation pour cette zone.</p>
            </div>
          @endif

          <div class="d-flex justify-content-end align-items-center gap-2 pt-2 border-top" style="border-color:#eef2f7 !important;">
            @if($prediction && ($prediction->probability >= 0.70 || $prediction->risk_level === 'high') && $prediction->estimated_outage_start)
              <a href="{{ route('admin.coupures.create', [
                  'zone_id'    => $zone->id,
                  'date_debut' => $prediction->estimated_outage_start->format('Y-m-d\TH:i'),
                  'date_fin'   => $prediction->estimated_outage_end ? $prediction->estimated_outage_end->format('Y-m-d\TH:i') : '',
                  'type'       => str_contains(strtolower($prediction->estimated_outage_cause ?? ''), 'surcharge') ? 'surcharge' : 'delestage',
                  'cause'      => 'Surcharge thermique suite prédiction météo (' . number_format($prediction->probability * 100, 1) . '% de risque)',
              ]) }}" class="m-btn btn-danger text-white fw-semibold" style="height:34px;padding:0 12px;font-size:12px;background:#dc2626;border-color:#dc2626;white-space:nowrap;" title="Planifier cette coupure préventive (Délestage STEG)">
                <i class="fa-solid fa-bolt me-1"></i>Planifier coupure
              </a>
            @endif
            <a href="{{ route('admin.zones.show', $zone) }}" class="m-btn m-btn--ghost" style="height:34px;padding:0 12px;font-size:12px;white-space:nowrap;">
              <i class="fa-solid fa-eye me-1"></i>Voir la zone
            </a>
            @if($zone->latitude !== null && $zone->longitude !== null)
              <button type="button" class="m-btn m-btn--primary js-predict-zone" style="height:34px;padding:0 12px;font-size:12px;white-space:nowrap;" data-url="{{ route('admin.zones.prediction', $zone) }}">
                <i class="fa-solid fa-rotate me-1"></i> {{ $prediction ? 'Actualiser' : 'Prédire' }}
              </button>
            @else
              <span class="m-btn m-btn--ghost disabled" style="height:34px;padding:0 12px;font-size:12px;opacity:.6;white-space:nowrap;" title="Coordonnées manquantes">
                <i class="fa-solid fa-location-crosshairs me-1"></i>Coordonnées requises
              </span>
            @endif
          </div>
        </div>
      </article>
    </div>
  @empty
    <div class="col-12">
      <div class="m-card text-center py-5">
        <i class="fa-solid fa-map-location-dot fa-2x text-secondary mb-3"></i>
        <h2 class="h5">Aucune zone trouvée</h2>
        <p class="text-muted mb-0">Modifie la recherche ou vérifie les zones enregistrées.</p>
      </div>
    </div>
  @endforelse
</div>

@if($zones->hasPages())
  <div class="d-flex justify-content-center mt-4">{{ $zones->links() }}</div>
@endif
@endsection

{{-- Modal de Confirmation Global placé hors du conteneur pour éviter les conflits de z-index/backdrop --}}
<div class="modal fade" id="predictAllConfirmModal" tabindex="-1" aria-labelledby="predictAllConfirmModalLabel" aria-hidden="true" style="z-index: 1065;">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
      <div class="modal-header border-0 pb-0 pt-4 px-4 position-relative">
        <div class="d-flex align-items-center gap-3">
          <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(14, 165, 233, 0.12); color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-dark mb-0" id="predictAllConfirmModalLabel" style="font-size: 1.15rem;">Prédiction Globale</h5>
            <span class="text-primary small fw-semibold"><i class="fa-solid fa-cloud-bolt me-1"></i>Actualisation automatique</span>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer" style="margin-top: -12px; margin-right: -4px;"></button>
      </div>
      <div class="modal-body px-4 py-3">
        <p class="mb-3 text-secondary" style="font-size: 0.95rem;">
          Voulez-vous lancer le calcul de prédiction de coupures pour <strong>toutes les zones actives</strong> du réseau ?
        </p>
        <div class="p-3 rounded-3 bg-light border border-light-subtle d-flex align-items-center gap-2">
          <i class="fa-solid fa-circle-nodes text-primary flex-shrink-0"></i>
          <span class="fw-bold text-dark text-truncate" style="font-size: 0.92rem;">
            Toutes les zones de surveillance ({{ $zones->total() }} zones)
          </span>
        </div>
        <small class="text-muted mt-2 d-block" style="font-size: 0.8rem;">
          <i class="fa-solid fa-info-circle me-1"></i>Cette opération interroge le modèle ML et la météo en temps réel pour chaque zone.
        </small>
      </div>
      <div class="modal-footer border-0 px-4 pb-4 pt-1 d-flex gap-2">
        <button type="button" class="m-btn m-btn--ghost flex-grow-1 text-center py-2" data-bs-dismiss="modal">
          <i class="fa-solid fa-xmark me-2"></i>Annuler
        </button>
        <button type="button" id="btn-confirm-predict-all" class="btn btn-primary flex-grow-1 py-2 fw-semibold d-flex align-items-center justify-content-center gap-2 shadow-sm" style="border-radius: 8px;" data-url="{{ route('admin.predictions.predict_all') }}">
          <i class="fa-solid fa-wand-magic-sparkles"></i> Lancer les prédictions
        </button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  const errorBox = document.getElementById('prediction-error');

  document.querySelectorAll('.js-predict-zone').forEach(function (button) {
    button.addEventListener('click', async function () {
      const original = button.innerHTML;
      button.disabled = true;
      button.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>Calcul…';
      errorBox.classList.add('d-none');

      try {
        const response = await fetch(button.dataset.url, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
          },
          body: JSON.stringify({}),
        });
        const body = await response.json();
        if (!response.ok || !body.success) {
          throw new Error(body.message || 'Le service de prédiction est indisponible.');
        }
        window.location.reload();
      } catch (error) {
        errorBox.textContent = error.message;
        errorBox.classList.remove('d-none');
        button.disabled = false;
        button.innerHTML = original;
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
    });
  });

  // Prédiction globale pour toutes les zones via la Modal Bootstrap
  const confirmPredictAllBtn = document.getElementById('btn-confirm-predict-all');
  const modalEl = document.getElementById('predictAllConfirmModal');

  if (confirmPredictAllBtn) {
    confirmPredictAllBtn.addEventListener('click', async function () {
      const originalHtml = confirmPredictAllBtn.innerHTML;
      confirmPredictAllBtn.disabled = true;
      confirmPredictAllBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>Calcul en cours…';
      errorBox.classList.add('d-none');

      try {
        const response = await fetch(confirmPredictAllBtn.dataset.url, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
          },
          body: JSON.stringify({}),
        });
        const body = await response.json();
        if (!response.ok || !body.success) {
          throw new Error(body.message || 'Erreur lors du calcul global.');
        }

        // Fermer la modal avant de recharger
        if (modalEl && typeof bootstrap !== 'undefined') {
          const bsModal = bootstrap.Modal.getInstance(modalEl);
          if (bsModal) bsModal.hide();
        }

        window.location.reload();
      } catch (error) {
        errorBox.textContent = error.message;
        errorBox.classList.remove('d-none');
        confirmPredictAllBtn.disabled = false;
        confirmPredictAllBtn.innerHTML = originalHtml;

        // Fermer la modal pour laisser voir l'erreur
        if (modalEl && typeof bootstrap !== 'undefined') {
          const bsModal = bootstrap.Modal.getInstance(modalEl);
          if (bsModal) bsModal.hide();
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
    });
  }
});
</script>
@endpush
