@extends('layouts.admin.admin')

@section('title', 'Détails de l\'Alerte — ' . $alerte->titre)

@section('content')
<div class="page-header">
  <div>
    <nav aria-label="breadcrumb" class="mb-2">
      <a href="{{ route('admin.alertes.index') }}" class="text-decoration-none text-muted" style="font-size:.85rem;">
        <i class="fa-solid fa-arrow-left me-1"></i>Retour à la liste des alertes
      </a>
    </nav>
    <div class="d-flex align-items-center gap-3">
      <h1><i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i>{{ $alerte->titre }}</h1>
      @php
        $badgeClass = match($alerte->niveau) {
          'rouge'  => 'bg-danger',
          'orange' => 'bg-warning text-dark',
          'jaune'  => 'bg-info text-dark',
          default  => 'bg-success',
        };
      @endphp
      <span class="badge {{ $badgeClass }}" style="font-size:.88rem;text-transform:uppercase;padding:6px 12px;">
        Vigilance {{ $alerte->niveau }}
      </span>
      @if($alerte->statut === 'active')
        <span class="badge bg-danger" style="font-size:.85rem;"><i class="fa-solid fa-bolt me-1"></i>En cours</span>
      @elseif($alerte->statut === 'brouillon')
        <span class="badge bg-secondary" style="font-size:.85rem;">Brouillon</span>
      @elseif($alerte->statut === 'terminee')
        <span class="badge bg-dark" style="font-size:.85rem;">Terminée</span>
      @else
        <span class="badge bg-secondary" style="font-size:.85rem;">Annulée</span>
      @endif
    </div>
    <p class="subtitle">
      @if($alerte->zone)
        <i class="fa-solid fa-location-dot me-1 text-danger"></i>Zone : <a href="{{ route('admin.zones.show', $alerte->zone) }}" class="text-decoration-none fw-bold text-dark">{{ $alerte->zone->nom }} ({{ $alerte->zone->ville }})</a>
      @else
        <span class="text-muted"><i class="fa-solid fa-globe me-1"></i>Alerte nationale / Sans zone</span>
      @endif
      <span class="mx-2">•</span>
      <i class="fa-regular fa-calendar me-1"></i>Du {{ $alerte->date_debut ? $alerte->date_debut->format('d/m/Y H:i') : '—' }} au {{ $alerte->date_fin ? $alerte->date_fin->format('d/m/Y H:i') : 'Indéterminé' }}
    </p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('admin.alertes.edit', $alerte) }}" class="m-btn m-btn--primary">
      <i class="fa-solid fa-pen-to-square"></i> Modifier l'alerte
    </a>
    <button type="button" class="m-btn m-btn--ghost js-trigger-delete" style="border:1px solid #dc2626;color:#dc2626;"
            data-action="{{ route('admin.alertes.destroy', $alerte) }}"
            data-name="{{ $alerte->titre }}"
            data-type="l'alerte"
            title="Supprimer l'alerte">
      <i class="fa-solid fa-trash"></i> Supprimer
    </button>
  </div>
</div>

{{-- ── METRICS WEATHER CARDS ── --}}
<div class="row g-3 mb-4">
  <div class="col-md-2 col-sm-4 col-6">
    <div class="m-card text-center" style="padding:16px;border-top:3px solid #dc2626;">
      <div class="text-muted small text-uppercase fw-bold" style="font-size:.72rem;">Temp. Max</div>
      <div style="font-size:1.8rem;font-weight:900;color:#dc2626;">
        {{ $alerte->temperature_max !== null ? $alerte->temperature_max . '°C' : '—' }}
      </div>
      <small class="text-muted"><i class="fa-solid fa-temperature-arrow-up text-danger"></i> Pic prévu</small>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 col-6">
    <div class="m-card text-center" style="padding:16px;border-top:3px solid #f97316;">
      <div class="text-muted small text-uppercase fw-bold" style="font-size:.72rem;">Ressentie</div>
      <div style="font-size:1.8rem;font-weight:900;color:#f97316;">
        {{ $alerte->temperature_ressentie !== null ? $alerte->temperature_ressentie . '°C' : '—' }}
      </div>
      <small class="text-muted"><i class="fa-solid fa-fire-flame-curved text-warning"></i> Chaleur ressentie</small>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 col-6">
    <div class="m-card text-center" style="padding:16px;border-top:3px solid #0284c7;">
      <div class="text-muted small text-uppercase fw-bold" style="font-size:.72rem;">Temp. Min</div>
      <div style="font-size:1.8rem;font-weight:900;color:#0284c7;">
        {{ $alerte->temperature_min !== null ? $alerte->temperature_min . '°C' : '—' }}
      </div>
      <small class="text-muted"><i class="fa-solid fa-temperature-arrow-down text-info"></i> Nuit</small>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 col-6">
    <div class="m-card text-center" style="padding:16px;border-top:3px solid #8b5cf6;">
      <div class="text-muted small text-uppercase fw-bold" style="font-size:.72rem;">Indice UV</div>
      <div style="font-size:1.8rem;font-weight:900;color:#8b5cf6;">
        {{ $alerte->indice_uv !== null ? $alerte->indice_uv . '/11+' : '—' }}
      </div>
      <small class="text-muted"><i class="fa-solid fa-sun text-warning"></i> Rayonnement</small>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 col-6">
    <div class="m-card text-center" style="padding:16px;border-top:3px solid #0d9488;">
      <div class="text-muted small text-uppercase fw-bold" style="font-size:.72rem;">Humidité</div>
      <div style="font-size:1.8rem;font-weight:900;color:#0d9488;">
        {{ $alerte->humidite !== null ? $alerte->humidite . '%' : '—' }}
      </div>
      <small class="text-muted"><i class="fa-solid fa-droplet text-primary"></i> Humidité relative</small>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 col-6">
    <div class="m-card text-center" style="padding:16px;border-top:3px solid #475569;">
      <div class="text-muted small text-uppercase fw-bold" style="font-size:.72rem;">Vent</div>
      <div style="font-size:1.8rem;font-weight:900;color:#0f172a;">
        {{ $alerte->vitesse_vent !== null ? $alerte->vitesse_vent . ' km/h' : '—' }}
      </div>
      <small class="text-muted"><i class="fa-solid fa-wind text-secondary"></i> Rafales</small>
    </div>
  </div>
