@extends('layouts.admin.admin')

@section('title', 'Points de Fraîcheur')

@section('content')
<div class="page-header">
  <div>
    <h1><i class="fa-solid fa-snowflake me-2 text-info"></i>Gestion des Points de Fraîcheur</h1>
    <p class="subtitle">{{ $points->total() }} point(s) de fraîcheur enregistré(s).</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('admin.avis_points.index') }}" class="m-btn m-btn--ghost" style="border:1px solid #0ea5e9;color:#0ea5e9;">
      <i class="fa-solid fa-star-half-stroke me-1"></i> Supervision des avis
    </a>
    <a href="{{ route('admin.points_fraicheur.create') }}" class="m-btn m-btn--primary">
      <i class="fa-solid fa-plus me-1"></i> Nouveau point
    </a>
  </div>
</div>

{{-- ── BARRE DE RECHERCHE AVANCÉE DYNAMIQUE EN 1 CLIC ── --}}
<div class="m-card mb-4 p-3" style="border-radius:14px;background:#ffffff;box-shadow:0 2px 10px rgba(0,0,0,0.03);">
  <form action="{{ route('admin.points_fraicheur.index') }}" method="GET" id="pf-search-form">
    {{-- Champ de recherche principal avec bouton cliquable --}}
    <div class="row g-2 align-items-center mb-3">
      <div class="col-lg-6 col-md-12">
        <div class="input-group" style="border-radius:10px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
          <span class="input-group-text bg-white border-end-0 text-muted" style="border-color:#e2e8f0;padding-left:14px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          </span>
          <input type="text"
                 name="search"
                 id="pf-live-search"
                 class="form-control border-start-0 border-end-0 py-2 ps-1"
                 style="border-color:#e2e8f0;font-size:14px;"
                 placeholder="Rechercher par nom, adresse, ville… (frappe instantanée)"
                 value="{{ request('search') }}"
                 autocomplete="off">
          @if(request('search'))
            <button type="button" class="btn btn-outline-secondary border-start-0 border-end-0 bg-white" onclick="document.getElementById('pf-live-search').value=''; document.getElementById('pf-search-form').submit();" title="Effacer la recherche">
              ✕
            </button>
          @endif
          <button type="submit" class="btn btn-primary px-3 fw-semibold" style="background:#0ea5e9;border-color:#0ea5e9;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1" style="vertical-align:-1px;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            Rechercher
          </button>
        </div>
      </div>

      {{-- Filtre Zone --}}
      <div class="col-lg-3 col-md-6">
        <select name="zone_id" id="pf-zone-select" class="form-select" style="border-color:#e2e8f0;font-size:13.5px;border-radius:10px;" onchange="document.getElementById('pf-search-form').submit();">
          <option value="">— Toutes les zones —</option>
          @foreach($zones as $z)
            <option value="{{ $z->id }}" {{ request('zone_id') == $z->id ? 'selected' : '' }}>
              {{ $z->nom }}{{ $z->gouvernorat ? ' (' . $z->gouvernorat . ')' : '' }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Bouton Réinitialiser --}}
      <div class="col-lg-3 col-md-6 d-flex justify-content-lg-end gap-2">
        @if(request()->anyFilled(['search', 'type', 'zone_id', 'actif', 'pmr']))
          <a href="{{ route('admin.points_fraicheur.index') }}" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1" style="border-radius:10px;height:38px;padding:0 14px;">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
            Réinitialiser
          </a>
        @endif
      </div>
    </div>

    {{-- Champs cachés pour filtres rapides en 1 clic --}}
    <input type="hidden" name="type" id="pf-type-hidden" value="{{ request('type') }}">
    <input type="hidden" name="pmr" id="pf-pmr-hidden" value="{{ request('pmr') }}">
    <input type="hidden" name="actif" id="pf-actif-hidden" value="{{ request('actif') }}">

    {{-- ── FILTRES RAPIDES EN 1 CLIC (CHIPS) ── --}}
    <div class="d-flex flex-wrap align-items-center gap-2 pt-2 border-top" style="border-color:#f1f5f9 !important;">
      <span class="text-muted small fw-semibold me-1">Types :</span>
      <button type="button" class="btn btn-sm pf-chip {{ !request('type') ? 'btn-dark' : 'btn-light border text-dark' }}" data-filter-type="" onclick="selectTypeFilter('')">
        Tous
      </button>
      <button type="button" class="btn btn-sm pf-chip {{ request('type')==='parc' ? 'btn-success text-white' : 'btn-light border text-dark' }}" data-filter-type="parc" onclick="selectTypeFilter('parc')">
        <i class="fa-solid fa-tree me-1 text-success"></i>Parcs & Espaces verts
      </button>
      <button type="button" class="btn btn-sm pf-chip {{ request('type')==='salle_climatisee' ? 'btn-primary text-white' : 'btn-light border text-dark' }}" data-filter-type="salle_climatisee" onclick="selectTypeFilter('salle_climatisee')">
        <i class="fa-solid fa-snowflake me-1 text-primary"></i>Salles climatisées
      </button>
      <button type="button" class="btn btn-sm pf-chip {{ request('type')==='fontaine' ? 'btn-info text-white' : 'btn-light border text-dark' }}" data-filter-type="fontaine" onclick="selectTypeFilter('fontaine')">
        <i class="fa-solid fa-droplet me-1 text-info"></i>Fontaines / Bornes
      </button>
      <button type="button" class="btn btn-sm pf-chip {{ request('type')==='piscine' ? 'btn-secondary text-white' : 'btn-light border text-dark' }}" data-filter-type="piscine" onclick="selectTypeFilter('piscine')">
        <i class="fa-solid fa-person-swimming me-1"></i>Piscines
      </button>
      <button type="button" class="btn btn-sm pf-chip {{ request('type')==='bibliotheque' ? 'btn-dark text-white' : 'btn-light border text-dark' }}" data-filter-type="bibliotheque" onclick="selectTypeFilter('bibliotheque')">
        <i class="fa-solid fa-book me-1"></i>Bibliothèques
      </button>
      <span class="text-muted small fw-semibold ms-lg-2 me-1">Accessibilité :</span>
      <button type="button" class="btn btn-sm pf-chip {{ request('pmr') ? 'btn-success text-white' : 'btn-light border text-dark' }}" onclick="togglePmrFilter()">
        <i class="fa-solid fa-wheelchair me-1 {{ request('pmr') ? 'text-white' : 'text-primary' }}"></i>Accessible PMR
      </button>

      <button type="button" class="btn btn-sm pf-chip {{ request('actif') === '1' ? 'btn-success text-white' : 'btn-light border text-dark' }}" onclick="toggleActifFilter()">
        <i class="fa-solid fa-circle-check me-1 {{ request('actif')==='1' ? 'text-white' : 'text-success' }}"></i>Actifs uniquement
      </button>

      <div class="ms-auto small text-muted d-none d-md-block" id="pf-results-counter">
        {{ $points->total() }} résultat(s)
      </div>
    </div>
  </form>
