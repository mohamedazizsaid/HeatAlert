@extends('layouts.admin.admin')
@section('title', 'Modifier — ' . Str::limit($alerte->titre, 30))
@section('content')
<div class="page-header">
  <div>
    <h1><i class="fa-solid fa-pen-to-square me-2"></i>Modifier l'Alerte Météo</h1>
    <p class="subtitle">{{ Str::limit($alerte->titre, 60) }}</p>
  </div>
</div>
<form action="{{ route('admin.alertes.update', $alerte) }}" method="POST" novalidate>
  @csrf
  @method('PUT')
  @include('admin.alertes._form')
</form>
@endsection
