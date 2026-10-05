@extends('layouts.admin.admin')
@section('title', 'Détails — ' . $point->nom)

@section('content')
<div class="page-header">
  <div>
    <nav aria-label="breadcrumb" class="mb-2">
      <a href="{{ route('admin.points_fraicheur.index') }}" class="text-decoration-none text-muted" style="font-size:.85rem;">
        <i class="fa-solid fa-arrow-left me-1"></i>Retour à la liste
      </a>
    </nav>
    <div class="d-flex align-items-center gap-3">
      @php
        $icon  = \App\Models\PointFraicheur::$typeIcons[$point->type]  ?? 'fa-location-dot';
        $color = \App\Models\PointFraicheur::$typeColors[$point->type] ?? '#64748b';
        $label = \App\Models\PointFraicheur::$typeLabels[$point->type] ?? ucfirst($point->type);
      @endphp
      <h1><i class="fa-solid {{ $icon }} me-2" style="color:{{ $color }}"></i>{{ $point->nom }}</h1>
      @if($point->actif)
        <span class="badge bg-success" style="font-size:.85rem;"><i class="fa-solid fa-circle-check me-1"></i>Actif</span>
      @else
        <span class="badge bg-secondary" style="font-size:.85rem;"><i class="fa-solid fa-circle-xmark me-1"></i>Inactif</span>
      @endif
    </div>
    <p class="subtitle">{{ $label }} — {{ $point->adresse }}</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('admin.points_fraicheur.edit', $point) }}" class="m-btn m-btn--primary">
      <i class="fa-solid fa-pen-to-square"></i> Modifier
    </a>
    <button type="button" class="m-btn m-btn--ghost js-trigger-delete" style="border:1px solid #dc2626;color:#dc2626;"
            data-action="{{ route('admin.points_fraicheur.destroy', $point) }}"
            data-name="{{ $point->nom }}"
            data-type="le point de fraîcheur">
      <i class="fa-solid fa-trash"></i> Supprimer
    </button>
  </div>
</div>

