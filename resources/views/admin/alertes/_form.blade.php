{{-- Partial: Formulaire AlerteMeteo --}}
<div class="row align-items-stretch">
  <div class="col-lg-8">
    {{-- Bannière d'erreur dynamique --}}
    <div id="alerte-form-alert" class="alert alert-danger d-none mb-3 py-2 px-3 small rounded-3" style="border-radius:10px;">
      <i class="fa-solid fa-triangle-exclamation me-1"></i>
      <span>Veuillez corriger les erreurs ci-dessous avant d'enregistrer l'alerte.</span>
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

    {{-- Infos principales --}}
    <section class="m-card mb-3">
      <header class="m-card__header">
        <h2 class="m-card__title">Informations principales</h2>
      </header>
      <div class="row g-3 p-3">
        {{-- Titre --}}
        <div class="col-12">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <label for="titre" class="form-label fw-semibold mb-0">Titre <span class="text-danger">*</span></label>
            <span class="small text-muted" id="titre-char-counter">0 / 150</span>
          </div>
          <input type="text"
                 id="titre" name="titre"
                 class="form-control @error('titre') is-invalid @enderror"
                 value="{{ old('titre', $alerte->titre ?? '') }}"
                 placeholder="Ex: Vague de chaleur intense sur Tunis"
                 maxlength="150" required>
          <div class="invalid-feedback" id="titre-error"><i class="fa-solid fa-circle-exclamation me-1"></i>@error('titre'){{ $message }}@else{{ 'Le titre de l\'alerte est obligatoire.' }}@enderror</div>
        </div>

        {{-- Type --}}
        <div class="col-md-4">
          <label for="type" class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
          <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
            @foreach($types as $t)
              <option value="{{ $t }}" {{ old('type', $alerte->type ?? '') === $t ? 'selected' : '' }}>
                {{ ucfirst(str_replace('_', ' ', $t)) }}
              </option>
            @endforeach
          </select>
          <div class="invalid-feedback" id="type-error"><i class="fa-solid fa-circle-exclamation me-1"></i>@error('type'){{ $message }}@else{{ 'Le type d\'aléa est obligatoire.' }}@enderror</div>
        </div>

        {{-- Niveau --}}
        <div class="col-md-4">
          <label for="niveau" class="form-label fw-semibold">Niveau <span class="text-danger">*</span></label>
          <select id="niveau" name="niveau" class="form-select @error('niveau') is-invalid @enderror" required>
            @foreach($niveaux as $n)
              <option value="{{ $n }}" {{ old('niveau', $alerte->niveau ?? 'jaune') === $n ? 'selected' : '' }}>
                {{ ucfirst($n) }}
              </option>
            @endforeach
          </select>
          <div class="invalid-feedback" id="niveau-error"><i class="fa-solid fa-circle-exclamation me-1"></i>@error('niveau'){{ $message }}@else{{ 'Le niveau de gravité est obligatoire.' }}@enderror</div>
        </div>

        {{-- Statut --}}
        <div class="col-md-4">
          <label for="statut" class="form-label fw-semibold">Statut <span class="text-danger">*</span></label>
          <select id="statut" name="statut" class="form-select @error('statut') is-invalid @enderror" required>
            @foreach($statuts as $s)
              <option value="{{ $s }}" {{ old('statut', $alerte->statut ?? 'brouillon') === $s ? 'selected' : '' }}>
                {{ ucfirst($s) }}
              </option>
            @endforeach
          </select>
          <div class="invalid-feedback" id="statut-error"><i class="fa-solid fa-circle-exclamation me-1"></i>@error('statut'){{ $message }}@else{{ 'Le statut est obligatoire.' }}@enderror</div>
        </div>

        {{-- Zone --}}
        <div class="col-md-6">
          <label for="zone_id" class="form-label fw-semibold">Zone géographique</label>
          <select id="zone_id" name="zone_id" class="form-select @error('zone_id') is-invalid @enderror">
            <option value="">— Toutes les zones —</option>
            @foreach($zones as $zone)
              <option value="{{ $zone->id }}" {{ old('zone_id', $alerte->zone_id ?? '') == $zone->id ? 'selected' : '' }}>
                {{ $zone->ville }} ({{ $zone->gouvernorat }})
              </option>
            @endforeach
          </select>
          <div class="invalid-feedback" id="zone_id-error"><i class="fa-solid fa-circle-exclamation me-1"></i>@error('zone_id'){{ $message }}@enderror</div>
        </div>

        {{-- Source --}}
        <div class="col-md-6">
          <label for="source" class="form-label fw-semibold">Source</label>
          <input type="text"
                 id="source" name="source"
                 class="form-control @error('source') is-invalid @enderror"
                 value="{{ old('source', $alerte->source ?? '') }}"
                 placeholder="Ex: INM Tunisie"
                 maxlength="100">
          <div class="invalid-feedback" id="source-error"><i class="fa-solid fa-circle-exclamation me-1"></i>@error('source'){{ $message }}@enderror</div>
        </div>

        {{-- Description --}}
        <div class="col-12">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <label for="description" class="form-label fw-semibold mb-0">Description <span class="text-danger">*</span></label>
            <span class="small text-muted" id="description-char-counter">0 caractères (min: 10)</span>
          </div>
          <textarea id="description" name="description"
                    class="form-control @error('description') is-invalid @enderror"
                    rows="4"
                    placeholder="Description détaillée de l'alerte météo..."
                    minlength="10">{{ old('description', $alerte->description ?? '') }}</textarea>
          <div class="invalid-feedback" id="description-error"><i class="fa-solid fa-circle-exclamation me-1"></i>@error('description'){{ $message }}@else{{ 'La description doit comporter au moins 10 caractères.' }}@enderror</div>
        </div>
      </div>
    </section>

    {{-- Données météo --}}
    <section class="m-card mb-3">
      <header class="m-card__header">
        <h2 class="m-card__title"><i class="fa-solid fa-thermometer-half me-2"></i>Données météorologiques</h2>
      </header>
      <div class="row g-3 p-3">
        <div class="col-md-4">
          <label for="temperature_min" class="form-label fw-semibold">Temp. min (°C)</label>
          <input type="number" id="temperature_min" name="temperature_min"
                 class="form-control @error('temperature_min') is-invalid @enderror"
                 value="{{ old('temperature_min', $alerte->temperature_min ?? '') }}"
                 step="0.1" min="-50" max="60" placeholder="Ex: 28">
          @error('temperature_min')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-4">
          <label for="temperature_max" class="form-label fw-semibold">Temp. max (°C)</label>
          <input type="number" id="temperature_max" name="temperature_max"
                 class="form-control @error('temperature_max') is-invalid @enderror"
                 value="{{ old('temperature_max', $alerte->temperature_max ?? '') }}"
                 step="0.1" min="-50" max="60" placeholder="Ex: 45">
          @error('temperature_max')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-4">
          <label for="temperature_ressentie" class="form-label fw-semibold">Temp. ressentie (°C)</label>
          <input type="number" id="temperature_ressentie" name="temperature_ressentie"
                 class="form-control @error('temperature_ressentie') is-invalid @enderror"
                 value="{{ old('temperature_ressentie', $alerte->temperature_ressentie ?? '') }}"
                 step="0.1" min="-50" max="70" placeholder="Ex: 50">
          @error('temperature_ressentie')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-4">
          <label for="humidite" class="form-label fw-semibold">Humidité (%)</label>
          <input type="number" id="humidite" name="humidite"
                 class="form-control @error('humidite') is-invalid @enderror"
                 value="{{ old('humidite', $alerte->humidite ?? '') }}"
                 min="0" max="100" placeholder="Ex: 45">
          @error('humidite')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-4">
          <label for="indice_uv" class="form-label fw-semibold">Indice UV (0-20)</label>
          <input type="number" id="indice_uv" name="indice_uv"
                 class="form-control @error('indice_uv') is-invalid @enderror"
                 value="{{ old('indice_uv', $alerte->indice_uv ?? '') }}"
                 min="0" max="20" placeholder="Ex: 10">
          @error('indice_uv')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-4">
          <label for="vitesse_vent" class="form-label fw-semibold">Vitesse vent (km/h)</label>
          <input type="number" id="vitesse_vent" name="vitesse_vent"
                 class="form-control @error('vitesse_vent') is-invalid @enderror"
                 value="{{ old('vitesse_vent', $alerte->vitesse_vent ?? '') }}"
                 step="0.1" min="0" max="300" placeholder="Ex: 25">
          @error('vitesse_vent')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-6">
          <label for="date_debut" class="form-label fw-semibold">Date de début <span class="text-danger">*</span></label>
          <input type="datetime-local" id="date_debut" name="date_debut"
                 class="form-control @error('date_debut') is-invalid @enderror"
                 value="{{ old('date_debut', isset($alerte->date_debut) ? $alerte->date_debut->format('Y-m-d\TH:i') : '') }}"
                 required>
          <div class="invalid-feedback" id="date_debut-error"><i class="fa-solid fa-circle-exclamation me-1"></i>@error('date_debut'){{ $message }}@else{{ 'La date de début est obligatoire.' }}@enderror</div>
        </div>

        <div class="col-md-6">
          <label for="date_fin" class="form-label fw-semibold">Date de fin</label>
          <input type="datetime-local" id="date_fin" name="date_fin"
                 class="form-control @error('date_fin') is-invalid @enderror"
                 value="{{ old('date_fin', isset($alerte->date_fin) ? $alerte->date_fin->format('Y-m-d\TH:i') : '') }}">
          <div class="invalid-feedback" id="date_fin-error"><i class="fa-solid fa-circle-exclamation me-1"></i>@error('date_fin'){{ $message }}@else{{ 'La date de fin doit être égale ou postérieure à la date de début.' }}@enderror</div>
        </div>

        <div class="col-12">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox"
                   id="risque_coupure" name="risque_coupure" value="1"
                   {{ old('risque_coupure', $alerte->risque_coupure ?? false) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="risque_coupure">
              <i class="fa-solid fa-bolt me-1 text-warning"></i>Risque de coupure électrique
            </label>
          </div>
        </div>
      </div>
    </section>

  </div>

  {{-- Sidebar actions & Simulateur HeatAlert --}}
  <div class="col-lg-4 d-flex flex-column">
    <section class="m-card mb-3 flex-shrink-0">
      <header class="m-card__header">
        <h2 class="m-card__title">Actions</h2>
      </header>
      <div class="p-3 d-grid gap-2">
        <button type="submit" class="m-btn m-btn--primary">
          <i class="fa-solid fa-floppy-disk me-2"></i>Enregistrer
        </button>
        <a href="{{ route('admin.alertes.index') }}" class="m-btn m-btn--ghost text-center">
          <i class="fa-solid fa-xmark me-2"></i>Annuler
        </a>
      </div>
    </section>

    {{-- Widget HeatAlert: Simulateur & Aperçu de Vigilance en Temps Réel --}}
    <section class="m-card flex-grow-1 d-flex flex-column mb-3 shadow-sm border-0" id="heat-preview-card" style="border-radius: 12px; overflow: hidden;">
      <header class="m-card__header d-flex justify-content-between align-items-center flex-shrink-0 py-2 px-3 bg-light border-bottom">
        <h2 class="m-card__title fs-6 mb-0 d-flex align-items-center">
          <i class="fa-solid fa-tower-broadcast text-danger me-2"></i>Aperçu & Impact Plateforme
        </h2>
        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1" style="font-size: 0.7rem;">
          <i class="fa-solid fa-circle-dot fa-fade me-1"></i>Live HeatAlert
        </span>
      </header>

      <div class="p-3 d-flex flex-column gap-3 flex-grow-1 bg-white">

        {{-- Carte Vigilance Dynamique --}}
        <div id="preview-vigilance-card" class="rounded-3 p-3 text-white transition-all shadow-sm position-relative overflow-hidden" style="background: linear-gradient(135deg, #d97706, #f59e0b); transition: all 0.35s ease;">
          <div class="position-absolute end-0 bottom-0 opacity-10 pe-2 pb-1" style="font-size: 4.5rem; pointer-events: none; line-height: 1;">
            <i class="fa-solid fa-triangle-exclamation" id="preview-bg-watermark"></i>
          </div>
          <div class="d-flex justify-content-between align-items-start mb-2 position-relative">
            <span class="badge bg-black bg-opacity-30 text-uppercase fw-bold text-white px-2 py-1" id="preview-level-badge" style="letter-spacing: 0.5px; font-size: 0.72rem;">
              <i class="fa-solid fa-bell me-1"></i>Vigilance Jaune
            </span>
            {{-- Élément conservé pour le JS (caché visuellement) --}}
            <span id="preview-status-badge" style="display: none;"></span>
          </div>
          <h5 class="fw-bold mb-1 text-white text-truncate position-relative" id="preview-titre" title="Vague de chaleur">
            Vague de chaleur
          </h5>
          <div class="small text-white-50 d-flex align-items-center gap-1 mb-2 position-relative">
            <i class="fa-solid fa-location-dot text-white"></i>
            <span id="preview-zone" class="text-white fw-medium">Toutes les zones (National)</span>
          </div>
          <div class="d-flex align-items-center justify-content-between pt-2 border-top border-white border-opacity-25 small position-relative">
            <span><i class="fa-solid fa-shield-halved me-1 text-white-50"></i>Type: <strong id="preview-type">Canicule</strong></span>
            <span><i class="fa-solid fa-clock me-1 text-white-50"></i><span id="preview-dates">Dates à définir</span></span>
          </div>
        </div>

        {{-- Calculateur de Risque Thermique & Heat Index --}}
        <div class="p-3 rounded-3 border bg-light bg-opacity-75">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="fw-bold text-dark small">
              <i class="fa-solid fa-calculator text-primary me-1"></i>Stress Thermique (Heat Index)
            </span>
            <span class="badge bg-dark rounded-pill px-2 py-1" id="preview-heat-index-val">-- °C</span>
          </div>

          <div class="progress my-2" style="height: 8px; border-radius: 4px; background-color: #e2e8f0;">
            <div id="preview-heat-progress" class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: 40%; transition: width 0.4s ease;"></div>
          </div>
          <div class="d-flex justify-content-between align-items-center" style="font-size: 0.72rem;">
            <span class="text-muted fw-medium" id="preview-heat-diag">Niveau de confort standard</span>
            <span class="fw-bold text-warning" id="preview-heat-category">Modéré</span>
          </div>
        </div>

        {{-- Métriques Clés en direct --}}
        <div class="row g-2">
          <div class="col-4">
            <div class="border rounded-2 p-2 text-center bg-white shadow-xs">
              <div class="text-muted small" style="font-size: 0.68rem;"><i class="fa-solid fa-temperature-high text-danger me-1"></i>Max</div>
              <div class="fw-bold text-dark fs-6" id="preview-stat-temp-max">-- °C</div>
            </div>
          </div>
          <div class="col-4">
            <div class="border rounded-2 p-2 text-center bg-white shadow-xs">
              <div class="text-muted small" style="font-size: 0.68rem;"><i class="fa-solid fa-droplet text-info me-1"></i>Humidité</div>
              <div class="fw-bold text-dark fs-6" id="preview-stat-humidite">-- %</div>
            </div>
          </div>
          <div class="col-4">
            <div class="border rounded-2 p-2 text-center bg-white shadow-xs">
              <div class="text-muted small" style="font-size: 0.68rem;"><i class="fa-solid fa-sun text-warning me-1"></i>UV Max</div>
              <div class="fw-bold text-dark fs-6" id="preview-stat-uv">--</div>
            </div>
          </div>
        </div>

        {{-- Protocoles d'Urgence Automatisés HeatAlert --}}
        <div class="p-3 rounded-3 border bg-white shadow-xs">
          <div class="fw-bold text-dark small mb-2 d-flex align-items-center justify-content-between border-bottom pb-2">
            <span><i class="fa-solid fa-bolt-lightning text-warning me-1"></i>Protocoles Déclenchés</span>
            <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.68rem;" id="preview-protocol-count">3 protocoles</span>
          </div>
          <ul class="list-unstyled mb-0 d-flex flex-column gap-2" style="font-size: 0.78rem;">
            <li class="d-flex align-items-center gap-2" id="protocol-diffusion">
              <i class="fa-solid fa-circle-check text-success flex-shrink-0" style="font-size: 0.9rem;"></i>
              <span class="text-dark" id="protocol-diffusion-text">Diffusion immédiate portail & app mobile</span>
            </li>
            <li class="d-flex align-items-center gap-2" id="protocol-vulnerable">
              <i class="fa-solid fa-person-shelter text-warning flex-shrink-0" id="protocol-vulnerable-icon" style="font-size: 0.9rem;"></i>
              <span class="text-dark" id="protocol-vulnerable-text">Pré-alerte structures d'accueil & hôpitaux</span>
            </li>
            <li class="d-flex align-items-center gap-2" id="protocol-power">
              <i class="fa-solid fa-plug-circle-check text-secondary flex-shrink-0" id="protocol-power-icon" style="font-size: 0.9rem;"></i>
              <span class="text-secondary" id="protocol-power-text">Réseau électrique : Surveillance standard</span>
            </li>
            <li class="d-flex align-items-center gap-2 text-danger d-none" id="protocol-fire">
              <i class="fa-solid fa-fire-flame-curved text-danger flex-shrink-0" style="font-size: 0.9rem;"></i>
              <span class="fw-medium">Vents forts (> 30 km/h) : Risque de feux de forêt élevé !</span>
            </li>
          </ul>
        </div>

        {{-- Badge d'engagement HeatAlert --}}
        <div class="p-2 rounded-2 bg-primary-subtle text-primary border border-primary-subtle small d-flex align-items-start gap-2 mt-auto" style="font-size: 0.74rem;">
          <i class="fa-solid fa-circle-info text-primary mt-1"></i>
          <div>
            <strong>Système d'Alerte Précoce :</strong> Les alertes de niveau <strong>Orange</strong> et <strong>Rouge</strong> génèrent automatiquement des notifications push urgentes aux populations exposées.
          </div>
        </div>

      </div>
    </section>
  </div>
</div>

@push('styles')
<style>
  #preview-vigilance-card {
    position: relative;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  }
  .shadow-xs {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const titreInput = document.getElementById('titre');
  const typeSelect = document.getElementById('type');
  const niveauSelect = document.getElementById('niveau');
  const statutSelect = document.getElementById('statut');
  const zoneSelect = document.getElementById('zone_id');
  const tempMaxInput = document.getElementById('temperature_max');
  const tempRessentieInput = document.getElementById('temperature_ressentie');
  const humiditeInput = document.getElementById('humidite');
  const uvInput = document.getElementById('indice_uv');
  const ventInput = document.getElementById('vitesse_vent');
  const dateDebutInput = document.getElementById('date_debut');
  const dateFinInput = document.getElementById('date_fin');
  const risqueCoupureInput = document.getElementById('risque_coupure');

  // Preview elements
  const prevCard = document.getElementById('preview-vigilance-card');
  const prevTitre = document.getElementById('preview-titre');
  const prevZone = document.getElementById('preview-zone');
  const prevType = document.getElementById('preview-type');
  const prevLevelBadge = document.getElementById('preview-level-badge');
  const prevStatusBadge = document.getElementById('preview-status-badge');
  const prevDates = document.getElementById('preview-dates');
  const prevBgWatermark = document.getElementById('preview-bg-watermark');

  const prevHeatIndexVal = document.getElementById('preview-heat-index-val');
  const prevHeatProgress = document.getElementById('preview-heat-progress');
  const prevHeatDiag = document.getElementById('preview-heat-diag');
  const prevHeatCategory = document.getElementById('preview-heat-category');

  const prevStatTempMax = document.getElementById('preview-stat-temp-max');
  const prevStatHumidite = document.getElementById('preview-stat-humidite');
  const prevStatUv = document.getElementById('preview-stat-uv');

  const protocolDiffusionText = document.getElementById('protocol-diffusion-text');
  const protocolVulnerableIcon = document.getElementById('protocol-vulnerable-icon');
  const protocolVulnerableText = document.getElementById('protocol-vulnerable-text');
  const protocolPower = document.getElementById('protocol-power');
  const protocolPowerIcon = document.getElementById('protocol-power-icon');
  const protocolPowerText = document.getElementById('protocol-power-text');
  const protocolFire = document.getElementById('protocol-fire');
  const protocolCount = document.getElementById('preview-protocol-count');

  // Level configuration
  const levelThemes = {
    vert: {
      gradient: 'linear-gradient(135deg, #059669 0%, #10b981 100%)',
      badgeClass: 'Vigilance Verte (Normale)',
      icon: 'fa-solid fa-circle-check',
      watermark: 'fa-shield-heart'
    },
    jaune: {
      gradient: 'linear-gradient(135deg, #d97706 0%, #f59e0b 100%)',
      badgeClass: 'Vigilance Jaune (attentif)',
      icon: 'fa-solid fa-triangle-exclamation',
      watermark: 'fa-triangle-exclamation'
    },
    orange: {
      gradient: 'linear-gradient(135deg, #ea580c 0%, #f97316 100%)',
      badgeClass: 'Vigilance Orange (vigilant)',
      icon: 'fa-solid fa-fire',
      watermark: 'fa-fire'
    },
    rouge: {
      gradient: 'linear-gradient(135deg, #991b1b 0%, #ef4444 100%)',
      badgeClass: 'Vigilance Rouge (Danger)',
      icon: 'fa-solid fa-skull-crossbones',
      watermark: 'fa-skull-crossbones'
    }
  };

  function calculateHeatIndex(tempC, humidity) {
    if (isNaN(tempC) || tempC === null || tempC === '') return null;
    if (tempC < 25 || isNaN(humidity) || humidity === null || humidity === '') {
      return tempC;
    }

    // Convert C to F
    const T = (tempC * 9 / 5) + 32;
    const R = parseFloat(humidity);

    // Rothfusz regression
    let HI = -42.379 + 2.04901523 * T + 10.14333127 * R - 0.22475541 * T * R
      - 0.00683783 * (T * T) - 0.05481717 * (R * R) + 0.00122874 * (T * T) * R
      + 0.00085282 * T * (R * R) - 0.00000199 * (T * T) * (R * R);

    // Convert back to C
    const HI_C = (HI - 32) * 5 / 9;
    return Math.max(tempC, Math.round(HI_C * 10) / 10);
  }

  function formatDateRange(debutStr, finStr) {
    if (!debutStr) return 'Dates à définir';
    try {
      const d1 = new Date(debutStr);
      const opt = { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' };
      const d1Formatted = d1.toLocaleDateString('fr-FR', opt);
      if (!finStr) return `À partir du ${d1Formatted}`;
      const d2 = new Date(finStr);
      const d2Formatted = d2.toLocaleDateString('fr-FR', opt);
      return `${d1Formatted} au ${d2Formatted}`;
    } catch (e) {
      return 'Dates renseignées';
    }
  }

  function updatePreview() {
    // 1. Titre & Type
    const titre = titreInput ? titreInput.value.trim() : '';
    prevTitre.textContent = titre || 'Nouvelle alerte météo';

    if (typeSelect && typeSelect.value) {
      prevType.textContent = typeSelect.options[typeSelect.selectedIndex].text;
    }

    // 2. Zone
    if (zoneSelect) {
      if (zoneSelect.value && zoneSelect.selectedIndex > 0) {
        prevZone.textContent = zoneSelect.options[zoneSelect.selectedIndex].text;
      } else {
        prevZone.textContent = 'Toutes les zones (National)';
      }
    }

    // 3. Statut
    if (statutSelect && statutSelect.value) {
      prevStatusBadge.textContent = statutSelect.value;
      if (statutSelect.value === 'actif') {
        prevStatusBadge.className = 'badge bg-success text-white fw-bold px-2 py-1 text-uppercase';
      } else if (statutSelect.value === 'termine' || statutSelect.value === 'archive') {
        prevStatusBadge.className = 'badge bg-secondary text-white fw-bold px-2 py-1 text-uppercase';
      } else {
        prevStatusBadge.className = 'badge bg-white text-dark fw-bold px-2 py-1 text-uppercase';
      }
    }

    // 4. Niveau & Visual Theme
    const niveau = (niveauSelect ? niveauSelect.value : 'jaune').toLowerCase();
    const theme = levelThemes[niveau] || levelThemes['jaune'];

    if (prevCard) {
      prevCard.style.background = theme.gradient;
    }
    if (prevLevelBadge) {
      prevLevelBadge.innerHTML = `<i class="${theme.icon} me-1"></i>${theme.badgeClass}`;
    }
    if (prevBgWatermark) {
      prevBgWatermark.className = `fa-solid ${theme.watermark}`;
    }

    // 5. Dates
    const dDebut = dateDebutInput ? dateDebutInput.value : '';
    const dFin = dateFinInput ? dateFinInput.value : '';
    if (prevDates) {
      prevDates.textContent = formatDateRange(dDebut, dFin);
    }

    // 6. Metrics & Heat Index
    const tMax = tempMaxInput && tempMaxInput.value ? parseFloat(tempMaxInput.value) : null;
    const hum = humiditeInput && humiditeInput.value ? parseFloat(humiditeInput.value) : null;
    const uv = uvInput && uvInput.value ? parseFloat(uvInput.value) : null;
    const vent = ventInput && ventInput.value ? parseFloat(ventInput.value) : null;

    prevStatTempMax.textContent = tMax !== null ? `${tMax} °C` : '-- °C';
    prevStatHumidite.textContent = hum !== null ? `${hum} %` : '-- %';
    prevStatUv.textContent = uv !== null ? uv : '--';

    // Heat Index Calculation
    const heatIdx = calculateHeatIndex(tMax, hum);

    if (heatIdx !== null && !isNaN(heatIdx)) {
      prevHeatIndexVal.textContent = `${heatIdx} °C`;

      let progressWidth = Math.min(100, Math.max(10, ((heatIdx - 20) / 35) * 100));
      prevHeatProgress.style.width = `${progressWidth}%`;

      if (heatIdx < 27) {
        prevHeatProgress.className = 'progress-bar bg-success';
        prevHeatCategory.textContent = 'Normal';
        prevHeatCategory.className = 'fw-bold text-success';
        prevHeatDiag.textContent = 'Stress thermique faible ou nul';
      } else if (heatIdx < 32) {
        prevHeatProgress.className = 'progress-bar bg-info';
        prevHeatCategory.textContent = 'Vigilance';
        prevHeatCategory.className = 'fw-bold text-info';
        prevHeatDiag.textContent = 'Fatigue possible en cas d\'effort';
      } else if (heatIdx < 41) {
        prevHeatProgress.className = 'progress-bar bg-warning';
        prevHeatCategory.textContent = 'Élevé';
        prevHeatCategory.className = 'fw-bold text-warning';
        prevHeatDiag.textContent = 'Risque de crampes & insolation';
      } else if (heatIdx < 54) {
        prevHeatProgress.className = 'progress-bar bg-orange text-white';
        prevHeatProgress.style.backgroundColor = '#ea580c';
        prevHeatCategory.textContent = 'Danger';
        prevHeatCategory.className = 'fw-bold text-danger';
        prevHeatDiag.textContent = 'Coup de chaleur très probable';
      } else {
        prevHeatProgress.className = 'progress-bar bg-danger';
        prevHeatCategory.textContent = 'Danger Extrême';
        prevHeatCategory.className = 'fw-bold text-danger text-uppercase';
        prevHeatDiag.textContent = 'Risque vital immédiat en plein air';
      }
    } else {
      prevHeatIndexVal.textContent = '-- °C';
      prevHeatProgress.style.width = '20%';
      prevHeatProgress.className = 'progress-bar bg-secondary';
      prevHeatCategory.textContent = 'En attente';
      prevHeatCategory.className = 'fw-bold text-secondary';
      prevHeatDiag.textContent = 'Saisir température & humidité';
    }

    // 7. Automated Protocols
    let activeProtocols = 2; // default: portal + health preview

    if (niveau === 'orange' || niveau === 'rouge') {
      protocolDiffusionText.textContent = 'Diffusion Prioritaire Push & Flash SMS';
      protocolDiffusionText.className = 'text-dark fw-bold';
      protocolVulnerableText.textContent = 'Mobilisation plan d\'urgence & SAMU / Protection Civile';
      protocolVulnerableIcon.className = 'fa-solid fa-truck-medical text-danger flex-shrink-0';
    } else {
      protocolDiffusionText.textContent = 'Diffusion standard portail & app mobile';
      protocolDiffusionText.className = 'text-dark';
      protocolVulnerableText.textContent = 'Pré-alerte structures d\'accueil & hôpitaux';
      protocolVulnerableIcon.className = 'fa-solid fa-person-shelter text-warning flex-shrink-0';
    }

    // Power cut protocol
    if (risqueCoupureInput && risqueCoupureInput.checked) {
      activeProtocols++;
      protocolPower.classList.remove('text-muted');
      protocolPowerIcon.className = 'fa-solid fa-bolt text-warning flex-shrink-0';
      protocolPowerText.className = 'text-dark fw-medium';
      protocolPowerText.textContent = 'Réseau STEG : Alerte pic de charge & risque de délestage';
    } else {
      protocolPowerIcon.className = 'fa-solid fa-plug-circle-check text-secondary flex-shrink-0';
      protocolPowerText.className = 'text-secondary';
      protocolPowerText.textContent = 'Réseau électrique : Surveillance standard';
    }

    // Forest fire risk protocol
    if (vent !== null && vent >= 28 && tMax !== null && tMax >= 34) {
      activeProtocols++;
      protocolFire.classList.remove('d-none');
    } else {
      protocolFire.classList.add('d-none');
    }

    protocolCount.textContent = `${activeProtocols} protocoles`;
  }

  // Bind change/input listeners
  const monitoredInputs = [
    titreInput, typeSelect, niveauSelect, statutSelect, zoneSelect,
    tempMaxInput, tempRessentieInput, humiditeInput, uvInput, ventInput,
    dateDebutInput, dateFinInput, risqueCoupureInput
  ];

  monitoredInputs.forEach(input => {
    if (input) {
      input.addEventListener('input', updatePreview);
      input.addEventListener('change', updatePreview);
    }
  });

  // Initial trigger
  updatePreview();

  // ─── Contrôle de saisie live et synchronisation des erreurs ──────────────
  const form = document.getElementById('alerte-form');
  const alertBanner = document.getElementById('alerte-form-alert');
  const descTextarea = document.getElementById('description');
  const titreCounter = document.getElementById('titre-char-counter');
  const descCounter = document.getElementById('description-char-counter');

  function updateCounters() {
    if (titreInput && titreCounter) {
      const len = titreInput.value.length;
      titreCounter.textContent = `${len} / 150`;
      titreCounter.style.color = len >= 145 ? '#dc2626' : (len > 0 ? '#16a34a' : '#64748b');
    }
    if (descTextarea && descCounter) {
      const len = descTextarea.value.length;
      descCounter.textContent = `${len} caractères (min: 10)`;
      descCounter.style.color = len >= 10 ? '#16a34a' : (len > 0 ? '#ea580c' : '#dc2626');
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

  function validateTitre() {
    const val = (titreInput?.value || '').trim();
    if (!val) {
      setFieldError(titreInput, 'titre-error', 'Le titre de l\'alerte est obligatoire.');
      return false;
    }
    if (val.length < 3) {
      setFieldError(titreInput, 'titre-error', 'Le titre doit comporter au moins 3 caractères.');
      return false;
    }
    if (val.length > 150) {
      setFieldError(titreInput, 'titre-error', 'Le titre ne peut pas dépasser 150 caractères.');
      return false;
    }
    setFieldSuccess(titreInput, 'titre-error');
    return true;
  }

  function validateType() {
    if (!typeSelect?.value) {
      setFieldError(typeSelect, 'type-error', 'Le type d\'aléa est obligatoire.');
      return false;
    }
    setFieldSuccess(typeSelect, 'type-error');
    return true;
  }

  function validateNiveau() {
    if (!niveauSelect?.value) {
      setFieldError(niveauSelect, 'niveau-error', 'Le niveau de gravité est obligatoire.');
      return false;
    }
    setFieldSuccess(niveauSelect, 'niveau-error');
    return true;
  }

  function validateStatut() {
    if (!statutSelect?.value) {
      setFieldError(statutSelect, 'statut-error', 'Le statut est obligatoire.');
      return false;
    }
    setFieldSuccess(statutSelect, 'statut-error');
    return true;
  }

  function validateDesc() {
    const val = (descTextarea?.value || '').trim();
    if (!val) {
      setFieldError(descTextarea, 'description-error', 'La description est obligatoire.');
      return false;
    }
    if (val.length < 10) {
      setFieldError(descTextarea, 'description-error', 'La description doit comporter au moins 10 caractères.');
      return false;
    }
    setFieldSuccess(descTextarea, 'description-error');
    return true;
  }

  function validateDates() {
    let ok = true;
    if (!dateDebutInput?.value) {
      setFieldError(dateDebutInput, 'date_debut-error', 'La date de début est obligatoire.');
      ok = false;
    } else {
      setFieldSuccess(dateDebutInput, 'date_debut-error');
    }

    if (dateFinInput?.value) {
      if (dateDebutInput?.value && dateFinInput.value < dateDebutInput.value) {
        setFieldError(dateFinInput, 'date_fin-error', 'La date de fin doit être égale ou postérieure à la date de début.');
        ok = false;
      } else {
        setFieldSuccess(dateFinInput, 'date_fin-error');
      }
    } else {
      if (dateFinInput) {
        dateFinInput.classList.remove('is-invalid');
        const err = document.getElementById('date_fin-error');
        if (err) err.style.display = 'none';
      }
    }
    return ok;
  }

  if (titreInput) {
    titreInput.addEventListener('input', function() { updateCounters(); validateTitre(); });
    titreInput.addEventListener('blur', validateTitre);
  }
  if (typeSelect) typeSelect.addEventListener('change', validateType);
  if (niveauSelect) niveauSelect.addEventListener('change', validateNiveau);
  if (statutSelect) statutSelect.addEventListener('change', validateStatut);
  if (descTextarea) {
    descTextarea.addEventListener('input', function() { updateCounters(); validateDesc(); });
    descTextarea.addEventListener('blur', validateDesc);
  }
  if (dateDebutInput) {
    dateDebutInput.addEventListener('input', validateDates);
    dateDebutInput.addEventListener('change', validateDates);
  }
  if (dateFinInput) {
    dateFinInput.addEventListener('input', validateDates);
    dateFinInput.addEventListener('change', validateDates);
  }

  updateCounters();

  if (form) {
    form.addEventListener('submit', function(e) {
      const v1 = validateTitre();
      const v2 = validateType();
      const v3 = validateNiveau();
      const v4 = validateStatut();
      const v5 = validateDesc();
      const v6 = validateDates();

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

