@extends('layouts.admin.admin')

@section('title', 'Nouvelle coupure')

@section('content')
<div class="page-header">
  <div>
    <h1><i class="fa-solid fa-bolt me-2 text-warning"></i>Nouvelle Coupure</h1>
    <p class="subtitle">Enregistrer une nouvelle coupure électrique.</p>
  </div>
</div>

<form action="{{ route('admin.coupures.store') }}" method="POST" novalidate>
  @csrf
  @include('admin.coupures._form')
</form>
@endsection
