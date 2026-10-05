@extends('layouts.admin.admin')

@section('title', 'Alertes Météo')

@section('content')
<div class="page-header">
  <div>
    <h1><i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i>Gestion des Alertes Météo</h1>
    <p class="subtitle">{{ $alertes->total() }} alerte(s) enregistrée(s).</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('admin.alertes.create') }}" class="m-btn m-btn--primary">
      <i class="fa-solid fa-plus"></i> Nouvelle alerte
    </a>
  </div>
</div>

{{-- ── BARRE DE RECHERCHE AVANCÉE DYNAMIQUE EN 1 CLIC ── --}}
<div class="m-card mb-4 p-3" style="border-radius:14px;background:#ffffff;box-shadow:0 2px 10px rgba(0,0,0,0.03);">
  <form action="{{ route('admin.alertes.index') }}" method="GET" id="alertes-search-form">
    <div class="row g-2 align-items-center mb-3">
      {{-- Input recherche texte avec bouton cliquable --}}
      <div class="col-lg-5 col-md-12">
        <div class="input-group" style="border-radius:10px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
          <span class="input-group-text bg-white border-end-0 text-muted" style="border-color:#e2e8f0;padding-left:14px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          </span>
          <input type="text"
                 name="search"
                 id="alertes-live-search"
                 class="form-control border-start-0 border-end-0 py-2 ps-1"
                 style="border-color:#e2e8f0;font-size:14px;"
                 placeholder="Rechercher par titre, description, ville… (frappe instantanée)"
                 value="{{ request('search') }}"
                 autocomplete="off">
          @if(request('search'))
            <button type="button" class="btn btn-outline-secondary border-start-0 border-end-0 bg-white" onclick="document.getElementById('alertes-live-search').value=''; document.getElementById('alertes-search-form').submit();" title="Effacer la recherche">
              ✕
            </button>
          @endif
          <button type="submit" class="btn btn-danger px-3 fw-semibold text-white" style="background:#dc2626;border-color:#dc2626;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1" style="vertical-align:-1px;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            Rechercher
          </button>
        </div>
      </div>

      {{-- Sélection Zone --}}
      <div class="col-lg-3 col-md-5">
        <select name="zone_id" id="alertes-zone-select" class="form-select" style="border-color:#e2e8f0;font-size:13.5px;border-radius:10px;" onchange="document.getElementById('alertes-search-form').submit();">
          <option value="">— Toutes les zones —</option>
          @foreach($zones as $z)
            <option value="{{ $z->id }}" {{ request('zone_id') == $z->id ? 'selected' : '' }}>
              {{ $z->ville }} ({{ $z->gouvernorat }})
            </option>
          @endforeach
        </select>
      </div>

      {{-- Sélection Type aléa --}}
      <div class="col-lg-2 col-md-4">
        <select name="type" id="alertes-type-select" class="form-select" style="border-color:#e2e8f0;font-size:13.5px;border-radius:10px;" onchange="document.getElementById('alertes-search-form').submit();">
          <option value="">— Tous les types —</option>
          @foreach($types as $t)
            <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>
              {{ ucfirst(str_replace('_', ' ', $t)) }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Bouton reset --}}
      <div class="col-lg-2 col-md-3 d-flex justify-content-lg-end">
        @if(request()->anyFilled(['search', 'zone_id', 'type', 'niveau', 'statut']))
          <a href="{{ route('admin.alertes.index') }}" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1 w-100 justify-content-center" style="border-radius:10px;height:38px;">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
            Réinitialiser
          </a>
        @endif
      </div>
    </div>

    {{-- Champs cachés pour filtres rapides en 1 clic --}}
    <input type="hidden" name="niveau" id="alertes-niveau-hidden" value="{{ request('niveau') }}">
    <input type="hidden" name="statut" id="alertes-statut-hidden" value="{{ request('statut') }}">

    {{-- ── FILTRES RAPIDES PAR NIVEAU & STATUT EN 1 CLIC ── --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-2 border-top" style="border-color:#f1f5f9 !important;">
      {{-- Pilules Niveau --}}
      <div class="d-flex flex-wrap align-items-center gap-1">
        <span class="text-muted small fw-semibold me-1">Niveau :</span>
        <button type="button" class="btn btn-sm {{ !request('niveau') ? 'btn-dark' : 'btn-light border text-dark' }}" onclick="selectNiveauFilter('')">
          Tous
        </button>
        <button type="button" class="btn btn-sm {{ request('niveau')==='rouge' ? 'btn-danger text-white fw-bold' : 'btn-light border text-dark' }}" onclick="selectNiveauFilter('rouge')">
          🔴 Rouge
        </button>
        <button type="button" class="btn btn-sm {{ request('niveau')==='orange' ? 'btn-warning text-dark fw-bold' : 'btn-light border text-dark' }}" onclick="selectNiveauFilter('orange')" style="background:{{ request('niveau')==='orange' ? '#ea580c' : '' }};color:{{ request('niveau')==='orange' ? '#fff' : '' }};">
          🟠 Orange
        </button>
        <button type="button" class="btn btn-sm {{ request('niveau')==='jaune' ? 'btn-warning text-dark fw-bold' : 'btn-light border text-dark' }}" onclick="selectNiveauFilter('jaune')">
          🟡 Jaune
        </button>
        <button type="button" class="btn btn-sm {{ request('niveau')==='vert' ? 'btn-success text-white' : 'btn-light border text-dark' }}" onclick="selectNiveauFilter('vert')">
          🟢 Vert
        </button>
      </div>

      {{-- Pilules Statut --}}
      <div class="d-flex flex-wrap align-items-center gap-1">
        <span class="text-muted small fw-semibold me-1">Statut :</span>
        <button type="button" class="btn btn-sm {{ !request('statut') ? 'btn-dark' : 'btn-light border text-dark' }}" onclick="selectStatutFilter('')">
          Tous
        </button>
        <button type="button" class="btn btn-sm {{ request('statut')==='active' ? 'btn-success text-white fw-bold' : 'btn-light border text-dark' }}" onclick="selectStatutFilter('active')">
          <i class="fa-solid fa-circle-check me-1"></i>Active
        </button>
        <button type="button" class="btn btn-sm {{ request('statut')==='brouillon' ? 'btn-secondary text-white' : 'btn-light border text-dark' }}" onclick="selectStatutFilter('brouillon')">
          <i class="fa-solid fa-pen-ruler me-1"></i>Brouillon
        </button>
        <button type="button" class="btn btn-sm {{ request('statut')==='terminee' ? 'btn-dark text-white' : 'btn-light border text-dark' }}" onclick="selectStatutFilter('terminee')">
          Terminée
        </button>
        <button type="button" class="btn btn-sm {{ request('statut')==='annulee' ? 'btn-danger text-white' : 'btn-light border text-dark' }}" onclick="selectStatutFilter('annulee')">
          Annulée
        </button>
      </div>

      <div class="small text-muted" id="alertes-results-counter">
        {{ $alertes->total() }} alerte(s)
      </div>
    </div>
  </form>
</div>

<section class="m-card">
  <div class="table-responsive">
    <table class="m-table" id="alertes-table">
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
      <tbody id="alertes-table-body">
        @forelse($alertes as $alerte)
        @php
          $niveauClass = match($alerte->niveau) {
            'rouge'  => 'bg-danger text-white',
            'orange' => 'bg-warning text-dark',
            'jaune'  => 'bg-info text-dark',
            default  => 'bg-success text-white'
          };
          $statutClass = match($alerte->statut) {
            'active'    => 'bg-success text-white',
            'brouillon' => 'bg-secondary text-white',
            'terminee'  => 'bg-dark text-white',
            default     => 'bg-danger text-white'
          };
        @endphp
        <tr class="alerte-table-row"
            data-titre="{{ strtolower($alerte->titre ?? '') }}"
            data-desc="{{ strtolower($alerte->description ?? '') }}"
            data-zone="{{ strtolower(($alerte->zone?->nom ?? '') . ' ' . ($alerte->zone?->ville ?? '') . ' ' . ($alerte->zone?->gouvernorat ?? '')) }}"
            data-type="{{ strtolower($alerte->type ?? '') }}"
            data-niveau="{{ $alerte->niveau }}"
            data-statut="{{ $alerte->statut }}">
          <td>
            <strong>{{ Str::limit($alerte->titre, 40) }}</strong>
            @if($alerte->risque_coupure)
              <span class="badge bg-warning text-dark ms-1" title="Risque coupure électrique"><i class="fa-solid fa-bolt"></i></span>
            @endif
            @if($alerte->description)
              <small class="d-block text-muted">{{ Str::limit($alerte->description, 50) }}</small>
            @endif
          </td>
          <td style="text-align:center;">{{ $alerte->zone?->ville ?? $alerte->zone?->nom ?? '—' }}</td>
          <td style="text-align:center;"><span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$alerte->type)) }}</span></td>
          <td style="text-align:center;"><span class="badge {{ $niveauClass }}">{{ ucfirst($alerte->niveau) }}</span></td>
          <td style="text-align:center;"><span class="badge {{ $statutClass }}">{{ ucfirst($alerte->statut) }}</span></td>
          <td style="text-align:center;">{{ $alerte->temperature_max !== null ? $alerte->temperature_max . '°C' : '—' }}</td>
          <td style="text-align:center;">{{ $alerte->date_debut?->format('d/m/Y H:i') }}</td>
          <td style="text-align:center; white-space:nowrap;">
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
        <tr id="alertes-no-records">
          <td colspan="8" class="text-center py-4 text-muted">
            <i class="fa-solid fa-triangle-exclamation fa-2x mb-2 d-block text-secondary"></i>
            Aucune alerte météo trouvée. <a href="{{ route('admin.alertes.create') }}">Créez la première</a>.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- ── PAGINATION AVEC ICÔNES CHEVRON SVG ET ÉTAT DÉSACTIVÉ GARANTI ── --}}
  @if($alertes->hasPages())
  <div class="d-flex flex-column align-items-center mt-3 pb-3 border-top pt-3" style="gap:8px;">
    <div class="text-muted" style="font-size:12.5px;">
      Affichage de <strong>{{ $alertes->firstItem() }}</strong> à <strong>{{ $alertes->lastItem() }}</strong> sur <strong>{{ $alertes->total() }}</strong> alertes
    </div>
    <div class="d-flex justify-content-center align-items-center" style="gap:6px;">
      @if(!$alertes->onFirstPage())
        <a href="{{ $alertes->previousPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;" title="Page précédente">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;"><path d="M15 18l-6-6 6-6"/></svg>
        </a>
      @else
        <span class="m-btn m-btn--ghost disabled" style="height:32px;padding:0 12px;font-size:13px;opacity:0.35;cursor:not-allowed;" title="Page précédente">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;"><path d="M15 18l-6-6 6-6"/></svg>
        </span>
      @endif

      @foreach($alertes->getUrlRange(max(1, $alertes->currentPage() - 2), min($alertes->lastPage(), $alertes->currentPage() + 2)) as $page => $url)
        <a href="{{ $url }}" class="m-btn {{ $page == $alertes->currentPage() ? 'm-btn--primary' : 'm-btn--ghost' }}" style="height:32px;padding:0 12px;font-size:13px;min-width:34px;text-align:center;">
          {{ $page }}
        </a>
      @endforeach

      @if($alertes->hasMorePages())
        <a href="{{ $alertes->nextPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;" title="Page suivante">
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
// ─── Sélection Niveau & Statut en 1 clic ──────────────────────────────────────────
function selectNiveauFilter(val) {
  document.getElementById('alertes-niveau-hidden').value = val;
  document.getElementById('alertes-search-form').submit();
}

