@extends('layouts.front.front')

@section('title', 'Points de Fraîcheur — HeatAlert')
@section('meta_description', 'Trouvez les espaces frais proches de chez vous : parcs, fontaines, salles climatisées, piscines accessibles en Tunisie.')

@section('content')

{{-- ── Page Title ── --}}
<div class="page-title position-relative overflow-hidden"
     style="background: linear-gradient(135deg, rgba(3, 67, 100, 0.88) 0%, rgba(14, 116, 144, 0.84) 100%), url('{{ asset('assets/front/img/health/facilities-9.webp') }}') center/cover no-repeat; padding-top: 135px; padding-bottom: 45px;">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill small fw-bold shadow-sm"
               style="background:rgba(14,165,233,.25);color:#7dd3fc;border:1px solid rgba(125,211,252,.3);">
            <i class="bi bi-snow"></i>
            <span>Réseau de Fraîcheur Tunisien</span>
          </div>
          <h1 class="heading-title text-white" style="font-weight:800;font-size:36px;letter-spacing:-0.5px;">
            <i class="bi bi-snow me-2" style="color:#7dd3fc;"></i>Points de Fraîcheur
          </h1>
          <p class="mb-0" style="font-size:16px;color:#f1f5f9;max-width:680px;margin:0 auto;line-height:1.6;">
            Localisez les espaces frais proches de chez vous : parcs, fontaines, salles climatisées et piscines accessibles gratuitement.
          </p>
          <div class="mt-3 d-flex justify-content-center gap-4 flex-wrap">
            <div class="d-flex align-items-center gap-2 text-white-50" style="font-size:13px;">
              <i class="bi bi-geo-alt-fill" style="color:#7dd3fc;"></i>
              <span><strong class="text-white">{{ $points->total() }}</strong> point(s) disponible(s)</span>
            </div>
            <div class="d-flex align-items-center gap-2 text-white-50" style="font-size:13px;">
              <i class="bi bi-map-fill" style="color:#7dd3fc;"></i>
              <span><strong class="text-white">{{ $zones->count() }}</strong> zone(s) couvertes</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs mt-4" style="background:rgba(0,0,0,0.3);backdrop-filter:blur(8px);border-top:1px solid rgba(255,255,255,0.1);">
    <div class="container">
      <ol class="mb-0">
        <li><a href="{{ route('front.home') }}" class="text-white-50">Accueil</a></li>
        <li class="current text-white fw-semibold">Points de Fraîcheur</li>
      </ol>
    </div>
  </nav>
</div>

