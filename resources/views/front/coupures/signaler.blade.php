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
        <form action="{{ route('front.coupures.signaler.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-4 shadow-sm p-4">
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
            @error('coupure_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-4">
            <label for="description" class="form-label fw-bold">Description <span class="text-danger">*</span></label>
            <textarea id="description" name="description" rows="6" minlength="10" maxlength="1000" required class="form-control @error('description') is-invalid @enderror" placeholder="Décrivez la coupure et les rues concernées...">{{ old('description') }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-4">
            <label for="photo" class="form-label fw-bold">Photo <span class="text-muted fw-normal">(facultative)</span></label>
            <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png" class="form-control @error('photo') is-invalid @enderror">
            <div class="form-text">Formats JPG, JPEG ou PNG, 2 Mo maximum.</div>
            @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
@endsection
