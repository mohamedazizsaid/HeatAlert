@extends('layouts.admin.admin')
@section('title', 'Modifier — ' . $point->nom)
@section('content')
<div class="page-header">
  <div>
    <nav aria-label="breadcrumb" class="mb-2">
      <a href="{{ route('admin.points_fraicheur.index') }}" class="text-decoration-none text-muted" style="font-size:.85rem;">
        <i class="fa-solid fa-arrow-left me-1"></i>Retour à la liste
      </a>
    </nav>
    <h1><i class="fa-solid fa-pen-to-square me-2 text-info"></i>Modifier : {{ $point->nom }}</h1>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('admin.points_fraicheur.show', $point) }}" class="m-btn m-btn--ghost">
      <i class="fa-solid fa-eye"></i> Voir la fiche
    </a>
  </div>
</div>

<form method="POST" action="{{ route('admin.points_fraicheur.update', $point) }}" id="point-fraicheur-form" novalidate>
  @csrf
  @method('PUT')
  @include('admin.points_fraicheur._form')
</form>
@endsection
