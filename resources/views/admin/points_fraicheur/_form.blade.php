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
        <div class="col-12 d-none" id="pf-form-alert">
          <div class="alert alert-danger d-flex align-items-center mb-1 py-2 px-3 rounded-3" style="font-size: 13px;">
            <i class="fa-solid fa-triangle-exclamation me-2 fs-5"></i>
            <span id="pf-form-alert-msg">Veuillez corriger les erreurs indiquées ci-dessous avant d'enregistrer.</span>
          </div>
        </div>

        {{-- Nom --}}
        <div class="col-md-8">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <label for="nom" class="form-label fw-semibold mb-0">Nom du point <span class="text-danger">*</span></label>
            <small class="text-muted" id="nom-counter" style="font-size:11px;">0 / 150</small>
          </div>
          <input type="text"
                 id="nom" name="nom"
                 class="form-control @error('nom') is-invalid @enderror"
                 value="{{ old('nom', $point->nom ?? '') }}"
                 placeholder="Ex: Parc Habib Thameur (min 3 car.)"
                 maxlength="150" required>
          <div id="nom-client-error" class="text-danger small mt-1 d-none"><i class="fa-solid fa-circle-exclamation me-1"></i><span></span></div>
          @error('nom')<div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
        </div>

        {{-- Type --}}
        <div class="col-md-4">
          <label for="type" class="form-label fw-semibold mb-1">Type d'espace <span class="text-danger">*</span></label>
          <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
            <option value="">— Sélectionner —</option>
            @foreach(\App\Models\PointFraicheur::$typeLabels as $val => $label)
              <option value="{{ $val }}" {{ old('type', $point->type ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
          <div id="type-client-error" class="text-danger small mt-1 d-none"><i class="fa-solid fa-circle-exclamation me-1"></i><span></span></div>
          @error('type')<div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
        </div>

        {{-- Adresse --}}
        <div class="col-12">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <label for="adresse" class="form-label fw-semibold mb-0">Adresse géographique <span class="text-danger">*</span></label>
            <small class="text-muted" id="adresse-counter" style="font-size:11px;">0 / 255</small>
          </div>
          <input type="text"
                 id="adresse" name="adresse"
                 class="form-control @error('adresse') is-invalid @enderror"
                 value="{{ old('adresse', $point->adresse ?? '') }}"
                 placeholder="Ex: Avenue de la République, Bab El Khadra, Tunis (min 5 car.)"
                 maxlength="255" required>
          <div id="adresse-client-error" class="text-danger small mt-1 d-none"><i class="fa-solid fa-circle-exclamation me-1"></i><span></span></div>
          @error('adresse')<div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
        </div>

        {{-- Latitude --}}
        <div class="col-md-6">
          <label for="latitude" class="form-label fw-semibold mb-1">Latitude (GPS)</label>
          <input type="number" id="latitude" name="latitude"
                 class="form-control @error('latitude') is-invalid @enderror"
                 value="{{ old('latitude', $point->latitude ?? '') }}"
                 placeholder="Ex: 36.8197 (-90 à 90)" step="0.0000001" min="-90" max="90">
          <div id="latitude-client-error" class="text-danger small mt-1 d-none"><i class="fa-solid fa-circle-exclamation me-1"></i><span></span></div>
          @error('latitude')<div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
        </div>

        {{-- Longitude --}}
        <div class="col-md-6">
          <label for="longitude" class="form-label fw-semibold mb-1">Longitude (GPS)</label>
          <input type="number" id="longitude" name="longitude"
                 class="form-control @error('longitude') is-invalid @enderror"
                 value="{{ old('longitude', $point->longitude ?? '') }}"
                 placeholder="Ex: 10.1662 (-180 à 180)" step="0.0000001" min="-180" max="180">
          <div id="longitude-client-error" class="text-danger small mt-1 d-none"><i class="fa-solid fa-circle-exclamation me-1"></i><span></span></div>
          @error('longitude')<div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>@enderror
        </div>

        {{-- Horaires --}}
        <div class="col-md-8">
          <label for="horaires" class="form-label fw-semibold mb-1">Horaires d'ouverture</label>
          <input type="text" id="horaires" name="horaires"
                 class="form-control @error('horaires') is-invalid @enderror"
                 value="{{ old('horaires', $point->horaires ?? '') }}"
                 placeholder="Ex: 8h00 – 18h00, 7j/7"
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

  // ─── Contrôle de saisie en temps réel (Client-side validation) ───────────────
  const form = document.getElementById('point-fraicheur-form');
  const typeSelect = document.getElementById('type');
  const adresseInput = document.getElementById('adresse');
  const formAlert = document.getElementById('pf-form-alert');
  const nomCounter = document.getElementById('nom-counter');
  const adresseCounter = document.getElementById('adresse-counter');

  function setValid(input, errorEl) {
    input.classList.remove('is-invalid');
    input.classList.add('is-valid');
    if (errorEl) {
      errorEl.classList.add('d-none');
      errorEl.querySelector('span').textContent = '';
    }
  }

  function setInvalid(input, errorEl, message) {
    input.classList.remove('is-valid');
    input.classList.add('is-invalid');
    if (errorEl) {
      errorEl.classList.remove('d-none');
      errorEl.querySelector('span').textContent = message;
    }
  }

  function clearState(input, errorEl) {
    input.classList.remove('is-valid', 'is-invalid');
    if (errorEl) {
      errorEl.classList.add('d-none');
      errorEl.querySelector('span').textContent = '';
    }
  }

  // Nom validation
  function validateNom() {
    const val = (nomInput?.value || '').trim();
    if (nomCounter) nomCounter.textContent = `${(nomInput?.value || '').length} / 150`;
    const err = document.getElementById('nom-client-error');
    if (!val) {
      setInvalid(nomInput, err, 'Le nom du point de fraîcheur est obligatoire.');
      return false;
    }
    if (val.length < 3) {
      setInvalid(nomInput, err, 'Le nom doit comporter au moins 3 caractères.');
      return false;
    }
    if (val.length > 150) {
      setInvalid(nomInput, err, 'Le nom ne peut pas dépasser 150 caractères.');
      return false;
    }
    setValid(nomInput, err);
    return true;
  }

  // Type validation
  function validateType() {
    const val = typeSelect?.value || '';
    const err = document.getElementById('type-client-error');
    if (!val) {
      setInvalid(typeSelect, err, 'Veuillez sélectionner un type d\'espace.');
      return false;
    }
    setValid(typeSelect, err);
    return true;
  }

  // Adresse validation
  function validateAdresse() {
    const val = (adresseInput?.value || '').trim();
    if (adresseCounter) adresseCounter.textContent = `${(adresseInput?.value || '').length} / 255`;
    const err = document.getElementById('adresse-client-error');
    if (!val) {
      setInvalid(adresseInput, err, 'L\'adresse géographique est obligatoire.');
      return false;
    }
    if (val.length < 5) {
      setInvalid(adresseInput, err, 'L\'adresse doit comporter au moins 5 caractères.');
      return false;
    }
    if (val.length > 255) {
      setInvalid(adresseInput, err, 'L\'adresse ne peut pas dépasser 255 caractères.');
      return false;
    }
    setValid(adresseInput, err);
    return true;
  }

  // Latitude validation
  function validateLatitude() {
    const val = latInput?.value?.trim();
    const err = document.getElementById('latitude-client-error');
    if (!val) {
      clearState(latInput, err);
      return true;
    }
    const num = parseFloat(val);
    if (isNaN(num) || num < -90 || num > 90) {
      setInvalid(latInput, err, 'La latitude doit être un nombre décimal entre -90 et 90.');
      return false;
    }
    setValid(latInput, err);
    return true;
  }

  // Longitude validation
  function validateLongitude() {
    const val = lngInput?.value?.trim();
    const err = document.getElementById('longitude-client-error');
    if (!val) {
      clearState(lngInput, err);
      return true;
    }
    const num = parseFloat(val);
    if (isNaN(num) || num < -180 || num > 180) {
      setInvalid(lngInput, err, 'La longitude doit être un nombre décimal entre -180 et 180.');
      return false;
    }
    setValid(lngInput, err);
    return true;
  }

  // Event listeners live
  nomInput?.addEventListener('input', validateNom);
  nomInput?.addEventListener('blur', validateNom);
  typeSelect?.addEventListener('change', validateType);
  adresseInput?.addEventListener('input', validateAdresse);
  adresseInput?.addEventListener('blur', validateAdresse);
  latInput?.addEventListener('input', validateLatitude);
  latInput?.addEventListener('blur', validateLatitude);
  lngInput?.addEventListener('input', validateLongitude);
  lngInput?.addEventListener('blur', validateLongitude);

  // Initial counters
  if (nomInput && nomCounter) nomCounter.textContent = `${nomInput.value.length} / 150`;
  if (adresseInput && adresseCounter) adresseCounter.textContent = `${adresseInput.value.length} / 255`;

  // Submit blocker if invalid
  form?.addEventListener('submit', function(e) {
    const okNom = validateNom();
    const okType = validateType();
    const okAdr = validateAdresse();
    const okLat = validateLatitude();
    const okLng = validateLongitude();

    if (!okNom || !okType || !okAdr || !okLat || !okLng) {
      e.preventDefault();
      e.stopPropagation();
      if (formAlert) {
        formAlert.classList.remove('d-none');
        formAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
      const firstInvalid = form.querySelector('.is-invalid');
      if (firstInvalid) {
        firstInvalid.focus();
      }
    } else {
      if (formAlert) formAlert.classList.add('d-none');
    }
  });
});
</script>
@endpush
