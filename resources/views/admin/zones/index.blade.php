@extends('layouts.admin.admin')

@section('title', 'Zones')

@section('content')
<div class="page-header">
  <div>
    <h1><i class="fa-solid fa-map-location-dot me-2"></i>Gestion des Zones</h1>
    <p class="subtitle">{{ $zones->total() }} zone(s) enregistrée(s).</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('admin.zones.create') }}" class="m-btn m-btn--primary">
      <i class="fa-solid fa-plus"></i> Ajouter une zone
    </a>
  </div>
</div>

@include('admin.partials._search_filter', [
  'searchRoute' => 'admin.zones.index',
  'placeholder' => 'Rechercher par nom, ville, gouvernorat…',
  'resultCount' => $zones->total(),
  'filters'     => [
    [
      'name'    => 'actif',
      'label'   => '— Statut —',
      'options' => ['1' => 'Active', '0' => 'Inactive'],
    ],
  ],
])

<section class="m-card">
  <div class="table-responsive">
    <table class="m-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Nom</th>
          <th>Ville</th>
          <th>Gouvernorat</th>
          <th>Code postal</th>
          <th>Coordonnées</th>
          <th>Statut</th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($zones as $zone)
        <tr>
          <td>{{ $zone->id }}</td>
          <td><strong>{{ $zone->nom }}</strong></td>
          <td>{{ $zone->ville }}</td>
          <td>{{ $zone->gouvernorat ?? '—' }}</td>
          <td>{{ $zone->code_postal ?? '—' }}</td>
          <td>
            @if($zone->latitude && $zone->longitude)
              <small class="text-muted">{{ number_format($zone->latitude, 4) }}, {{ number_format($zone->longitude, 4) }}</small>
            @else <span class="text-muted">—</span> @endif
          </td>
          <td>
            @if($zone->actif)
              <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
            @else
              <span class="badge bg-secondary"><i class="fa-solid fa-circle-xmark me-1"></i>Inactive</span>
            @endif
          </td>
          <td style="text-align:right;">
            <a href="{{ route('admin.zones.show', $zone) }}" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;color:#2563eb;" title="Détails">
              <i class="fa-solid fa-eye"></i>
            </a>
            <a href="{{ route('admin.zones.edit', $zone) }}" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;" title="Modifier">
              <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <form action="{{ route('admin.zones.destroy', $zone) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer la zone « {{ addslashes($zone->nom) }} » ?')">
              @csrf @method('DELETE')
              <button type="submit" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;color:#dc3545;" title="Supprimer">
                <i class="fa-solid fa-trash"></i>
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="text-center py-4 text-muted">
            <i class="fa-solid fa-map-location-dot fa-2x mb-2 d-block"></i>
            Aucune zone trouvée. <a href="{{ route('admin.zones.create') }}">Créez la première</a>.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($zones->hasPages())
  <div class="d-flex justify-content-center align-items-center mt-3 pb-2" style="gap:6px;">
    @if(!$zones->onFirstPage())
      <a href="{{ $zones->previousPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;">
        <i class="fa-solid fa-chevron-left"></i>
      </a>
    @endif

    @foreach($zones->getUrlRange(max(1, $zones->currentPage()-2), min($zones->lastPage(), $zones->currentPage()+2)) as $page => $url)
      <a href="{{ $url }}" class="m-btn {{ $page == $zones->currentPage() ? 'm-btn--primary' : 'm-btn--ghost' }}" style="height:32px;padding:0 11px;font-size:13px;min-width:32px;text-align:center;">
        {{ $page }}
      </a>
    @endforeach

    @if($zones->hasMorePages())
      <a href="{{ $zones->nextPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;">
        <i class="fa-solid fa-chevron-right"></i>
      </a>
    @endif
  </div>
  @endif
</section>
@endsection
