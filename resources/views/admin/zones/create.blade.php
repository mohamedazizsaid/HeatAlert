@extends('layouts.admin.admin')

@section('title', 'Ajouter une zone')

@section('content')
<div class="page-header">
  <div>
    <h1><i class="fa-solid fa-map-location-dot me-2"></i>Ajouter une Zone</h1>
    <p class="subtitle">Créer une nouvelle zone géographique.</p>
  </div>
</div>

<form action="{{ route('admin.zones.store') }}" method="POST" novalidate>
  @csrf
  @include('admin.zones._form')
</form>
@endsection
