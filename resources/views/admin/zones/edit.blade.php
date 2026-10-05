@extends('layouts.admin.admin')

@section('title', 'Modifier la zone — ' . $zone->nom)

@section('content')
<div class="page-header">
  <div>
    <h1><i class="fa-solid fa-pen-to-square me-2"></i>Modifier la Zone</h1>
    <p class="subtitle">{{ $zone->nom }} — {{ $zone->ville }}, {{ $zone->gouvernorat }}</p>
  </div>
</div>

<form action="{{ route('admin.zones.update', $zone) }}" method="POST" id="zone-form" novalidate>
  @csrf
  @method('PUT')
  @include('admin.zones._form')
</form>
@endsection