</div>

<section class="m-card">
  <div class="table-responsive">
    <table class="m-table" id="pf-table">
      <thead>
        <tr>
          <th>Nom / Adresse</th>
          <th style="text-align:center;">Type</th>
          <th style="text-align:center;">Zone</th>
          <th style="text-align:center;">PMR</th>
          <th style="text-align:center;">Avis</th>
          <th style="text-align:center;">Note moy.</th>
          <th style="text-align:center;">Statut</th>
          <th style="text-align:center;">Actions</th>
        </tr>
      </thead>
      <tbody id="pf-table-body">
        @forelse($points as $point)
        <tr class="pf-table-row" data-name="{{ strtolower($point->nom) }}" data-adresse="{{ strtolower($point->adresse) }}" data-type="{{ $point->type }}" data-pmr="{{ $point->accessible_pmr ? '1' : '0' }}" data-actif="{{ $point->actif ? '1' : '0' }}" data-zone="{{ $point->zone_id }}">
          <td>
            <a href="{{ route('admin.points_fraicheur.show', $point) }}" class="fw-bold text-dark text-decoration-none">
              {{ $point->nom }}
            </a>
            <br><small class="text-muted"><i class="fa-solid fa-location-dot me-1 text-danger"></i>{{ Str::limit($point->adresse, 45) }}</small>
          </td>
          <td style="text-align:center;">
            @php
              $icon  = \App\Models\PointFraicheur::$typeIcons[$point->type]  ?? 'fa-location-dot';
              $color = \App\Models\PointFraicheur::$typeColors[$point->type] ?? '#64748b';
              $label = \App\Models\PointFraicheur::$typeLabels[$point->type] ?? ucfirst($point->type);
            @endphp
            <span class="badge rounded-pill" style="background:{{ $color }}20;color:{{ $color }};border:1px solid {{ $color }}40;font-size:.75rem;">
              <i class="fa-solid {{ $icon }} me-1"></i>{{ $label }}
            </span>
          </td>
          <td style="text-align:center;">
            {{ $point->zone?->nom ?? '—' }}
          </td>
          <td style="text-align:center;">
            @if($point->accessible_pmr)
              <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="fa-solid fa-wheelchair me-1"></i>Oui</span>
            @else
              <span class="badge bg-light text-muted border">Non</span>
            @endif
          </td>
          <td style="text-align:center;">
            <a href="{{ route('admin.avis_points.index', ['point_fraicheur_id' => $point->id]) }}" class="badge bg-light text-dark border text-decoration-none" title="Voir les avis de ce point">
              {{ $point->avis_points_count ?? 0 }}
            </a>
          </td>
          <td style="text-align:center;">
            @php $moy = $point->avis_points_avg_note ?? 0; @endphp
            @if($moy > 0)
              <span style="color:#f59e0b;font-weight:700;">
                @for($s = 1; $s <= 5; $s++)
                  <i class="fa-{{ $s <= round($moy) ? 'solid' : 'regular' }} fa-star" style="font-size:12px;"></i>
                @endfor
                <span class="ms-1 text-muted" style="font-size:.78rem;">{{ number_format($moy, 1) }}</span>
              </span>
            @else
              <span class="text-muted" style="font-size:.8rem;">—</span>
            @endif
          </td>
          <td style="text-align:center;">
            @if($point->actif)
              <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>Actif</span>
            @else
              <span class="badge bg-secondary"><i class="fa-solid fa-circle-xmark me-1"></i>Inactif</span>
            @endif
          </td>
          <td style="text-align:center;">
            <a href="{{ route('admin.points_fraicheur.show', $point) }}" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;color:#2563eb;" title="Détails">
              <i class="fa-solid fa-eye"></i>
            </a>
            <a href="{{ route('admin.points_fraicheur.edit', $point) }}" class="m-btn m-btn--ghost" style="height:28px;padding:0 10px;font-size:12px;" title="Modifier">
              <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <button type="button" class="m-btn m-btn--ghost js-trigger-delete"
                    style="height:28px;padding:0 10px;font-size:12px;color:#dc3545;"
                    data-action="{{ route('admin.points_fraicheur.destroy', $point) }}"
                    data-name="{{ $point->nom }}"
                    data-type="le point de fraîcheur"
                    title="Supprimer">
              <i class="fa-solid fa-trash"></i>
            </button>
          </td>
        </tr>
        @empty
        <tr id="pf-no-records">
          <td colspan="8" class="text-center py-4 text-muted">
            <i class="fa-solid fa-snowflake fa-2x mb-2 d-block text-info"></i>
            Aucun point de fraîcheur trouvé. <a href="{{ route('admin.points_fraicheur.create') }}">Créez le premier</a>.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- ── PAGINATION AVEC ICÔNES CHEVRON SVG ET ÉTAT DÉSACTIVÉ GARANTI ── --}}
  @if($points->hasPages())
  <div class="d-flex flex-column align-items-center mt-3 pb-3 border-top pt-3" style="gap:8px;">
    <div class="text-muted" style="font-size:12.5px;">
      Affichage de <strong>{{ $points->firstItem() }}</strong> à <strong>{{ $points->lastItem() }}</strong> sur <strong>{{ $points->total() }}</strong> points de fraîcheur
    </div>
    <div class="d-flex justify-content-center align-items-center" style="gap:6px;">
      {{-- Bouton Page Précédente --}}
      @if($points->onFirstPage())
        <span class="m-btn m-btn--ghost disabled" style="height:32px;padding:0 12px;font-size:13px;opacity:0.35;cursor:not-allowed;" title="Page précédente">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;"><path d="M15 18l-6-6 6-6"/></svg>
        </span>
      @else
        <a href="{{ $points->previousPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;" title="Page précédente">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;"><path d="M15 18l-6-6 6-6"/></svg>
        </a>
      @endif

      {{-- Numéros de page --}}
      @foreach($points->getUrlRange(max(1, $points->currentPage()-2), min($points->lastPage(), $points->currentPage()+2)) as $page => $url)
        <a href="{{ $url }}" class="m-btn {{ $page == $points->currentPage() ? 'm-btn--primary' : 'm-btn--ghost' }}" style="height:32px;padding:0 12px;font-size:13px;min-width:34px;text-align:center;">
          {{ $page }}
        </a>
      @endforeach

      {{-- Bouton Page Suivante --}}
      @if($points->hasMorePages())
        <a href="{{ $points->nextPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;" title="Page suivante">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;"><path d="M9 18l6-6-6-6"/></svg>
        </a>
      @else
        <span class="m-btn m-btn--ghost disabled" style="height:32px;padding:0 12px;font-size:13px;opacity:0.35;cursor:not-allowed;" title="Page suivante">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;"><path d="M9 18l6-6-6-6"/></svg>
        </span>
      @endif
    </div>
  </div>
  @endif
