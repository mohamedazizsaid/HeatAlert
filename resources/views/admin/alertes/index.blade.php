@extends('layouts.admin.admin')

@section('title', 'Alertes Météo')

@section('content')
<div class="page-header">
  <div>
    <h1><i class="fa-solid fa-triangle-exclamation me-2"></i>Gestion des Alertes Météo</h1>
    <p class="subtitle">{{ $alertes->total() }} alerte(s) enregistrée(s).</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('admin.alertes.create') }}" class="m-btn m-btn--primary">
      <i class="fa-solid fa-plus"></i> Nouvelle alerte
    </a>
  </div>
</div>

@include('admin.partials._search_filter', [
  'searchRoute' => 'admin.alertes.index',
  'placeholder' => 'Rechercher par titre, description, ville…',
  'resultCount' => $alertes->total(),
  'filters'     => [
    [
      'name'    => 'niveau',
      'label'   => '— Niveau —',
      'options' => ['vert' => '🟢 Vert', 'jaune' => '🟡 Jaune', 'orange' => '🟠 Orange', 'rouge' => '🔴 Rouge'],
    ],
    [
      'name'    => 'statut',
      'label'   => '— Statut —',
      'options' => ['active' => 'Active', 'brouillon' => 'Brouillon', 'terminee' => 'Terminée', 'annulee' => 'Annulée'],
    ],
    [
      'name'    => 'type',
      'label'   => '— Type —',
      'options' => ['canicule' => 'Canicule', 'secheresse' => 'Sécheresse', 'vent_fort' => 'Vent fort', 'tempete_sable' => 'Tempête sable', 'pollution' => 'Pollution'],
    ],
  ],
])

<section class="m-card">
  <div class="table-responsive">
    <table class="m-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Titre</th>
          <th>Zone</th>
          <th>Type</th>
          <th>Niveau</th>
          <th>Statut</th>
          <th>Temp. max</th>
          <th>Début</th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($alertes as $alerte)
        <tr>
          <td>{{ $alerte->id }}</td>
          <td>
            <strong>{{ Str::limit($alerte->titre, 40) }}</strong>
            @if($alerte->risque_coupure)
              <span class="badge bg-warning text-dark ms-1" title="Risque coupure électrique"><i class="fa-solid fa-bolt"></i></span>
            @endif
          </td>
          <td>{{ $alerte->zone?->ville ?? '—' }}</td>
          <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$alerte->type)) }}</span></td>
          <td>
            @php $niveauClass = match($alerte->niveau) { 'rouge' => 'bg-danger', 'orange' => 'bg-warning text-dark', 'jaune' => 'bg-info text-dark', default => 'bg-success' }; @endphp
            <span class="badge {{ $niveauClass }}">{{ ucfirst($alerte->niveau) }}</span>
          </td>
          <td>
            @php $statutClass = match($alerte->statut) { 'active' => 'bg-success', 'brouillon' => 'bg-secondary', 'terminee' => 'bg-dark', default => 'bg-danger' }; @endphp
            <span class="badge {{ $statutClass }}">{{ ucfirst($alerte->statut) }}</span>
          </td>
          <td>{{ $alerte->temperature_max !== null ? $alerte->temperature_max . '°C' : '—' }}</td>
          <td>{{ $alerte->date_debut?->format('d/m/Y') }}</td>
          <td style="text-align:right;">
            <a href="{{ route('admin.alertes.edit', $alerte) }}" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;" title="Modifier">
              <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <form action="{{ route('admin.alertes.destroy', $alerte) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer l\'alerte « {{ addslashes(Str::limit($alerte->titre, 40)) }} » ?')">
              @csrf @method('DELETE')
              <button type="submit" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;color:#dc3545;" title="Supprimer">
                <i class="fa-solid fa-trash"></i>
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="9" class="text-center py-4 text-muted">
            <i class="fa-solid fa-triangle-exclamation fa-2x mb-2 d-block"></i>
            Aucune alerte météo. <a href="{{ route('admin.alertes.create') }}">Créez la première</a>.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($alertes->hasPages())
  <div class="d-flex justify-content-center align-items-center mt-3 pb-2" style="gap:6px;">
    @if(!$alertes->onFirstPage())
      <a href="{{ $alertes->previousPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;">
        <i class="fa-solid fa-chevron-left"></i>
      </a>
    @endif
    @foreach($alertes->getUrlRange(max(1,$alertes->currentPage()-2), min($alertes->lastPage(),$alertes->currentPage()+2)) as $page => $url)
      <a href="{{ $url }}" class="m-btn {{ $page==$alertes->currentPage() ? 'm-btn--primary' : 'm-btn--ghost' }}" style="height:32px;padding:0 11px;font-size:13px;min-width:32px;text-align:center;">
        {{ $page }}
      </a>
    @endforeach
    @if($alertes->hasMorePages())
      <a href="{{ $alertes->nextPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;">
        <i class="fa-solid fa-chevron-right"></i>
      </a>
    @endif
  </div>
  @endif
</section>
@endsection
