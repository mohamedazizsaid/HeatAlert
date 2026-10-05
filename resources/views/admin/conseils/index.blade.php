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

{{-- ── BARRE DE RECHERCHE AVANCÉE DYNAMIQUE EN 1 CLIC ── --}}
<div class="m-card mb-4 p-3" style="border-radius:14px;background:#ffffff;box-shadow:0 2px 10px rgba(0,0,0,0.03);">
  <form action="{{ route('admin.conseils.index') }}" method="GET" id="conseils-search-form">
    <div class="row g-2 align-items-center mb-3">
      {{-- Input recherche texte --}}
      <div class="col-lg-5 col-md-12">
        <div class="input-group" style="border-radius:10px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
          <span class="input-group-text bg-white border-end-0 text-muted" style="border-color:#e2e8f0;padding-left:14px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          </span>
          <input type="text"
                 name="search"
                 id="conseils-live-search"
                 class="form-control border-start-0 border-end-0 py-2 ps-1"
                 style="border-color:#e2e8f0;font-size:14px;"
                 placeholder="Rechercher par titre ou contenu… (frappe instantanée)"
                 value="{{ request('search') }}"
                 autocomplete="off">
          @if(request('search'))
            <button type="button" class="btn btn-outline-secondary border-start-0 border-end-0 bg-white"
                    onclick="document.getElementById('conseils-live-search').value=''; document.getElementById('conseils-search-form').submit();"
                    title="Effacer">✕</button>
          @endif
          <button type="submit" class="btn px-3 fw-semibold text-white" style="background:#f59e0b;border-color:#f59e0b;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1" style="vertical-align:-1px;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            Rechercher
          </button>
        </div>
      </div>

      {{-- Sélection Catégorie --}}
      <div class="col-lg-3 col-md-5">
        <select name="categorie" id="conseils-categorie-select" class="form-select"
                style="border-color:#e2e8f0;font-size:13.5px;border-radius:10px;"
                onchange="document.getElementById('conseils-search-form').submit();">
          <option value="">— Toutes les catégories —</option>
          @foreach(['hydratation' => '💧 Hydratation', 'energie' => '⚡ Énergie', 'equipements' => '🛡️ Équipements', 'sante' => '🩺 Santé', 'habitat' => '🏠 Habitat', 'deplacement' => '🚗 Déplacement'] as $val => $label)
            <option value="{{ $val }}" {{ request('categorie') === $val ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>

      {{-- Bouton reset --}}
      <div class="col-lg-4 col-md-7 d-flex justify-content-lg-end">
        @if(request()->anyFilled(['search', 'categorie', 'niveau', 'actif']))
          <a href="{{ route('admin.conseils.index') }}"
             class="btn btn-outline-warning btn-sm d-inline-flex align-items-center gap-1 justify-content-center"
             style="border-radius:10px;height:38px;min-width:130px;">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
            Réinitialiser
          </a>
        @endif
      </div>
    </div>

    {{-- Champs cachés pour filtres rapides 1 clic --}}
    <input type="hidden" name="niveau" id="conseils-niveau-hidden" value="{{ request('niveau') }}">
    <input type="hidden" name="actif"  id="conseils-actif-hidden"  value="{{ request('actif')  }}">

    {{-- ── FILTRES RAPIDES EN 1 CLIC ── --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-2 border-top" style="border-color:#f1f5f9 !important;">
      {{-- Pilules Niveau --}}
      <div class="d-flex flex-wrap align-items-center gap-1">
        <span class="text-muted small fw-semibold me-1">Niveau :</span>
        <button type="button" class="btn btn-sm {{ !request('niveau') ? 'btn-dark' : 'btn-light border text-dark' }}" onclick="selectConseilNiveauFilter('')">Tous</button>
        <button type="button" class="btn btn-sm {{ request('niveau')==='rouge'  ? 'btn-danger text-white fw-bold'  : 'btn-light border text-dark' }}" onclick="selectConseilNiveauFilter('rouge')">🔴 Rouge</button>
        <button type="button" class="btn btn-sm {{ request('niveau')==='orange' ? 'btn-warning text-white fw-bold' : 'btn-light border text-dark' }}" onclick="selectConseilNiveauFilter('orange')" style="{{ request('niveau')==='orange' ? 'background:#ea580c;border-color:#ea580c;' : '' }}">🟠 Orange</button>
        <button type="button" class="btn btn-sm {{ request('niveau')==='jaune'  ? 'btn-warning text-dark fw-bold'  : 'btn-light border text-dark' }}" onclick="selectConseilNiveauFilter('jaune')">🟡 Jaune</button>
        <button type="button" class="btn btn-sm {{ request('niveau')==='vert'   ? 'btn-success text-white'         : 'btn-light border text-dark' }}" onclick="selectConseilNiveauFilter('vert')">🟢 Vert</button>
      </div>

      {{-- Pilules Statut --}}
      <div class="d-flex flex-wrap align-items-center gap-1">
        <span class="text-muted small fw-semibold me-1">Statut :</span>
        <button type="button" class="btn btn-sm {{ !request()->filled('actif') ? 'btn-dark' : 'btn-light border text-dark' }}" onclick="selectConseilActifFilter('')">Tous</button>
        <button type="button" class="btn btn-sm {{ request('actif')==='1' ? 'btn-success text-white fw-bold'   : 'btn-light border text-dark' }}" onclick="selectConseilActifFilter('1')"><i class="fa-solid fa-circle-check me-1"></i>Actif</button>
        <button type="button" class="btn btn-sm {{ request('actif')==='0' ? 'btn-secondary text-white'         : 'btn-light border text-dark' }}" onclick="selectConseilActifFilter('0')"><i class="fa-solid fa-circle-xmark me-1"></i>Inactif</button>
      </div>

      <div class="small text-muted" id="conseils-results-counter">{{ $conseils->total() }} conseil(s)</div>
    </div>
  </form>
</div>

<section class="m-card">
  <div class="table-responsive">
    <table class="m-table" id="conseils-table">
      <thead>
        <tr>
          <th>Titre</th>
          <th style="text-align:center;">Catégorie</th>
          <th style="text-align:center;">Niveau cible</th>
          <th style="text-align:center;">Icône</th>
          <th style="text-align:center;">Statut</th>
          <th style="text-align:center;">Actions</th>
        </tr>
      </thead>
      <tbody id="conseils-table-body">
        @forelse($conseils as $conseil)
        <tr class="conseil-table-row"
            data-titre="{{ strtolower($conseil->titre ?? '') }}"
            data-contenu="{{ strtolower($conseil->contenu ?? '') }}"
            data-categorie="{{ strtolower($conseil->categorie ?? '') }}"
            data-niveau="{{ $conseil->niveau_alerte_cible ?? '' }}"
            data-actif="{{ $conseil->actif ? '1' : '0' }}">
          <td>
            <strong>{{ Str::limit($conseil->titre, 50) }}</strong>
            <br>
            <small class="text-muted">{{ Str::limit($conseil->contenu, 60) }}</small>
          </td>
          <td style="text-align:center;">
            <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $conseil->categorie)) }}</span>
          </td>
          <td style="text-align:center;">
            @if($conseil->niveau_alerte_cible)
              @php
                $niveauClass = match($conseil->niveau_alerte_cible) {
                  'rouge'  => 'bg-danger text-white',
                  'orange' => 'bg-warning text-dark',
                  'jaune'  => 'bg-info text-dark',
                  default  => 'bg-success text-white',
                };
              @endphp
              <span class="badge {{ $niveauClass }}">{{ ucfirst($conseil->niveau_alerte_cible) }}</span>
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td style="text-align:center;">
            @if($conseil->icone)
              <i class="{{ $conseil->icone }}"></i>
              <small class="text-muted ms-1">{{ $conseil->icone }}</small>
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td style="text-align:center;">
            @if($conseil->actif)
              <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>Actif</span>
            @else
              <span class="badge bg-secondary"><i class="fa-solid fa-circle-xmark me-1"></i>Inactif</span>
            @endif
          </td>
          <td style="text-align:center;white-space:nowrap;">
            <a href="{{ route('admin.conseils.show', $conseil) }}" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;color:#2563eb;" title="Détails"><i class="fa-solid fa-eye"></i></a>
            <a href="{{ route('admin.conseils.edit', $conseil) }}" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;" title="Modifier"><i class="fa-solid fa-pen-to-square"></i></a>
            <button type="button" class="m-btn m-btn--ghost js-trigger-delete"
                    style="height:28px;padding:0 10px;font-size:12px;color:#dc3545;"
                    data-action="{{ route('admin.conseils.destroy', $conseil) }}"
                    data-name="{{ $conseil->titre }}"
                    data-type="le conseil"
                    title="Supprimer"><i class="fa-solid fa-trash"></i></button>
          </td>
        </tr>
        @empty
        <tr id="conseils-no-records">
          <td colspan="6" class="text-center py-4 text-muted">
            <i class="fa-solid fa-lightbulb fa-2x mb-2 d-block text-secondary"></i>
            Aucun conseil trouvé. <a href="{{ route('admin.conseils.create') }}">Créez le premier</a>.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($conseils->hasPages())
  <div class="d-flex flex-column align-items-center mt-3 pb-3 border-top pt-3" style="gap:8px;">
    <div class="text-muted" style="font-size:12.5px;">
      Affichage de <strong>{{ $conseils->firstItem() }}</strong> à <strong>{{ $conseils->lastItem() }}</strong> sur <strong>{{ $conseils->total() }}</strong> conseils
    </div>
    <div class="d-flex justify-content-center align-items-center" style="gap:6px;">
      @if(!$conseils->onFirstPage())
        <a href="{{ $conseils->previousPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;" title="Page précédente">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;"><path d="M15 18l-6-6 6-6"/></svg>
        </a>
      @else
        <span class="m-btn m-btn--ghost disabled" style="height:32px;padding:0 12px;font-size:13px;opacity:0.35;cursor:not-allowed;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;"><path d="M15 18l-6-6 6-6"/></svg>
        </span>
      @endif
      @foreach($conseils->getUrlRange(max(1, $conseils->currentPage()-2), min($conseils->lastPage(), $conseils->currentPage()+2)) as $page => $url)
        <a href="{{ $url }}" class="m-btn {{ $page == $conseils->currentPage() ? 'm-btn--primary' : 'm-btn--ghost' }}" style="height:32px;padding:0 12px;font-size:13px;min-width:34px;text-align:center;">{{ $page }}</a>
      @endforeach
      @if($conseils->hasMorePages())
        <a href="{{ $conseils->nextPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;" title="Page suivante">
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
function selectConseilNiveauFilter(val) {
  document.getElementById('conseils-niveau-hidden').value = val;
  document.getElementById('conseils-search-form').submit();
}
function selectConseilActifFilter(val) {
  document.getElementById('conseils-actif-hidden').value = val;
  document.getElementById('conseils-search-form').submit();
}
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('conseils-live-search');
  const rows        = document.querySelectorAll('.conseil-table-row');
  const counterEl   = document.getElementById('conseils-results-counter');
  const tbody       = document.getElementById('conseils-table-body');

  if (searchInput && rows.length > 0) {
    searchInput.addEventListener('input', function () {
      const q = this.value.trim().toLowerCase();
      let count = 0;
      rows.forEach(r => {
        const titre     = r.getAttribute('data-titre')    || '';
        const contenu   = r.getAttribute('data-contenu')  || '';
        const categorie = r.getAttribute('data-categorie')|| '';
        const niveau    = r.getAttribute('data-niveau')   || '';
        const match = titre.includes(q) || contenu.includes(q) || categorie.includes(q) || niveau.includes(q);
        if (match) { r.style.display = ''; count++; }
        else        { r.style.display = 'none'; }
      });
      if (counterEl) counterEl.textContent = `${count} conseil(s) affiché(s)`;
      let emptyRow = document.getElementById('conseils-live-empty-row');
      if (count === 0) {
        if (!emptyRow) {
          emptyRow = document.createElement('tr');
          emptyRow.id = 'conseils-live-empty-row';
          emptyRow.innerHTML = `<td colspan="6" class="text-center py-4 text-muted"><i class="fa-solid fa-magnifying-glass fa-2x mb-2 d-block text-secondary"></i>Aucun conseil ne correspond à « <em>${q}</em> ».</td>`;
          tbody.appendChild(emptyRow);
        } else { emptyRow.style.display = ''; emptyRow.querySelector('em').textContent = q; }
      } else if (emptyRow) { emptyRow.style.display = 'none'; }
    });
  }
});
</script>
@endpush
@endsection