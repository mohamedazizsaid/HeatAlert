{{-- Partial: Formulaire AlerteMeteo --}}
<div class="row">
  <div class="col-lg-8">

    {{-- Infos principales --}}
    <section class="m-card mb-3">
      <header class="m-card__header">
        <h2 class="m-card__title">Informations principales</h2>
      </header>
      <div class="row g-3 p-3">
        {{-- Titre --}}
        <div class="col-12">
          <label for="titre" class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
          <input type="text"
                 id="titre" name="titre"
                 class="form-control @error('titre') is-invalid @enderror"
                 value="{{ old('titre', $alerte->titre ?? '') }}"
                 placeholder="Ex: Vague de chaleur intense sur Tunis"
                 maxlength="150" required>
          @error('titre')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
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
          @error('type')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
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
          @error('niveau')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
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
          @error('statut')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
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
          @error('zone_id')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
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
          @error('source')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
        </div>

        {{-- Description --}}
        <div class="col-12">
          <label for="description" class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
          <textarea id="description" name="description"
                    class="form-control @error('description') is-invalid @enderror"
                    rows="4"
                    placeholder="Description détaillée de l'alerte météo..."
                    minlength="10">{{ old('description', $alerte->description ?? '') }}</textarea>
          @error('description')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
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
          @error('date_debut')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-6">
          <label for="date_fin" class="form-label fw-semibold">Date de fin</label>
          <input type="datetime-local" id="date_fin" name="date_fin"
                 class="form-control @error('date_fin') is-invalid @enderror"
                 value="{{ old('date_fin', isset($alerte->date_fin) ? $alerte->date_fin->format('Y-m-d\TH:i') : '') }}">
          @error('date_fin')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
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

  {{-- Sidebar actions --}}
  <div class="col-lg-4">
    <section class="m-card">
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
  </div>
</div>