function selectStatutFilter(val) {
  document.getElementById('alertes-statut-hidden').value = val;
  document.getElementById('alertes-search-form').submit();
}

// ─── Live search instantané dans le tableau (Client-side) ─────────────────────
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('alertes-live-search');
  const rows = document.querySelectorAll('.alerte-table-row');
  const counterEl = document.getElementById('alertes-results-counter');
  const tbody = document.getElementById('alertes-table-body');

  if (searchInput && rows.length > 0) {
    searchInput.addEventListener('input', function() {
      const q = this.value.trim().toLowerCase();
      let count = 0;

      rows.forEach(r => {
        const titre = r.getAttribute('data-titre') || '';
        const desc = r.getAttribute('data-desc') || '';
        const zone = r.getAttribute('data-zone') || '';
        const type = r.getAttribute('data-type') || '';
        const niveau = r.getAttribute('data-niveau') || '';
        const statut = r.getAttribute('data-statut') || '';
        const match = titre.includes(q) || desc.includes(q) || zone.includes(q) || type.includes(q) || niveau.includes(q) || statut.includes(q);

        if (match) {
          r.style.display = '';
          count++;
        } else {
          r.style.display = 'none';
        }
      });

      if (counterEl) {
        counterEl.textContent = `${count} alerte(s) affichée(s)`;
      }

      let emptyRow = document.getElementById('alertes-live-empty-row');
      if (count === 0) {
        if (!emptyRow) {
          emptyRow = document.createElement('tr');
          emptyRow.id = 'alertes-live-empty-row';
          emptyRow.innerHTML = `<td colspan="8" class="text-center py-4 text-muted"><i class="fa-solid fa-magnifying-glass fa-2x mb-2 d-block text-secondary"></i>Aucune alerte ne correspond à « <em>${q}</em> ».</td>`;
          tbody.appendChild(emptyRow);
        } else {
          emptyRow.style.display = '';
          emptyRow.querySelector('em').textContent = q;
        }
      } else if (emptyRow) {
        emptyRow.style.display = 'none';
      }
    });
  }
});
</script>
@endpush
@endsection
