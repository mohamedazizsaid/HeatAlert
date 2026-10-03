@extends('layouts.front.front')

@section('content')
<div class="page-title position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(15, 23, 42, .88), rgba(154, 52, 18, .82)), url('{{ asset('assets/front/img/health/emergency-1.webp') }}') center/cover; padding-top:135px; padding-bottom:45px;">
  <div class="container text-center">
    <span class="badge rounded-pill bg-warning text-dark px-3 py-2 mb-3"><i class="bi bi-lightning-charge-fill me-1"></i>Réseau électrique</span>
    <h1 class="heading-title text-white fw-bold">Coupures électriques</h1>
    <p class="text-white-50 mb-0">Consultez les coupures de votre zone et anticipez les interruptions de courant.</p>
  </div>
  <nav class="breadcrumbs mt-4" style="background:rgba(0,0,0,.3);">
    <div class="container"><ol class="mb-0"><li><a href="{{ route('front.home') }}" class="text-white-50">Accueil</a></li><li class="current text-white">Coupures</li></ol></div>
  </nav>
</div>

<section class="py-4 bg-white border-bottom">
  <div class="container">
    <form method="GET" action="{{ route('front.coupures.index') }}" class="row g-3 align-items-end">
      <div class="col-lg-3 col-md-6">
        <label for="type" class="form-label small fw-bold text-muted">Type de coupure</label>
        <select id="type" name="type" class="form-select">
          <option value="">Tous les types</option>
          @foreach($types as $type)
            <option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst($type) }}</option>
          @endforeach
        </select>
      </div>
      @auth
      <div class="col-lg-3 col-md-6">
        <label for="statut" class="form-label small fw-bold text-muted">Statut</label>
        <select id="statut" name="statut" class="form-select">
          <option value="">Tous les statuts</option>
          @foreach($statuts as $statut)
            <option value="{{ $statut }}" @selected(request('statut') === $statut)>{{ ucfirst(str_replace('_', ' ', $statut)) }}</option>
          @endforeach
        </select>
      </div>
      @endauth
      <div class="col-lg-3 col-md-6">
        <label for="tri" class="form-label small fw-bold text-muted">Trier par date</label>
        <select id="tri" name="tri" class="form-select">
          <option value="plus_proche" @selected(request('tri', 'plus_proche') === 'plus_proche')>Date la plus proche</option>
          <option value="plus_lointaine" @selected(request('tri') === 'plus_lointaine')>Date la plus éloignée</option>
        </select>
      </div>
      <div class="col-lg-3 col-md-6 d-flex gap-2">
        <button type="submit" class="btn btn-danger flex-grow-1"><i class="bi bi-funnel me-1"></i>Filtrer</button>
        <a href="{{ route('front.coupures.index') }}" class="btn btn-outline-secondary" title="Réinitialiser"><i class="bi bi-arrow-counterclockwise"></i></a>
      </div>
    </form>
  </div>
</section>

<section class="py-5" style="background:#f8fafc;">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
      <div>
        <h2 class="h4 mb-1">{{ auth()->check() ? 'Les coupures de ma zone' : 'Coupures en cours' }}</h2>
        <p class="text-muted mb-0"><span id="coupures-count">{{ $coupures->total() }}</span> résultat(s)</p>
      </div>
      @auth
        <a href="{{ route('front.coupures.signaler') }}" class="btn btn-danger rounded-pill"><i class="bi bi-megaphone-fill me-2"></i>Signaler une coupure</a>
      @else
        <a href="{{ route('login') }}" class="btn btn-outline-danger rounded-pill"><i class="bi bi-person-lock me-2"></i>Se connecter pour signaler</a>
      @endauth
    </div>

    <div id="coupures-results" class="row g-4">
      @forelse($coupures as $coupure)
        <div class="col-lg-6 coupure-item" data-type="{{ $coupure->type }}" data-statut="{{ $coupure->statut }}" data-date="{{ $coupure->date_debut?->timestamp ?? 0 }}">
          @include('front.coupures._carte_coupure', ['coupure' => $coupure])
        </div>
      @empty
        <div class="col-12"><div class="text-center bg-white rounded-4 p-5 shadow-sm"><i class="bi bi-lightning-charge text-muted" style="font-size:3rem;"></i><h3 class="h5 mt-3">Aucune coupure trouvée</h3><p class="text-muted mb-0">Aucune coupure ne correspond à votre recherche.</p></div></div>
      @endforelse
    </div>

    <div id="coupures-no-results" class="text-center bg-white rounded-4 p-5 shadow-sm" style="display:none;">
      <i class="bi bi-funnel text-muted" style="font-size:3rem;"></i>
      <h3 class="h5 mt-3">Aucune coupure ne correspond aux filtres.</h3>
    </div>

    @if($coupures->hasPages())
      <div id="coupures-pagination" class="d-flex justify-content-center mt-5">{{ $coupures->links() }}</div>
    @endif
  </div>
</section>

<section class="py-4" style="background:#f8fafc;">
  <div class="container">
    <div class="bg-white rounded-4 shadow-sm p-3 p-md-4">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
          <h2 class="h4 mb-1"><i class="bi bi-calendar3 text-danger me-2"></i>Calendrier des coupures prévues</h2>
          <p class="text-muted mb-0">Les coupures prévues dans toutes les zones au cours des 7 prochains jours.</p>
        </div>
        <span class="badge rounded-pill bg-warning text-dark"><i class="bi bi-clock me-1"></i>7 prochains jours</span>
      </div>
      <div id="coupures-calendar"></div>
      @if($calendrierEvents->isEmpty())
        <p class="text-center text-muted small mb-0 mt-3">Aucune coupure prévue sur cette période.</p>
      @endif
    </div>
  </div>
</section>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
<style>
  #coupures-calendar { min-height: 520px; }
  #coupures-calendar .fc-toolbar-title { font-size: 1.1rem; color: #1e3a5f; }
  #coupures-calendar .fc-button-primary { background: #dc2626; border-color: #dc2626; }
  #coupures-calendar .fc-button-primary:hover { background: #b91c1c; border-color: #b91c1c; }
  #coupures-calendar .fc-event { cursor: pointer; border-radius: 6px; padding: 2px 4px; }
  @media (max-width: 576px) {
    #coupures-calendar { min-height: 600px; }
    #coupures-calendar .fc-toolbar { display: block; }
    #coupures-calendar .fc-toolbar-chunk { margin-bottom: 8px; }
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const calendarElement = document.getElementById('coupures-calendar');
    if (!calendarElement) return;

    const calendar = new FullCalendar.Calendar(calendarElement, {
      locale: 'fr',
      initialView: 'listWeek',
      height: 'auto',
      firstDay: 1,
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: '',
      },
      eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
      events: @json($calendrierEvents),
      eventClick: function (info) {
        info.jsEvent.preventDefault();
        if (info.event.url) window.location.href = info.event.url;
      },
    });

    calendar.render();
  });
</script>
@endpush
@endsection
