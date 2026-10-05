@extends('layouts.front.front')

@section('title', 'Points de Fraîcheur — HeatAlert')
@section('meta_description', 'Trouvez les points de fraîcheur près de chez vous : parcs, fontaines, salles climatisées, piscines.')

@section('content')

{{-- ── Page Title ── --}}
<div class="page-title position-relative overflow-hidden"
     style="background: linear-gradient(135deg, rgba(3, 67, 100, 0.88) 0%, rgba(14, 116, 144, 0.84) 100%), url('{{ asset('assets/front/img/health/facilities-9.webp') }}') center/cover no-repeat; padding-top: 135px; padding-bottom: 45px;">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill small fw-bold shadow-sm" style="background:rgba(14,165,233,.25);color:#7dd3fc;border:1px solid rgba(125,211,252,.3);">
            <i class="bi bi-snow"></i>
            <span>Réseau de Fraîcheur Tunisien</span>
          </div>
          <h1 class="heading-title text-white" style="font-weight:800;font-size:36px;letter-spacing:-0.5px;">
            <i class="bi bi-snow me-2" style="color:#7dd3fc;"></i>Points de Fraîcheur
          </h1>
          <p class="mb-0" style="font-size:16px;color:#f1f5f9;max-width:680px;margin:0 auto;line-height:1.6;">
            Localisez les espaces frais proches de chez vous : parcs, fontaines, salles climatisées et piscines accessibles gratuitement.
          </p>
          <div class="mt-3 d-flex justify-content-center gap-3 flex-wrap">
            <div class="d-flex align-items-center gap-1 text-white-50" style="font-size:13px;">
              <i class="bi bi-geo-alt-fill" style="color:#7dd3fc;"></i>
              <span>{{ $points->total() }} point(s) disponible(s)</span>
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

