@extends('layouts.front.front')

@section('title', $point->nom . ' — Points de Fraîcheur — HeatAlert')

@section('content')
@php
  $icon  = \App\Models\PointFraicheur::$typeIcons[$point->type]  ?? 'fa-location-dot';
  $color = \App\Models\PointFraicheur::$typeColors[$point->type] ?? '#64748b';
  $label = \App\Models\PointFraicheur::$typeLabels[$point->type] ?? ucfirst($point->type);
  $moy   = round($point->avisPoints()->avg('note') ?? 0, 1);
@endphp

{{-- ── Page Title ── --}}
<div class="page-title position-relative overflow-hidden"
     style="background: linear-gradient(135deg, rgba(3,67,100,.9) 0%, {{ $color }}88 100%); padding-top:135px;padding-bottom:45px;">
  <div class="heading">
    <div class="container">
      <div class="row d-flex justify-content-center text-center">
        <div class="col-lg-8">
          <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill small fw-bold" style="background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.25);">
            <i class="fa-solid {{ $icon }}"></i><span>{{ $label }}</span>
          </div>
          <h1 class="heading-title text-white" style="font-weight:800;font-size:34px;">{{ $point->nom }}</h1>
          <p class="mb-0" style="font-size:15px;color:#e0f2fe;"><i class="bi bi-geo-alt me-1"></i>{{ $point->adresse }}</p>
        </div>
      </div>
    </div>
  </div>
  <nav class="breadcrumbs mt-4" style="background:rgba(0,0,0,.3);backdrop-filter:blur(8px);border-top:1px solid rgba(255,255,255,.1);">
    <div class="container">
      <ol class="mb-0">
        <li><a href="{{ route('front.home') }}" class="text-white-50">Accueil</a></li>
        <li><a href="{{ route('front.points_fraicheur.index') }}" class="text-white-50">Points de Fraîcheur</a></li>
        <li class="current text-white fw-semibold">{{ Str::limit($point->nom, 30) }}</li>
      </ol>
    </div>
  </nav>
</div>

