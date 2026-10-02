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
                    rows="4"
                    placeholder="Rédigez le contenu détaillé du conseil..."
                    minlength="10">{{ old('contenu', $conseil->contenu ?? '') }}</textarea>
          @error('contenu')
            <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
        </div>

        {{-- Liaison multiple aux alertes météo (Sélection simple par cases à cocher) --}}
        <div class="col-12">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="form-label fw-semibold mb-0">
              <i class="fa-solid fa-triangle-exclamation text-danger me-1"></i>
              Associer ce conseil à des alertes météo
            </label>
            <div class="d-flex align-items-center gap-2">
              <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-select-all" style="font-size:11px;padding:3px 9px;">
                <i class="fa-solid fa-check-double me-1"></i>Tout cocher
              </button>
              <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-deselect-all" style="font-size:11px;padding:3px 9px;">
                <i class="fa-solid fa-xmark me-1"></i>Tout décocher
              </button>
              <span id="selected-alertes-count" class="badge bg-light text-dark border" style="font-size:.8rem;">
                0 sélectionnée(s)
              </span>
            </div>
          </div>

          {{-- Champ de recherche instantané pour filtrer facilement --}}
          <div class="input-group input-group-sm mb-2">
            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
            <input type="text" id="alerte-filter" class="form-control border-start-0" placeholder="Filtrer les alertes par titre, zone, niveau..." style="font-size:.84rem;">
          </div>

          {{-- Liste déroulante à choix multiple avec simple clic --}}
          <div id="alertes-list" style="max-height: 240px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 12px; padding: 8px; background: #f8fafc;">
            @forelse($alertes as $alerte)
              @php
                $badgeClass = match($alerte->niveau) {
                  'rouge'  => 'bg-danger text-white',
                  'orange' => 'bg-warning text-dark',
                  'jaune'  => 'bg-info text-dark',
                  default  => 'bg-success text-white',
                };
                $zoneName = $alerte->zone ? $alerte->zone->nom . ' (' . $alerte->zone->ville . ')' : 'Nationale';
                $isSel = in_array($alerte->id, old('alerte_ids', $selectedAlertes ?? []));
                $searchable = strtolower($alerte->titre . ' ' . $zoneName . ' ' . $alerte->niveau . ' ' . $alerte->type);
              @endphp
              <label class="alerte-item-card d-flex align-items-center justify-content-between p-2 mb-2 rounded border {{ $isSel ? 'border-danger' : '' }}"
                     style="cursor: pointer; transition: all .15s ease; background: {{ $isSel ? '#fef2f2' : '#ffffff' }};"
                     data-search="{{ $searchable }}">
                <div class="d-flex align-items-center gap-3">
                  <input type="checkbox"
                         name="alerte_ids[]"
                         value="{{ $alerte->id }}"
                         class="form-check-input alerte-cb m-0"
                         style="width: 18px; height: 18px; accent-color: #dc2626; cursor: pointer; flex-shrink: 0;"
                         {{ $isSel ? 'checked' : '' }}>
                  <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                      <span class="badge {{ $badgeClass }}" style="font-size: .72rem; text-transform: uppercase;">
                        {{ ucfirst($alerte->niveau) }}
                      </span>
                      <strong style="font-size: .9rem; color: #0f172a;">{{ $alerte->titre }}</strong>
                      @if($alerte->statut === 'active')
                        <span class="badge bg-danger" style="font-size: .65rem;"><i class="fa-solid fa-bolt me-1"></i>Active</span>
                      @endif
                    </div>
                    <div class="text-muted" style="font-size: .78rem; margin-top: 2px;">
                      <i class="fa-solid fa-location-dot text-danger me-1"></i>{{ $zoneName }}
                      <span class="mx-1">•</span>
                      <i class="fa-regular fa-calendar me-1"></i>{{ $alerte->date_debut ? $alerte->date_debut->format('d/m/Y') : '—' }}
                      @if($alerte->temperature_max)
                        <span class="mx-1">•</span>
                        <span class="text-danger fw-semibold"><i class="fa-solid fa-temperature-high me-1"></i>Max {{ $alerte->temperature_max }}°C</span>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="text-muted pe-2 d-none d-sm-block">
                  <span class="badge bg-light text-dark border" style="font-size: .75rem;">
                    {{ ucfirst(str_replace('_', ' ', $alerte->type)) }}
                  </span>
                </div>
              </label>
            @empty
              <div class="text-center py-4 text-muted">
                <i class="fa-solid fa-triangle-exclamation fa-2x mb-2 d-block"></i>
                Aucune alerte météo existante en base de données.
              </div>
            @endforelse
          </div>
          @error('alerte_ids')
            <div class="invalid-feedback d-block"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
          @enderror
          <small class="text-muted mt-1 d-block" style="font-size:.78rem;">
            <i class="fa-solid fa-circle-info me-1 text-primary"></i>Cliquez simplement sur une alerte pour la cocher ou la décocher.
          </small>
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
  document.getElementById('icone')?.addEventListener('input', function() {
    document.getElementById('icon-display').className = this.value || 'fa-solid fa-circle-info';
  });

  // Gestion de la sélection multiple simple d'alertes météo
  (function() {
    const listContainer = document.getElementById('alertes-list');
    const countBadge = document.getElementById('selected-alertes-count');
    const filterInput = document.getElementById('alerte-filter');

    if (!listContainer) return;

    function updateCountAndStyle() {
      const checkboxes = listContainer.querySelectorAll('.alerte-cb');
      let count = 0;

      checkboxes.forEach(cb => {
        const card = cb.closest('.alerte-item-card');
        if (cb.checked) {
          count++;
          if (card) {
            card.classList.add('border-danger');
            card.style.background = '#fef2f2';
          }
        } else {
          if (card) {
            card.classList.remove('border-danger');
            card.style.background = '#ffffff';
          }
        }
      });

      if (countBadge) {
        countBadge.textContent = count + ' sélectionnée(s)';
        if (count > 0) {
          countBadge.className = 'badge bg-danger text-white';
        } else {
          countBadge.className = 'badge bg-light text-dark border';
        }
      }
    }

    // Écoute des changements sur les cases à cocher
    listContainer.addEventListener('change', function(e) {
      if (e.target.classList.contains('alerte-cb')) {
        updateCountAndStyle();
      }
    });

    // Bouton "Tout cocher"
    document.getElementById('btn-select-all')?.addEventListener('click', function() {
      const items = listContainer.querySelectorAll('.alerte-item-card');
      items.forEach(card => {
        if (card.style.display !== 'none') {
          const cb = card.querySelector('.alerte-cb');
          if (cb) cb.checked = true;
        }
      });
      updateCountAndStyle();
    });

    // Bouton "Tout décocher"
    document.getElementById('btn-deselect-all')?.addEventListener('click', function() {
      const checkboxes = listContainer.querySelectorAll('.alerte-cb');
      checkboxes.forEach(cb => cb.checked = false);
      updateCountAndStyle();
    });

    // Filtrage instantané
    filterInput?.addEventListener('input', function() {
      const query = this.value.toLowerCase().trim();
      const items = listContainer.querySelectorAll('.alerte-item-card');

      items.forEach(card => {
        const text = card.getAttribute('data-search') || '';
        if (text.includes(query)) {
          card.style.display = 'flex';
        } else {
          card.style.display = 'none';
        }
      });
    });

    // Initialisation au chargement
    updateCountAndStyle();
  })();
</script>
@endpush
