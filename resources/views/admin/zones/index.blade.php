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
              <small class="text-muted">
                {{ number_format($zone->latitude, 4) }}, {{ number_format($zone->longitude, 4) }}
              </small>
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td>
            @if($zone->actif)
              <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
            @else
              <span class="badge bg-secondary"><i class="fa-solid fa-circle-xmark me-1"></i>Inactive</span>
            @endif
          </td>
          <td style="text-align:right;">
            <a href="{{ route('admin.zones.edit', $zone) }}"
               class="m-btn m-btn--ghost"
               style="height:28px;padding:0 10px;font-size:12px;"
               title="Modifier">
              <i class="fa-solid fa-pen-to-square"></i>
            </a>

            <form action="{{ route('admin.zones.destroy', $zone) }}"
                  method="POST"
                  style="display:inline;"
                  onsubmit="return confirm('Supprimer la zone « {{ addslashes($zone->nom) }} » ?')">
              @csrf
              @method('DELETE')
              <button type="submit"
                      class="m-btn m-btn--ghost"
                      style="height:28px;padding:0 10px;font-size:12px;color:#dc3545;"
                      title="Supprimer">
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
  <div class="d-flex justify-content-center mt-3">
    {{ $zones->links() }}
  </div>
  @endif
</section>
@endsection
