@extends('layouts.front.front')

@section('content')
<div class="page-title position-relative overflow-hidden"
     style="background:linear-gradient(135deg,rgba(15,23,42,.9),rgba(154,52,18,.82)),url('{{ asset('assets/front/img/health/emergency-1.webp') }}') center/cover;padding-top:135px;padding-bottom:45px;">
  <div class="container text-center">
    <span class="badge rounded-pill bg-warning text-dark px-3 py-2 mb-3"><i class="bi bi-megaphone-fill me-1"></i>Alerte citoyenne</span>
    <h1 class="heading-title text-white fw-bold">Signaler une coupure</h1>
    <p class="text-white-50 mb-0">Votre signalement sera vérifié par l'administration avant publication.</p>
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

        {{-- Info Cloudinary --}}
        <div class="alert alert-info border-0 shadow-sm rounded-3 mb-3 py-2 px-3 small d-flex align-items-center gap-2">
          <i class="bi bi-cloud-check-fill text-primary fs-5 flex-shrink-0"></i>
          <span>Les photos sont hébergées de façon sécurisée sur <strong>Cloudinary</strong>.</span>
        </div>

        <form action="{{ route('front.coupures.signaler.store') }}" method="POST"
              enctype="multipart/form-data"
              id="signaler-form"
              class="bg-white rounded-4 shadow-sm p-4"
              novalidate>
          @csrf

          {{-- Coupure --}}
          <div class="mb-4">
            <label for="coupure_id" class="form-label fw-bold">Coupure concernée</label>
            <select id="coupure_id" name="coupure_id"
                    class="form-select @error('coupure_id') is-invalid @enderror">
              <option value="">Coupure non encore répertoriée</option>
              @foreach($coupures as $coupure)
                <option value="{{ $coupure->id }}" @selected(old('coupure_id', request('coupure_id')) == $coupure->id)>
                  {{ ucfirst($coupure->type) }} — {{ $coupure->date_debut?->format('d/m/Y H:i') }}
                </option>
              @endforeach
            </select>
            <div class="invalid-feedback" id="coupure_id-error">@error('coupure_id'){{ $message }}@enderror</div>
          </div>

          {{-- Description --}}
          <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label for="description" class="form-label fw-bold mb-0">
                Description <span class="text-danger">*</span>
              </label>
              <span class="small text-muted" id="description-char-counter">0 / 1000 (min: 10)</span>
            </div>
            <textarea id="description" name="description" rows="6"
                      minlength="10" maxlength="1000" required
                      class="form-control @error('description') is-invalid @enderror"
                      placeholder="Décrivez précisément la coupure, les rues et quartiers concernés...">{{ old('description') }}</textarea>
            <div class="invalid-feedback" id="description-error">
              @error('description'){{ $message }}@else{{ 'La description doit comporter entre 10 et 1000 caractères.' }}@enderror
            </div>
          </div>

          {{-- Photo — input file classique, upload vers Cloudinary côté serveur --}}
          <div class="mb-4">
            <label for="photo" class="form-label fw-bold">
              Photo <span class="text-muted fw-normal">(facultative)</span>
            </label>

            {{-- Zone de preview --}}
            <div id="photo-preview-box" class="d-none mb-3">
              <div class="p-2 rounded-3 border bg-light d-flex align-items-center gap-3">
                <img id="photo-preview-img" src="#" alt="Aperçu"
                     class="rounded-2 shadow-sm"
                     style="width:80px;height:60px;object-fit:cover;">
                <div class="flex-grow-1">
                  <div id="photo-preview-name" class="fw-semibold small text-dark"></div>
                  <div id="photo-preview-size" class="text-muted" style="font-size:.78rem;"></div>
                </div>
                <button type="button" id="photo-preview-clear"
                        class="btn btn-sm btn-outline-danger"
                        style="border-radius:8px;" title="Retirer la photo">
                  <i class="bi bi-x-lg"></i>
                </button>
              </div>
            </div>

            {{-- Input file stylisé --}}
            <div id="photo-drop-area"
                 class="border-2 border-dashed rounded-3 text-center p-4 @error('photo') border-danger @enderror"
                 style="border-style:dashed;border-color:#cbd5e1;cursor:pointer;transition:.2s;"
                 onclick="document.getElementById('photo').click()">
              <i class="bi bi-cloud-upload fs-3 d-block mb-2" style="color:#0ea5e9;"></i>
              <div class="fw-semibold text-dark" style="font-size:.9rem;">Cliquez pour sélectionner une photo</div>
              <div class="text-muted mt-1" style="font-size:.78rem;">
                JPG, JPEG, PNG ou WebP — 10 Mo max<br>
                <span class="badge rounded-pill mt-1" style="background:#e0f2fe;color:#0369a1;font-size:.72rem;">
                  <i class="bi bi-cloud-check me-1"></i>Stocké sur Cloudinary
                </span>
              </div>
            </div>
            <input type="file" id="photo" name="photo"
                   accept=".jpg,.jpeg,.png,.webp"
                   class="d-none @error('photo') is-invalid @enderror">
            <div class="invalid-feedback d-block" id="photo-error">@error('photo'){{ $message }}@enderror</div>
          </div>

          {{-- Actions --}}
          <div class="d-flex gap-2 justify-content-end mt-2">
            <a href="{{ route('front.coupures.index') }}" class="btn btn-outline-secondary rounded-3">Annuler</a>
            <button type="submit" class="btn btn-danger rounded-3" id="signaler-submit-btn">
              <i class="bi bi-send me-2"></i>Envoyer le signalement
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form         = document.getElementById('signaler-form');
  const alertBanner  = document.getElementById('signaler-form-alert');
  const descEl       = document.getElementById('description');
  const descCounter  = document.getElementById('description-char-counter');
  const photoInput   = document.getElementById('photo');
  const dropArea     = document.getElementById('photo-drop-area');
  const previewBox   = document.getElementById('photo-preview-box');
  const previewImg   = document.getElementById('photo-preview-img');
  const previewName  = document.getElementById('photo-preview-name');
  const previewSize  = document.getElementById('photo-preview-size');
  const clearBtn     = document.getElementById('photo-preview-clear');
  const photoErrEl   = document.getElementById('photo-error');

  // ─── Preview photo ─────────────────────────────────────────────────────────
  function showPreview(file) {
    const reader = new FileReader();
    reader.onload = e => {
      previewImg.src = e.target.result;
      previewName.textContent = file.name;
      previewSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' Mo';
      previewBox.classList.remove('d-none');
      dropArea.classList.add('d-none');
    };
    reader.readAsDataURL(file);
  }

  function clearPhoto() {
    photoInput.value = '';
    previewImg.src = '#';
    previewBox.classList.add('d-none');
    dropArea.classList.remove('d-none');
    photoInput.classList.remove('is-invalid');
    if (photoErrEl) photoErrEl.textContent = '';
  }

  photoInput.addEventListener('change', function () {
    if (this.files && this.files[0]) {
      validatePhoto(this.files[0]);
      if (!this.classList.contains('is-invalid')) {
        showPreview(this.files[0]);
      }
    }
  });

  clearBtn?.addEventListener('click', clearPhoto);

  // Drag & drop
  dropArea.addEventListener('dragover', e => { e.preventDefault(); dropArea.style.borderColor = '#0ea5e9'; dropArea.style.background = '#f0f9ff'; });
  dropArea.addEventListener('dragleave', e => { dropArea.style.borderColor = '#cbd5e1'; dropArea.style.background = ''; });
  dropArea.addEventListener('drop', e => {
    e.preventDefault();
    dropArea.style.borderColor = '#cbd5e1';
    dropArea.style.background = '';
    const dt = e.dataTransfer;
    if (dt.files && dt.files[0]) {
      const file = dt.files[0];
      // Injecter le fichier dans l'input
      const dT = new DataTransfer();
      dT.items.add(file);
      photoInput.files = dT.files;
      validatePhoto(file);
      if (!photoInput.classList.contains('is-invalid')) showPreview(file);
    }
  });

  // ─── Validation photo ──────────────────────────────────────────────────────
  function validatePhoto(file) {
    if (!file) {
      photoInput.classList.remove('is-invalid');
      if (photoErrEl) photoErrEl.textContent = '';
      return true;
    }
    const allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    if (!allowed.includes(file.type)) {
      photoInput.classList.add('is-invalid');
      if (photoErrEl) photoErrEl.textContent = 'Format non supporté (JPG, JPEG, PNG ou WebP uniquement).';
      return false;
    }
    if (file.size > 10 * 1024 * 1024) {
      photoInput.classList.add('is-invalid');
      if (photoErrEl) photoErrEl.textContent = 'Le fichier dépasse la taille maximale de 10 Mo.';
      return false;
    }
    photoInput.classList.remove('is-invalid');
    if (photoErrEl) photoErrEl.textContent = '';
    return true;
  }

  // ─── Compteur description ──────────────────────────────────────────────────
  function updateDescCounter() {
    if (!descEl || !descCounter) return;
    const len = descEl.value.length;
    descCounter.textContent = `${len} / 1000${len < 10 ? ' (min: 10)' : ''}`;
    descCounter.style.color = (len >= 10 && len <= 1000) ? '#16a34a' : (len > 0 ? '#ea580c' : '#94a3b8');
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
    if (errEl) errEl.style.display = 'none';
  }

  function validateDesc() {
    const val = (descEl.value || '').trim();
    if (!val) { setFieldError(descEl, 'description-error', 'La description est obligatoire.'); return false; }
    if (val.length < 10) { setFieldError(descEl, 'description-error', 'La description doit comporter au moins 10 caractères.'); return false; }
    if (val.length > 1000) { setFieldError(descEl, 'description-error', 'La description ne peut pas dépasser 1000 caractères.'); return false; }
    setFieldSuccess(descEl, 'description-error');
    return true;
  }

  if (descEl) {
    descEl.addEventListener('input', () => { updateDescCounter(); validateDesc(); });
    descEl.addEventListener('blur', validateDesc);
    updateDescCounter();
  }

  // ─── Soumission ───────────────────────────────────────────────────────────
  if (form) {
    form.addEventListener('submit', function (e) {
      const vDesc  = validateDesc();
      const vPhoto = photoInput.files && photoInput.files[0] ? validatePhoto(photoInput.files[0]) : true;
      if (!vDesc || !vPhoto) {
        e.preventDefault();
        e.stopPropagation();
        alertBanner?.classList.remove('d-none');
        alertBanner?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        form.querySelector('.is-invalid')?.focus();
      } else {
        alertBanner?.classList.add('d-none');
        // Feedback visuel pendant l'upload
        const btn = document.getElementById('signaler-submit-btn');
        if (btn) {
          btn.disabled = true;
          btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Envoi en cours…';
        }
      }
    });
  }
});
</script>
@endpush
@endsection