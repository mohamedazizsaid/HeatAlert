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

@include('admin.partials._search_filter', [
  'searchRoute' => 'admin.conseils.index',
  'placeholder' => 'Rechercher par titre ou contenu…',
  'resultCount' => $conseils->total(),
  'filters'     => [
    [
      'name'    => 'categorie',
      'label'   => '— Catégorie —',
      'options' => [
        'hydratation' => '💧 Hydratation',
        'energie'     => '⚡ Énergie',
        'equipements' => '🛡️ Équipements',
        'sante'       => '🩺 Santé',
        'habitat'     => '🏠 Habitat',
        'deplacement' => '🚗 Déplacement',
      ],
    ],
    [
      'name'    => 'niveau_alerte_cible',
      'label'   => '— Niveau cible —',
      'options' => [
        'vert'   => '🟢 Vert',
        'jaune'  => '🟡 Jaune',
        'orange' => '🟠 Orange',
        'rouge'  => '🔴 Rouge',
      ],
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
          <th >Titre</th>
          <th style="text-align: center;">Catégorie</th>
          <th style="text-align: center;">Niveau cible</th>
          <th style="text-align: center;">Icône</th>
          <th style="text-align: center;">Statut</th>
          <th style="text-align:center;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($conseils as $conseil)
        <tr>
          <td>
            <strong>{{ Str::limit($conseil->titre, 50) }}</strong>
            <br>
            <small class="text-muted">{{ Str::limit($conseil->contenu, 60) }}</small>
          </td>
          <td style="text-align: center;">
            <span class="badge bg-secondary">
              {{ ucfirst(str_replace('_', ' ', $conseil->categorie)) }}
            </span>
          </td>
          <td style="text-align: center;">
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
          <td style="text-align: center;">
            @if($conseil->icone)
              <i class="{{ $conseil->icone }}"></i>
              <small class="text-muted ms-1">{{ $conseil->icone }}</small>
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td style="text-align: center;">
            @if($conseil->actif)
              <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>Actif</span>
            @else
              <span class="badge bg-secondary"><i class="fa-solid fa-circle-xmark me-1"></i>Inactif</span>
            @endif
          </td>
          <td style="text-align:center;">
            <a href="{{ route('admin.conseils.show', $conseil) }}"
               class="m-btn m-btn--ghost"
               style="height:28px;padding:0 10px;font-size:12px;color:#2563eb;"
               title="Détails">
              <i class="fa-solid fa-eye"></i>
            </a>
            <a href="{{ route('admin.conseils.edit', $conseil) }}"
               class="m-btn m-btn--ghost"
               style="height:28px;padding:0 10px;font-size:12px;"
               title="Modifier">
              <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <button type="button"
                    class="m-btn m-btn--ghost js-trigger-delete"
                    style="height:28px;padding:0 10px;font-size:12px;color:#dc3545;"
                    data-action="{{ route('admin.conseils.destroy', $conseil) }}"
                    data-name="{{ $conseil->titre }}"
                    data-type="le conseil"
                    title="Supprimer">
              <i class="fa-solid fa-trash"></i>
            </button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center py-4 text-muted">
            <i class="fa-solid fa-lightbulb fa-2x mb-2 d-block"></i>
            Aucun conseil trouvé. <a href="{{ route('admin.conseils.create') }}">Créez le premier</a>.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($conseils->hasPages())
  <div class="d-flex justify-content-center align-items-center mt-3 pb-2" style="gap:6px;">
    @if(!$conseils->onFirstPage())
      <a href="{{ $conseils->previousPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;">
        <i class="fa-solid fa-chevron-left"></i>
      </a>
    @endif

    @foreach($conseils->getUrlRange(max(1, $conseils->currentPage()-2), min($conseils->lastPage(), $conseils->currentPage()+2)) as $page => $url)
      <a href="{{ $url }}" class="m-btn {{ $page == $conseils->currentPage() ? 'm-btn--primary' : 'm-btn--ghost' }}" style="height:32px;padding:0 11px;font-size:13px;min-width:32px;text-align:center;">
        {{ $page }}
      </a>
    @endforeach

    @if($conseils->hasMorePages())
      <a href="{{ $conseils->nextPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;">
        <i class="fa-solid fa-chevron-right"></i>
      </a>
    @endif
  </div>
  @endif
</section>
@endsection
