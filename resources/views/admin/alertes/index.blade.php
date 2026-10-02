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
          <th>Titre</th>
          <th style="text-align:center;">Zone</th>
          <th style="text-align:center;">Type</th>
          <th style="text-align:center;">Niveau</th>
          <th style="text-align:center;">Statut</th>
          <th style="text-align:center;">Temp. max</th>
          <th style="text-align:center;">Début</th>
          <th style="text-align:center;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($alertes as $alerte)
        <tr>
          <td>
            <strong>{{ Str::limit($alerte->titre, 40) }}</strong>
            @if($alerte->risque_coupure)
              <span class="badge bg-warning text-dark ms-1" title="Risque coupure électrique"><i class="fa-solid fa-bolt"></i></span>
            @endif
          </td>
          <td style="text-align:center;">{{ $alerte->zone?->ville ?? '—' }}</td>
          <td style="text-align:center;"><span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$alerte->type)) }}</span></td>
          <td style="text-align:center;">
            @php $niveauClass = match($alerte->niveau) { 'rouge' => 'bg-danger', 'orange' => 'bg-warning text-dark', 'jaune' => 'bg-info text-dark', default => 'bg-success' }; @endphp
            <span class="badge {{ $niveauClass }}">{{ ucfirst($alerte->niveau) }}</span>
          </td>
          <td style="text-align:center;">
            @php $statutClass = match($alerte->statut) { 'active' => 'bg-success', 'brouillon' => 'bg-secondary', 'terminee' => 'bg-dark', default => 'bg-danger' }; @endphp
            <span class="badge {{ $statutClass }}">{{ ucfirst($alerte->statut) }}</span>
          </td>
          <td style="text-align:center;">{{ $alerte->temperature_max !== null ? $alerte->temperature_max . '°C' : '—' }}</td>
          <td style="text-align:center;">{{ $alerte->date_debut?->format('d/m/Y') }}</td>
          <td style="text-align:center;">
            <a href="{{ route('admin.alertes.show', $alerte) }}" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;color:#2563eb;" title="Détails">
              <i class="fa-solid fa-eye"></i>
            </a>
            <a href="{{ route('admin.alertes.edit', $alerte) }}" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;" title="Modifier">
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
