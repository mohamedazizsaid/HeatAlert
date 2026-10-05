@extends('layouts.front.front')

@section('content')
<div class="page-title position-relative overflow-hidden" style="background:linear-gradient(135deg,rgba(15,23,42,.9),rgba(154,52,18,.82)),url('{{ asset('assets/front/img/health/emergency-1.webp') }}') center/cover;padding-top:135px;padding-bottom:45px;">
  <div class="container text-center">
    <span class="badge rounded-pill bg-warning text-dark px-3 py-2 mb-3"><i class="bi bi-megaphone-fill me-1"></i>Alerte citoyenne</span>
    <h1 class="heading-title text-white fw-bold">Signaler une coupure</h1>
    <p class="text-white-50 mb-0">Votre signalement sera vérifié par l’administration avant publication.</p>
  </div>
</div>

<section class="py-5" style="background:#f8fafc;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        {{-- Bannière d'erreur dynamique --}}
        <div id="signaler-form-alert" class="alert alert-danger d-none mb-3 py-2 px-3 small rounded-3">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <span>Veuillez corriger les erreurs ci-dessous avant d'envoyer votre signalement.</span>
        </div>

        @if ($errors->any())
          <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-circle-fill me-1"></i>Des erreurs sont survenues :</div>
            <ul class="mb-0 ps-3">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('front.coupures.signaler.store') }}" method="POST" enctype="multipart/form-data" id="signaler-form" class="bg-white rounded-4 shadow-sm p-4" novalidate>
          @csrf
          <div class="mb-4">
            <label for="coupure_id" class="form-label fw-bold">Coupure concernée</label>
            <select id="coupure_id" name="coupure_id" class="form-select @error('coupure_id') is-invalid @enderror">
              <option value="">Coupure non encore répertoriée</option>
              @foreach($coupures as $coupure)
                <option value="{{ $coupure->id }}" @selected(old('coupure_id', request('coupure_id')) == $coupure->id)>
                  {{ ucfirst($coupure->type) }} - {{ $coupure->date_debut?->format('d/m/Y H:i') }}
                </option>
              @endforeach
            </select>
            <div class="invalid-feedback" id="coupure_id-error">@error('coupure_id'){{ $message }}@enderror</div>
          </div>

          <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label for="description" class="form-label fw-bold mb-0">Description <span class="text-danger">*</span></label>
              <span class="small text-muted" id="description-char-counter">0 caractères (min: 10)</span>
            </div>
            <textarea id="description" name="description" rows="6" minlength="10" maxlength="1000" required
                      class="form-control @error('description') is-invalid @enderror"
                      placeholder="Décrivez précisément la coupure, les rues et quartiers concernés...">{{ old('description') }}</textarea>
            <div class="invalid-feedback" id="description-error">@error('description'){{ $message }}@else{{ 'La description doit comporter entre 10 et 1000 caractères.' }}@enderror</div>
          </div>

          <div class="mb-4">
            <label for="photo" class="form-label fw-bold">Photo <span class="text-muted fw-normal">(facultative)</span></label>
            <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png" class="form-control @error('photo') is-invalid @enderror">
            <div class="form-text">Formats JPG, JPEG ou PNG, 2 Mo maximum.</div>
            <div class="invalid-feedback" id="photo-error">@error('photo'){{ $message }}@else{{ 'La photo ne peut pas dépasser 2 Mo.' }}@enderror</div>
          </div>

          <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('front.coupures.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button type="submit" class="btn btn-danger"><i class="bi bi-send me-2"></i>Envoyer le signalement</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('signaler-form');
  const alertBanner = document.getElementById('signaler-form-alert');
  const descTextarea = document.getElementById('description');
  const descCounter = document.getElementById('description-char-counter');
  const photoInput = document.getElementById('photo');

  function updateDescCounter() {
    if (!descTextarea || !descCounter) return;
    const len = descTextarea.value.length;
    descCounter.textContent = `${len} caractères (min: 10, max: 1000)`;
    descCounter.style.color = len >= 10 && len <= 1000 ? '#16a34a' : (len > 0 ? '#ea580c' : '#dc2626');
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

  function validateDesc() {
    const val = (descTextarea.value || '').trim();
    if (!val) {
      setFieldError(descTextarea, 'description-error', 'La description du signalement est obligatoire.');
      return false;
    }
    if (val.length < 10) {
      setFieldError(descTextarea, 'description-error', 'La description doit comporter au moins 10 caractères.');
      return false;
    }
    if (val.length > 1000) {
      setFieldError(descTextarea, 'description-error', 'La description ne peut pas dépasser 1000 caractères.');
      return false;
    }
    setFieldSuccess(descTextarea, 'description-error');
    return true;
  }

  function validatePhoto() {
    if (!photoInput || !photoInput.files || photoInput.files.length === 0) {
      photoInput.classList.remove('is-invalid');
      const err = document.getElementById('photo-error');
      if (err) err.style.display = 'none';
      return true;
    }
    const file = photoInput.files[0];
    const maxSize = 2 * 1024 * 1024; // 2 Mo
    if (file.size > maxSize) {
      setFieldError(photoInput, 'photo-error', 'Le fichier sélectionné dépasse la taille maximale autorisée de 2 Mo.');
      return false;
    }
    setFieldSuccess(photoInput, 'photo-error');
    return true;
  }

  if (descTextarea) {
    descTextarea.addEventListener('input', function() {
      updateDescCounter();
      validateDesc();
    });
    descTextarea.addEventListener('blur', validateDesc);
    updateDescCounter();
  }

  if (photoInput) {
    photoInput.addEventListener('change', validatePhoto);
  }

  if (form) {
    form.addEventListener('submit', function (e) {
      const vDesc = validateDesc();
      const vPhoto = validatePhoto();

      if (!vDesc || !vPhoto) {
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
@endsection