</section>

@push('scripts')
<script>
// ─── Fonctions pour filtres rapides en 1 clic ──────────────────────────────────
function selectTypeFilter(typeVal) {
  document.getElementById('pf-type-hidden').value = typeVal;
  document.getElementById('pf-search-form').submit();
}

function togglePmrFilter() {
  const input = document.getElementById('pf-pmr-hidden');
  input.value = input.value === '1' ? '' : '1';
  document.getElementById('pf-search-form').submit();
}

function toggleActifFilter() {
  const input = document.getElementById('pf-actif-hidden');
  input.value = input.value === '1' ? '' : '1';
  document.getElementById('pf-search-form').submit();
}

// ─── Recherche dynamique instantanée (Live table filter en JS) ─────────────────
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('pf-live-search');
  const rows = document.querySelectorAll('.pf-table-row');
  const counterEl = document.getElementById('pf-results-counter');
  const tbody = document.getElementById('pf-table-body');

  if (!searchInput || rows.length === 0) return;

  searchInput.addEventListener('input', function() {
    const q = this.value.trim().toLowerCase();
    let visibleCount = 0;

    rows.forEach(row => {
      const nom = row.getAttribute('data-name') || '';
      const adr = row.getAttribute('data-adresse') || '';
      const match = nom.includes(q) || adr.includes(q);

      if (match) {
        row.style.display = '';
        visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });

    if (counterEl) {
      counterEl.textContent = `${visibleCount} point(s) affiché(s)`;
    }

    let emptyRow = document.getElementById('pf-live-empty-row');
    if (visibleCount === 0) {
      if (!emptyRow) {
        emptyRow = document.createElement('tr');
        emptyRow.id = 'pf-live-empty-row';
        emptyRow.innerHTML = `<td colspan="8" class="text-center py-4 text-muted"><i class="fa-solid fa-magnifying-glass fa-2x mb-2 d-block text-secondary"></i>Aucun point de fraîcheur ne correspond à « <em>${q}</em> ».</td>`;
        tbody.appendChild(emptyRow);
      } else {
        emptyRow.style.display = '';
        emptyRow.querySelector('em').textContent = q;
      }
    } else if (emptyRow) {
      emptyRow.style.display = 'none';
    }
  });
});
</script>
@endpush
@endsection
