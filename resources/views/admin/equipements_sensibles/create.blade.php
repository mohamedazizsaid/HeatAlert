@extends('layouts.admin.admin')
@section('title', 'Ajouter un équipement sensible')
@section('content')
<div class="page-header"><div><h1>Ajouter un équipement sensible</h1><p class="subtitle">Renseignez les informations du site à protéger.</p></div></div>
<div class="m-card p-4"><form method="POST" action="{{ route('admin.equipements-sensibles.store') }}">@csrf @include('admin.equipements_sensibles._form')</form></div>
@endsection
