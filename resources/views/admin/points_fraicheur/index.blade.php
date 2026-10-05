@extends('layouts.admin.admin')

@section('title', 'Points de Fraîcheur')

@section('content')
<div class="page-header">
  <div>
    <h1><i class="fa-solid fa-snowflake me-2 text-info"></i>Gestion des Points de Fraîcheur</h1>
    <p class="subtitle">{{ $points->total() }} point(s) enregistré(s).</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('admin.avis_points.index') }}" class="m-btn m-btn--ghost" style="border:1px solid #0ea5e9;color:#0ea5e9;">
      <i class="fa-solid fa-star-half-stroke"></i> Supervision des avis
    </a>
    <a href="{{ route('admin.points_fraicheur.create') }}" class="m-btn m-btn--primary">
      <i class="fa-solid fa-plus"></i> Ajouter un point
    </a>
  </div>
</div>

@include('admin.partials._search_filter', [
  'searchRoute' => 'admin.points_fraicheur.index',
  'placeholder' => 'Rechercher par nom, adresse…',
  'resultCount' => $points->total(),
  'filters'     => [
    [
      'name'    => 'type',
      'label'   => '— Type —',
      'options' => [
        'parc'             => 'Parc / Espace vert',
        'salle_climatisee' => 'Salle climatisée',
        'fontaine'         => 'Fontaine / Borne',
        'piscine'          => 'Piscine municipale',
        'bibliotheque'     => 'Bibliothèque',
        'autre'            => 'Autre',
      ],
    ],
    [
      'name'    => 'zone_id',
      'label'   => '— Zone —',
      'options' => $zones->pluck('nom', 'id')->toArray(),
    ],
    [
      'name'    => 'actif',
      'label'   => '— Statut —',
      'options' => ['1' => 'Actif', '0' => 'Inactif'],
    ],
  ],
])

<section class="m-card">
  <div class="table-responsive">
    <table class="m-table">
      <thead>
        <tr>
          <th>Nom / Adresse</th>
          <th style="text-align:center;">Type</th>
          <th style="text-align:center;">Zone</th>
          <th style="text-align:center;">PMR</th>
          <th style="text-align:center;">Avis</th>
          <th style="text-align:center;">Note moy.</th>
          <th style="text-align:center;">Statut</th>
          <th style="text-align:center;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($points as $point)
        <tr>
          <td>
            <strong>{{ $point->nom }}</strong>
            <br><small class="text-muted"><i class="fa-solid fa-location-dot me-1"></i>{{ Str::limit($point->adresse, 45) }}</small>
          </td>
          <td style="text-align:center;">
            @php
              $icon  = \App\Models\PointFraicheur::$typeIcons[$point->type]  ?? 'fa-location-dot';
              $color = \App\Models\PointFraicheur::$typeColors[$point->type] ?? '#64748b';
              $label = \App\Models\PointFraicheur::$typeLabels[$point->type] ?? ucfirst($point->type);
            @endphp
            <span class="badge rounded-pill" style="background:{{ $color }}20;color:{{ $color }};border:1px solid {{ $color }}40;font-size:.75rem;">
              <i class="fa-solid {{ $icon }} me-1"></i>{{ $label }}
            </span>
          </td>
          <td style="text-align:center;">
            {{ $point->zone?->nom ?? '—' }}
          </td>
          <td style="text-align:center;">
            @if($point->accessible_pmr)
              <span class="badge bg-success"><i class="fa-solid fa-wheelchair me-1"></i>Oui</span>
            @else
              <span class="badge bg-light text-muted border">Non</span>
            @endif
          </td>
          <td style="text-align:center;">
            <span class="badge bg-light text-dark border">{{ $point->avis_points_count ?? 0 }}</span>
          </td>
          <td style="text-align:center;">
            @php $moy = $point->avis_points_avg_note ?? 0; @endphp
            @if($moy > 0)
              <span style="color:#f59e0b;font-weight:700;">
                @for($s = 1; $s <= 5; $s++)
                  <i class="fa-{{ $s <= round($moy) ? 'solid' : 'regular' }} fa-star" style="font-size:12px;"></i>
                @endfor
                <span class="ms-1 text-muted" style="font-size:.78rem;">{{ number_format($moy, 1) }}</span>
              </span>
            @else
              <span class="text-muted" style="font-size:.8rem;">—</span>
            @endif
          </td>
          <td style="text-align:center;">
            @if($point->actif)
              <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>Actif</span>
            @else
              <span class="badge bg-secondary"><i class="fa-solid fa-circle-xmark me-1"></i>Inactif</span>
            @endif
          </td>
          <td style="text-align:center;">
            <a href="{{ route('admin.points_fraicheur.show', $point) }}" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;color:#2563eb;" title="Détails">
              <i class="fa-solid fa-eye"></i>
            </a>
            <a href="{{ route('admin.points_fraicheur.edit', $point) }}" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;" title="Modifier">
              <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <button type="button" class="m-btn m-btn--ghost js-trigger-delete"
                    style="height:28px;padding:0 10px;font-size:12px;color:#dc3545;"
                    data-action="{{ route('admin.points_fraicheur.destroy', $point) }}"
                    data-name="{{ $point->nom }}"
                    data-type="le point de fraîcheur"
                    title="Supprimer">
              <i class="fa-solid fa-trash"></i>
            </button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="text-center py-4 text-muted">
            <i class="fa-solid fa-snowflake fa-2x mb-2 d-block text-info"></i>
            Aucun point de fraîcheur trouvé. <a href="{{ route('admin.points_fraicheur.create') }}">Créez le premier</a>.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($points->hasPages())
  <div class="d-flex justify-content-center align-items-center mt-3 pb-2" style="gap:6px;">
    @if(!$points->onFirstPage())
      <a href="{{ $points->previousPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;">
        <i class="fa-solid fa-chevron-left"></i>
      </a>
    @endif
    @foreach($points->getUrlRange(max(1, $points->currentPage()-2), min($points->lastPage(), $points->currentPage()+2)) as $page => $url)
      <a href="{{ $url }}" class="m-btn {{ $page == $points->currentPage() ? 'm-btn--primary' : 'm-btn--ghost' }}" style="height:32px;padding:0 11px;font-size:13px;min-width:32px;text-align:center;">{{ $page }}</a>
    @endforeach
    @if($points->hasMorePages())
      <a href="{{ $points->nextPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;">
        <i class="fa-solid fa-chevron-right"></i>
      </a>
    @endif
  </div>
  @endif
</section>
@endsection
