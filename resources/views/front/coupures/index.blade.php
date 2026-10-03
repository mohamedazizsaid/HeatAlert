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
      <div class="col-md-5">
        <label for="type" class="form-label small fw-bold text-muted">Type de coupure</label>
        <select id="type" name="type" class="form-select">
          <option value="">Tous les types</option>
          @foreach($types as $type)
            <option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst($type) }}</option>
          @endforeach
        </select>
      </div>
      @auth
      <div class="col-md-5">
        <label for="statut" class="form-label small fw-bold text-muted">Statut</label>
        <select id="statut" name="statut" class="form-select">
          <option value="">Tous les statuts</option>
          @foreach($statuts as $statut)
            <option value="{{ $statut }}" @selected(request('statut') === $statut)>{{ ucfirst(str_replace('_', ' ', $statut)) }}</option>
          @endforeach
        </select>
      </div>
      @endauth
      <div class="col-md-2 d-flex gap-2">
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
        <p class="text-muted mb-0">{{ $coupures->total() }} résultat(s)</p>
      </div>
      @auth
        <a href="{{ route('front.coupures.signaler') }}" class="btn btn-danger rounded-pill"><i class="bi bi-megaphone-fill me-2"></i>Signaler une coupure</a>
      @else
        <a href="{{ route('login') }}" class="btn btn-outline-danger rounded-pill"><i class="bi bi-person-lock me-2"></i>Se connecter pour signaler</a>
      @endauth
    </div>

    <div class="row g-4">
      @forelse($coupures as $coupure)
        <div class="col-lg-6">@include('front.coupures._carte_coupure', ['coupure' => $coupure])</div>
      @empty
        <div class="col-12"><div class="text-center bg-white rounded-4 p-5 shadow-sm"><i class="bi bi-lightning-charge text-muted" style="font-size:3rem;"></i><h3 class="h5 mt-3">Aucune coupure trouvée</h3><p class="text-muted mb-0">Aucune coupure ne correspond à votre recherche.</p></div></div>
      @endforelse
    </div>

    @if($coupures->hasPages())
      <div class="d-flex justify-content-center mt-5">{{ $coupures->links() }}</div>
    @endif
  </div>
</section>
@endsection
