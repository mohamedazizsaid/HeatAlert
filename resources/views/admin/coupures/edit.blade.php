@extends('layouts.admin.admin')

@section('title', 'Modifier la coupure')

@section('content')
<div class="page-header">
  <div>
    <h1><i class="fa-solid fa-pen-to-square me-2"></i>Modifier la Coupure</h1>
    <p class="subtitle">Mettre à jour les informations de la coupure.</p>
  </div>
</div>

<form action="{{ route('admin.coupures.update', $coupure) }}" method="POST" novalidate>
  @csrf
  @method('PUT')
  @include('admin.coupures._form')
</form>
@endsection