</div>

<div class="row g-4">
  {{-- ── COLONNE GAUCHE: Bulletin & Description ── --}}
  <div class="col-lg-7">
    <div class="m-card mb-4">
      <div class="m-card__header d-flex justify-content-between align-items-center" style="padding:16px 20px;border-bottom:1px solid #f1f5f9;">
        <h5 style="margin:0;font-weight:700;font-size:1.05rem;"><i class="fa-solid fa-file-waveform me-2 text-danger"></i>Bulletin et description détaillée</h5>
        <span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_', ' ', $alerte->type)) }}</span>
      </div>
      <div style="padding:22px;">
        <div style="background:#fff5f5;border-left:4px solid #dc2626;padding:16px 18px;border-radius:8px;margin-bottom:20px;">
          <h6 style="color:#991b1b;font-weight:700;margin-bottom:6px;"><i class="fa-solid fa-bullhorn me-2"></i>Synthèse de l'alerte</h6>
          <p style="margin:0;color:#7f1d1d;font-size:.95rem;line-height:1.6;">{{ $alerte->description }}</p>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <div style="background:#f8fafc;padding:14px;border-radius:10px;border:1px solid #e2e8f0;">
              <div class="text-muted small fw-bold text-uppercase mb-1" style="font-size:.75rem;"><i class="fa-solid fa-tower-broadcast me-1 text-primary"></i>Source de l'information</div>
              <div class="fw-bold" style="font-size:.92rem;color:#0f172a;">{{ $alerte->source ?: 'Institut National de la Météorologie (INM)' }}</div>
            </div>
          </div>
          <div class="col-md-6">
            <div style="background:#f8fafc;padding:14px;border-radius:10px;border:1px solid #e2e8f0;">
              <div class="text-muted small fw-bold text-uppercase mb-1" style="font-size:.75rem;"><i class="fa-solid fa-plug-circle-exclamation me-1 text-warning"></i>Risque de coupure d'énergie / eau</div>
              <div>
                @if($alerte->risque_coupure)
                  <span class="badge bg-danger"><i class="fa-solid fa-triangle-exclamation me-1"></i>Risque élevé signalé</span>
                @else
                  <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>Faible / Normal</span>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Conseils associés à cette alerte --}}
    <div class="m-card">
      <div class="m-card__header d-flex justify-content-between align-items-center" style="padding:16px 20px;border-bottom:1px solid #f1f5f9;">
        <h5 style="margin:0;font-weight:700;font-size:1.05rem;"><i class="fa-solid fa-lightbulb me-2 text-warning"></i>Conseils santé & recommandations associés</h5>
        <a href="{{ route('admin.conseils.index') }}" class="m-btn m-btn--ghost" style="font-size:12px;height:28px;padding:0 10px;">
          Gérer les conseils
        </a>
      </div>
      <div style="padding:20px;">
        @if($alerte->conseils && $alerte->conseils->count() > 0)
          <div class="row g-3">
            @foreach($alerte->conseils as $conseil)
            <div class="col-12">
              <div class="d-flex align-items-start gap-3 p-3 rounded" style="background:#f8fafc;border:1px solid #e2e8f0;">
                <div style="width:40px;height:40px;border-radius:10px;background:#fee2e2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
                  <i class="{{ $conseil->icone ?: 'fa-solid fa-heart-pulse' }}"></i>
                </div>
                <div class="flex-grow-1">
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6 class="mb-0 fw-bold" style="font-size:.95rem;">{{ $conseil->titre }}</h6>
                    <span class="badge bg-secondary" style="font-size:.75rem;">{{ ucfirst(str_replace('_', ' ', $conseil->categorie)) }}</span>
                  </div>
                  <p class="text-muted small mb-0" style="line-height:1.5;">{{ $conseil->contenu }}</p>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        @else
          <div class="text-center py-4 text-muted">
            <i class="fa-solid fa-lightbulb fa-2x mb-2 d-block text-warning"></i>
            <p class="mb-2">Aucun conseil spécifique directement associé via la table de liaison.</p>
            <p class="small text-muted mb-0">Les conseils généraux ciblés sur le niveau <strong>{{ ucfirst($alerte->niveau) }}</strong> s'appliquent automatiquement.</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  {{-- ── COLONNE DROITE: Zone & Paramètres ── --}}
  <div class="col-lg-5">
    {{-- Carte Zone --}}
    <div class="m-card mb-4">
      <div class="m-card__header d-flex justify-content-between align-items-center" style="padding:16px 20px;border-bottom:1px solid #f1f5f9;">
        <h5 style="margin:0;font-weight:700;font-size:1.05rem;"><i class="fa-solid fa-map-location-dot me-2 text-danger"></i>Zone géographique ciblée</h5>
        @if($alerte->zone)
          <a href="{{ route('admin.zones.show', $alerte->zone) }}" class="m-btn m-btn--ghost" style="font-size:12px;height:28px;padding:0 10px;">
            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Fiche zone
          </a>
        @endif
      </div>
      <div style="padding:20px;">
        @if($alerte->zone)
          <div class="d-flex align-items-center gap-3 mb-3">
            <div style="width:48px;height:48px;border-radius:12px;background:rgba(220,38,38,.1);color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:1.4rem;">
              <i class="fa-solid fa-city"></i>
            </div>
            <div>
              <h5 class="mb-0 fw-bold">{{ $alerte->zone->nom }}</h5>
              <span class="text-muted small">{{ $alerte->zone->ville }} — Gouvernorat de {{ $alerte->zone->gouvernorat ?? 'N/A' }}</span>
            </div>
          </div>
          <table class="table table-sm table-borderless mb-0" style="font-size:.88rem;">
            <tbody>
              <tr>
                <td class="text-muted"><i class="fa-solid fa-map-pin me-2"></i>Code Postal :</td>
                <td class="fw-bold">{{ $alerte->zone->code_postal ?? '—' }}</td>
              </tr>
              <tr>
                <td class="text-muted"><i class="fa-solid fa-compass me-2"></i>GPS :</td>
                <td>
                  @if($alerte->zone->latitude && $alerte->zone->longitude)
                    <code>{{ number_format($alerte->zone->latitude, 4) }}, {{ number_format($alerte->zone->longitude, 4) }}</code>
                  @else <span class="text-muted">—</span> @endif
                </td>
              </tr>
              <tr>
                <td class="text-muted"><i class="fa-solid fa-toggle-on me-2"></i>Statut zone :</td>
                <td>
                  @if($alerte->zone->actif)
                    <span class="badge bg-success">Zone active</span>
                  @else
                    <span class="badge bg-secondary">Zone inactive</span>
                  @endif
                </td>
              </tr>
            </tbody>
          </table>
        @else
          <div class="text-center py-3 text-muted">
            <i class="fa-solid fa-globe fa-2x mb-2 d-block"></i>
            Alerte sans zone spécifique (vigilance globale).
          </div>
        @endif
      </div>
    </div>

    {{-- Métadonnées de l'alerte --}}
    <div class="m-card">
      <div class="m-card__header" style="padding:16px 20px;border-bottom:1px solid #f1f5f9;">
        <h5 style="margin:0;font-weight:700;font-size:1.05rem;"><i class="fa-solid fa-clock me-2 text-primary"></i>Calendrier & Horodatage</h5>
      </div>
      <div style="padding:20px;">
        <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
          <div>
            <div class="text-muted small text-uppercase fw-bold" style="font-size:.72rem;">Début de l'événement</div>
            <div class="fw-bold text-dark" style="font-size:.95rem;">
              {{ $alerte->date_debut ? $alerte->date_debut->format('d/m/Y à H:i') : '—' }}
            </div>
          </div>
          <span class="badge bg-primary"><i class="fa-solid fa-play me-1"></i>Début</span>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
          <div>
            <div class="text-muted small text-uppercase fw-bold" style="font-size:.72rem;">Fin estimée</div>
            <div class="fw-bold text-dark" style="font-size:.95rem;">
              {{ $alerte->date_fin ? $alerte->date_fin->format('d/m/Y à H:i') : 'En continu' }}
            </div>
          </div>
          <span class="badge bg-secondary"><i class="fa-solid fa-flag-checkered me-1"></i>Fin</span>
        </div>
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small text-uppercase fw-bold" style="font-size:.72rem;">Création dans le système</div>
            <div class="small text-muted">
              {{ $alerte->created_at ? $alerte->created_at->format('d/m/Y H:i') : '—' }} ({{ $alerte->created_at ? $alerte->created_at->diffForHumans() : '' }})
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
