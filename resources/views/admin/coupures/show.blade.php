@extends('layouts.admin.admin')

@section('title', 'Détails de la coupure')

@section('content')
<div class="page-header">
  <div>
    <nav aria-label="breadcrumb" class="mb-2">
      <a href="{{ route('admin.coupures.index') }}" class="text-decoration-none text-muted" style="font-size:.85rem;">
        <i class="fa-solid fa-arrow-left me-1"></i>Retour à la liste des coupures
      </a>
    </nav>
    <h1><i class="fa-solid fa-bolt me-2 text-warning"></i>Détails de la Coupure</h1>
    <p class="subtitle">{{ $coupure->zone?->nom ?? 'Zone inconnue' }} — {{ $coupure->date_debut?->format('d/m/Y H:i') }}</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('admin.coupures.edit', $coupure) }}" class="m-btn m-btn--primary">
      <i class="fa-solid fa-pen-to-square"></i> Modifier
    </a>
    @if(in_array($coupure->type, ['panne', 'surcharge'], true))
      <a href="{{ route('admin.coupures.interventions.create', $coupure) }}" class="m-btn m-btn--ghost" style="border:1px solid #d97706;color:#b45309;">
        <i class="fa-solid fa-screwdriver-wrench"></i> Planifier une intervention
      </a>
    @else
      <span class="m-btn m-btn--ghost text-muted" title="Le délestage est déjà une coupure planifiée.">
        <i class="fa-solid fa-calendar-check"></i> Coupure planifiée
      </span>
    @endif
    <button type="button" class="m-btn m-btn--ghost js-trigger-delete"
            style="border:1px solid #dc2626;color:#dc2626;"
            data-action="{{ route('admin.coupures.destroy', $coupure) }}"
            data-name="Coupure {{ ucfirst($coupure->type) }} - {{ $coupure->zone?->nom ?? 'Zone inconnue' }}"
            data-type="la coupure"
            title="Supprimer la coupure">
      <i class="fa-solid fa-trash"></i> Supprimer
    </button>
  </div>
</div>

<section class="m-card mb-4">
  <header class="m-card__header d-flex justify-content-between align-items-center">
    <div>
      <h2 class="m-card__title"><i class="fa-solid fa-screwdriver-wrench me-2 text-warning"></i>Interventions</h2>
      <p class="m-card__subtitle mb-0">Planification des travaux de rétablissement.</p>
    </div>
    <span class="badge bg-warning text-dark">{{ $coupure->interventions->count() }}</span>
  </header>
  <div class="p-3">
    @if($coupure->interventions->isEmpty())
      <p class="text-muted mb-0">Aucune intervention planifiée.</p>
    @else
      <div class="table-responsive">
        <table class="m-table">
          <thead>
            <tr><th>Équipe</th><th>Date prévue</th><th>Durée</th><th>Rétablissement estimé</th><th>Statut</th></tr>
          </thead>
          <tbody>
            @foreach($coupure->interventions->sortByDesc('date_prevue') as $intervention)
              @php
                $interventionClass = match($intervention->statut) {
                  'en_cours' => 'bg-danger',
                  'terminee' => 'bg-success',
                  default => 'bg-warning text-dark',
                };
              @endphp
              <tr>
                <td><strong>{{ $intervention->equipe }}</strong>@if($intervention->notes)<small class="d-block text-muted">{{ Str::limit($intervention->notes, 70) }}</small>@endif</td>
                <td>{{ $intervention->date_prevue?->format('d/m/Y H:i') }}</td>
                <td>{{ $intervention->duree_estimee_min }} min</td>
                <td>{{ $intervention->date_prevue?->copy()->addMinutes($intervention->duree_estimee_min)->format('d/m/Y H:i') }}</td>
                <td><span class="badge {{ $interventionClass }}">{{ ucfirst(str_replace('_', ' ', $intervention->statut)) }}</span></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</section>

<div class="row g-4">
  <div class="col-lg-5">
    <section class="m-card">
      <header class="m-card__header">
        <h2 class="m-card__title">Informations de la coupure</h2>
      </header>
      <div class="p-3">
        @php
          $statutClass = match($coupure->statut) {
            'prevue' => 'bg-warning text-dark',
            'en_cours' => 'bg-danger',
            'terminee' => 'bg-success',
            default => 'bg-secondary',
          };
        @endphp
        <dl class="row mb-0">
          <dt class="col-sm-5 text-muted">Type</dt>
          <dd class="col-sm-7">{{ ucfirst($coupure->type) }}</dd>

          <dt class="col-sm-5 text-muted">Statut</dt>
          <dd class="col-sm-7"><span class="badge {{ $statutClass }}">{{ ucfirst(str_replace('_', ' ', $coupure->statut)) }}</span></dd>

          <dt class="col-sm-5 text-muted">Zone</dt>
          <dd class="col-sm-7">{{ $coupure->zone?->nom ?? '—' }}<small class="d-block text-muted">{{ $coupure->zone?->ville ?? '' }}</small></dd>

          <dt class="col-sm-5 text-muted">Début</dt>
          <dd class="col-sm-7">{{ $coupure->date_debut?->format('d/m/Y H:i') }}</dd>

          <dt class="col-sm-5 text-muted">Fin</dt>
          <dd class="col-sm-7">{{ $coupure->date_fin?->format('d/m/Y H:i') ?? 'Non définie' }}</dd>

          <dt class="col-sm-5 text-muted">Cause</dt>
          <dd class="col-sm-7">{{ $coupure->cause ?? 'Non renseignée' }}</dd>
        </dl>
      </div>
    </section>
  </div>

  <div class="col-lg-7">
    <section class="m-card">
      <header class="m-card__header d-flex justify-content-between align-items-center">
        <h2 class="m-card__title">Signalements</h2>
        <span class="badge bg-info text-dark">{{ $coupure->signalements->count() }}</span>
      </header>
      <div class="p-3">
        @forelse($coupure->signalements as $signalement)
          <article class="border-bottom pb-3 mb-3">
            <div class="d-flex justify-content-between align-items-start gap-3">
              <div>
                <strong>{{ $signalement->user?->prenom }} {{ $signalement->user?->nom }}</strong>
                <small class="d-block text-muted">{{ $signalement->date_signalement?->format('d/m/Y H:i') }}</small>
              </div>
              @php
                $validationClass = match($signalement->statut_validation) {
                  'valide' => 'bg-success',
                  'rejete' => 'bg-danger',
                  default => 'bg-warning text-dark',
                };
              @endphp
              <span class="badge {{ $validationClass }}">{{ ucfirst(str_replace('_', ' ', $signalement->statut_validation)) }}</span>
            </div>
            <p class="mb-0 mt-2">{{ $signalement->description }}</p>
            @if($signalement->photo)
              <small class="text-muted"><i class="fa-solid fa-image me-1"></i>{{ $signalement->photo }}</small>
            @endif
          </article>
        @empty
          <div class="text-center py-4 text-muted">
            <i class="fa-solid fa-comment-slash fa-2x mb-2 d-block"></i>
            Aucun signalement pour cette coupure.
          </div>
        @endforelse
      </div>
    </section>
  </div>
</div>
@endsection
