@extends('layouts.admin.admin')

@section('title', 'Détails de la Zone — ' . $zone->nom)

@section('content')
<div class="page-header">
  <div>
    <nav aria-label="breadcrumb" class="mb-2">
      <a href="{{ route('admin.zones.index') }}" class="text-decoration-none text-muted" style="font-size:.85rem;">
        <i class="fa-solid fa-arrow-left me-1"></i>Retour à la liste des zones
      </a>
    </nav>
    <div class="d-flex align-items-center gap-3">
      <h1><i class="fa-solid fa-map-location-dot me-2 text-danger"></i>{{ $zone->nom }}</h1>
      @if($zone->actif)
        <span class="badge bg-success" style="font-size:.85rem;"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
      @else
        <span class="badge bg-secondary" style="font-size:.85rem;"><i class="fa-solid fa-circle-xmark me-1"></i>Inactive</span>
      @endif
    </div>
    <p class="subtitle">{{ $zone->ville }}{{ $zone->gouvernorat ? ', Gouvernorat de ' . $zone->gouvernorat : '' }}</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('admin.zones.edit', $zone) }}" class="m-btn m-btn--primary">
      <i class="fa-solid fa-pen-to-square"></i> Modifier la zone
    </a>
    <a href="{{ route('admin.alertes.create', ['zone_id' => $zone->id]) }}" class="m-btn m-btn--ghost" style="border:1px solid #dc2626;color:#dc2626;">
      <i class="fa-solid fa-triangle-exclamation"></i> Déclarer une alerte
    </a>
    <button type="button" class="m-btn m-btn--ghost js-trigger-delete" style="border:1px solid #dc2626;color:#dc2626;"
            data-action="{{ route('admin.zones.destroy', $zone) }}"
            data-name="{{ $zone->nom }}"
            data-type="la zone"
            title="Supprimer la zone">
      <i class="fa-solid fa-trash"></i> Supprimer
    </button>
  </div>
</div>

