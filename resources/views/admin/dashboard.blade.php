@extends('layouts.admin.admin')

@section('title', 'Dashboard')

@section('content')
  <div class="page-header">
    <div>
      <h1><i class="fa-solid fa-gauge-high me-2"></i>Dashboard</h1>
      <p class="subtitle">Bienvenue — aperçu de l'état des alertes météo en Tunisie.</p>
    </div>
    <div class="page-header__actions">
      <a href="{{ route('admin.alertes.create') }}" class="m-btn m-btn--primary">
        <i class="fa-solid fa-plus" aria-hidden="true"></i> Nouvelle alerte
      </a>
    </div>
  </div>

  {{-- KPI Strip --}}
  <div class="row row-tight">
    <div class="col-sm-6 col-lg-3">
      <article class="stat-card">
        <div class="stat-card__head">
          <p class="stat-card__label">Zones actives</p>
          <span class="stat-card__icon stat-card__icon--c1"><i class="fa-solid fa-map-location-dot"></i></span>
        </div>
        <p class="stat-card__value">{{ $stats['zones_actives'] }}</p>
        <p class="stat-card__delta stat-card__delta--up">
          <i class="fa-solid fa-map-marker-alt"></i> {{ $stats['zones'] }} zones au total
        </p>
      </article>
    </div>
    <div class="col-sm-6 col-lg-3">
      <article class="stat-card">
        <div class="stat-card__head">
          <p class="stat-card__label">Alertes actives</p>
          <span class="stat-card__icon stat-card__icon--c2"><i class="fa-solid fa-triangle-exclamation"></i></span>
        </div>
        <p class="stat-card__value">{{ $stats['alertes_active'] }}</p>
        <p class="stat-card__delta stat-card__delta--up">
          <i class="fa-solid fa-chart-bar"></i> {{ $stats['alertes'] }} alertes total
        </p>
      </article>
    </div>
    <div class="col-sm-6 col-lg-3">
      <article class="stat-card">
        <div class="stat-card__head">
          <p class="stat-card__label">Alertes rouges</p>
          <span class="stat-card__icon stat-card__icon--c4"><i class="fa-solid fa-radiation"></i></span>
        </div>
        <p class="stat-card__value">{{ $stats['alertes_rouge'] }}</p>
        <p class="stat-card__delta {{ $stats['alertes_rouge'] > 0 ? 'stat-card__delta--down' : 'stat-card__delta--up' }}">
          <i class="fa-solid fa-{{ $stats['alertes_rouge'] > 0 ? 'exclamation-triangle' : 'check' }}"></i>
          {{ $stats['alertes_rouge'] > 0 ? 'Niveau critique actif' : 'Aucun niveau critique' }}
        </p>
      </article>
    </div>
    <div class="col-sm-6 col-lg-3">
      <article class="stat-card">
        <div class="stat-card__head">
          <p class="stat-card__label">Conseils actifs</p>
          <span class="stat-card__icon stat-card__icon--c3"><i class="fa-solid fa-lightbulb"></i></span>
        </div>
        <p class="stat-card__value">{{ $stats['conseils_actifs'] }}</p>
        <p class="stat-card__delta stat-card__delta--up">
          <i class="fa-solid fa-list"></i> {{ $stats['conseils'] }} conseils total
        </p>
      </article>
    </div>
  </div>

  {{-- Dernières alertes actives --}}
  <div class="row row-tight" style="margin-top:16px;">
    <div class="col-lg-12">
      <section class="m-card" aria-labelledby="recent-alerts-title">
        <header class="m-card__header">
          <div>
            <h2 class="m-card__title" id="recent-alerts-title">
              <i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i>Alertes actives récentes
            </h2>
            <p class="m-card__subtitle">5 dernières alertes avec statut « active ».</p>
          </div>
          <a href="{{ route('admin.alertes.index') }}" class="m-btn m-btn--ghost"
            style="height:30px;padding:0 10px;font-size:12.5px;">
            Voir tout
          </a>
        </header>

        @if($dernieresAlertes->isEmpty())
          <div class="text-center py-4 text-muted">
            <i class="fa-solid fa-check-circle fa-2x mb-2 text-success"></i>
            <p>Aucune alerte active pour le moment.</p>
          </div>
        @else
          <table class="m-table">
            <thead>
              <tr>
                <th>Titre</th>
                <th>Zone</th>
                <th>Type</th>
                <th>Niveau</th>
                <th>Début</th>
                <th style="text-align:center">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($dernieresAlertes as $alerte)
                <tr>
                  <td><strong>{{ $alerte->titre }}</strong></td>
                  <td>{{ $alerte->zone?->ville ?? '—' }}</td>
                  <td>{{ ucfirst(str_replace('_', ' ', $alerte->type)) }}</td>
                  <td>
                    @php
                      $badgeClass = match ($alerte->niveau) {
                        'rouge' => 'badge bg-danger',
                        'orange' => 'badge bg-warning text-dark',
                        'jaune' => 'badge bg-info text-dark',
                        default => 'badge bg-success',
                      };
                    @endphp
                    <span class="{{ $badgeClass }}">{{ ucfirst($alerte->niveau) }}</span>
                  </td>
                  <td>{{ $alerte->date_debut?->format('d/m/Y') }}</td>
                  <td style="text-align:center">
                    <a href="{{ route('admin.alertes.edit', $alerte) }}" class="m-btn m-btn--ghost"
                      style="height:28px;padding:0 10px;font-size:12px;" title="Modifier">
                      <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <button type="button" class="m-btn m-btn--ghost js-trigger-delete"
                      style="height:28px;padding:0 10px;font-size:12px;color:#dc3545;"
                      data-action="{{ route('admin.alertes.destroy', $alerte) }}"
                      data-name="{{ $alerte->titre }}"
                      data-type="l'alerte"
                      title="Supprimer">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @endif
      </section>
    </div>
  </div>

  {{-- Raccourcis rapides --}}
  <div class="row row-tight" style="margin-top:16px;">
    <div class="col-md-4">
      <a href="{{ route('admin.zones.create') }}" class="m-card d-block text-decoration-none"
        style="text-align:center;padding:24px;">
        <i class="fa-solid fa-map-location-dot fa-2x mb-2" style="color:var(--m-primary)"></i>
        <p class="m-card__title">Ajouter une Zone</p>
      </a>
    </div>
    <div class="col-md-4">
      <a href="{{ route('admin.alertes.create') }}" class="m-card d-block text-decoration-none"
        style="text-align:center;padding:24px;">
        <i class="fa-solid fa-triangle-exclamation fa-2x mb-2 text-warning"></i>
        <p class="m-card__title">Créer une Alerte</p>
      </a>
    </div>
    <div class="col-md-4">
      <a href="{{ route('admin.conseils.create') }}" class="m-card d-block text-decoration-none"
        style="text-align:center;padding:24px;">
        <i class="fa-solid fa-lightbulb fa-2x mb-2 text-success"></i>
        <p class="m-card__title">Ajouter un Conseil</p>
      </a>
    </div>
  </div>
@endsection