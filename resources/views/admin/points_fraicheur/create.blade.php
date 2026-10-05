@extends('layouts.admin.admin')
@section('title', 'Ajouter un point de fraîcheur')
@section('content')
<div class="page-header">
  <div>
    <nav aria-label="breadcrumb" class="mb-2">
      <a href="{{ route('admin.points_fraicheur.index') }}" class="text-decoration-none text-muted" style="font-size:.85rem;">
        <i class="fa-solid fa-arrow-left me-1"></i>Retour à la liste
      </a>
    </nav>
    <h1><i class="fa-solid fa-plus me-2 text-info"></i>Nouveau Point de Fraîcheur</h1>
  </div>
</div>

<form method="POST" action="{{ route('admin.points_fraicheur.store') }}">
  @csrf
  @include('admin.points_fraicheur._form')
</form>
@endsection
