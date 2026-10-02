@extends('layouts.admin.admin')
@section('title', 'Nouveau conseil')
@section('content')
<div class="page-header">
  <div>
    <h1><i class="fa-solid fa-lightbulb me-2"></i>Ajouter un Conseil</h1>
    <p class="subtitle">Créer un nouveau conseil santé / météo.</p>
  </div>
</div>
<form action="{{ route('admin.conseils.store') }}" method="POST" novalidate>
  @csrf
  @include('admin.conseils._form')
</form>
@endsection