<section class="py-5" style="background:#f8fafc;">
  <div class="container">

    {{-- Alertes sessions --}}
    @foreach(['success','error','deleted'] as $stype)
      @if(session($stype))
      <div class="alert alert-{{ $stype === 'error' ? 'danger' : ($stype === 'deleted' ? 'warning' : 'success') }} alert-dismissible fade show mb-4 border-0 shadow-sm rounded-3" role="alert">
        {{ session($stype) }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      @endif
    @endforeach

    <div class="row g-4">

      {{-- ── Colonne gauche : Infos + Carte ── --}}
      <div class="col-lg-7">

        {{-- Stat cards --}}
        <div class="row g-3 mb-4">
          <div class="col-6 col-md-3">
            <div class="text-center p-3 bg-white rounded-3 shadow-sm">
              <div class="fw-bold" style="font-size:1.6rem;color:{{ $color }};"><i class="fa-solid {{ $icon }}"></i></div>
              <div class="small text-muted mt-1">{{ $label }}</div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="text-center p-3 bg-white rounded-3 shadow-sm">
              <div class="fw-bold" style="font-size:1.4rem;color:#f59e0b;">{{ $moy > 0 ? $moy . '/5' : '—' }}</div>
              <div class="small text-muted mt-1">Note moy.</div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="text-center p-3 bg-white rounded-3 shadow-sm">
              <div class="fw-bold" style="font-size:1.4rem;color:#2563eb;">{{ $point->avis_points_count }}</div>
              <div class="small text-muted mt-1">Avis</div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="text-center p-3 bg-white rounded-3 shadow-sm">
              <div class="fw-bold" style="font-size:1.2rem;color:{{ $point->accessible_pmr ? '#16a34a' : '#94a3b8' }};">
                <i class="fa-solid fa-wheelchair"></i>
              </div>
              <div class="small text-muted mt-1">{{ $point->accessible_pmr ? 'PMR OK' : 'Non PMR' }}</div>
            </div>
          </div>
        </div>

        {{-- Fiche info --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
          <div class="card-body p-4">
            <h5 class="fw-bold mb-3" style="color:#0f172a;"><i class="bi bi-info-circle me-2 text-info"></i>Informations</h5>
            <table class="table table-sm table-borderless mb-0" style="font-size:.92rem;">
              <tr><td class="text-muted" style="width:35%;padding:6px 0;"><i class="bi bi-geo-alt me-2"></i>Adresse</td><td class="fw-semibold">{{ $point->adresse }}</td></tr>
              @if($point->horaires)
              <tr><td class="text-muted" style="padding:6px 0;"><i class="bi bi-clock me-2"></i>Horaires</td><td>{{ $point->horaires }}</td></tr>
              @endif
              @if($point->zone)
              <tr><td class="text-muted" style="padding:6px 0;"><i class="bi bi-map me-2"></i>Zone</td><td><span class="badge bg-light text-dark border">{{ $point->zone->nom }}</span></td></tr>
              @endif
              @if($point->latitude && $point->longitude)
              <tr><td class="text-muted" style="padding:6px 0;"><i class="bi bi-crosshair me-2"></i>GPS</td>
                <td>
                  <code style="font-size:.8rem;">{{ number_format($point->latitude, 5) }}, {{ number_format($point->longitude, 5) }}</code>
                  <a href="https://www.google.com/maps?q={{ $point->latitude }},{{ $point->longitude }}" target="_blank" class="ms-2 text-info" style="font-size:.8rem;"><i class="bi bi-box-arrow-up-right"></i></a>
                </td>
              </tr>
              @endif
            </table>
          </div>
        </div>

        {{-- Carte Leaflet --}}
        @if($point->latitude && $point->longitude)
        <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;overflow:hidden;">
          <div id="pf-front-map" style="height:260px;width:100%;z-index:1;"></div>
        </div>
        @endif

        {{-- ── Formulaire avis ── --}}
        @auth
        <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;border-left:4px solid {{ $color }} !important;">
          <div class="card-body p-4">
            @if($monAvis)
              {{-- Modifier mon avis --}}
              <h5 class="fw-bold mb-3" style="color:#0f172a;"><i class="fa-solid fa-pen-to-square me-2" style="color:{{ $color }};"></i>Modifier mon avis</h5>
              <form method="POST" action="{{ route('front.avis_points.update', $monAvis) }}">
                @csrf @method('PUT')
                <div class="mb-3">
                  <label class="form-label fw-semibold">Ma note <span class="text-danger">*</span></label>
                  <div class="star-rating-input d-flex gap-2">
                    @for($s = 5; $s >= 1; $s--)
                    <div class="form-check form-check-inline m-0">
                      <input class="form-check-input d-none star-radio" type="radio" name="note" id="note-edit-{{ $s }}" value="{{ $s }}" {{ old('note', $monAvis->note) == $s ? 'checked' : '' }}>
                      <label class="form-check-label star-label" for="note-edit-{{ $s }}" style="cursor:pointer;font-size:1.8rem;color:#d1d5db;transition:.15s;">★</label>
                    </div>
                    @endfor
                  </div>
                </div>
                <div class="mb-3">
                  <label for="commentaire-edit" class="form-label fw-semibold">Commentaire</label>
                  <textarea id="commentaire-edit" name="commentaire" class="form-control" rows="3" maxlength="1000" placeholder="Partagez votre expérience…">{{ old('commentaire', $monAvis->commentaire) }}</textarea>
                </div>
                <div class="d-flex gap-2">
                  <button type="submit" class="btn fw-semibold px-4" style="background:{{ $color }};color:#fff;border-radius:10px;">
                    <i class="bi bi-check2 me-1"></i>Mettre à jour
                  </button>
                  <form method="POST" action="{{ route('front.avis_points.destroy', $monAvis) }}" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger fw-semibold px-4" style="border-radius:10px;" onclick="return confirm('Supprimer votre avis ?')">
                      <i class="bi bi-trash3 me-1"></i>Supprimer
                    </button>
                  </form>
                </div>
              </form>
            @else
              {{-- Laisser un avis --}}
              <h5 class="fw-bold mb-3" style="color:#0f172a;"><i class="fa-solid fa-star me-2" style="color:#f59e0b;"></i>Laisser un avis</h5>
              <form method="POST" action="{{ route('front.avis_points.store', $point) }}">
                @csrf
                <div class="mb-3">
                  <label class="form-label fw-semibold">Ma note <span class="text-danger">*</span></label>
                  <div class="star-rating-input d-flex gap-2" style="flex-direction:row-reverse;justify-content:flex-end;">
                    @for($s = 5; $s >= 1; $s--)
                    <div class="form-check form-check-inline m-0">
                      <input class="form-check-input d-none star-radio" type="radio" name="note" id="note-{{ $s }}" value="{{ $s }}" {{ old('note') == $s ? 'checked' : '' }} required>
                      <label class="form-check-label star-label" for="note-{{ $s }}" style="cursor:pointer;font-size:1.8rem;color:#d1d5db;transition:.15s;">★</label>
                    </div>
                    @endfor
                  </div>
                  @error('note')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                  <label for="commentaire-new" class="form-label fw-semibold">Commentaire (optionnel)</label>
                  <textarea id="commentaire-new" name="commentaire" class="form-control" rows="3" maxlength="1000" placeholder="Partagez votre expérience sur ce point de fraîcheur…">{{ old('commentaire') }}</textarea>
                  @error('commentaire')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn fw-semibold px-4" style="background:{{ $color }};color:#fff;border-radius:10px;">
                  <i class="bi bi-send me-1"></i>Publier mon avis
                </button>
              </form>
            @endif
          </div>
        </div>
        @else
        <div class="alert alert-info border-0 shadow-sm rounded-3 mb-4">
          <i class="bi bi-person-lock me-2"></i>
          <a href="{{ route('login') }}" class="fw-bold">Connectez-vous</a> pour laisser un avis sur ce point de fraîcheur.
        </div>
        @endauth
      </div>

      {{-- ── Colonne droite : Liste des avis ── --}}
      <div class="col-lg-5">
        <div class="card border-0 shadow-sm" style="border-radius:16px;">
          <div class="card-header bg-white border-0 rounded-3 p-4 pb-0">
            <h5 class="fw-bold mb-1" style="color:#0f172a;"><i class="fa-solid fa-comments me-2 text-info"></i>Avis citoyens</h5>
            @if($moy > 0)
            <div class="d-flex align-items-center gap-2 mb-3">
              <span style="font-size:1.6rem;font-weight:800;color:#f59e0b;">{{ number_format($moy, 1) }}</span>
              <div>
                <div style="color:#f59e0b;">
                  @for($s = 1; $s <= 5; $s++)<i class="fa-{{ $s <= round($moy) ? 'solid' : 'regular' }} fa-star" style="font-size:16px;"></i>@endfor
                </div>
                <div class="text-muted small">{{ $point->avis_points_count }} avis</div>
              </div>
            </div>
            @endif
          </div>
          <div class="card-body p-4 pt-2" style="max-height:600px;overflow-y:auto;">
            @forelse($avis as $av)
            <div class="pf-avis-item p-3 mb-3 rounded-3" style="background:#f8fafc;border-left:3px solid {{ $color }};">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="d-flex align-items-center gap-2">
                  <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white" style="width:36px;height:36px;background:{{ $color }};font-size:.85rem;">
                    {{ strtoupper(substr($av->user->prenom ?? 'A', 0, 1)) }}{{ strtoupper(substr($av->user->nom ?? '', 0, 1)) }}
                  </div>
                  <div>
                    <div class="fw-semibold" style="font-size:.9rem;color:#0f172a;">{{ $av->user->prenom ?? '' }} {{ $av->user->nom ?? '—' }}</div>
                    <div class="text-muted" style="font-size:.75rem;">{{ $av->date_avis?->format('d/m/Y') ?? '' }}</div>
                  </div>
                </div>
                <span style="color:#f59e0b;font-size:.9rem;">
                  @for($s = 1; $s <= 5; $s++)<i class="fa-{{ $s <= $av->note ? 'solid' : 'regular' }} fa-star"></i>@endfor
                </span>
              </div>
              @if($av->commentaire)
              <p class="mb-0 text-muted" style="font-size:.88rem;line-height:1.5;">{{ $av->commentaire }}</p>
              @else
              <p class="mb-0 text-muted fst-italic" style="font-size:.82rem;">Aucun commentaire.</p>
              @endif
            </div>
            @empty
            <div class="text-center py-4 text-muted">
              <i class="fa-solid fa-comment-slash fa-2x mb-2 d-block"></i>
              <p class="mb-0 small">Soyez le premier à donner votre avis !</p>
            </div>
            @endforelse

            @if($avis->hasPages())
            <div class="d-flex justify-content-center mt-3 gap-2">
              @if(!$avis->onFirstPage())
                <a href="{{ $avis->previousPageUrl() }}" class="btn btn-sm btn-light border"><i class="bi bi-chevron-left"></i></a>
              @endif
              @foreach($avis->getUrlRange(max(1, $avis->currentPage()-1), min($avis->lastPage(), $avis->currentPage()+1)) as $page => $url)
                <a href="{{ $url }}" class="btn btn-sm {{ $page == $avis->currentPage() ? 'btn-primary' : 'btn-light border' }}">{{ $page }}</a>
              @endforeach
              @if($avis->hasMorePages())
                <a href="{{ $avis->nextPageUrl() }}" class="btn btn-sm btn-light border"><i class="bi bi-chevron-right"></i></a>
              @endif
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<style>
.star-rating-input { flex-direction: row-reverse; justify-content: flex-end; }
.star-label { color: #d1d5db; font-size: 2rem; transition: color .15s; }
.star-radio:checked ~ .star-label,
.star-radio:checked ~ * .star-label,
.star-label:hover,
.star-label:hover ~ .star-label { color: #f59e0b !important; }
.star-rating-input .star-radio:checked + .star-label,
.star-rating-input .star-radio:checked ~ .star-radio + .star-label { color: #f59e0b !important; }
/* CSS-only star fill hack */
.star-rating-input:has(.star-radio:checked) .star-label { color: #d1d5db; }
.star-rating-input .star-radio:checked ~ .form-check-inline .star-label { color: #f59e0b; }
.pf-avis-item { transition: transform .15s; }
.pf-avis-item:hover { transform: translateX(3px); }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // ── Star rating interactive ──
  const starGroups = document.querySelectorAll('.star-rating-input');
  starGroups.forEach(group => {
    const labels = group.querySelectorAll('.star-label');
    const radios = group.querySelectorAll('.star-radio');
    const stars = Array.from(labels).reverse(); // 1 to 5 order

    function updateStars(upToIndex) {
      stars.forEach((star, i) => {
        star.style.color = i <= upToIndex ? '#f59e0b' : '#d1d5db';
      });
    }

    radios.forEach((radio, ri) => {
      if (radio.checked) updateStars(4 - ri);
      radio.addEventListener('change', () => updateStars(4 - ri));
    });

    labels.forEach((label, li) => {
      const realIndex = stars.indexOf(label);
      label.addEventListener('mouseenter', () => updateStars(realIndex));
      label.addEventListener('mouseleave', () => {
        const checked = Array.from(radios).findIndex(r => r.checked);
        if (checked >= 0) updateStars(4 - checked); else stars.forEach(s => s.style.color = '#d1d5db');
      });
    });

    // Init avec valeur existante
    const checked = Array.from(radios).findIndex(r => r.checked);
    if (checked >= 0) updateStars(4 - checked);
  });

  // ── Carte Leaflet ──
  @if($point->latitude && $point->longitude)
  const map = L.map('pf-front-map', { scrollWheelZoom: false, zoomControl: true }).setView([{{ (float)$point->latitude }}, {{ (float)$point->longitude }}], 15);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap' }).addTo(map);
  const icon = L.divIcon({
    className: '',
    html: '<div style="display:flex;align-items:center;justify-content:center;color:{{ $color }};filter:drop-shadow(0 2px 5px rgba(0,0,0,.3));"><i class="fa-solid fa-location-dot" style="font-size:36px;"></i></div>',
    iconSize:[36,36], iconAnchor:[18,36], popupAnchor:[0,-34]
  });
  L.marker([{{ (float)$point->latitude }}, {{ (float)$point->longitude }}], { icon })
    .addTo(map)
    .bindPopup('<strong>{{ addslashes($point->nom) }}</strong><br><small>{{ addslashes($point->adresse) }}</small>')
    .openPopup();
  setTimeout(() => map.invalidateSize(), 250);
  @endif
});
</script>
@endpush
@endsection
