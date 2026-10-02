@extends('layouts.admin.admin')

@section('title', 'Conseils')

@section('content')
<div class="page-header">
  <div>
    <h1><i class="fa-solid fa-lightbulb me-2"></i>Gestion des Conseils</h1>
    <p class="subtitle">{{ $conseils->total() }} conseil(s) enregistré(s).</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('admin.conseils.create') }}" class="m-btn m-btn--primary">
      <i class="fa-solid fa-plus"></i> Ajouter un conseil
    </a>
  </div>
</div>

<section class="m-card">
  <div class="table-responsive">
    <table class="m-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Titre</th>
          <th>Catégorie</th>
          <th>Niveau cible</th>
          <th>Icône</th>
          <th>Statut</th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($conseils as $conseil)
        <tr>
          <td>{{ $conseil->id }}</td>
          <td>
            <strong>{{ Str::limit($conseil->titre, 50) }}</strong>
            <br>
            <small class="text-muted">{{ Str::limit($conseil->contenu, 60) }}</small>
          </td>
          <td>
            <span class="badge bg-secondary">
              {{ ucfirst(str_replace('_', ' ', $conseil->categorie)) }}
            </span>
          </td>
          <td>
            @if($conseil->niveau_alerte_cible)
              @php
                $niveauClass = match($conseil->niveau_alerte_cible) {
                  'rouge'  => 'bg-danger',
                  'orange' => 'bg-warning text-dark',
                  'jaune'  => 'bg-info text-dark',
                  default  => 'bg-success',
                };
              @endphp
              <span class="badge {{ $niveauClass }}">{{ ucfirst($conseil->niveau_alerte_cible) }}</span>
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td>
            @if($conseil->icone)
              <i class="{{ $conseil->icone }}"></i>
              <small class="text-muted ms-1">{{ $conseil->icone }}</small>
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td>
            @if($conseil->actif)
              <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>Actif</span>
            @else
              <span class="badge bg-secondary"><i class="fa-solid fa-circle-xmark me-1"></i>Inactif</span>
            @endif
          </td>
          <td style="text-align:right;">
            <a href="{{ route('admin.conseils.edit', $conseil) }}"
               class="m-btn m-btn--ghost"
               style="height:28px;padding:0 10px;font-size:12px;"
               title="Modifier">
              <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <form action="{{ route('admin.conseils.destroy', $conseil) }}"
                  method="POST"
                  style="display:inline;"
                  onsubmit="return confirm('Supprimer le conseil « {{ addslashes(Str::limit($conseil->titre, 40)) }} » ?')">
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
          <td colspan="7" class="text-center py-4 text-muted">
            <i class="fa-solid fa-lightbulb fa-2x mb-2 d-block"></i>
            Aucun conseil. <a href="{{ route('admin.conseils.create') }}">Créez le premier</a>.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($conseils->hasPages())
  <div class="d-flex justify-content-center mt-3">
    {{ $conseils->links() }}
  </div>
  @endif
</section>
@endsection
