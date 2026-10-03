{{-- Formulaire partagé des coupures électriques --}}
<div class="row align-items-stretch">
  <div class="col-lg-8">
    <section class="m-card mb-3">
      <header class="m-card__header">
        <h2 class="m-card__title">Informations de la coupure</h2>
      </header>
      <div class="row g-3 p-3">
        <div class="col-md-6">
          <label for="type" class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
          <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
            <option value="">Sélectionner un type</option>
            @foreach($types as $type)
              <option value="{{ $type }}" {{ old('type', $coupure->type ?? '') === $type ? 'selected' : '' }}>
                {{ ucfirst($type) }}
              </option>
            @endforeach
          </select>
          @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
          <label for="statut" class="form-label fw-semibold">Statut <span class="text-danger">*</span></label>
          <select id="statut" name="statut" class="form-select @error('statut') is-invalid @enderror" required>
            @foreach($statuts as $statut)
              <option value="{{ $statut }}" {{ old('statut', $coupure->statut ?? 'prevue') === $statut ? 'selected' : '' }}>
                {{ ucfirst(str_replace('_', ' ', $statut)) }}
              </option>
            @endforeach
          </select>
          @error('statut')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
          <label for="zone_id" class="form-label fw-semibold">Zone géographique <span class="text-danger">*</span></label>
          <select id="zone_id" name="zone_id" class="form-select @error('zone_id') is-invalid @enderror" required>
            <option value="">Sélectionner une zone</option>
            @foreach($zones as $zone)
              <option value="{{ $zone->id }}" {{ old('zone_id', $coupure->zone_id ?? '') == $zone->id ? 'selected' : '' }}>
                {{ $zone->nom }} — {{ $zone->ville }} ({{ $zone->gouvernorat }})
              </option>
            @endforeach
          </select>
          @error('zone_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
          <label for="date_debut" class="form-label fw-semibold">Date de début <span class="text-danger">*</span></label>
          <input type="datetime-local" id="date_debut" name="date_debut"
                 class="form-control @error('date_debut') is-invalid @enderror"
             value="{{ old('date_debut', isset($coupure->date_debut) ? $coupure->date_debut->format('Y-m-d\TH:i') : '') }}"
             step="60" required>
           <small class="form-text text-muted">Choisissez la date, l’heure et les minutes exactes.</small>
          @error('date_debut')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
          <label for="date_fin" class="form-label fw-semibold">Date de fin</label>
          <input type="datetime-local" id="date_fin" name="date_fin"
                 class="form-control @error('date_fin') is-invalid @enderror"
             value="{{ old('date_fin', isset($coupure->date_fin) ? $coupure->date_fin->format('Y-m-d\TH:i') : '') }}"
             step="60" disabled>
           <small class="form-text text-muted">Choisissez d’abord le début. La fin doit être ultérieure, même le même jour.</small>
          @error('date_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
          <label for="cause" class="form-label fw-semibold">Cause <span class="text-danger">*</span></label>
          <textarea id="cause" name="cause" rows="4" maxlength="500"
                    class="form-control @error('cause') is-invalid @enderror"
                    placeholder="Décrire la cause de la coupure..." required>{{ old('cause', $coupure->cause ?? '') }}</textarea>
          @error('cause')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>
    </section>
  </div>

  <div class="col-lg-4 d-flex flex-column">
    <section class="m-card mb-3">
      <header class="m-card__header">
        <h2 class="m-card__title">Actions</h2>
      </header>
      <div class="p-3 d-grid gap-2">
        <button type="submit" class="m-btn m-btn--primary">
          <i class="fa-solid fa-floppy-disk me-2"></i>Enregistrer
        </button>
        <a href="{{ route('admin.coupures.index') }}" class="m-btn m-btn--ghost text-center">
          <i class="fa-solid fa-xmark me-2"></i>Annuler
        </a>
      </div>
    </section>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const debut = document.getElementById('date_debut');
    const fin = document.getElementById('date_fin');

    if (!debut || !fin) return;

    const synchroniserDates = function () {
      fin.disabled = !debut.value;
      fin.min = debut.value || '';

      if (fin.value && debut.value && fin.value <= debut.value) {
        fin.value = '';
      }

      fin.setCustomValidity('');
    };

    debut.addEventListener('input', synchroniserDates);
    debut.addEventListener('change', synchroniserDates);
    fin.addEventListener('input', synchroniserDates);
    fin.addEventListener('change', synchroniserDates);
    synchroniserDates();
  });
</script>
@endpush
