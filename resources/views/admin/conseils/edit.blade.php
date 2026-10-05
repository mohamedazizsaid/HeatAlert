@extends('layouts.admin.admin')
@section('title', 'Modifier — ' . Str::limit($conseil->titre, 30))
@section('content')
<div class="page-header">
  <div>
    <h1><i class="fa-solid fa-pen-to-square me-2"></i>Modifier le Conseil</h1>
    <p class="subtitle">{{ Str::limit($conseil->titre, 60) }}</p>
  </div>
</div>
<form action="{{ route('admin.conseils.update', $conseil) }}" method="POST" id="conseil-form" novalidate>
  @csrf
  @method('PUT')
  @include('admin.conseils._form')
</form>
@endsection
