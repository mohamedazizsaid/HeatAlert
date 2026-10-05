@php
  $statut = match($coupure->statut) {
    'en_cours' => ['bg' => '#fee2e2', 'color' => '#b91c1c', 'label' => 'En cours', 'icon' => 'bi-lightning-charge-fill'],
    'prevue' => ['bg' => '#fef3c7', 'color' => '#b45309', 'label' => 'Prévue', 'icon' => 'bi-calendar-event-fill'],
    'terminee' => ['bg' => '#dcfce7', 'color' => '#15803d', 'label' => 'Terminée', 'icon' => 'bi-check-circle-fill'],
    default => ['bg' => '#e2e8f0', 'color' => '#475569', 'label' => ucfirst($coupure->statut), 'icon' => 'bi-info-circle-fill'],
  };
@endphp

<article class="h-100 p-4 bg-white border rounded-4 shadow-sm position-relative overflow-hidden">
  <div class="position-absolute top-0 start-0 w-100" style="height: 4px; background: {{ $statut['color'] }};"></div>
  <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
    <div class="d-flex align-items-center gap-2">
      <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width:42px;height:42px;background:#fff7ed;color:#ea580c;">
        <i class="bi bi-lightning-charge-fill fs-5"></i>
      </span>
      <div>
        <h2 class="h5 mb-1">Coupure {{ ucfirst($coupure->type) }}</h2>
        <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $coupure->zone?->ville ?? 'Zone inconnue' }}</small>
      </div>
    </div>
    <span class="badge rounded-pill" style="background:{{ $statut['bg'] }};color:{{ $statut['color'] }};">
      <i class="bi {{ $statut['icon'] }} me-1"></i>{{ $statut['label'] }}
    </span>
  </div>

  <dl class="row small mb-3">
    <dt class="col-5 text-muted">Début</dt>
    <dd class="col-7 mb-2">{{ $coupure->date_debut?->format('d/m/Y à H:i') }}</dd>
    <dt class="col-5 text-muted">Rétablissement</dt>
    <dd class="col-7 mb-0">{{ $coupure->retablissement_estime?->format('d/m/Y à H:i') ?? 'Non communiqué' }}</dd>
  </dl>

  @if($coupure->cause)
    <p class="small text-muted mb-3">{{ Str::limit($coupure->cause, 120) }}</p>
  @endif

  <div class="d-flex gap-2 mt-auto pt-2">
    <a href="{{ route('front.coupures.show', $coupure) }}" class="btn btn-outline-secondary rounded-pill flex-grow-1 text-nowrap">
      <i class="bi bi-eye me-1"></i>Détails
    </a>
    @if($coupure->statut === 'prevue')
    <a href="{{ route('front.coupures.signaler', ['coupure_id' => $coupure->id]) }}" class="btn btn-danger rounded-pill flex-grow-1 text-nowrap">
      <i class="bi bi-megaphone-fill me-1"></i>Signaler
    </a>
    @endif
  </div>
</article>
