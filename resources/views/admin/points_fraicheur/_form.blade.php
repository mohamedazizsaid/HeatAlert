{{-- Partial : Formulaire Point de Fraîcheur (create & edit) --}}
<div class="row g-4 align-items-stretch">
  <div class="col-lg-8 d-flex flex-column">
    <section class="m-card flex-grow-1 h-100 mb-0">
      <header class="m-card__header">
        <div>
          <h2 class="m-card__title">Informations du point de fraîcheur</h2>
          <p class="m-card__subtitle">Les champs marqués * sont obligatoires.</p>
        </div>
      </header>

      <div class="row g-3 p-3">
        {{-- Nom --}}
        <div class="col-md-8">
          <label for="nom" class="form-label fw-semibold">Nom du point <span class="text-danger">*</span></label>
          <input type="text"
                 id="nom" name="nom"
                 class="form-control @error('nom') is-invalid @enderror"
                 value="{{ old('nom', $point->nom ?? '') }}"
                 placeholder="Ex: Parc Habib Thameur"
                 maxlength="150" required>
          @error('nom')<div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
        </div>

        {{-- Type --}}
        <div class="col-md-4">
          <label for="type" class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
          <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
            <option value="">— Sélectionner —</option>
            @foreach(\App\Models\PointFraicheur::$typeLabels as $val => $label)
              <option value="{{ $val }}" {{ old('type', $point->type ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
          @error('type')<div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
        </div>

        {{-- Adresse --}}
        <div class="col-12">
          <label for="adresse" class="form-label fw-semibold">Adresse <span class="text-danger">*</span></label>
          <input type="text"
                 id="adresse" name="adresse"
                 class="form-control @error('adresse') is-invalid @enderror"
                 value="{{ old('adresse', $point->adresse ?? '') }}"
                 placeholder="Ex: Avenue de la République, Tunis"
                 maxlength="255" required>
          @error('adresse')<div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
        </div>

        {{-- Latitude --}}
        <div class="col-md-6">
          <label for="latitude" class="form-label fw-semibold">Latitude</label>
          <input type="number" id="latitude" name="latitude"
                 class="form-control @error('latitude') is-invalid @enderror"
                 value="{{ old('latitude', $point->latitude ?? '') }}"
                 placeholder="Ex: 36.8197" step="0.0000001" min="-90" max="90">
          @error('latitude')<div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
        </div>

        {{-- Longitude --}}
        <div class="col-md-6">
          <label for="longitude" class="form-label fw-semibold">Longitude</label>
          <input type="number" id="longitude" name="longitude"
                 class="form-control @error('longitude') is-invalid @enderror"
                 value="{{ old('longitude', $point->longitude ?? '') }}"
                 placeholder="Ex: 10.1662" step="0.0000001" min="-180" max="180">
          @error('longitude')<div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
        </div>

        {{-- Horaires --}}
        <div class="col-md-8">
          <label for="horaires" class="form-label fw-semibold">Horaires d'ouverture</label>
          <input type="text" id="horaires" name="horaires"
                 class="form-control @error('horaires') is-invalid @enderror"
                 value="{{ old('horaires', $point->horaires ?? '') }}"
                 placeholder="Ex: 8h00 – 18h00, fermé le dimanche"
                 maxlength="255">
          @error('horaires')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Zone --}}
        <div class="col-md-4">
          <label for="zone_id" class="form-label fw-semibold">Zone associée</label>
          <select id="zone_id" name="zone_id" class="form-select @error('zone_id') is-invalid @enderror">
            <option value="">— Aucune —</option>
            @foreach($zones as $zone)
              <option value="{{ $zone->id }}" {{ old('zone_id', $point->zone_id ?? '') == $zone->id ? 'selected' : '' }}>
                {{ $zone->nom }}{{ $zone->gouvernorat ? ' (' . $zone->gouvernorat . ')' : '' }}
              </option>
            @endforeach
          </select>
          @error('zone_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Options --}}
        <div class="col-md-6">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="accessible_pmr" name="accessible_pmr" value="1"
                   {{ old('accessible_pmr', $point->accessible_pmr ?? false) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="accessible_pmr">
              <i class="fa-solid fa-wheelchair me-1 text-info"></i>Accessible PMR (Personnes à mobilité réduite)
            </label>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="actif" name="actif" value="1"
                   {{ old('actif', $point->actif ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="actif">Point actif (visible sur la carte)</label>
          </div>
        </div>
      </div>
    </section>
  </div>

  {{-- Sidebar actions & Carte --}}
  <div class="col-lg-4 d-flex flex-column">
    <section class="m-card mb-3 flex-shrink-0">
      <header class="m-card__header">
        <h2 class="m-card__title">Actions</h2>
      </header>
      <div class="p-3 d-grid gap-2">
        <button type="submit" class="m-btn m-btn--primary">
          <i class="fa-solid fa-floppy-disk me-2"></i>Enregistrer
        </button>
        <a href="{{ route('admin.points_fraicheur.index') }}" class="m-btn m-btn--ghost text-center">
          <i class="fa-solid fa-xmark me-2"></i>Annuler
        </a>
      </div>
    </section>

    {{-- Carte Leaflet --}}
    <section class="m-card flex-grow-1 d-flex flex-column mb-0">
      <header class="m-card__header d-flex justify-content-between align-items-center flex-shrink-0">
        <div>
          <h2 class="m-card__title" style="font-size:1rem;">
            <i class="fa-solid fa-map-location-dot text-info me-2"></i>Localisation
          </h2>
          <p class="m-card__subtitle mb-0" style="font-size:.76rem;">Cliquez sur la carte ou saisissez les coordonnées</p>
        </div>
      </header>
      <div class="p-2 flex-grow-1 d-flex flex-column">
        <div id="pf-map" style="flex:1; min-height:280px; width:100%; border-radius:8px; border:1px solid #cbd5e1; box-shadow:inset 0 1px 3px rgba(0,0,0,.08); position:relative; z-index:1;"></div>
        <div class="d-flex justify-content-between align-items-center mt-2 px-1 flex-shrink-0" style="font-size:.78rem;color:#64748b;">
          <span id="pf-map-status"><i class="fa-solid fa-crosshairs me-1 text-info"></i>Cliquez sur la carte</span>
          <button type="button" id="btn-pf-recenter" class="btn btn-sm btn-link p-0 text-decoration-none" style="font-size:.75rem;color:#0ea5e9;">
            <i class="fa-solid fa-rotate-right me-1"></i>Tunisie
          </button>
        </div>
      </div>
    </section>
  </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<style>
  .pf-custom-pin { display:flex;align-items:center;justify-content:center;color:#0ea5e9;filter:drop-shadow(0 2px 5px rgba(14,165,233,0.5));animation:pfPinBounce .4s ease; }
  @keyframes pfPinBounce {0%{transform:translateY(-12px) scale(.8);opacity:0}50%{transform:translateY(2px) scale(1.1)}100%{transform:translateY(0) scale(1);opacity:1}}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const latInput = document.getElementById('latitude');
  const lngInput = document.getElementById('longitude');
  const nomInput = document.getElementById('nom');
  const mapStatus = document.getElementById('pf-map-status');
  const recenterBtn = document.getElementById('btn-pf-recenter');
  const mapContainer = document.getElementById('pf-map');
  if (!mapContainer) return;

  const TUNISIA_CENTER = [34.0, 9.5];
  const map = L.map('pf-map', { scrollWheelZoom: true }).setView(TUNISIA_CENTER, 6.5);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap' }).addTo(map);

  const blueIcon = L.divIcon({
    className: 'pf-custom-pin',
    html: '<i class="fa-solid fa-location-dot" style="font-size:32px;"></i>',
    iconSize: [32, 32], iconAnchor: [16, 32], popupAnchor: [0, -30]
  });

  let marker = null;

  function ping(lat, lng, flyTo = true) {
    if (isNaN(lat) || isNaN(lng)) { if(marker){map.removeLayer(marker);marker=null;} return; }
    const title = (nomInput?.value || 'Point').trim();
    if (!marker) {
      marker = L.marker([lat, lng], { icon: blueIcon, draggable: true }).addTo(map);
      marker.on('dragend', e => {
        const p = e.target.getLatLng();
        latInput.value = p.lat.toFixed(6); lngInput.value = p.lng.toFixed(6);
        ping(p.lat, p.lng, false);
      });
    } else { marker.setLatLng([lat, lng]); }
    marker.bindPopup(`<strong><i class="fa-solid fa-snowflake text-info me-1"></i>${title}</strong><br><small>${lat.toFixed(5)}, ${lng.toFixed(5)}</small>`).openPopup();
    if (flyTo) map.flyTo([lat, lng], Math.max(map.getZoom(), 13), { duration: 0.8 });
    mapStatus.innerHTML = `<span class="text-success"><i class="fa-solid fa-circle-check me-1"></i>Localisé (${lat.toFixed(4)}, ${lng.toFixed(4)})</span>`;
  }

  function handleInput() {
    const lat = parseFloat(latInput.value), lng = parseFloat(lngInput.value);
    if (!isNaN(lat) && !isNaN(lng)) ping(lat, lng, true);
  }

  latInput.addEventListener('input', handleInput); lngInput.addEventListener('input', handleInput);
  latInput.addEventListener('change', handleInput); lngInput.addEventListener('change', handleInput);

  map.on('click', e => {
    latInput.value = e.latlng.lat.toFixed(6); lngInput.value = e.latlng.lng.toFixed(6);
    ping(e.latlng.lat, e.latlng.lng, false);
  });

  recenterBtn?.addEventListener('click', () => map.flyTo(TUNISIA_CENTER, 6.5, { duration: 0.6 }));

  const il = parseFloat(latInput.value), iln = parseFloat(lngInput.value);
  if (!isNaN(il) && !isNaN(iln)) setTimeout(() => { map.invalidateSize(); ping(il, iln, true); }, 250);
  else setTimeout(() => map.invalidateSize(), 250);
  window.addEventListener('resize', () => map.invalidateSize());
});
</script>
@endpush
