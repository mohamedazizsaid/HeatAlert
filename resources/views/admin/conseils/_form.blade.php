{{-- Partial: Formulaire Conseil --}}
<div class="row">
  <div class="col-lg-8">
    <section class="m-card">
      <header class="m-card__header">
        <h2 class="m-card__title">Informations du conseil</h2>
        <p class="m-card__subtitle">Les champs marqués * sont obligatoires.</p>
      </header>
      <div class="row g-3 p-3">

        {{-- Titre --}}
        <div class="col-12">
          <label for="titre" class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
          <input type="text"
                 id="titre" name="titre"
                 class="form-control @error('titre') is-invalid @enderror"
                 value="{{ old('titre', $conseil->titre ?? '') }}"
                 placeholder="Ex: Boire au moins 2 litres d'eau par jour"
                 maxlength="150" required>
          @error('titre')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
        </div>

        {{-- Catégorie --}}
        <div class="col-md-6">
          <label for="categorie" class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
          <select id="categorie" name="categorie"
                  class="form-select @error('categorie') is-invalid @enderror" required>
            @foreach($categories as $cat)
              <option value="{{ $cat }}" {{ old('categorie', $conseil->categorie ?? '') === $cat ? 'selected' : '' }}>
                {{ ucfirst(str_replace('_', ' ', $cat)) }}
              </option>
            @endforeach
          </select>
          @error('categorie')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
        </div>

        {{-- Niveau alerte cible --}}
        <div class="col-md-6">
          <label for="niveau_alerte_cible" class="form-label fw-semibold">Niveau d'alerte cible</label>
          <select id="niveau_alerte_cible" name="niveau_alerte_cible"
                  class="form-select @error('niveau_alerte_cible') is-invalid @enderror">
            <option value="">— Tous les niveaux —</option>
            @foreach($niveaux as $n)
              <option value="{{ $n }}" {{ old('niveau_alerte_cible', $conseil->niveau_alerte_cible ?? '') === $n ? 'selected' : '' }}>
                {{ ucfirst($n) }}
              </option>
            @endforeach
          </select>
          @error('niveau_alerte_cible')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
        </div>

        {{-- Icône --}}
        <div class="col-md-8">
          <label for="icone" class="form-label fw-semibold">Icône FontAwesome</label>
          <div class="input-group">
            <span class="input-group-text" id="icone-preview">
              <i id="icon-display" class="{{ old('icone', $conseil->icone ?? 'fa-solid fa-circle-info') }}"></i>
            </span>
            <input type="text"
                   id="icone" name="icone"
                   class="form-control @error('icone') is-invalid @enderror"
                   value="{{ old('icone', $conseil->icone ?? '') }}"
                   placeholder="Ex: fa-solid fa-droplet"
                   maxlength="60">
          </div>
          @error('icone')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
          <div class="form-text">Classe FontAwesome 6 (ex: <code>fa-solid fa-droplet</code>)</div>
        </div>

        {{-- Actif --}}
        <div class="col-md-4 d-flex align-items-end">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox"
                   id="actif" name="actif" value="1"
                   {{ old('actif', $conseil->actif ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="actif">Conseil actif</label>
          </div>
        </div>

        {{-- Contenu --}}
        <div class="col-12">
          <label for="contenu" class="form-label fw-semibold">Contenu <span class="text-danger">*</span></label>
          <textarea id="contenu" name="contenu"
                    class="form-control @error('contenu') is-invalid @enderror"
                    rows="5"
                    placeholder="Rédigez le contenu détaillé du conseil..."
                    minlength="10">{{ old('contenu', $conseil->contenu ?? '') }}</textarea>
          @error('contenu')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
        </div>

      </div>
    </section>
  </div>

  {{-- Sidebar --}}
  <div class="col-lg-4">
    <section class="m-card">
      <header class="m-card__header">
        <h2 class="m-card__title">Actions</h2>
      </header>
      <div class="p-3 d-grid gap-2">
        <button type="submit" class="m-btn m-btn--primary">
          <i class="fa-solid fa-floppy-disk me-2"></i>Enregistrer
        </button>
        <a href="{{ route('admin.conseils.index') }}" class="m-btn m-btn--ghost text-center">
          <i class="fa-solid fa-xmark me-2"></i>Annuler
        </a>
      </div>

      {{-- Icônes suggérées --}}
      <div class="p-3 border-top">
        <p class="fw-semibold mb-2" style="font-size:13px;">Icônes suggérées :</p>
        @foreach([
          'fa-solid fa-droplet'      => 'Hydratation',
          'fa-solid fa-bolt'         => 'Énergie',
          'fa-solid fa-heart-pulse'  => 'Santé',
          'fa-solid fa-kit-medical'  => 'Équipements',
          'fa-solid fa-house'        => 'Habitat',
          'fa-solid fa-car'          => 'Déplacement',
          'fa-solid fa-sun'          => 'Soleil',
          'fa-solid fa-temperature-high' => 'Chaleur',
        ] as $cls => $label)
        <button type="button"
                class="btn btn-sm btn-outline-secondary mb-1 me-1"
                onclick="document.getElementById('icone').value='{{ $cls }}'; document.getElementById('icon-display').className='{{ $cls }}';"
                title="{{ $label }}">
          <i class="{{ $cls }}"></i> <small>{{ $label }}</small>
        </button>
        @endforeach
      </div>
    </section>
  </div>
</div>

@push('scripts')
<script>
  // Live icon preview
  document.getElementById('icone').addEventListener('input', function() {
    document.getElementById('icon-display').className = this.value || 'fa-solid fa-circle-info';
  });
</script>
@endpush