{{-- Stat cards --}}
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="m-card" style="padding:18px 20px;border-left:4px solid {{ $color }};">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <div class="text-muted" style="font-size:.78rem;text-transform:uppercase;font-weight:700;letter-spacing:.5px;">Type</div>
          <div style="font-size:1rem;font-weight:800;color:#0f172a;">{{ $label }}</div>
        </div>
        <div style="width:44px;height:44px;border-radius:12px;background:{{ $color }}20;display:flex;align-items:center;justify-content:center;color:{{ $color }};font-size:1.2rem;">
          <i class="fa-solid {{ $icon }}"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="m-card" style="padding:18px 20px;border-left:4px solid #f59e0b;">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <div class="text-muted" style="font-size:.78rem;text-transform:uppercase;font-weight:700;letter-spacing:.5px;">Note moyenne</div>
          @php $moy = round($point->avisPoints()->avg('note') ?? 0, 1); @endphp
          <div style="font-size:1.8rem;font-weight:800;color:#f59e0b;">{{ $moy > 0 ? $moy . '/5' : '—' }}</div>
        </div>
        <div style="width:44px;height:44px;border-radius:12px;background:rgba(245,158,11,.1);display:flex;align-items:center;justify-content:center;color:#f59e0b;font-size:1.2rem;">
          <i class="fa-solid fa-star"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="m-card" style="padding:18px 20px;border-left:4px solid #2563eb;">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <div class="text-muted" style="font-size:.78rem;text-transform:uppercase;font-weight:700;letter-spacing:.5px;">Total avis</div>
          <div style="font-size:1.8rem;font-weight:800;color:#2563eb;">{{ $point->avis_points_count }}</div>
        </div>
        <div style="width:44px;height:44px;border-radius:12px;background:rgba(37,99,235,.1);display:flex;align-items:center;justify-content:center;color:#2563eb;font-size:1.2rem;">
          <i class="fa-solid fa-comments"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="m-card" style="padding:18px 20px;border-left:4px solid #16a34a;">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <div class="text-muted" style="font-size:.78rem;text-transform:uppercase;font-weight:700;letter-spacing:.5px;">Accès PMR</div>
          <div style="font-size:1rem;font-weight:800;color:#0f172a;">{{ $point->accessible_pmr ? 'Oui' : 'Non' }}</div>
        </div>
        <div style="width:44px;height:44px;border-radius:12px;background:rgba(22,163,74,.1);display:flex;align-items:center;justify-content:center;color:#16a34a;font-size:1.2rem;">
          <i class="fa-solid fa-wheelchair"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-4 mb-4 align-items-stretch">
  {{-- Infos générales --}}
  <div class="col-lg-6 d-flex flex-column">
    <div class="m-card h-100 mb-0">
      <div class="m-card__header d-flex justify-content-between align-items-center" style="padding:16px 20px;border-bottom:1px solid #f1f5f9;">
        <h5 style="margin:0;font-weight:700;font-size:1.05rem;"><i class="fa-solid fa-circle-info me-2 text-info"></i>Informations générales</h5>
      </div>
      <div style="padding:20px;">
        <table class="table table-sm table-borderless mb-0" style="font-size:.92rem;">
          <tbody>
            <tr><td class="text-muted" style="width:40%;padding:8px 0;"><i class="fa-solid fa-tag me-2"></i>Nom</td><td class="fw-bold" style="padding:8px 0;">{{ $point->nom }}</td></tr>
            <tr><td class="text-muted" style="padding:8px 0;"><i class="fa-solid fa-location-dot me-2"></i>Adresse</td><td style="padding:8px 0;">{{ $point->adresse }}</td></tr>
            <tr><td class="text-muted" style="padding:8px 0;"><i class="fa-solid fa-clock me-2"></i>Horaires</td><td style="padding:8px 0;">{{ $point->horaires ?? '—' }}</td></tr>
            <tr><td class="text-muted" style="padding:8px 0;"><i class="fa-solid fa-map-pin me-2"></i>Zone</td>
              <td style="padding:8px 0;">
                @if($point->zone)
                  <span class="badge bg-light text-dark border">{{ $point->zone->nom }}</span>
                @else <span class="text-muted">—</span> @endif
              </td>
            </tr>
            <tr><td class="text-muted" style="padding:8px 0;"><i class="fa-solid fa-compass me-2"></i>GPS</td>
              <td style="padding:8px 0;">
                @if($point->latitude && $point->longitude)
                  <code>{{ number_format($point->latitude, 5) }}, {{ number_format($point->longitude, 5) }}</code>
                  <a href="https://www.google.com/maps?q={{ $point->latitude }},{{ $point->longitude }}" target="_blank" class="ms-1 text-info"><i class="fa-solid fa-up-right-from-square"></i></a>
                @else <span class="text-muted">—</span> @endif
              </td>
            </tr>
            <tr><td class="text-muted" style="padding:8px 0;"><i class="fa-solid fa-calendar-plus me-2"></i>Créé le</td><td style="padding:8px 0;">{{ $point->created_at?->format('d/m/Y H:i') ?? '—' }}</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Carte --}}
  <div class="col-lg-6 d-flex flex-column">
    <div class="m-card h-100 d-flex flex-column mb-0" style="overflow:hidden;">
      <div class="m-card__header d-flex justify-content-between align-items-center flex-shrink-0" style="padding:16px 20px;border-bottom:1px solid #f1f5f9;">
        <h5 style="margin:0;font-weight:700;font-size:1.05rem;"><i class="fa-solid fa-map-location-dot me-2 text-info"></i>Localisation</h5>
        @if($point->latitude && $point->longitude)
          <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2 py-1" style="font-size:.74rem;">
            <i class="fa-solid fa-crosshairs me-1"></i>{{ number_format($point->latitude, 4) }}, {{ number_format($point->longitude, 4) }}
          </span>
        @endif
      </div>
      <div class="p-3 flex-grow-1 d-flex flex-column">
        <div id="pf-show-map" style="flex:1;min-height:300px;width:100%;border-radius:10px;border:1px solid #cbd5e1;position:relative;z-index:1;"></div>
      </div>
    </div>
  </div>
</div>

