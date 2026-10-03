@extends('layouts.admin.admin')

@section('title', 'Modifier une intervention')

@section('content')
<div class="page-header">
  <div>
    <nav aria-label="breadcrumb" class="mb-2">
      <a href="{{ route('admin.coupures.show', $coupure) }}" class="text-decoration-none text-muted" style="font-size:.85rem;">
        <i class="fa-solid fa-arrow-left me-1"></i>Retour à la coupure
      </a>
    </nav>
    <h1><i class="fa-solid fa-pen-to-square me-2 text-warning"></i>Modifier l’intervention</h1>
    <p class="subtitle">Coupure {{ ucfirst($coupure->type) }} — {{ $coupure->zone?->nom ?? 'Zone inconnue' }}</p>
  </div>
</div>

<div class="row justify-content-center">
  <div class="col-lg-8">
    <form action="{{ route('admin.coupures.interventions.update', [$coupure, $intervention]) }}" method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" name="coupure_id" value="{{ $coupure->id }}">

      <section class="m-card mb-3">
        <header class="m-card__header"><h2 class="m-card__title">Informations de l’intervention</h2></header>
        <div class="p-3 row g-3">
          <div class="col-12">
            <div class="alert alert-light border d-flex flex-wrap gap-4 mb-0">
              <div><span class="d-block small text-muted">Début de la coupure</span><strong>{{ $coupure->date_debut?->format('d/m/Y à H:i') }}</strong></div>
              <div><span class="d-block small text-muted">Fin prévue de la coupure</span><strong>{{ $coupure->date_fin?->format('d/m/Y à H:i') ?? 'Non définie' }}</strong></div>
            </div>
          </div>
          <div class="col-12">
            <label for="equipe" class="form-label fw-semibold">Équipe d’intervention <span class="text-danger">*</span></label>
            <input type="text" id="equipe" name="equipe" value="{{ old('equipe', $intervention->equipe) }}" class="form-control @error('equipe') is-invalid @enderror" required>
            @error('equipe')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6">
            <label for="date_prevue" class="form-label fw-semibold">Date prévue <span class="text-danger">*</span></label>
            <input type="datetime-local" id="date_prevue" name="date_prevue" value="{{ old('date_prevue', $intervention->date_prevue?->format('Y-m-d\TH:i')) }}" min="{{ $coupure->date_debut?->format('Y-m-d\TH:i') }}" class="form-control @error('date_prevue') is-invalid @enderror" step="60" required>
            <div class="form-text">La date ne peut pas être antérieure au début de la coupure.</div>
            @error('date_prevue')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6">
            <label for="duree_estimee_min" class="form-label fw-semibold">Durée estimée (minutes) <span class="text-danger">*</span></label>
            <input type="number" id="duree_estimee_min" name="duree_estimee_min" value="{{ old('duree_estimee_min', $intervention->duree_estimee_min) }}" class="form-control @error('duree_estimee_min') is-invalid @enderror" min="1" max="10080" required>
            @error('duree_estimee_min')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6">
            <label for="statut" class="form-label fw-semibold">Statut <span class="text-danger">*</span></label>
            <select id="statut" name="statut" class="form-select @error('statut') is-invalid @enderror" required>
              <option value="planifiee" selected>Planifiée</option>
            </select>
            <div class="form-text">Le statut est actualisé automatiquement selon la date prévue et la durée.</div>
            @error('statut')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-12">
            <label for="notes" class="form-label fw-semibold">Notes</label>
            <textarea id="notes" name="notes" rows="4" maxlength="2000" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $intervention->notes) }}</textarea>
            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
      </section>

      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.coupures.show', $coupure) }}" class="m-btn m-btn--ghost">Annuler</a>
        <button type="submit" class="m-btn m-btn--primary"><i class="fa-solid fa-floppy-disk me-2"></i>Enregistrer</button>
      </div>
    </form>
  </div>
</div>
@endsection