{{-- ── Barre de filtres avancée ── --}}
<section class="py-4 bg-white border-bottom" style="box-shadow:0 2px 12px rgba(14,116,144,.08);">
  <div class="container">
    <form method="GET" action="{{ route('front.points_fraicheur.index') }}" id="pf-filter-form">
      <div class="row g-3 align-items-center">

        {{-- Recherche texte --}}
        <div class="col-lg-4 col-md-12">
          <div class="input-group" style="border-radius:12px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.06);">
            <span class="input-group-text bg-light border-end-0 text-muted" style="border-color:#e2e8f0;">
              <i class="bi bi-search" style="color:#0ea5e9;"></i>
            </span>
            <input type="text" name="search" id="pf-search-input"
                   class="form-control bg-light border-start-0 border-end-0 ps-1 py-2"
                   style="border-color:#e2e8f0;font-size:14px;"
                   placeholder="Nom, adresse… (frappe instantanée)"
                   value="{{ $filters['search'] ?? '' }}"
                   autocomplete="off">
            @if(!empty($filters['search']))
              <button type="button" class="btn btn-outline-secondary bg-white border-start-0"
                      style="border-color:#e2e8f0;"
                      onclick="document.getElementById('pf-search-input').value=''; document.getElementById('pf-filter-form').submit();"
                      title="Effacer">✕</button>
            @endif
            <button type="submit" class="btn fw-semibold px-3 text-white"
                    style="background:#0ea5e9;border-color:#0ea5e9;">
              <i class="bi bi-search me-1"></i>Rechercher
            </button>
          </div>
        </div>

        {{-- Type --}}
        <div class="col-lg-2 col-md-4">
          <select name="type" class="form-select" style="border-color:#e2e8f0;border-radius:10px;font-size:13.5px;"
                  onchange="document.getElementById('pf-filter-form').submit()">
            <option value="">— Type —</option>
            @foreach(\App\Models\PointFraicheur::$typeLabels as $val => $label)
              <option value="{{ $val }}" {{ ($filters['type'] ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        {{-- Zone --}}
        <div class="col-lg-3 col-md-4">
          <select name="zone_id" class="form-select" style="border-color:#e2e8f0;border-radius:10px;font-size:13.5px;"
                  onchange="document.getElementById('pf-filter-form').submit()">
            <option value="">— Toutes les zones —</option>
            @foreach($zones as $zone)
              <option value="{{ $zone->id }}" {{ ($filters['zone_id'] ?? '') == $zone->id ? 'selected' : '' }}>{{ $zone->nom }}</option>
            @endforeach
          </select>
        </div>

        {{-- PMR --}}
        <div class="col-lg-2 col-md-3">
          <div class="form-check form-switch pt-1 d-flex align-items-center gap-2">
            <input class="form-check-input" type="checkbox" id="pf-pmr" name="pmr" value="1"
                   style="width:36px;height:20px;cursor:pointer;"
                   {{ !empty($filters['pmr']) ? 'checked' : '' }}
                   onchange="document.getElementById('pf-filter-form').submit()">
            <label class="form-check-label fw-semibold small" for="pf-pmr" style="cursor:pointer;color:#0f172a;">
              <i class="bi bi-person-wheelchair" style="color:#0ea5e9;"></i> Accessible PMR
            </label>
          </div>
        </div>

        {{-- Reset --}}
        <div class="col-lg-1 col-md-1 d-flex justify-content-end">
          @if(request()->anyFilled(['search','type','zone_id','pmr']))
            <a href="{{ route('front.points_fraicheur.index') }}"
               class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1"
               style="border-radius:10px;height:38px;" title="Réinitialiser">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
            </a>
          @endif
        </div>
      </div>

      {{-- Pilules de type rapide --}}
      <input type="hidden" name="type_pill" id="pf-type-pill-hidden" value="">
      <div class="d-flex flex-wrap align-items-center gap-2 mt-3 pt-3" style="border-top:1px solid #f1f5f9;">
        <span class="text-muted small fw-semibold me-1">Type :</span>
        <button type="button"
                class="btn btn-sm {{ !($filters['type'] ?? '') ? 'fw-bold' : '' }}"
                style="{{ !($filters['type'] ?? '') ? 'background:#0ea5e9;color:#fff;' : 'background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;' }}"
                onclick="selectPfType('')">
          Tous
        </button>
        @foreach(\App\Models\PointFraicheur::$typeLabels as $val => $typeLabel)
        @php
          $tc = \App\Models\PointFraicheur::$typeColors[$val] ?? '#64748b';
          $ti = \App\Models\PointFraicheur::$typeIcons[$val] ?? 'fa-location-dot';
        @endphp
        <button type="button"
                class="btn btn-sm {{ ($filters['type'] ?? '') === $val ? 'fw-bold' : '' }}"
                style="{{ ($filters['type'] ?? '') === $val ? "background:{$tc};color:#fff;box-shadow:0 2px 8px {$tc}55;" : 'background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;' }}"
                onclick="selectPfType('{{ $val }}')">
          <i class="fa-solid {{ $ti }} me-1"></i>{{ $typeLabel }}
        </button>
        @endforeach

        <span class="ms-auto text-muted small" id="pf-results-counter">
          {{ $points->total() }} résultat(s)
        </span>
      </div>
    </form>
  </div>
</section>

{{-- ── Grille des points ── --}}
<section class="py-5" style="background:#f8fafc;">
  <div class="container">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm rounded-3" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm rounded-3" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
    @if(session('deleted'))
      <div class="alert alert-warning alert-dismissible fade show mb-4 border-0 shadow-sm rounded-3" role="alert">
        <i class="bi bi-trash3-fill me-2"></i>{{ session('deleted') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <div class="row g-4" id="pf-grid">
      @forelse($points as $point)
      @php
        $icon  = \App\Models\PointFraicheur::$typeIcons[$point->type]  ?? 'fa-location-dot';
        $color = \App\Models\PointFraicheur::$typeColors[$point->type] ?? '#64748b';
        $label = \App\Models\PointFraicheur::$typeLabels[$point->type] ?? ucfirst($point->type);
        $moy   = round($point->avis_points_avg_note ?? 0, 1);
        $nbAvis = $point->avis_points_count ?? 0;
      @endphp
      <div class="col-lg-4 col-md-6 pf-card-col"
           data-nom="{{ strtolower($point->nom) }}"
           data-adresse="{{ strtolower($point->adresse ?? '') }}"
           data-type="{{ $point->type }}"
           data-zone="{{ strtolower($point->zone?->nom ?? '') }}"
           data-pmr="{{ $point->accessible_pmr ? '1' : '0' }}">
        <article class="pf-card h-100 position-relative" style="border-radius:18px;overflow:hidden;background:#fff;box-shadow:0 2px 14px rgba(0,0,0,.07);transition:transform .22s,box-shadow .22s;">

          {{-- Ruban couleur haut --}}
          <div style="height:6px;background:linear-gradient(90deg, {{ $color }} 0%, {{ $color }}99 100%);"></div>

          {{-- Header info + badges --}}
          <div class="px-4 pt-4 pb-3 d-flex align-items-start justify-content-between gap-2">
            <div>
              {{-- Icône + type --}}
              <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2"
                   style="background:{{ $color }}18;border:1.5px solid {{ $color }}40;">
                <i class="fa-solid {{ $icon }}" style="color:{{ $color }};font-size:.85rem;"></i>
                <span style="color:{{ $color }};font-size:.78rem;font-weight:700;">{{ $label }}</span>
              </div>
              <h5 class="fw-bold mb-0 lh-sm" style="color:#0f172a;font-size:1.05rem;">{{ $point->nom }}</h5>
            </div>
            {{-- Badge PMR + icône flottante --}}
            <div class="d-flex flex-column align-items-end gap-2 flex-shrink-0">
              <div class="d-flex align-items-center justify-content-center rounded-circle"
                   style="width:46px;height:46px;background:{{ $color }}18;border:2px solid {{ $color }}30;color:{{ $color }};font-size:1.25rem;flex-shrink:0;">
                <i class="fa-solid {{ $icon }}"></i>
              </div>
              @if($point->accessible_pmr)
              <span class="badge d-flex align-items-center gap-1" style="background:#dbeafe;color:#1d4ed8;font-size:.72rem;font-weight:600;padding:4px 8px;border-radius:6px;">
                <i class="fa-solid fa-wheelchair"></i> PMR
              </span>
              @endif
            </div>
          </div>

          {{-- Séparateur --}}
          <div style="height:1px;background:linear-gradient(90deg,transparent,#e2e8f0 30%,#e2e8f0 70%,transparent);margin:0 16px;"></div>

          {{-- Infos --}}
          <div class="px-4 py-3">
            <p class="mb-2 d-flex align-items-start gap-2" style="font-size:.83rem;color:#475569;">
              <i class="bi bi-geo-alt-fill mt-1 flex-shrink-0" style="color:{{ $color }};"></i>
              <span>{{ Str::limit($point->adresse, 62) }}</span>
            </p>

            @if($point->horaires)
            <p class="mb-2 d-flex align-items-center gap-2" style="font-size:.82rem;color:#475569;">
              <i class="bi bi-clock-fill flex-shrink-0" style="color:#0ea5e9;"></i>
              <span>{{ $point->horaires }}</span>
            </p>
            @endif

            @if($point->zone)
            <p class="mb-0 d-flex align-items-center gap-2" style="font-size:.8rem;">
              <i class="bi bi-map flex-shrink-0 text-muted"></i>
              <span class="badge bg-light text-dark border" style="font-weight:500;font-size:.72rem;border-radius:6px;">{{ $point->zone->nom }}</span>
            </p>
            @endif
          </div>

          {{-- Note + CTA --}}
          <div class="px-4 pb-4 pt-2" style="border-top:1px solid #f1f5f9;">
            <div class="d-flex align-items-center justify-content-between mb-3">
              {{-- Étoiles --}}
              <div class="d-flex align-items-center gap-2">
                <span style="color:#f59e0b;letter-spacing:-1px;">
                  @for($s = 1; $s <= 5; $s++)
                    <i class="fa-{{ $s <= round($moy) ? 'solid' : 'regular' }} fa-star" style="font-size:13px;"></i>
                  @endfor
                </span>
                <span style="font-size:.82rem;color:#64748b;">
                  {{ $moy > 0 ? number_format($moy, 1) . ' / 5' : 'Non noté' }}
                </span>
              </div>
              <span class="badge rounded-pill" style="background:#f1f5f9;color:#64748b;font-size:.75rem;font-weight:500;">
                {{ $nbAvis }} avis
              </span>
            </div>
            <a href="{{ route('front.points_fraicheur.show', $point) }}"
               class="btn w-100 fw-semibold d-flex align-items-center justify-content-center gap-2"
               style="background:{{ $color }};color:#fff;border-radius:12px;padding:10px 0;font-size:.88rem;box-shadow:0 4px 14px {{ $color }}44;transition:.2s;border:none;">
              <i class="bi bi-eye"></i>Voir les détails &amp; avis
            </a>
          </div>
        </article>
      </div>
      @empty
      <div class="col-12 text-center py-5">
        <div style="font-size:3.5rem;color:#cbd5e1;margin-bottom:1rem;">
          <i class="fa-solid fa-snowflake"></i>
        </div>
        <h5 class="text-muted fw-semibold">Aucun point de fraîcheur trouvé</h5>
        <p class="text-muted small">Modifiez vos filtres ou revenez plus tard.</p>
        <a href="{{ route('front.points_fraicheur.index') }}"
           class="btn btn-outline-primary btn-sm mt-2 rounded-pill px-4">
          <i class="bi bi-arrow-counterclockwise me-1"></i>Réinitialiser les filtres
        </a>
      </div>
      @endforelse
    </div>

    {{-- Message live search vide --}}
    <div id="pf-live-empty" class="text-center py-5 d-none">
      <div style="font-size:3rem;color:#cbd5e1;margin-bottom:1rem;">
        <i class="bi bi-search"></i>
      </div>
      <h5 class="text-muted fw-semibold">Aucun résultat pour « <span id="pf-empty-q" class="text-primary"></span> »</h5>
      <p class="text-muted small">Essayez un autre terme ou utilisez les filtres ci-dessus.</p>
    </div>

    {{-- Pagination --}}
    @if($points->hasPages())
    <div class="d-flex flex-column align-items-center mt-5 gap-2">
      <div class="text-muted" style="font-size:13px;">
        Affichage de <strong>{{ $points->firstItem() }}</strong>
        à <strong>{{ $points->lastItem() }}</strong>
        sur <strong>{{ $points->total() }}</strong> points
      </div>
      <div class="d-flex justify-content-center align-items-center gap-2">
        @if(!$points->onFirstPage())
          <a href="{{ $points->previousPageUrl() }}" class="btn btn-light border shadow-sm px-3" style="border-radius:10px;" title="Précédent">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;"><path d="M15 18l-6-6 6-6"/></svg>
          </a>
        @else
          <span class="btn btn-light border px-3" style="border-radius:10px;opacity:.35;cursor:not-allowed;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;"><path d="M15 18l-6-6 6-6"/></svg>
          </span>
        @endif
        @foreach($points->getUrlRange(max(1, $points->currentPage()-2), min($points->lastPage(), $points->currentPage()+2)) as $page => $url)
          <a href="{{ $url }}" class="btn {{ $page == $points->currentPage() ? 'btn-primary' : 'btn-light border' }} shadow-sm px-3" style="border-radius:10px;">{{ $page }}</a>
        @endforeach
        @if($points->hasMorePages())
          <a href="{{ $points->nextPageUrl() }}" class="btn btn-light border shadow-sm px-3" style="border-radius:10px;" title="Suivant">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;"><path d="M9 18l6-6-6-6"/></svg>
          </a>
        @else
          <span class="btn btn-light border px-3" style="border-radius:10px;opacity:.35;cursor:not-allowed;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;"><path d="M9 18l6-6-6-6"/></svg>
          </span>
        @endif
      </div>
    </div>
    @endif
  </div>
</section>

@push('styles')
<style>
.pf-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 16px 40px rgba(0,0,0,.12) !important;
}
.pf-card .btn[style*="background"]:hover {
  filter: brightness(1.08);
  transform: translateY(-1px);
}
</style>
@endpush

@push('scripts')
<script>
function selectPfType(val) {
  document.querySelector('[name="type"]').value = val;
  document.getElementById('pf-filter-form').submit();
}

document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('pf-search-input');
  const cards       = document.querySelectorAll('.pf-card-col');
  const counterEl   = document.getElementById('pf-results-counter');
  const emptyBox    = document.getElementById('pf-live-empty');
  const emptyQ      = document.getElementById('pf-empty-q');
  const grid        = document.getElementById('pf-grid');

  if (!searchInput || cards.length === 0) return;

  searchInput.addEventListener('input', function () {
    const q = this.value.trim().toLowerCase();
    let count = 0;

    cards.forEach(card => {
      const nom     = card.getAttribute('data-nom')     || '';
      const adresse = card.getAttribute('data-adresse') || '';
      const type    = card.getAttribute('data-type')    || '';
      const zone    = card.getAttribute('data-zone')    || '';
      const match   = !q || nom.includes(q) || adresse.includes(q) || type.includes(q) || zone.includes(q);

      card.style.display = match ? '' : 'none';
      if (match) count++;
    });

    if (counterEl) counterEl.textContent = `${count} résultat(s)`;

    if (q && count === 0) {
      emptyBox?.classList.remove('d-none');
      if (emptyQ) emptyQ.textContent = q;
      if (grid) grid.style.display = 'none';
    } else {
      emptyBox?.classList.add('d-none');
      if (grid) grid.style.display = '';
    }
  });
});
</script>
@endpush
@endsection