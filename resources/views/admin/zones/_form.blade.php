{{-- Partial: Formulaire Zone (create & edit) --}}
<div class="row">
  <div class="col-lg-8">
    <section class="m-card">
      <header class="m-card__header">
        <div>
          <h2 class="m-card__title">Informations de la zone</h2>
          <p class="m-card__subtitle">Les champs marqués * sont obligatoires.</p>
        </div>
      </header>

      <div class="row g-3 p-3">
        {{-- Nom --}}
        <div class="col-md-6">
          <label for="nom" class="form-label fw-semibold">Nom de la zone <span class="text-danger">*</span></label>
          <input type="text"
                 id="nom"
                 name="nom"
                 class="form-control @error('nom') is-invalid @enderror"
                 value="{{ old('nom', $zone->nom ?? '') }}"
                 placeholder="Ex: Zone Tunis Centre"
                 maxlength="100"
                 required>
          @error('nom')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
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
          @error('ville')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
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
          @error('code_postal')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
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
          @error('latitude')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
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
          @error('longitude')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
        </div>

        {{-- Description --}}
        <div class="col-12">
          <label for="description" class="form-label fw-semibold">Description</label>
          <textarea id="description"
                    name="description"
                    class="form-control @error('description') is-invalid @enderror"
                    rows="3"
                    maxlength="500"
                    placeholder="Description de la zone géographique...">{{ old('description', $zone->description ?? '') }}</textarea>
          @error('description')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
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
        <a href="{{ route('admin.zones.index') }}" class="m-btn m-btn--ghost text-center">
          <i class="fa-solid fa-xmark me-2"></i>Annuler
        </a>
      </div>
    </section>
  </div>
</div>
