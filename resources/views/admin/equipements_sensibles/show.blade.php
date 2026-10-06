@extends('layouts.admin.admin')
@section('title', $equipement->nom)
@section('content')
<div class="page-header"><div><a href="{{ route('admin.equipements-sensibles.index') }}" class="text-muted"><i class="fa-solid fa-arrow-left me-1"></i>Retour</a><h1 class="mt-2"><i class="fa-solid fa-shield-heart me-2"></i>{{ $equipement->nom }}</h1></div><div class="page-header__actions"><a href="{{ route('admin.equipements-sensibles.edit', $equipement) }}" class="m-btn m-btn--primary">Modifier</a></div></div>
<div class="m-card p-4"><dl class="row mb-0"><dt class="col-sm-3">Type</dt><dd class="col-sm-9">{{ $equipement->type }}</dd><dt class="col-sm-3">Zone</dt><dd class="col-sm-9">{{ $equipement->zone?->nom ?? '—' }}</dd><dt class="col-sm-3">Adresse</dt><dd class="col-sm-9">{{ $equipement->adresse ?? '—' }}</dd><dt class="col-sm-3">Sensibilité</dt><dd class="col-sm-9">{{ ucfirst($equipement->niveau_sensibilite) }}</dd><dt class="col-sm-3">Statut</dt><dd class="col-sm-9">{{ $equipement->actif ? 'Actif' : 'Inactif' }}</dd><dt class="col-sm-3">Description</dt><dd class="col-sm-9">{{ $equipement->description ?? '—' }}</dd></dl></div>
@endsection
