@extends('layouts.front.front')

@section('content')
<div class="page-title position-relative overflow-hidden" style="background:linear-gradient(135deg,rgba(15,23,42,.9),rgba(154,52,18,.82)),url('{{ asset('assets/front/img/health/emergency-1.webp') }}') center/cover;padding-top:135px;padding-bottom:45px;">
  <div class="container text-center">
    <span class="badge rounded-pill bg-warning text-dark px-3 py-2 mb-3"><i class="bi bi-lightning-charge-fill me-1"></i>Détail de la coupure</span>
    <h1 class="heading-title text-white fw-bold">Coupure {{ ucfirst($coupure->type) }}</h1>
    <p class="text-white-50 mb-0">{{ $coupure->zone?->nom }} — {{ $coupure->zone?->ville }}</p>
  </div>
</div>

<section class="py-5" style="background:#f8fafc;">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <a href="{{ route('front.coupures.index') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i>Retour aux coupures</a>
      @auth
        @if($coupure->statut === 'en_cours')
        <a href="{{ route('front.coupures.signaler', ['coupure_id' => $coupure->id]) }}" class="btn btn-danger rounded-pill"><i class="bi bi-megaphone-fill me-2"></i>Signaler un problème</a>
        @endif
      @endauth
    </div>
    <div class="row g-4">
      <div class="col-lg-7">
        <article class="bg-white rounded-4 shadow-sm p-4 h-100">
          <h2 class="h4 mb-4">Informations de la coupure</h2>
          <dl class="row mb-0">
            <dt class="col-sm-5 text-muted mb-3">Statut</dt>
            <dd class="col-sm-7 mb-3"><span class="badge {{ $coupure->statut === 'en_cours' ? 'bg-danger' : ($coupure->statut === 'prevue' ? 'bg-warning text-dark' : 'bg-success') }}">{{ ucfirst(str_replace('_', ' ', $coupure->statut)) }}</span></dd>
            <dt class="col-sm-5 text-muted mb-3">Zone</dt><dd class="col-sm-7 mb-3">{{ $coupure->zone?->nom }} ({{ $coupure->zone?->ville }})</dd>
            <dt class="col-sm-5 text-muted mb-3">Début</dt><dd class="col-sm-7 mb-3">{{ $coupure->date_debut?->format('d/m/Y à H:i') }}</dd>
            <dt class="col-sm-5 text-muted mb-3">Rétablissement estimé</dt><dd class="col-sm-7 mb-3">{{ $coupure->retablissement_estime?->format('d/m/Y à H:i') ?? 'Non communiqué' }}</dd>
            <dt class="col-sm-5 text-muted">Cause</dt><dd class="col-sm-7">{{ $coupure->cause }}</dd>
          </dl>
        </article>
      </div>
      <div class="col-lg-5">
        <article class="bg-white rounded-4 shadow-sm p-4 text-center h-100 d-flex flex-column justify-content-center">
          <i class="bi bi-people-fill text-danger" style="font-size:3rem;"></i>
          <h2 class="h5 mt-3">Signalements confirmés</h2>
          <div class="display-4 fw-bold text-danger">{{ $coupure->signalements_valides_count }}</div>
          <p class="text-muted mb-0">signalement(s) validé(s) par l’administration</p>
        </article>
      </div>
    </div>
  </div>
</section>
@endsection