{{-- ── Barre de filtres ── --}}
<section class="py-4 bg-white border-bottom shadow-sm">
  <div class="container">
    <form method="GET" action="{{ route('front.points_fraicheur.index') }}" id="pf-filter-form">
      <div class="row g-3 align-items-center">
        <div class="col-lg-4 col-md-6">
          <div class="input-group">
            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" name="search" class="form-control bg-light border-start-0 ps-0"
                   placeholder="Nom, adresse…" value="{{ $filters['search'] ?? '' }}" autocomplete="off">
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <select name="type" class="form-select bg-light" onchange="this.form.submit()">
            <option value="">— Tous les types —</option>
            @foreach(\App\Models\PointFraicheur::$typeLabels as $val => $label)
              <option value="{{ $val }}" {{ ($filters['type'] ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-lg-3 col-md-6">
          <select name="zone_id" class="form-select bg-light" onchange="this.form.submit()">
            <option value="">— Toutes les zones —</option>
            @foreach($zones as $zone)
              <option value="{{ $zone->id }}" {{ ($filters['zone_id'] ?? '') == $zone->id ? 'selected' : '' }}>{{ $zone->nom }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-lg-1 col-md-3">
          <div class="form-check form-switch pt-2">
            <input class="form-check-input" type="checkbox" id="pmr" name="pmr" value="1"
                   {{ !empty($filters['pmr']) ? 'checked' : '' }} onchange="this.form.submit()">
            <label class="form-check-label small fw-semibold" for="pmr" title="Accessible PMR">
              <i class="bi bi-person-wheelchair" style="color:#0ea5e9;"></i> PMR
            </label>
          </div>
        </div>
        <div class="col-lg-1 col-md-3 d-flex justify-content-end gap-2">
          <button type="submit" class="btn btn-sm btn-primary px-3" title="Rechercher">
            <i class="bi bi-funnel"></i>
          </button>
          <a href="{{ route('front.points_fraicheur.index') }}" class="btn btn-sm btn-light border" title="Réinitialiser">
            <i class="bi bi-x-circle"></i>
          </a>
        </div>
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

    <div class="row g-4">
      @forelse($points as $point)
      @php
        $icon  = \App\Models\PointFraicheur::$typeIcons[$point->type]  ?? 'fa-location-dot';
        $color = \App\Models\PointFraicheur::$typeColors[$point->type] ?? '#64748b';
        $label = \App\Models\PointFraicheur::$typeLabels[$point->type] ?? ucfirst($point->type);
        $moy   = round($point->avis_points_avg_note ?? 0, 1);
      @endphp
      <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm h-100 pf-card" style="border-radius:16px;overflow:hidden;transition:transform .2s,box-shadow .2s;">
          {{-- Header coloré --}}
          <div class="pf-card-header position-relative" style="height:110px;background:linear-gradient(135deg, {{ $color }}25 0%, {{ $color }}10 100%);border-bottom:3px solid {{ $color }};">
            <div class="position-absolute" style="top:16px;left:20px;">
              <span class="badge rounded-pill px-3 py-2 fw-semibold" style="background:{{ $color }};color:#fff;font-size:.78rem;">
                <i class="fa-solid {{ $icon }} me-1"></i>{{ $label }}
              </span>
            </div>
            @if($point->accessible_pmr)
            <div class="position-absolute" style="top:16px;right:16px;">
              <span class="badge bg-white border shadow-sm px-2 py-1" style="color:#0ea5e9;font-size:.75rem;" title="Accessible PMR">
                <i class="fa-solid fa-wheelchair me-1"></i>PMR
              </span>
            </div>
            @endif
            <div class="position-absolute d-flex align-items-center justify-content-center" style="bottom:-24px;right:20px;width:50px;height:50px;border-radius:50%;background:#fff;box-shadow:0 3px 10px rgba(0,0,0,.12);color:{{ $color }};font-size:1.4rem;">
              <i class="fa-solid {{ $icon }}"></i>
            </div>
          </div>

          <div class="card-body pt-4 pb-3" style="padding-left:20px;padding-right:20px;">
            <h5 class="fw-bold mb-1 mt-1" style="color:#0f172a;font-size:1.05rem;">{{ $point->nom }}</h5>
            <p class="text-muted small mb-2" style="font-size:.82rem;"><i class="bi bi-geo-alt me-1"></i>{{ Str::limit($point->adresse, 55) }}</p>

            @if($point->horaires)
            <p class="text-muted small mb-2" style="font-size:.80rem;">
              <i class="bi bi-clock me-1" style="color:#0ea5e9;"></i>{{ $point->horaires }}
            </p>
            @endif

            @if($point->zone)
            <p class="small mb-2" style="font-size:.78rem;">
              <i class="bi bi-map me-1 text-muted"></i>
              <span class="badge bg-light text-dark border" style="font-size:.72rem;">{{ $point->zone->nom }}</span>
            </p>
            @endif

            {{-- Note --}}
            <div class="d-flex align-items-center gap-2 mt-2">
              <span style="color:#f59e0b;">
                @for($s = 1; $s <= 5; $s++)
                  <i class="fa-{{ $s <= round($moy) ? 'solid' : 'regular' }} fa-star" style="font-size:13px;"></i>
                @endfor
              </span>
              <span class="text-muted small">
                {{ $moy > 0 ? number_format($moy, 1) . '/5' : 'Pas encore noté' }}
                ({{ $point->avis_points_count }} avis)
              </span>
            </div>
          </div>

          <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 px-4">
            <a href="{{ route('front.points_fraicheur.show', $point) }}"
               class="btn btn-sm w-100 fw-semibold"
               style="background:{{ $color }};color:#fff;border-radius:10px;padding:8px 0;font-size:.85rem;transition:.2s;">
              <i class="bi bi-eye me-1"></i>Voir les détails & avis
            </a>
          </div>
        </div>
      </div>
      @empty
      <div class="col-12 text-center py-5">
        <i class="fa-solid fa-snowflake fa-3x mb-3 text-muted"></i>
        <h5 class="text-muted">Aucun point de fraîcheur trouvé</h5>
        <p class="text-muted small">Modifiez vos filtres ou revenez plus tard.</p>
        <a href="{{ route('front.points_fraicheur.index') }}" class="btn btn-outline-primary btn-sm mt-2">Réinitialiser</a>
      </div>
      @endforelse
    </div>

    {{-- Pagination --}}
    @if($points->hasPages())
    <div class="d-flex justify-content-center mt-5 gap-2">
      @if(!$points->onFirstPage())
        <a href="{{ $points->previousPageUrl() }}" class="btn btn-light border shadow-sm px-3"><i class="bi bi-chevron-left"></i></a>
      @endif
      @foreach($points->getUrlRange(max(1, $points->currentPage()-2), min($points->lastPage(), $points->currentPage()+2)) as $page => $url)
        <a href="{{ $url }}" class="btn {{ $page == $points->currentPage() ? 'btn-primary' : 'btn-light border' }} shadow-sm px-3">{{ $page }}</a>
      @endforeach
      @if($points->hasMorePages())
        <a href="{{ $points->nextPageUrl() }}" class="btn btn-light border shadow-sm px-3"><i class="bi bi-chevron-right"></i></a>
      @endif
    </div>
    @endif
  </div>
</section>

@push('styles')
<style>
.pf-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,.12) !important; }
</style>
@endpush
@endsection
