@extends('layouts.admin.admin')
@section('title', 'Nouvelle alerte météo')
@section('content')
<div class="page-header">
  <div>
    <h1><i class="fa-solid fa-triangle-exclamation me-2"></i>Nouvelle Alerte Météo</h1>
    <p class="subtitle">Créer une nouvelle alerte météorologique.</p>
  </div>
</div>
<form action="{{ route('admin.alertes.store') }}" method="POST" id="alerte-form" novalidate>
  @csrf
  @include('admin.alertes._form')
</form>
@endsection
