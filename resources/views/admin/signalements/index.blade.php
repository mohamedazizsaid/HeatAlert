@extends('layouts.admin.admin')

@section('title', 'Signalements de coupures')

@section('content')
<div class="page-header">
  <div>
    <h1><i class="fa-solid fa-flag me-2 text-danger"></i>Modération des signalements</h1>
    <p class="subtitle">{{ $signalements->total() }} signalement(s) enregistré(s).</p>
  </div>
</div>

@include('admin.partials._search_filter', [
  'searchRoute' => 'admin.signalements.index',
  'placeholder' => 'Filtrer par statut de validation…',
  'resultCount' => $signalements->total(),
  'filters' => [
    [
      'name' => 'statut_validation',
      'label' => '— Statut —',
      'options' => [
        'en_attente' => 'En attente',
        'valide' => 'Validés',
        'rejete' => 'Rejetés',
      ],
    ],
  ],
])

<section class="m-card">
  <div class="table-responsive">
    <table class="m-table">
      <thead>
        <tr>
          <th>Auteur</th>
          <th>Coupure liée</th>
          <th>Description</th>
          <th style="text-align:center;">Photo</th>
          <th style="text-align:center;">Statut</th>
          <th style="text-align:center;">Date</th>
          <th style="text-align:center;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($signalements as $signalement)
          @php
            $statutClass = match($signalement->statut_validation) {
              'valide' => 'bg-success',
              'rejete' => 'bg-danger',
              default => 'bg-warning text-dark',
            };
          @endphp
          <tr>
            <td>
              <strong>{{ $signalement->user?->prenom }} {{ $signalement->user?->nom }}</strong>
              <small class="d-block text-muted">{{ $signalement->user?->email ?? 'Utilisateur inconnu' }}</small>
            </td>
            <td>
              @if($signalement->coupure)
                <a href="{{ route('admin.coupures.show', $signalement->coupure) }}" class="text-decoration-none fw-semibold">
                  {{ ucfirst($signalement->coupure->type) }}
                </a>
                <small class="d-block text-muted">{{ $signalement->coupure->zone?->nom ?? 'Zone inconnue' }}</small>
              @else
                <span class="text-muted">Sans coupure officielle</span>
              @endif
            </td>
            <td>{{ Str::limit($signalement->description, 75) }}</td>
            <td style="text-align:center;">
              @if($signalement->photo)
                <img src="{{ asset('storage/' . ltrim($signalement->photo, '/')) }}"
                     alt="Photo du signalement"
                     style="width:48px;height:48px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb;">
              @else
                <span class="text-muted">—</span>
              @endif
            </td>
            <td style="text-align:center;"><span class="badge {{ $statutClass }}">{{ ucfirst(str_replace('_', ' ', $signalement->statut_validation)) }}</span></td>
            <td style="text-align:center;">{{ $signalement->date_signalement?->format('d/m/Y H:i') }}</td>
            <td style="text-align:center;white-space:nowrap;">
              <a href="{{ route('admin.signalements.show', $signalement) }}"
                 class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;color:#2563eb;" title="Voir">
                <i class="fa-solid fa-eye"></i>
              </a>
              @if($signalement->statut_validation !== 'valide')
                <form action="{{ route('admin.signalements.valider', $signalement) }}" method="POST" class="d-inline">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;color:#16a34a;" title="Valider">
                    <i class="fa-solid fa-check"></i>
                  </button>
                </form>
              @endif
              @if($signalement->statut_validation !== 'rejete')
                <form action="{{ route('admin.signalements.rejeter', $signalement) }}" method="POST" class="d-inline">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;color:#dc2626;" title="Rejeter">
                    <i class="fa-solid fa-xmark"></i>
                  </button>
                </form>
              @endif
              <button type="button" class="m-btn m-btn--ghost js-trigger-delete"
                      style="height:28px;padding:0 10px;font-size:12px;color:#dc3545;"
                      data-action="{{ route('admin.signalements.destroy', $signalement) }}"
                      data-name="Signalement de {{ $signalement->user?->prenom }} {{ $signalement->user?->nom }}"
                      data-type="le signalement"
                      title="Supprimer">
                <i class="fa-solid fa-trash"></i>
              </button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-4 text-muted">
              <i class="fa-solid fa-flag fa-2x mb-2 d-block"></i>
              Aucun signalement trouvé.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($signalements->hasPages())
    <div class="d-flex justify-content-center align-items-center mt-3 pb-2" style="gap:6px;">
      @if(!$signalements->onFirstPage())
        <a href="{{ $signalements->previousPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;">
          <i class="fa-solid fa-chevron-left"></i>
        </a>
      @endif
      @foreach($signalements->getUrlRange(max(1, $signalements->currentPage() - 2), min($signalements->lastPage(), $signalements->currentPage() + 2)) as $page => $url)
        <a href="{{ $url }}" class="m-btn {{ $page == $signalements->currentPage() ? 'm-btn--primary' : 'm-btn--ghost' }}" style="height:32px;padding:0 11px;font-size:13px;min-width:32px;text-align:center;">
          {{ $page }}
        </a>
      @endforeach
      @if($signalements->hasMorePages())
        <a href="{{ $signalements->nextPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;">
          <i class="fa-solid fa-chevron-right"></i>
        </a>
      @endif
    </div>
  @endif
</section>
@endsection
