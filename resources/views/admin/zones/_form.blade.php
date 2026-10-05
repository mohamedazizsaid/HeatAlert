{{-- Partial: Formulaire Zone (create & edit) --}}
<div class="row g-4 align-items-stretch">
  <div class="col-lg-8 d-flex flex-column">
    {{-- Bannière d'erreur dynamique --}}
    <div id="zone-form-alert" class="alert alert-danger d-none mb-3 py-2 px-3 small rounded-3" style="border-radius:10px;">
      <i class="fa-solid fa-triangle-exclamation me-1"></i>
      <span>Veuillez corriger les erreurs ci-dessous avant d'enregistrer la zone.</span>
    </div>

    @if ($errors->any())
      <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3" style="border-radius:10px;">
        <div class="fw-bold mb-1"><i class="fa-solid fa-circle-exclamation me-1"></i>Des erreurs sont survenues :</div>
        <ul class="mb-0 ps-3">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <section class="m-card flex-grow-1 h-100 mb-0">
      <header class="m-card__header">
        <div>
          <h2 class="m-card__title">Informations de la zone</h2>
          <p class="m-card__subtitle">Les champs marqués * sont obligatoires.</p>
        </div>
      </header>

      <div class="row g-3 p-3">
        {{-- Nom --}}
        <div class="col-md-6">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <label for="nom" class="form-label fw-semibold mb-0">Nom de la zone <span class="text-danger">*</span></label>
            <span class="small text-muted" id="nom-char-counter">0 / 100</span>
          </div>
          <input type="text"
                 id="nom"
                 name="nom"
                 class="form-control @error('nom') is-invalid @enderror"
                 value="{{ old('nom', $zone->nom ?? '') }}"
                 placeholder="Ex: Zone Tunis Centre"
                 maxlength="100"
                 required>
          <div class="invalid-feedback" id="nom-error"><i class="fa-solid fa-circle-exclamation me-1"></i>@error('nom'){{ $message }}@else{{ 'Le nom de la zone est obligatoire.' }}@enderror</div>
        </div>

        {{-- Ville --}}
        <div class="col-md-6">
          <label for="ville" class="form-label fw-semibold">Ville <span class="text-danger">*</span></label>
          <input type="text"
                 id="ville"
                 name="ville"
                 class="form-control @error('ville') is-invalid @enderror"
                 value="{{ old('ville', $zone->ville ?? '') }}"
                 placeholder="Ex: Tunis"
                 maxlength="100"
                 required>
          <div class="invalid-feedback" id="ville-error"><i class="fa-solid fa-circle-exclamation me-1"></i>@error('ville'){{ $message }}@else{{ 'La ville est obligatoire.' }}@enderror</div>
        </div>

        {{-- Gouvernorat --}}
        <div class="col-md-6">
          <label for="gouvernorat" class="form-label fw-semibold">Gouvernorat</label>
          <input type="text"
                 id="gouvernorat"
                 name="gouvernorat"
                 class="form-control @error('gouvernorat') is-invalid @enderror"
                 value="{{ old('gouvernorat', $zone->gouvernorat ?? '') }}"
                 placeholder="Ex: Tunis"
                 maxlength="100">
          @error('gouvernorat')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
        </div>

        {{-- Code postal --}}
        <div class="col-md-6">
          <label for="code_postal" class="form-label fw-semibold">Code postal</label>
          <input type="text"
                 id="code_postal"
                 name="code_postal"
                 class="form-control @error('code_postal') is-invalid @enderror"
                 value="{{ old('code_postal', $zone->code_postal ?? '') }}"
                 placeholder="Ex: 1000"
                 maxlength="4"
                 pattern="[0-9]{4}"
                 inputmode="numeric">
          <div class="invalid-feedback" id="code_postal-error"><i class="fa-solid fa-circle-exclamation me-1"></i>@error('code_postal'){{ $message }}@else{{ 'Le code postal doit comporter exactement 4 chiffres.' }}@enderror</div>
          <div class="form-text">4 chiffres (code postal tunisien)</div>
        </div>

        {{-- Latitude --}}
        <div class="col-md-6">
          <label for="latitude" class="form-label fw-semibold">Latitude</label>
          <input type="number"
                 id="latitude"
                 name="latitude"
                 class="form-control @error('latitude') is-invalid @enderror"
                 value="{{ old('latitude', $zone->latitude ?? '') }}"
                 placeholder="Ex: 36.8190"
                 step="0.0000001"
                 min="-90" max="90">
          <div class="invalid-feedback" id="latitude-error"><i class="fa-solid fa-circle-exclamation me-1"></i>@error('latitude'){{ $message }}@else{{ 'La latitude doit être comprise entre -90 et 90.' }}@enderror</div>
        </div>

        {{-- Longitude --}}
        <div class="col-md-6">
          <label for="longitude" class="form-label fw-semibold">Longitude</label>
          <input type="number"
                 id="longitude"
                 name="longitude"
                 class="form-control @error('longitude') is-invalid @enderror"
                 value="{{ old('longitude', $zone->longitude ?? '') }}"
                 placeholder="Ex: 10.1658"
                 step="0.0000001"
                 min="-180" max="180">
          <div class="invalid-feedback" id="longitude-error"><i class="fa-solid fa-circle-exclamation me-1"></i>@error('longitude'){{ $message }}@else{{ 'La longitude doit être comprise entre -180 et 180.' }}@enderror</div>
        </div>

        {{-- Description --}}
        <div class="col-12">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <label for="description" class="form-label fw-semibold mb-0">Description</label>
            <span class="small text-muted" id="description-char-counter">0 / 500</span>
          </div>
          <textarea id="description"
                    name="description"
                    class="form-control @error('description') is-invalid @enderror"
                    rows="3"
                    maxlength="500"
                    placeholder="Description de la zone géographique...">{{ old('description', $zone->description ?? '') }}</textarea>
          <div class="invalid-feedback" id="description-error"><i class="fa-solid fa-circle-exclamation me-1"></i>@error('description'){{ $message }}@else{{ 'La description ne peut pas dépasser 500 caractères.' }}@enderror</div>
        </div>

        {{-- Actif --}}
        <div class="col-12">
          <div class="form-check form-switch">
            <input class="form-check-input"
                   type="checkbox"
                   id="actif"
                   name="actif"
                   value="1"
                   {{ old('actif', $zone->actif ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="actif">Zone active</label>
          </div>
          <div class="form-text">Une zone inactive n'apparaîtra pas dans les sélections.</div>
        </div>
      </div>
    </section>
  </div>

  {{-- Sidebar actions & Carte (même niveau et hauteur que la colonne gauche) --}}
  <div class="col-lg-4 d-flex flex-column">
    <section class="m-card mb-3 flex-shrink-0">
      <header class="m-card__header">
        <h2 class="m-card__title">Actions</h2>
      </header>
      <div class="p-3 d-grid gap-2">
        <button type="submit" class="m-btn m-btn--primary">
          <i class="fa-solid fa-floppy-disk me-2"></i>Enregistrer
        </button>
        <a href="{{ route('admin.zones.index') }}" class="m-btn m-btn--ghost text-center">
          <i class="fa-solid fa-xmark me-2"></i>Annuler
        </a>
      </div>
    </section>

    {{-- Carte interactive OpenStreetMap / Leaflet étirée à la même hauteur --}}
    <section class="m-card flex-grow-1 d-flex flex-column mb-0">
      <header class="m-card__header d-flex justify-content-between align-items-center flex-shrink-0">
        <div>
          <h2 class="m-card__title" style="font-size:1rem;">
            <i class="fa-solid fa-map-location-dot text-danger me-2"></i>Localisation sur la carte
          </h2>
          <p class="m-card__subtitle mb-0" style="font-size:.76rem;">Ping automatique selon latitude et longitude</p>
        </div>
      </header>
      <div class="p-2 flex-grow-1 d-flex flex-column">
        <div id="zone-map" style="flex: 1; min-height: 280px; width: 100%; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: inset 0 1px 3px rgba(0,0,0,.08); position: relative; z-index: 1;"></div>
        <div class="d-flex justify-content-between align-items-center mt-2 px-1 flex-shrink-0" style="font-size: .78rem; color: #64748b;">
          <span id="map-status">
            <i class="fa-solid fa-crosshairs me-1 text-primary"></i>Cliquez sur la carte ou saisissez les coordonnées
          </span>
          <button type="button" id="btn-recenter" class="btn btn-sm btn-link p-0 text-decoration-none" style="font-size:.75rem; color:#dc2626;">
            <i class="fa-solid fa-rotate-right me-1"></i>Tunisie
          </button>
        </div>
      </div>
    </section>
  </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
  .leaflet-popup-content-wrapper {
    border-radius: 8px;
    font-family: inherit;
    font-size: 13px;
  }
  .zone-custom-pin {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #dc2626;
    filter: drop-shadow(0 2px 5px rgba(220, 38, 38, 0.5));
    animation: pinBounce 0.4s ease;
  }
  @keyframes pinBounce {
    0% { transform: translateY(-12px) scale(0.8); opacity: 0; }
    50% { transform: translateY(2px) scale(1.1); }
    100% { transform: translateY(0) scale(1); opacity: 1; }
  }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const latInput = document.getElementById('latitude');
  const lngInput = document.getElementById('longitude');
  const nomInput = document.getElementById('nom');
  const villeInput = document.getElementById('ville');
  const mapStatus = document.getElementById('map-status');
  const recenterBtn = document.getElementById('btn-recenter');
  const mapContainer = document.getElementById('zone-map');

  if (!mapContainer || !latInput || !lngInput) return;

  // Centre par défaut : Tunisie (34.0, 9.5)
  const TUNISIA_CENTER = [34.0, 9.5];
  const DEFAULT_ZOOM = 6.5;

  // Initialisation de la carte Leaflet
  const map = L.map('zone-map', {
    scrollWheelZoom: true
  }).setView(TUNISIA_CENTER, DEFAULT_ZOOM);

  // Couche OpenStreetMap
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>'
  }).addTo(map);

  // Icône personnalisée rouge HeatAlert
  const redPinIcon = L.divIcon({
    className: 'zone-custom-pin',
    html: '<i class="fa-solid fa-location-dot" style="font-size: 32px;"></i>',
    iconSize: [32, 32],
    iconAnchor: [16, 32],
    popupAnchor: [0, -30]
  });

  let marker = null;

  // Fonction pour mettre à jour ou créer le ping sur la carte
  function pingZone(lat, lng, flyTo = true) {
    if (isNaN(lat) || isNaN(lng) || lat < -90 || lat > 90 || lng < -180 || lng > 180) {
      if (marker) {
        map.removeLayer(marker);
        marker = null;
      }
      mapStatus.innerHTML = '<span class="text-danger"><i class="fa-solid fa-circle-exclamation me-1"></i>Coordonnées invalides</span>';
      return;
    }

    const title = (nomInput?.value || villeInput?.value || 'Zone').trim();
    const popupContent = `<strong><i class="fa-solid fa-map-pin text-danger me-1"></i>${title}</strong><br><small class="text-muted">${lat.toFixed(5)}, ${lng.toFixed(5)}</small>`;

    if (!marker) {
      marker = L.marker([lat, lng], {
        icon: redPinIcon,
        draggable: true
      }).addTo(map);

      // Si l'utilisateur déplace le marqueur à la main
      marker.on('dragend', function(e) {
        const pos = e.target.getLatLng();
        latInput.value = pos.lat.toFixed(6);
        lngInput.value = pos.lng.toFixed(6);
        pingZone(pos.lat, pos.lng, false);
      });
    } else {
      marker.setLatLng([lat, lng]);
    }

    marker.bindPopup(popupContent).openPopup();

    if (flyTo) {
      const targetZoom = Math.max(map.getZoom(), 12);
      map.flyTo([lat, lng], targetZoom, { duration: 0.8 });
    }

    mapStatus.innerHTML = `<span class="text-success"><i class="fa-solid fa-circle-check me-1"></i>Zone localisée (${lat.toFixed(4)}, ${lng.toFixed(4)})</span>`;
  }

  // Écoute des champs de saisie Latitude et Longitude
  function handleCoordInput() {
    const lat = parseFloat(latInput.value);
    const lng = parseFloat(lngInput.value);

    if (!isNaN(lat) && !isNaN(lng)) {
      pingZone(lat, lng, true);
    } else if (!latInput.value && !lngInput.value) {
      if (marker) {
        map.removeLayer(marker);
        marker = null;
      }
      mapStatus.innerHTML = '<i class="fa-solid fa-crosshairs me-1 text-primary"></i>Cliquez sur la carte ou saisissez les coordonnées';
    }
  }

  latInput.addEventListener('input', handleCoordInput);
  lngInput.addEventListener('input', handleCoordInput);
  latInput.addEventListener('change', handleCoordInput);
  lngInput.addEventListener('change', handleCoordInput);

  // Clic direct sur la carte pour pointer automatiquement
  map.on('click', function(e) {
    const lat = e.latlng.lat;
    const lng = e.latlng.lng;

    latInput.value = lat.toFixed(6);
    lngInput.value = lng.toFixed(6);

    pingZone(lat, lng, false);
  });

  // Mise à jour du nom dans le popup si modifié
  nomInput?.addEventListener('input', function() {
    const lat = parseFloat(latInput.value);
    const lng = parseFloat(lngInput.value);
    if (!isNaN(lat) && !isNaN(lng) && marker) {
      const title = (nomInput.value || villeInput?.value || 'Zone').trim();
      marker.bindPopup(`<strong><i class="fa-solid fa-map-pin text-danger me-1"></i>${title}</strong><br><small class="text-muted">${lat.toFixed(5)}, ${lng.toFixed(5)}</small>`);
    }
  });

  // Bouton recentrer sur la Tunisie
  recenterBtn?.addEventListener('click', function() {
    map.flyTo(TUNISIA_CENTER, DEFAULT_ZOOM, { duration: 0.6 });
  });

  // Initialisation au chargement si des coordonnées existent déjà (ex: édition ou après erreur de validation)
  const initialLat = parseFloat(latInput.value);
  const initialLng = parseFloat(lngInput.value);
  if (!isNaN(initialLat) && !isNaN(initialLng)) {
    setTimeout(function() {
      map.invalidateSize();
      pingZone(initialLat, initialLng, true);
    }, 250);
  } else {
    setTimeout(function() {
      map.invalidateSize();
    }, 250);
  }

  window.addEventListener('resize', function() {
    map.invalidateSize();
  });

  // ─── Contrôle de saisie live et synchronisation des erreurs ──────────────
  const form = document.getElementById('zone-form');
  const alertBanner = document.getElementById('zone-form-alert');
  const cpInput = document.getElementById('code_postal');
  const descTextarea = document.getElementById('description');
  const nomCounter = document.getElementById('nom-char-counter');
  const descCounter = document.getElementById('description-char-counter');

  function updateCounters() {
    if (nomInput && nomCounter) {
      const len = nomInput.value.length;
      nomCounter.textContent = `${len} / 100`;
      nomCounter.style.color = len >= 95 ? '#dc2626' : (len > 0 ? '#16a34a' : '#64748b');
    }
    if (descTextarea && descCounter) {
      const len = descTextarea.value.length;
      descCounter.textContent = `${len} / 500`;
      descCounter.style.color = len >= 480 ? '#dc2626' : (len > 0 ? '#16a34a' : '#64748b');
    }
  }

  function setFieldError(field, errorId, msg) {
    if (!field) return;
    field.classList.remove('is-valid');
    field.classList.add('is-invalid');
    const errEl = document.getElementById(errorId);
    if (errEl) {
      errEl.innerHTML = `<i class="fa-solid fa-circle-exclamation me-1"></i>${msg}`;
      errEl.style.display = 'block';
    }
  }

  function setFieldSuccess(field, errorId) {
    if (!field) return;
    field.classList.remove('is-invalid');
    field.classList.add('is-valid');
    const errEl = document.getElementById(errorId);
    if (errEl) errEl.style.display = 'none';
  }

  function validateNom() {
    const val = (nomInput?.value || '').trim();
    if (!val) {
      setFieldError(nomInput, 'nom-error', 'Le nom de la zone est obligatoire.');
      return false;
    }
    if (val.length < 2) {
      setFieldError(nomInput, 'nom-error', 'Le nom doit comporter au moins 2 caractères.');
      return false;
    }
    if (val.length > 100) {
      setFieldError(nomInput, 'nom-error', 'Le nom ne peut pas dépasser 100 caractères.');
      return false;
    }
    setFieldSuccess(nomInput, 'nom-error');
    return true;
  }

  function validateVille() {
    const val = (villeInput?.value || '').trim();
    if (!val) {
      setFieldError(villeInput, 'ville-error', 'La ville est obligatoire.');
      return false;
    }
    if (val.length < 2) {
      setFieldError(villeInput, 'ville-error', 'La ville doit comporter au moins 2 caractères.');
      return false;
    }
    if (val.length > 100) {
      setFieldError(villeInput, 'ville-error', 'La ville ne peut pas dépasser 100 caractères.');
      return false;
    }
    setFieldSuccess(villeInput, 'ville-error');
    return true;
  }

  function validateCP() {
    if (!cpInput) return true;
    const val = cpInput.value.trim();
    if (!val) {
      cpInput.classList.remove('is-invalid');
      const err = document.getElementById('code_postal-error');
      if (err) err.style.display = 'none';
      return true;
    }
    if (!/^\d{4}$/.test(val)) {
      setFieldError(cpInput, 'code_postal-error', 'Le code postal doit comporter exactement 4 chiffres.');
      return false;
    }
    setFieldSuccess(cpInput, 'code_postal-error');
    return true;
  }

  function validateLat() {
    if (!latInput) return true;
    const val = latInput.value.trim();
    if (!val) {
      latInput.classList.remove('is-invalid');
      const err = document.getElementById('latitude-error');
      if (err) err.style.display = 'none';
      return true;
    }
    const num = parseFloat(val);
    if (isNaN(num) || num < -90 || num > 90) {
      setFieldError(latInput, 'latitude-error', 'La latitude doit être comprise entre -90 et 90.');
      return false;
    }
    setFieldSuccess(latInput, 'latitude-error');
    return true;
  }

  function validateLng() {
    if (!lngInput) return true;
    const val = lngInput.value.trim();
    if (!val) {
      lngInput.classList.remove('is-invalid');
      const err = document.getElementById('longitude-error');
      if (err) err.style.display = 'none';
      return true;
    }
    const num = parseFloat(val);
    if (isNaN(num) || num < -180 || num > 180) {
      setFieldError(lngInput, 'longitude-error', 'La longitude doit être comprise entre -180 et 180.');
      return false;
    }
    setFieldSuccess(lngInput, 'longitude-error');
    return true;
  }

  function validateDesc() {
    if (!descTextarea) return true;
    const val = descTextarea.value;
    if (val.length > 500) {
      setFieldError(descTextarea, 'description-error', 'La description ne peut pas dépasser 500 caractères.');
      return false;
    }
    setFieldSuccess(descTextarea, 'description-error');
    return true;
  }

  if (nomInput) {
    nomInput.addEventListener('input', function() { updateCounters(); validateNom(); });
    nomInput.addEventListener('blur', validateNom);
  }
  if (villeInput) {
    villeInput.addEventListener('input', validateVille);
    villeInput.addEventListener('blur', validateVille);
  }
  if (cpInput) {
    cpInput.addEventListener('input', validateCP);
    cpInput.addEventListener('blur', validateCP);
  }
  if (latInput) {
    latInput.addEventListener('input', validateLat);
    latInput.addEventListener('blur', validateLat);
  }
  if (lngInput) {
    lngInput.addEventListener('input', validateLng);
    lngInput.addEventListener('blur', validateLng);
  }
  if (descTextarea) {
    descTextarea.addEventListener('input', function() { updateCounters(); validateDesc(); });
    descTextarea.addEventListener('blur', validateDesc);
  }

  updateCounters();

  if (form) {
    form.addEventListener('submit', function(e) {
      const v1 = validateNom();
      const v2 = validateVille();
      const v3 = validateCP();
      const v4 = validateLat();
      const v5 = validateLng();
      const v6 = validateDesc();

      const allValid = v1 && v2 && v3 && v4 && v5 && v6;

      if (!allValid) {
        e.preventDefault();
        e.stopPropagation();

        if (alertBanner) {
          alertBanner.classList.remove('d-none');
          alertBanner.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        const firstInvalid = form.querySelector('.is-invalid');
        if (firstInvalid) firstInvalid.focus();
      } else {
        if (alertBanner) alertBanner.classList.add('d-none');
      }
    });
  }
});
</script>
@endpush