{{-- ── STATS CARDS ── --}}
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="m-card" style="padding:18px 20px;border-left:4px solid #dc2626;">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <div class="text-muted" style="font-size:.78rem;text-transform:uppercase;font-weight:700;letter-spacing:.5px;">Alertes actives</div>
          <div style="font-size:1.8rem;font-weight:800;color:#dc2626;">{{ $activeAlertesCount }}</div>
        </div>
        <div style="width:44px;height:44px;border-radius:12px;background:rgba(220,38,38,.1);display:flex;align-items:center;justify-content:center;color:#dc2626;font-size:1.2rem;">
          <i class="fa-solid fa-bell"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="m-card" style="padding:18px 20px;border-left:4px solid #f97316;">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <div class="text-muted" style="font-size:.78rem;text-transform:uppercase;font-weight:700;letter-spacing:.5px;">Total alertes</div>
          <div style="font-size:1.8rem;font-weight:800;color:#0f172a;">{{ $zone->alertes_count }}</div>
        </div>
        <div style="width:44px;height:44px;border-radius:12px;background:rgba(249,115,22,.1);display:flex;align-items:center;justify-content:center;color:#f97316;font-size:1.2rem;">
          <i class="fa-solid fa-clock-rotate-left"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="m-card" style="padding:18px 20px;border-left:4px solid #2563eb;">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <div class="text-muted" style="font-size:.78rem;text-transform:uppercase;font-weight:700;letter-spacing:.5px;">Citoyens inscrits</div>
          <div style="font-size:1.8rem;font-weight:800;color:#2563eb;">{{ $zone->users_count }}</div>
        </div>
        <div style="width:44px;height:44px;border-radius:12px;background:rgba(37,99,235,.1);display:flex;align-items:center;justify-content:center;color:#2563eb;font-size:1.2rem;">
          <i class="fa-solid fa-users"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="m-card" style="padding:18px 20px;border-left:4px solid #16a34a;">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <div class="text-muted" style="font-size:.78rem;text-transform:uppercase;font-weight:700;letter-spacing:.5px;">Code Postal</div>
          <div style="font-size:1.8rem;font-weight:800;color:#0f172a;">{{ $zone->code_postal ?? '—' }}</div>
        </div>
        <div style="width:44px;height:44px;border-radius:12px;background:rgba(22,163,74,.1);display:flex;align-items:center;justify-content:center;color:#16a34a;font-size:1.2rem;">
          <i class="fa-solid fa-envelope"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-4 mb-4 align-items-stretch">
  {{-- ── COLONNE GAUCHE: Informations Générales ── --}}
  <div class="col-lg-6 d-flex flex-column">
    <div class="m-card h-100 mb-0">
      <div class="m-card__header d-flex justify-content-between align-items-center" style="padding:16px 20px;border-bottom:1px solid #f1f5f9;">
        <h5 style="margin:0;font-weight:700;font-size:1.05rem;"><i class="fa-solid fa-circle-info me-2 text-primary"></i>Informations générales</h5>
      </div>
      <div style="padding:20px;" class="d-flex flex-column justify-content-between flex-grow-1">
        <table class="table table-sm table-borderless mb-0" style="font-size:.92rem;">
          <tbody>
            <tr>
              <td class="text-muted" style="width:40%;padding:8px 0;"><i class="fa-solid fa-tag me-2"></i>Nom de la zone</td>
              <td class="fw-bold" style="padding:8px 0;">{{ $zone->nom }}</td>
            </tr>
            <tr>
              <td class="text-muted" style="padding:8px 0;"><i class="fa-solid fa-city me-2"></i>Ville</td>
              <td class="fw-bold" style="padding:8px 0;">{{ $zone->ville }}</td>
            </tr>
            <tr>
              <td class="text-muted" style="padding:8px 0;"><i class="fa-solid fa-landmark me-2"></i>Gouvernorat</td>
              <td style="padding:8px 0;"><span class="badge bg-light text-dark border">{{ $zone->gouvernorat ?? 'Non spécifié' }}</span></td>
            </tr>
            <tr>
              <td class="text-muted" style="padding:8px 0;"><i class="fa-solid fa-map-pin me-2"></i>Code postal</td>
              <td style="padding:8px 0;">{{ $zone->code_postal ?? '—' }}</td>
            </tr>
            <tr>
              <td class="text-muted" style="padding:8px 0;"><i class="fa-solid fa-compass me-2"></i>Coordonnées GPS</td>
              <td style="padding:8px 0;">
                @if($zone->latitude && $zone->longitude)
                  <code>{{ number_format($zone->latitude, 5) }}, {{ number_format($zone->longitude, 5) }}</code>
                  <a href="https://www.google.com/maps?q={{ $zone->latitude }},{{ $zone->longitude }}" target="_blank" class="ms-1 text-danger" title="Ouvrir dans Google Maps">
                    <i class="fa-solid fa-up-right-from-square"></i>
                  </a>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>
            </tr>
            <tr>
              <td class="text-muted" style="padding:8px 0;"><i class="fa-solid fa-calendar-plus me-2"></i>Date de création</td>
              <td style="padding:8px 0;">{{ $zone->created_at ? $zone->created_at->format('d/m/Y H:i') : '—' }}</td>
            </tr>
            <tr>
              <td class="text-muted" style="padding:8px 0;"><i class="fa-solid fa-clock-rotate-left me-2"></i>Dernière mise à jour</td>
              <td style="padding:8px 0;">{{ $zone->updated_at ? $zone->updated_at->diffForHumans() : '—' }}</td>
            </tr>
          </tbody>
        </table>

        @if($zone->description)
          <div class="mt-3 pt-3 border-top">
            <div class="text-muted small fw-bold mb-1 text-uppercase" style="letter-spacing:.5px;">Description & Observations</div>
            <p class="mb-0" style="font-size:.9rem;color:#475569;line-height:1.6;background:#f8fafc;padding:12px 14px;border-radius:10px;">
              {{ $zone->description }}
            </p>
          </div>
        @endif
      </div>
    </div>
  </div>

  {{-- ── COLONNE DROITE: Carte de localisation Leaflet ── --}}
  <div class="col-lg-6 d-flex flex-column">
    <div class="m-card h-100 d-flex flex-column mb-0" style="overflow:hidden;">
      <div class="m-card__header d-flex justify-content-between align-items-center flex-shrink-0" style="padding:16px 20px;border-bottom:1px solid #f1f5f9;">
        <h5 style="margin:0;font-weight:700;font-size:1.05rem;">
          <i class="fa-solid fa-map-location-dot me-2 text-danger"></i>Localisation sur la carte
        </h5>
        @if($zone->latitude && $zone->longitude)
          <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1" style="font-size:.74rem;">
            <i class="fa-solid fa-crosshairs me-1"></i>{{ number_format($zone->latitude, 4) }}, {{ number_format($zone->longitude, 4) }}
          </span>
        @else
          <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1" style="font-size:.74rem;">
            <i class="fa-solid fa-circle-info me-1"></i>Coordonnées non renseignées
          </span>
        @endif
      </div>
      <div class="p-3 flex-grow-1 d-flex flex-column">
        <div id="zone-show-map" style="flex:1; min-height: 320px; width: 100%; border-radius: 10px; border: 1px solid #cbd5e1; box-shadow: inset 0 1px 3px rgba(0,0,0,.08); position: relative; z-index: 1;"></div>
        <div class="d-flex justify-content-between align-items-center mt-2 px-1 flex-shrink-0" style="font-size: .8rem; color: #64748b;">
          <span>
            <i class="fa-solid fa-location-dot me-1 text-danger"></i>
            {{ $zone->nom }} ({{ $zone->ville }}{{ $zone->gouvernorat ? ', ' . $zone->gouvernorat : '' }})
          </span>
          <div class="d-flex align-items-center gap-3">
            @if($zone->latitude && $zone->longitude)
              <a href="https://www.google.com/maps?q={{ $zone->latitude }},{{ $zone->longitude }}" target="_blank" class="text-decoration-none text-danger fw-semibold" style="font-size: .76rem;">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Google Maps
              </a>
            @endif
            <button type="button" id="btn-recenter-zone" class="btn btn-sm btn-link p-0 text-decoration-none text-muted" style="font-size: .76rem;">
              <i class="fa-solid fa-rotate-right me-1"></i>Recentrer
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ── LIGNE 3: Historique des alertes météo (pleine largeur) ── --}}
<div class="row">
  <div class="col-12">
    <div class="m-card">
      <div class="m-card__header d-flex justify-content-between align-items-center" style="padding:16px 20px;border-bottom:1px solid #f1f5f9;">
        <h5 style="margin:0;font-weight:700;font-size:1.05rem;"><i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i>Alertes météo dans cette zone ({{ $zone->alertes_count }})</h5>
        <a href="{{ route('admin.alertes.create', ['zone_id' => $zone->id]) }}" class="m-btn m-btn--ghost" style="font-size:12px;height:30px;padding:0 12px;">
          <i class="fa-solid fa-plus"></i> Nouvelle alerte
        </a>
      </div>
      <div style="padding:0;">
        <div class="table-responsive">
          <table class="m-table mb-0">
            <thead>
              <tr>
                <th>Titre</th>
                <th>Niveau</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Période</th>
                <th style="text-align:right;">Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($alertes as $alerte)
              <tr>
                <td>
                  <strong>{{ Str::limit($alerte->titre, 40) }}</strong>
                  @if($alerte->temperature_max)
                    <br><small class="text-danger fw-bold"><i class="fa-solid fa-temperature-high me-1"></i>Max {{ $alerte->temperature_max }}°C</small>
                  @endif
                </td>
                <td>
                  @php
                    $badgeClass = match($alerte->niveau) {
                      'rouge'  => 'bg-danger',
                      'orange' => 'bg-warning text-dark',
                      'jaune'  => 'bg-info text-dark',
                      default  => 'bg-success',
                    };
                  @endphp
                  <span class="badge {{ $badgeClass }}">{{ ucfirst($alerte->niveau) }}</span>
                </td>
                <td>
                  <span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_', ' ', $alerte->type)) }}</span>
                </td>
                <td>
                  @if($alerte->statut === 'active')
                    <span class="badge bg-danger"><i class="fa-solid fa-bolt me-1"></i>Active</span>
                  @else
                    <span class="badge bg-secondary">{{ ucfirst($alerte->statut) }}</span>
                  @endif
                </td>
                <td style="font-size:.82rem;">
                  {{ $alerte->date_debut ? $alerte->date_debut->format('d/m/Y') : '—' }}
                </td>
                <td style="text-align:right;">
                  <a href="{{ route('admin.alertes.show', $alerte) }}" class="m-btn m-btn--ghost" style="height:28px;padding:0 9px;font-size:12px;color:#2563eb;" title="Voir les détails">
                    <i class="fa-solid fa-eye"></i>
                  </a>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center py-4 text-muted">
                  <i class="fa-solid fa-shield-halved fa-2x mb-2 d-block text-success"></i>
                  Aucune alerte météo enregistrée pour cette zone.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if($alertes->hasPages())
        <div class="d-flex justify-content-center p-3 border-top">
          {{ $alertes->links() }}
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
  .leaflet-popup-content-wrapper {
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.12);
    font-family: inherit;
  }
  .zone-custom-pin {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #dc2626;
    filter: drop-shadow(0 3px 6px rgba(220, 38, 38, 0.45));
    animation: pinBounce 0.5s ease;
  }
  @keyframes pinBounce {
    0% { transform: translateY(-15px) scale(0.8); opacity: 0; }
    50% { transform: translateY(3px) scale(1.1); }
    100% { transform: translateY(0) scale(1); opacity: 1; }
  }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const mapContainer = document.getElementById('zone-show-map');
  if (!mapContainer) return;

  const lat = {{ $zone->latitude ? json_encode((float)$zone->latitude) : 'null' }};
  const lng = {{ $zone->longitude ? json_encode((float)$zone->longitude) : 'null' }};

  const hasCoords = lat !== null && lng !== null;
  const initialCenter = hasCoords ? [lat, lng] : [34.0, 9.5];
  const initialZoom = hasCoords ? 13 : 6.5;

  // Initialisation de Leaflet
  const map = L.map('zone-show-map', {
    scrollWheelZoom: true
  }).setView(initialCenter, initialZoom);

  // Fond de carte OpenStreetMap
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>'
  }).addTo(map);

  if (hasCoords) {
    // Pin rouge personnalisé HeatAlert
    const redPinIcon = L.divIcon({
      className: 'zone-custom-pin',
      html: '<i class="fa-solid fa-location-dot" style="font-size: 34px;"></i>',
      iconSize: [34, 34],
      iconAnchor: [17, 34],
      popupAnchor: [0, -32]
    });

    const marker = L.marker([lat, lng], { icon: redPinIcon }).addTo(map);

    const popupHtml = `
      <div style="min-width: 170px; padding: 2px;">
        <div style="font-weight: 700; font-size: 13.5px; color: #0f172a; margin-bottom: 2px;">
          <i class="fa-solid fa-map-pin text-danger me-1"></i>{{ addslashes($zone->nom) }}
        </div>
        <div style="font-size: 12px; color: #475569; margin-bottom: 4px;">
          <i class="fa-solid fa-city me-1 text-muted"></i>{{ addslashes($zone->ville) }}{{ $zone->gouvernorat ? ', ' . addslashes($zone->gouvernorat) : '' }}
        </div>
        <div style="font-size: 11px; color: #dc2626; font-family: monospace;">
          ${lat.toFixed(4)}, ${lng.toFixed(4)}
        </div>
      </div>
    `;

    marker.bindPopup(popupHtml).openPopup();

    // Rayon de surveillance HeatAlert (cercle de 3km)
    L.circle([lat, lng], {
      radius: 3000,
      color: '#dc2626',
      fillColor: '#ef4444',
      fillOpacity: 0.12,
      weight: 1.5,
      dashArray: '4, 6'
    }).addTo(map);
  }

  // Bouton recentrer
  const recenterBtn = document.getElementById('btn-recenter-zone');
  if (recenterBtn) {
    recenterBtn.addEventListener('click', function() {
      map.setView(initialCenter, initialZoom, { animate: true });
    });
  }

  // Invalidation de la taille du conteneur pour un rendu parfait
  setTimeout(function() {
    map.invalidateSize();
  }, 250);
});
</script>
@endpush

