@extends('layouts.admin.admin')
@section('title', 'Supervision des Avis')

@section('content')
<div class="page-header">
  <div>
    <nav aria-label="breadcrumb" class="mb-2">
      <a href="{{ route('admin.points_fraicheur.index') }}" class="text-decoration-none text-muted" style="font-size:.85rem;">
        <i class="fa-solid fa-arrow-left me-1"></i>Points de Fraîcheur
      </a>
    </nav>
    <h1><i class="fa-solid fa-star-half-stroke me-2 text-warning"></i>Supervision des Avis Citoyens</h1>
    <p class="subtitle">{{ $avis->total() }} avis enregistré(s).</p>
  </div>
</div>

@include('admin.partials._search_filter', [
  'searchRoute' => 'admin.avis_points.index',
  'placeholder' => 'Rechercher…',
  'resultCount' => $avis->total(),
  'filters'     => [
    [
      'name'    => 'point_fraicheur_id',
      'label'   => '— Point —',
      'options' => $points->pluck('nom', 'id')->toArray(),
    ],
    [
      'name'    => 'note',
      'label'   => '— Note —',
      'options' => ['1' => '★ 1', '2' => '★★ 2', '3' => '★★★ 3', '4' => '★★★★ 4', '5' => '★★★★★ 5'],
    ],
  ],
])

<section class="m-card">
  <div class="table-responsive">
    <table class="m-table">
      <thead>
        <tr>
          <th>Point de Fraîcheur</th>
          <th>Citoyen</th>
          <th style="text-align:center;">Note</th>
          <th>Commentaire</th>
          <th style="text-align:center;">Date</th>
          <th style="text-align:center;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($avis as $av)
        <tr>
          <td>
            <a href="{{ route('admin.points_fraicheur.show', $av->point_fraicheur_id) }}" class="text-decoration-none fw-bold">
              {{ $av->pointFraicheur?->nom ?? '—' }}
            </a>
          </td>
          <td>
            <strong>{{ $av->user?->prenom ?? '' }} {{ $av->user?->nom ?? '—' }}</strong>
            <br><small class="text-muted">{{ $av->user?->email ?? '' }}</small>
          </td>
          <td style="text-align:center;">
            <span style="color:#f59e0b;">
              @for($s = 1; $s <= 5; $s++)
                <i class="fa-{{ $s <= $av->note ? 'solid' : 'regular' }} fa-star" style="font-size:13px;"></i>
              @endfor
              <span class="ms-1 text-dark fw-bold" style="font-size:.8rem;">({{ $av->note }}/5)</span>
            </span>
          </td>
          <td>{{ $av->commentaire ? Str::limit($av->commentaire, 80) : '<em class=\'text-muted\'>Aucun commentaire</em>' }}</td>
          <td style="text-align:center;font-size:.82rem;">{{ $av->date_avis?->format('d/m/Y') ?? '—' }}</td>
          <td style="text-align:center;">
            <form method="POST" action="{{ route('admin.avis_points.destroy', $av) }}" style="display:inline;">
              @csrf @method('DELETE')
              <button type="submit" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;color:#dc2626;"
                      onclick="return confirm('Supprimer cet avis définitivement ?')" title="Supprimer">
                <i class="fa-solid fa-trash"></i>
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center py-4 text-muted">
            <i class="fa-solid fa-comment-slash fa-2x mb-2 d-block"></i>Aucun avis trouvé.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($avis->hasPages())
  <div class="d-flex flex-column align-items-center mt-3 pb-2" style="gap:8px;">
    <div class="text-muted" style="font-size:12px;">
      Affichage de {{ $avis->firstItem() }} à {{ $avis->lastItem() }} sur {{ $avis->total() }} avis
    </div>
    <div class="d-flex justify-content-center align-items-center" style="gap:6px;">
      @if(!$avis->onFirstPage())
        <a href="{{ $avis->previousPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;"><i class="fa-solid fa-chevron-left"></i></a>
      @endif
      @foreach($avis->getUrlRange(max(1, $avis->currentPage()-2), min($avis->lastPage(), $avis->currentPage()+2)) as $page => $url)
        <a href="{{ $url }}" class="m-btn {{ $page == $avis->currentPage() ? 'm-btn--primary' : 'm-btn--ghost' }}" style="height:32px;padding:0 11px;font-size:13px;min-width:32px;text-align:center;">{{ $page }}</a>
      @endforeach
      @if($avis->hasMorePages())
        <a href="{{ $avis->nextPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;"><i class="fa-solid fa-chevron-right"></i></a>
      @endif
    </div>
  </div>
  @endif
</section>
@endsection
