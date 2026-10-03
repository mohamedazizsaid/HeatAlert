@extends('layouts.admin.admin')

@section('title', 'Détails du signalement')

@section('content')
<div class="page-header">
  <div>
    <nav aria-label="breadcrumb" class="mb-2">
      <a href="{{ route('admin.signalements.index') }}" class="text-decoration-none text-muted" style="font-size:.85rem;">
        <i class="fa-solid fa-arrow-left me-1"></i>Retour aux signalements
      </a>
    </nav>
    <h1><i class="fa-solid fa-flag me-2 text-danger"></i>Détails du signalement</h1>
    <p class="subtitle">Signalé le {{ $signalement->date_signalement?->format('d/m/Y à H:i') }}</p>
  </div>
  <div class="page-header__actions">
    @if($signalement->statut_validation !== 'valide')
      <form action="{{ route('admin.signalements.valider', $signalement) }}" method="POST" class="d-inline">
        @csrf
        @method('PATCH')
        <button type="submit" class="m-btn m-btn--primary">
          <i class="fa-solid fa-check"></i> Valider
        </button>
      </form>
    @endif
    @if($signalement->statut_validation !== 'rejete')
      <form action="{{ route('admin.signalements.rejeter', $signalement) }}" method="POST" class="d-inline">
        @csrf
        @method('PATCH')
        <button type="submit" class="m-btn m-btn--ghost" style="border:1px solid #dc2626;color:#dc2626;">
          <i class="fa-solid fa-xmark"></i> Rejeter
        </button>
      </form>
    @endif
    <button type="button" class="m-btn m-btn--ghost js-trigger-delete"
            style="border:1px solid #dc2626;color:#dc2626;"
            data-action="{{ route('admin.signalements.destroy', $signalement) }}"
            data-name="Signalement de {{ $signalement->user?->prenom }} {{ $signalement->user?->nom }}"
            data-type="le signalement"
            title="Supprimer le signalement">
      <i class="fa-solid fa-trash"></i> Supprimer
    </button>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-7">
    <section class="m-card">
      <header class="m-card__header d-flex justify-content-between align-items-center">
        <h2 class="m-card__title">Signalement</h2>
        @php
          $statutClass = match($signalement->statut_validation) {
            'valide' => 'bg-success',
            'rejete' => 'bg-danger',
            default => 'bg-warning text-dark',
          };
        @endphp
        <span class="badge {{ $statutClass }}">{{ ucfirst(str_replace('_', ' ', $signalement->statut_validation)) }}</span>
      </header>
      <div class="p-3">
        <dl class="row mb-0">
          <dt class="col-sm-4 text-muted">Auteur</dt>
          <dd class="col-sm-8">
            {{ $signalement->user?->prenom }} {{ $signalement->user?->nom }}
            <small class="d-block text-muted">{{ $signalement->user?->email }}</small>
          </dd>
          <dt class="col-sm-4 text-muted">Date</dt>
          <dd class="col-sm-8">{{ $signalement->date_signalement?->format('d/m/Y H:i') }}</dd>
          <dt class="col-sm-4 text-muted">Description</dt>
          <dd class="col-sm-8" style="white-space:pre-line;">{{ $signalement->description }}</dd>
        </dl>

        @if($signalement->photo)
          <div class="mt-4">
            <h3 class="h6 fw-bold">Photo jointe</h3>
            <img src="{{ asset('storage/' . ltrim($signalement->photo, '/')) }}"
                 alt="Photo du signalement"
                 class="img-fluid rounded-3 border"
                 style="max-height:320px;object-fit:contain;">
          </div>
        @endif
      </div>
    </section>
  </div>

  <div class="col-lg-5">
    <section class="m-card">
      <header class="m-card__header">
        <h2 class="m-card__title">Coupure liée</h2>
      </header>
      <div class="p-3">
        @if($signalement->coupure)
          <dl class="row mb-3">
            <dt class="col-sm-5 text-muted">Type</dt>
            <dd class="col-sm-7">{{ ucfirst($signalement->coupure->type) }}</dd>
            <dt class="col-sm-5 text-muted">Zone</dt>
            <dd class="col-sm-7">{{ $signalement->coupure->zone?->nom ?? '—' }}</dd>
            <dt class="col-sm-5 text-muted">Début</dt>
            <dd class="col-sm-7">{{ $signalement->coupure->date_debut?->format('d/m/Y H:i') }}</dd>
            <dt class="col-sm-5 text-muted">Statut</dt>
            <dd class="col-sm-7">{{ ucfirst(str_replace('_', ' ', $signalement->coupure->statut)) }}</dd>
          </dl>
          <a href="{{ route('admin.coupures.show', $signalement->coupure) }}" class="m-btn m-btn--ghost w-100 text-center">
            <i class="fa-solid fa-bolt me-1"></i>Voir la coupure
          </a>
        @else
          <div class="text-center py-3 text-muted">
            <i class="fa-solid fa-link-slash fa-2x mb-2 d-block"></i>
            Aucun lien avec une coupure officielle.
          </div>
        @endif
      </div>
    </section>
  </div>
</div>
@endsection
