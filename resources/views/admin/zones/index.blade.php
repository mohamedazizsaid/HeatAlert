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

{{-- ── BARRE DE RECHERCHE AVANCÉE DYNAMIQUE EN 1 CLIC ── --}}
<div class="m-card mb-4 p-3" style="border-radius:14px;background:#ffffff;box-shadow:0 2px 10px rgba(0,0,0,0.03);">
  <form action="{{ route('admin.zones.index') }}" method="GET" id="zones-search-form">
    <div class="row g-2 align-items-center mb-3">
      {{-- Input recherche texte --}}
      <div class="col-lg-6 col-md-12">
        <div class="input-group" style="border-radius:10px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
          <span class="input-group-text bg-white border-end-0 text-muted" style="border-color:#e2e8f0;padding-left:14px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          </span>
          <input type="text"
                 name="search"
                 id="zones-live-search"
                 class="form-control border-start-0 border-end-0 py-2 ps-1"
                 style="border-color:#e2e8f0;font-size:14px;"
                 placeholder="Rechercher par nom, ville, gouvernorat… (frappe instantanée)"
                 value="{{ request('search') }}"
                 autocomplete="off">
          @if(request('search'))
            <button type="button" class="btn btn-outline-secondary border-start-0 border-end-0 bg-white"
                    onclick="document.getElementById('zones-live-search').value=''; document.getElementById('zones-search-form').submit();"
                    title="Effacer">✕</button>
          @endif
          <button type="submit" class="btn px-3 fw-semibold text-white" style="background:#0ea5e9;border-color:#0ea5e9;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1" style="vertical-align:-1px;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            Rechercher
          </button>
        </div>
      </div>

      {{-- Bouton reset --}}
      <div class="col-lg-6 col-md-12 d-flex justify-content-lg-end">
        @if(request()->anyFilled(['search', 'actif']))
          <a href="{{ route('admin.zones.index') }}"
             class="btn btn-outline-info btn-sm d-inline-flex align-items-center gap-1 justify-content-center"
             style="border-radius:10px;height:38px;min-width:130px;">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
            Réinitialiser
          </a>
        @endif
      </div>
    </div>

    {{-- Champs cachés pour filtres rapides 1 clic --}}
    <input type="hidden" name="actif" id="zones-actif-hidden" value="{{ request('actif') }}">

    {{-- ── FILTRES RAPIDES EN 1 CLIC ── --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-2 border-top" style="border-color:#f1f5f9 !important;">
      {{-- Pilules Statut --}}
      <div class="d-flex flex-wrap align-items-center gap-1">
        <span class="text-muted small fw-semibold me-1">Statut :</span>
        <button type="button" class="btn btn-sm {{ !request()->filled('actif') ? 'btn-dark' : 'btn-light border text-dark' }}" onclick="selectZoneActifFilter('')">Toutes</button>
        <button type="button" class="btn btn-sm {{ request('actif')==='1' ? 'btn-success text-white fw-bold'   : 'btn-light border text-dark' }}" onclick="selectZoneActifFilter('1')"><i class="fa-solid fa-circle-check me-1"></i>Active</button>
        <button type="button" class="btn btn-sm {{ request('actif')==='0' ? 'btn-secondary text-white'         : 'btn-light border text-dark' }}" onclick="selectZoneActifFilter('0')"><i class="fa-solid fa-circle-xmark me-1"></i>Inactive</button>
      </div>

      <div class="small text-muted" id="zones-results-counter">{{ $zones->total() }} zone(s)</div>
    </div>
  </form>
</div>

<section class="m-card">
  <div class="table-responsive">
    <table class="m-table" id="zones-table">
      <thead>
        <tr>
          <th>Nom</th>
          <th style="text-align:center;">Ville</th>
          <th style="text-align:center;">Gouvernorat</th>
          <th style="text-align:center;">Code postal</th>
          <th style="text-align:center;">Coordonnées</th>
          <th style="text-align:center;">Statut</th>
          <th style="text-align:center;">Actions</th>
        </tr>
      </thead>
      <tbody id="zones-table-body">
        @forelse($zones as $zone)
        <tr class="zone-table-row"
            data-nom="{{ strtolower($zone->nom ?? '') }}"
            data-ville="{{ strtolower($zone->ville ?? '') }}"
            data-gouvernorat="{{ strtolower($zone->gouvernorat ?? '') }}"
            data-code="{{ strtolower($zone->code_postal ?? '') }}"
            data-actif="{{ $zone->actif ? '1' : '0' }}">
          <td><strong>{{ $zone->nom }}</strong></td>
          <td style="text-align:center;">{{ $zone->ville }}</td>
          <td style="text-align:center;">{{ $zone->gouvernorat ?? '—' }}</td>
          <td style="text-align:center;">{{ $zone->code_postal ?? '—' }}</td>
          <td style="text-align:center;">
            @if($zone->latitude && $zone->longitude)
              <small class="text-muted">{{ number_format($zone->latitude, 4) }}, {{ number_format($zone->longitude, 4) }}</small>
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td style="text-align:center;">
            @if($zone->actif)
              <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
            @else
              <span class="badge bg-secondary"><i class="fa-solid fa-circle-xmark me-1"></i>Inactive</span>
            @endif
          </td>
          <td style="text-align:center;white-space:nowrap;">
            <a href="{{ route('admin.zones.show', $zone) }}" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;color:#2563eb;" title="Détails"><i class="fa-solid fa-eye"></i></a>
            <a href="{{ route('admin.zones.edit', $zone) }}" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;" title="Modifier"><i class="fa-solid fa-pen-to-square"></i></a>
            <button type="button" class="m-btn m-btn--ghost js-trigger-delete"
                    style="height:28px;padding:0 10px;font-size:12px;color:#dc3545;"
                    data-action="{{ route('admin.zones.destroy', $zone) }}"
                    data-name="{{ $zone->nom }}"
                    data-type="la zone"
                    title="Supprimer"><i class="fa-solid fa-trash"></i></button>
          </td>
        </tr>
        @empty
        <tr id="zones-no-records">
          <td colspan="7" class="text-center py-4 text-muted">
            <i class="fa-solid fa-map-location-dot fa-2x mb-2 d-block text-secondary"></i>
            Aucune zone trouvée. <a href="{{ route('admin.zones.create') }}">Créez la première</a>.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($zones->hasPages())
  <div class="d-flex flex-column align-items-center mt-3 pb-3 border-top pt-3" style="gap:8px;">
    <div class="text-muted" style="font-size:12.5px;">
      Affichage de <strong>{{ $zones->firstItem() }}</strong> à <strong>{{ $zones->lastItem() }}</strong> sur <strong>{{ $zones->total() }}</strong> zones
    </div>
    <div class="d-flex justify-content-center align-items-center" style="gap:6px;">
      @if(!$zones->onFirstPage())
        <a href="{{ $zones->previousPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;" title="Page précédente">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;"><path d="M15 18l-6-6 6-6"/></svg>
        </a>
      @else
        <span class="m-btn m-btn--ghost disabled" style="height:32px;padding:0 12px;font-size:13px;opacity:0.35;cursor:not-allowed;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;"><path d="M15 18l-6-6 6-6"/></svg>
        </span>
      @endif
      @foreach($zones->getUrlRange(max(1, $zones->currentPage()-2), min($zones->lastPage(), $zones->currentPage()+2)) as $page => $url)
        <a href="{{ $url }}" class="m-btn {{ $page == $zones->currentPage() ? 'm-btn--primary' : 'm-btn--ghost' }}" style="height:32px;padding:0 12px;font-size:13px;min-width:34px;text-align:center;">{{ $page }}</a>
      @endforeach
      @if($zones->hasMorePages())
        <a href="{{ $zones->nextPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;" title="Page suivante">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;"><path d="M9 18l6-6-6-6"/></svg>
        </a>
      @else
        <span class="m-btn m-btn--ghost disabled" style="height:32px;padding:0 12px;font-size:13px;opacity:0.35;cursor:not-allowed;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;"><path d="M9 18l6-6-6-6"/></svg>
        </span>
      @endif
    </div>
  </div>
  @endif
</section>

@push('scripts')
<script>
function selectZoneActifFilter(val) {
  document.getElementById('zones-actif-hidden').value = val;
  document.getElementById('zones-search-form').submit();
}
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('zones-live-search');
  const rows        = document.querySelectorAll('.zone-table-row');
  const counterEl   = document.getElementById('zones-results-counter');
  const tbody       = document.getElementById('zones-table-body');

  if (searchInput && rows.length > 0) {
    searchInput.addEventListener('input', function () {
      const q = this.value.trim().toLowerCase();
      let count = 0;
      rows.forEach(r => {
        const nom        = r.getAttribute('data-nom')         || '';
        const ville      = r.getAttribute('data-ville')       || '';
        const gouvern    = r.getAttribute('data-gouvernorat') || '';
        const code       = r.getAttribute('data-code')        || '';
        const match = nom.includes(q) || ville.includes(q) || gouvern.includes(q) || code.includes(q);
        if (match) { r.style.display = ''; count++; }
        else        { r.style.display = 'none'; }
      });
      if (counterEl) counterEl.textContent = `${count} zone(s) affichée(s)`;
      let emptyRow = document.getElementById('zones-live-empty-row');
      if (count === 0) {
        if (!emptyRow) {
          emptyRow = document.createElement('tr');
          emptyRow.id = 'zones-live-empty-row';
          emptyRow.innerHTML = `<td colspan="7" class="text-center py-4 text-muted"><i class="fa-solid fa-magnifying-glass fa-2x mb-2 d-block text-secondary"></i>Aucune zone ne correspond à « <em>${q}</em> ».</td>`;
          tbody.appendChild(emptyRow);
        } else { emptyRow.style.display = ''; emptyRow.querySelector('em').textContent = q; }
      } else if (emptyRow) { emptyRow.style.display = 'none'; }
    });
  }
});
</script>
@endpush
@endsection