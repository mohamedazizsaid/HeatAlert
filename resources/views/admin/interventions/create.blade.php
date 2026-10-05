@extends('layouts.admin.admin')

@section('title', 'Planifier une intervention')

@section('content')
<div class="page-header">
  <div>
    <nav aria-label="breadcrumb" class="mb-2">
      <a href="{{ route('admin.coupures.show', $coupure) }}" class="text-decoration-none text-muted" style="font-size:.85rem;">
        <i class="fa-solid fa-arrow-left me-1"></i>Retour à la coupure
      </a>
    </nav>
    <h1><i class="fa-solid fa-screwdriver-wrench me-2 text-warning"></i>Planifier une intervention</h1>
    <p class="subtitle">Coupure {{ ucfirst($coupure->type) }} — {{ $coupure->zone?->nom ?? 'Zone inconnue' }}</p>
  </div>
</div>

<div class="row justify-content-center">
  <div class="col-lg-8">
    {{-- Bannière d'erreur dynamique --}}
    <div id="intervention-alert" class="alert alert-danger d-none mb-3 py-2 px-3 small rounded-3" style="border-radius:10px;">
      <i class="fa-solid fa-triangle-exclamation me-1"></i>
      <span>Veuillez corriger les erreurs ci-dessous avant d'enregistrer.</span>
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

    <form action="{{ route('admin.coupures.interventions.store', $coupure) }}" method="POST" id="intervention-form" novalidate>
      @csrf
      <input type="hidden" name="coupure_id" value="{{ $coupure->id }}">

      <section class="m-card mb-3">
        <header class="m-card__header">
          <h2 class="m-card__title">Informations de l’intervention</h2>
        </header>
        <div class="p-3 row g-3">
          <div class="col-12">
            <div class="alert alert-light border d-flex flex-wrap gap-4 mb-0">
              <div><span class="d-block small text-muted">Début de la coupure</span><strong>{{ $coupure->date_debut?->format('d/m/Y à H:i') }}</strong></div>
              <div><span class="d-block small text-muted">Fin prévue de la coupure</span><strong>{{ $coupure->date_fin?->format('d/m/Y à H:i') ?? 'Non définie' }}</strong></div>
            </div>
          </div>
          <div class="col-12">
            <label for="equipe" class="form-label fw-semibold">Équipe d’intervention <span class="text-danger">*</span></label>
            <input type="text" id="equipe" name="equipe" value="{{ old('equipe') }}" class="form-control @error('equipe') is-invalid @enderror" placeholder="Ex. Équipe réseau Tunis Centre" maxlength="150" required>
            <div class="invalid-feedback" id="equipe-error">@error('equipe'){{ $message }}@else{{ 'L’équipe d’intervention est obligatoire (au moins 2 caractères).' }}@enderror</div>
          </div>
          <div class="col-md-6">
            <label for="date_prevue" class="form-label fw-semibold">Date prévue <span class="text-danger">*</span></label>
            <input type="datetime-local" id="date_prevue" name="date_prevue" value="{{ old('date_prevue', $coupure->date_debut?->format('Y-m-d\TH:i')) }}" min="{{ $coupure->date_debut?->format('Y-m-d\TH:i') }}" class="form-control @error('date_prevue') is-invalid @enderror" step="60" required>
            <div class="invalid-feedback" id="date_prevue-error">@error('date_prevue'){{ $message }}@else{{ 'La date prévue doit être égale ou postérieure au début de la coupure.' }}@enderror</div>
            <div class="form-text">Initialisée au début de la coupure.</div>
          </div>
          <div class="col-md-6">
            <label for="duree_estimee_min" class="form-label fw-semibold">Durée estimée (minutes) <span class="text-danger">*</span></label>
            <input type="number" id="duree_estimee_min" name="duree_estimee_min" value="{{ old('duree_estimee_min', 60) }}" class="form-control @error('duree_estimee_min') is-invalid @enderror" min="1" max="10080" required>
            <div class="invalid-feedback" id="duree-error">@error('duree_estimee_min'){{ $message }}@else{{ 'La durée doit être comprise entre 1 et 10080 minutes.' }}@enderror</div>
          </div>
          <div class="col-md-6">
            <label for="statut" class="form-label fw-semibold">Statut <span class="text-danger">*</span></label>
            <select id="statut" name="statut" class="form-select @error('statut') is-invalid @enderror" required>
              <option value="planifiee" selected>Planifiée</option>
            </select>
            <div class="invalid-feedback" id="statut-error">@error('statut'){{ $message }}@else{{ 'Le statut est obligatoire.' }}@enderror</div>
          </div>
          <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label for="notes" class="form-label fw-semibold mb-0">Notes</label>
              <span class="small text-muted" id="notes-char-counter">0 / 2000</span>
            </div>
            <textarea id="notes" name="notes" rows="4" maxlength="2000" class="form-control @error('notes') is-invalid @enderror" placeholder="Précisions sur les travaux à effectuer...">{{ old('notes') }}</textarea>
            <div class="invalid-feedback" id="notes-error">@error('notes'){{ $message }}@else{{ 'Les notes ne peuvent pas dépasser 2000 caractères.' }}@enderror</div>
          </div>
        </div>
      </section>

      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.coupures.show', $coupure) }}" class="m-btn m-btn--ghost">Annuler</a>
        <button type="submit" class="m-btn m-btn--primary"><i class="fa-solid fa-calendar-check me-2"></i>Planifier</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('intervention-form');
  const alertBanner = document.getElementById('intervention-alert');
  const equipeInput = document.getElementById('equipe');
  const datePrevueInput = document.getElementById('date_prevue');
  const dureeInput = document.getElementById('duree_estimee_min');
  const notesInput = document.getElementById('notes');
  const notesCounter = document.getElementById('notes-char-counter');
  const minDate = "{{ $coupure->date_debut?->format('Y-m-d\TH:i') }}";

  function updateNotesCounter() {
    if (!notesInput || !notesCounter) return;
    const len = notesInput.value.length;
    notesCounter.textContent = `${len} / 2000`;
  }

  function setFieldError(field, errorId, msg) {
    field.classList.remove('is-valid');
    field.classList.add('is-invalid');
    const errEl = document.getElementById(errorId);
    if (errEl) { errEl.textContent = msg; errEl.style.display = 'block'; }
  }

  function setFieldSuccess(field, errorId) {
    field.classList.remove('is-invalid');
    field.classList.add('is-valid');
    const errEl = document.getElementById(errorId);
    if (errEl) { errEl.style.display = 'none'; }
  }

  function validateEquipe() {
    const val = (equipeInput.value || '').trim();
    if (!val || val.length < 2) {
      setFieldError(equipeInput, 'equipe-error', 'L’équipe d’intervention est obligatoire (au moins 2 caractères).');
      return false;
    }
    setFieldSuccess(equipeInput, 'equipe-error');
    return true;
  }

  function validateDate() {
    const val = datePrevueInput.value;
    if (!val) {
      setFieldError(datePrevueInput, 'date_prevue-error', 'La date prévue est obligatoire.');
      return false;
    }
    if (minDate && val < minDate) {
      setFieldError(datePrevueInput, 'date_prevue-error', 'La date de l’intervention doit être égale ou postérieure au début de la coupure.');
      return false;
    }
    setFieldSuccess(datePrevueInput, 'date_prevue-error');
    return true;
  }

  function validateDuree() {
    const val = parseInt(dureeInput.value, 10);
    if (isNaN(val) || val < 1 || val > 10080) {
      setFieldError(dureeInput, 'duree-error', 'La durée estimée doit être un nombre de minutes entre 1 et 10080 (7 jours).');
      return false;
    }
    setFieldSuccess(dureeInput, 'duree-error');
    return true;
  }

  if (equipeInput) equipeInput.addEventListener('input', validateEquipe);
  if (datePrevueInput) {
    datePrevueInput.addEventListener('input', validateDate);
    datePrevueInput.addEventListener('change', validateDate);
  }
  if (dureeInput) {
    dureeInput.addEventListener('input', validateDuree);
    dureeInput.addEventListener('change', validateDuree);
  }
  if (notesInput) {
    notesInput.addEventListener('input', updateNotesCounter);
    updateNotesCounter();
  }

  if (form) {
    form.addEventListener('submit', function (e) {
      const v1 = validateEquipe();
      const v2 = validateDate();
      const v3 = validateDuree();

      if (!v1 || !v2 || !v3) {
        e.preventDefault();
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
@endsection