{{-- Tableau des avis --}}
<div class="row">
  <div class="col-12">
    <div class="m-card">
      <div class="m-card__header d-flex justify-content-between align-items-center" style="padding:16px 20px;border-bottom:1px solid #f1f5f9;">
        <h5 style="margin:0;font-weight:700;font-size:1.05rem;"><i class="fa-solid fa-star me-2 text-warning"></i>Avis citoyens ({{ $point->avis_points_count }})</h5>
        <a href="{{ route('admin.avis_points.index', ['point_fraicheur_id' => $point->id]) }}" class="m-btn m-btn--ghost" style="font-size:12px;height:30px;padding:0 12px;">
          Tout voir
        </a>
      </div>
      <div class="table-responsive">
        <table class="m-table mb-0">
          <thead>
            <tr>
              <th>Citoyen</th>
              <th style="text-align:center;">Note</th>
              <th>Commentaire</th>
              <th style="text-align:center;">Date</th>
              <th style="text-align:center;">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($avis as $av)
            <tr>
              <td>
                <strong>{{ $av->user->prenom ?? '' }} {{ $av->user->nom ?? '—' }}</strong>
                <br><small class="text-muted">{{ $av->user->email ?? '' }}</small>
              </td>
              <td style="text-align:center;">
                <span style="color:#f59e0b;">
                  @for($s = 1; $s <= 5; $s++)
                    <i class="fa-{{ $s <= $av->note ? 'solid' : 'regular' }} fa-star" style="font-size:12px;"></i>
                  @endfor
                </span>
              </td>
              <td>{{ $av->commentaire ? Str::limit($av->commentaire, 80) : '—' }}</td>
              <td style="text-align:center;font-size:.82rem;">{{ $av->date_avis?->format('d/m/Y') ?? '—' }}</td>
              <td style="text-align:center;">
                <form method="POST" action="{{ route('admin.avis_points.destroy', $av) }}" style="display:inline;">
                  @csrf @method('DELETE')
                  <button type="submit" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;color:#dc2626;" onclick="return confirm('Supprimer cet avis ?')" title="Supprimer">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center py-4 text-muted">
                <i class="fa-solid fa-comment-slash fa-2x mb-2 d-block"></i>Aucun avis pour l'instant.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($avis->hasPages())
      <div class="d-flex justify-content-center p-3 border-top">{{ $avis->links() }}</div>
      @endif
    </div>
  </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<style>
  .pf-custom-pin{display:flex;align-items:center;justify-content:center;color:#0ea5e9;filter:drop-shadow(0 3px 6px rgba(14,165,233,.45));animation:pfBounce .5s ease;}
  @keyframes pfBounce{0%{transform:translateY(-15px) scale(.8);opacity:0}50%{transform:translateY(3px) scale(1.1)}100%{transform:translateY(0) scale(1);opacity:1}}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const mapEl = document.getElementById('pf-show-map');
  if (!mapEl) return;
  const lat = {{ $point->latitude ? json_encode((float)$point->latitude) : 'null' }};
  const lng = {{ $point->longitude ? json_encode((float)$point->longitude) : 'null' }};
  const hasCoords = lat !== null && lng !== null;
  const map = L.map('pf-show-map', { scrollWheelZoom: true }).setView(hasCoords ? [lat, lng] : [34.0, 9.5], hasCoords ? 14 : 6.5);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap' }).addTo(map);
  if (hasCoords) {
    const icon = L.divIcon({ className: 'pf-custom-pin', html: '<i class="fa-solid fa-location-dot" style="font-size:34px;"></i>', iconSize:[34,34], iconAnchor:[17,34], popupAnchor:[0,-32] });
    L.marker([lat, lng], { icon }).addTo(map).bindPopup(`<strong><i class="fa-solid fa-snowflake text-info me-1"></i>{{ addslashes($point->nom) }}</strong><br><small>{{ addslashes($point->adresse) }}</small>`).openPopup();
    L.circle([lat, lng], { radius: 200, color: '#0ea5e9', fillColor: '#7dd3fc', fillOpacity: 0.15, weight: 1.5, dashArray: '4,6' }).addTo(map);
  }
  setTimeout(() => map.invalidateSize(), 250);
});
</script>
@endpush
