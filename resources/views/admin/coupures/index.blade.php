@extends('layouts.admin.admin')

@section('title', 'Coupures électriques')

@section('content')
<div class="page-header">
  <div>
    <h1><i class="fa-solid fa-bolt me-2 text-warning"></i>Gestion des Coupures</h1>
    <p class="subtitle">{{ $coupures->total() }} coupure(s) enregistrée(s).</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('admin.coupures.create') }}" class="m-btn m-btn--primary">
      <i class="fa-solid fa-plus"></i> Nouvelle coupure
    </a>
  </div>
</div>

@include('admin.partials._search_filter', [
  'searchRoute' => 'admin.coupures.index',
  'placeholder' => 'Rechercher par type, cause, zone…',
  'resultCount' => $coupures->total(),
  'filters' => [
    [
      'name' => 'type',
      'label' => '— Type —',
      'options' => [
        'delestage' => 'Délestage',
        'surcharge' => 'Surcharge',
        'panne' => 'Panne',
      ],
    ],
    [
      'name' => 'statut',
      'label' => '— Statut —',
      'options' => [
        'prevue' => 'Prévue',
        'en_cours' => 'En cours',
        'terminee' => 'Terminée',
      ],
    ],
    [
      'name' => 'zone_id',
      'label' => '— Zone —',
      'options' => $zones->mapWithKeys(fn ($zone) => [
        $zone->id => $zone->nom . ' (' . $zone->ville . ')',
      ])->all(),
    ],
    [
      'name' => 'tri',
      'label' => '— Trier par —',
      'options' => [
        'priorite' => 'Priorité métier',
        'date_debut_desc' => 'Début : plus récente',
        'date_debut_asc' => 'Début : plus ancienne',
        'signalements_desc' => 'Plus de signalements',
      ],
    ],
  ],
])

<section class="m-card">
  <div class="table-responsive">
    <table class="m-table">
      <thead>
        <tr>
          <th>Type</th>
          <th style="text-align:center;">Statut</th>
          <th>Zone</th>
          <th style="text-align:center;">Début</th>
          <th style="text-align:center;">Fin</th>
          <th style="text-align:center;">Signalements</th>
          <th style="text-align:center;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($coupures as $coupure)
        @php
          $statutClass = match($coupure->statut) {
            'prevue' => 'bg-warning text-dark',
            'en_cours' => 'bg-danger',
            'terminee' => 'bg-success',
            default => 'bg-secondary',
          };
        @endphp
        <tr>
          <td>
            <strong>{{ ucfirst($coupure->type) }}</strong>
            @if($coupure->cause)
              <small class="d-block text-muted">{{ Str::limit($coupure->cause, 55) }}</small>
            @endif
          </td>
          <td style="text-align:center;"><span class="badge {{ $statutClass }}">{{ ucfirst(str_replace('_', ' ', $coupure->statut)) }}</span></td>
          <td>{{ $coupure->zone?->nom ?? $coupure->zone?->ville ?? '—' }}</td>
          <td style="text-align:center;">{{ $coupure->date_debut?->format('d/m/Y H:i') }}</td>
          <td style="text-align:center;">{{ $coupure->date_fin?->format('d/m/Y H:i') ?? '—' }}</td>
          <td style="text-align:center;"><span class="badge bg-info text-dark">{{ $coupure->signalements_count }}</span></td>
          <td style="text-align:center; white-space:nowrap;">
            <a href="{{ route('admin.coupures.show', $coupure) }}" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;color:#2563eb;" title="Voir">
              <i class="fa-solid fa-eye"></i>
            </a>
            <a href="{{ route('admin.coupures.edit', $coupure) }}" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;" title="Modifier">
              <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <button type="button" class="m-btn m-btn--ghost js-trigger-delete"
                    style="height:28px;padding:0 10px;font-size:12px;color:#dc3545;"
                    data-action="{{ route('admin.coupures.destroy', $coupure) }}"
                    data-name="Coupure {{ ucfirst($coupure->type) }} - {{ $coupure->zone?->nom ?? 'Zone inconnue' }}"
                    data-type="la coupure"
                    title="Supprimer">
              <i class="fa-solid fa-trash"></i>
            </button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center py-4 text-muted">
            <i class="fa-solid fa-bolt fa-2x mb-2 d-block"></i>
            Aucune coupure enregistrée. <a href="{{ route('admin.coupures.create') }}">Créez la première</a>.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($coupures->hasPages())
  <div class="d-flex justify-content-center align-items-center mt-3 pb-2" style="gap:6px;">
    @if(!$coupures->onFirstPage())
      <a href="{{ $coupures->previousPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;">
        <i class="fa-solid fa-chevron-left"></i>
      </a>
    @endif
    @foreach($coupures->getUrlRange(max(1, $coupures->currentPage() - 2), min($coupures->lastPage(), $coupures->currentPage() + 2)) as $page => $url)
      <a href="{{ $url }}" class="m-btn {{ $page == $coupures->currentPage() ? 'm-btn--primary' : 'm-btn--ghost' }}" style="height:32px;padding:0 11px;font-size:13px;min-width:32px;text-align:center;">
        {{ $page }}
      </a>
    @endforeach
    @if($coupures->hasMorePages())
      <a href="{{ $coupures->nextPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;">
        <i class="fa-solid fa-chevron-right"></i>
      </a>
    @endif
  </div>
  @endif
</section>
@endsection
