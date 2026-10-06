@extends('layouts.admin.admin')
@section('title', 'Modifier un équipement sensible')
@section('content')
<div class="page-header"><div><h1>Modifier {{ $equipement->nom }}</h1></div></div>
<div class="m-card p-4"><form method="POST" action="{{ route('admin.equipements-sensibles.update', $equipement) }}">@csrf @method('PUT') @include('admin.equipements_sensibles._form')</form></div>
@endsection
