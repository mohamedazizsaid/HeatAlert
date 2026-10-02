@extends('layouts.admin.admin')

@section('title', 'Profil Administrateur & Sécurité')

@section('content')
<div class="container-fluid px-4 py-3">

  {{-- ── EN-TÊTE DE PAGE ── --}}
  <div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
      <h1 class="fw-bold mb-1" style="font-size: 1.65rem; color: #1e293b;">
        <i class="fa-solid fa-user-shield text-danger me-2"></i>Profil & Sécurité Administrateur
      </h1>
      <p class="text-muted mb-0 small">Gérez vos identifiants, privilèges d'administration et paramètres d'accès sécurisé.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <span class="badge bg-danger text-white px-3 py-2 rounded-pill fw-semibold shadow-sm" style="font-size: 11.5px; letter-spacing: 0.5px;">
        <i class="fa-solid fa-lock me-1"></i>ROLE_ADMIN ACCRÉDITÉ
      </span>
      <a href="{{ route('admin.dashboard') }}" class="m-btn m-btn--ghost">
        <i class="fa-solid fa-arrow-left me-1"></i>Dashboard
      </a>
    </div>
  </div>

  {{-- Messages Flash de succès --}}
  @if(session('status') === 'profile-updated')
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 d-flex align-items-center gap-3 p-3 mb-4 bg-white" role="alert" style="border-left: 5px solid #10b981 !important;">
      <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background: #10b981; color: white;">
        <i class="fa-solid fa-check fs-5"></i>
      </div>
      <div class="flex-grow-1">
        <h6 class="fw-bold mb-0 text-success">Profil administrateur mis à jour !</h6>
        <small class="text-muted">Vos informations personnelles ont été enregistrées avec succès.</small>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
  @endif

  @if(session('status') === 'password-updated')
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 d-flex align-items-center gap-3 p-3 mb-4 bg-white" role="alert" style="border-left: 5px solid #10b981 !important;">
      <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background: #10b981; color: white;">
        <i class="fa-solid fa-shield-halved fs-5"></i>
      </div>
      <div class="flex-grow-1">
        <h6 class="fw-bold mb-0 text-success">Mot de passe administrateur actualisé !</h6>
        <small class="text-muted">Votre mot de passe d'accès a été modifié avec succès. Votre session reste active.</small>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
  @endif

  {{-- ── 1. BANNIÈRE ADMIN / USER SPOTLIGHT CARD ── --}}
  <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
    <div class="p-4 p-md-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%); position: relative;">
      {{-- Décoration d'arrière-plan --}}
      <div class="position-absolute end-0 top-0 opacity-10 pe-4 pt-3 d-none d-md-block" style="font-size: 8rem; pointer-events: none; color: #ffffff;">
        <i class="fa-solid fa-shield-halved" style="opacity:0.2"></i>
      </div>

      <div class="row align-items-center g-4 position-relative">
        {{-- Avatar & Identité --}}
        <div class="col-lg-7">
          <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-start text-center text-sm-start gap-4">
            {{-- Avatar monogramme avec badge statut --}}
            <div class="position-relative flex-shrink-0">
              @php
                $initials = strtoupper(substr($user->prenom ?? $user->nom, 0, 1) . substr($user->nom, 0, 1));
              @endphp
              <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-lg"
                   style="width: 88px; height: 88px; font-size: 32px; background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); border: 3px solid rgba(255,255,255,0.3);">
                {{ $initials }}
              </div>
              <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white border-2 rounded-circle"
                    title="Administrateur Connecté" style="width: 18px; height: 18px;">
                <span class="visually-hidden">Connecté</span>
              </span>
            </div>

            {{-- Infos texte --}}
            <div class="text-white">
              <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-sm-start gap-2 mb-1">
                <h2 class="h3 fw-bold mb-0 text-white">{{ $user->prenom }} {{ $user->nom }}</h2>
                <span class="badge bg-danger rounded-pill px-3 py-1 fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">
                  <i class="fa-solid fa-crown me-1"></i>Super Admin
                </span>
              </div>

              <div class="text-white-50 small mb-3">
                <i class="fa-solid fa-envelope me-1"></i>{{ $user->email }}
                @if($user->telephone)
                  <span class="mx-2">•</span>
                  <i class="fa-solid fa-phone me-1"></i>{{ $user->telephone }}
                @endif
              </div>

              {{-- Badges rapides --}}
              <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-sm-start">
                <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-20 px-3 py-2 rounded-pill fw-normal">
                  <i class="fa-solid fa-map-pin text-warning me-1"></i>{{ $user->zone ? $user->zone->nom . ' (' . $user->zone->gouvernorat . ')' : 'Siège National HeatAlert' }}
                </span>

                <span class="badge bg-white bg-opacity-10 text-white-50 border border-white border-opacity-10 px-3 py-2 rounded-pill fw-normal">
                  <i class="fa-solid fa-calendar-check me-1"></i>Inscrit le {{ $user->created_at ? $user->created_at->format('d/m/Y') : '2026' }}
                </span>
              </div>
            </div>
          </div>
        </div>

        {{-- Statistiques clés de la plateforme --}}
        <div class="col-lg-5">
          <div class="row g-2 justify-content-center justify-content-lg-end text-center">
            <div class="col-4">
              <div class="p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-15 text-white">
                <div class="fs-4 fw-bold">{{ $adminStats['total_alertes'] }}</div>
                <div class="text-white-50 small" style="font-size: 11px;">Alertes</div>
              </div>
            </div>
            <div class="col-4">
              <div class="p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-15 text-white">
                <div class="fs-4 fw-bold">{{ $adminStats['total_zones'] }}</div>
                <div class="text-white-50 small" style="font-size: 11px;">Zones</div>
              </div>
            </div>
            <div class="col-4">
              <div class="p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-15 text-white">
                <div class="fs-4 fw-bold">{{ $adminStats['total_conseils'] }}</div>
                <div class="text-white-50 small" style="font-size: 11px;">Conseils</div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    {{-- Onglets de navigation stylisés --}}
    <div class="border-top px-4 bg-light">
      <ul class="nav nav-tabs border-0 flex-nowrap overflow-x-auto" id="adminProfileTab" role="tablist" style="scrollbar-width: none;">
        <li class="nav-item" role="presentation">
          <button class="nav-link active py-3 px-4 fw-semibold border-0 text-muted position-relative" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
            <i class="fa-solid fa-address-card me-2 text-danger"></i>Vue d'ensemble & Rôle
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link py-3 px-4 fw-semibold border-0 text-muted position-relative" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit" type="button" role="tab">
            <i class="fa-solid fa-user-pen me-2 text-primary"></i>Modifier mes Informations
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link py-3 px-4 fw-semibold border-0 text-muted position-relative" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
            <i class="fa-solid fa-shield-halved me-2 text-success"></i>Sécurité & Mot de Passe
          </button>
        </li>
      </ul>
    </div>
  </div>

  {{-- ── 2. CONTENU DES ONGLETS ── --}}
  <div class="tab-content" id="adminProfileTabContent">

    {{-- ══════════════════════════════════════════════════════
         ONGLET 1 : VUE D'ENSEMBLE & PRIVILÈGES
    ══════════════════════════════════════════════════════ --}}
    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
      <div class="row g-4">

        {{-- Détails identité --}}
        <div class="col-lg-7">
          <div class="card border-0 shadow-sm rounded-4 h-100 bg-white p-4">
            <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom">
              <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2 bg-danger bg-opacity-10 text-danger">
                  <i class="fa-solid fa-id-badge fs-4"></i>
                </div>
                <div>
                  <h5 class="fw-bold mb-0 text-dark">Informations d'Identification</h5>
                  <small class="text-muted">Profil administrateur HeatAlert</small>
                </div>
              </div>
              <button type="button" class="m-btn m-btn--ghost m-btn--sm" onclick="switchAdminProfileTab('edit-tab')">
                <i class="fa-solid fa-pen me-1"></i>Modifier
              </button>
            </div>

            <div class="row g-3">
              <div class="col-sm-6">
                <div class="p-3 rounded-3 bg-light border border-light">
                  <span class="text-muted small d-block mb-1">Prénom</span>
                  <strong class="text-dark fs-6">{{ $user->prenom ?? 'Non renseigné' }}</strong>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="p-3 rounded-3 bg-light border border-light">
                  <span class="text-muted small d-block mb-1">Nom de famille</span>
                  <strong class="text-dark fs-6">{{ $user->nom ?? 'Non renseigné' }}</strong>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="p-3 rounded-3 bg-light border border-light">
                  <span class="text-muted small d-block mb-1">Adresse Email Pro</span>
                  <strong class="text-dark fs-6 text-truncate d-block" title="{{ $user->email }}">{{ $user->email }}</strong>
                  <div class="mt-1">
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size: 11px;">
                      <i class="fa-solid fa-circle-check me-1"></i>Email Vérifié
                    </span>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="p-3 rounded-3 bg-light border border-light">
                  <span class="text-muted small d-block mb-1">Téléphone</span>
                  @if($user->telephone)
                    <strong class="text-dark fs-6">{{ $user->telephone }}</strong>
                    <div class="mt-1 text-muted small"><i class="fa-solid fa-comment-sms text-primary me-1"></i>Alertes d'urgence SMS</div>
                  @else
                    <span class="text-muted fst-italic">Non renseigné</span>
                    <div class="mt-1"><a href="javascript:void(0)" onclick="switchAdminProfileTab('edit-tab')" class="text-primary small text-decoration-none">+ Ajouter un numéro</a></div>
                  @endif
                </div>
              </div>

              <div class="col-sm-6">
                <div class="p-3 rounded-3 bg-light border border-light">
                  <span class="text-muted small d-block mb-1">Zone Géographique de Rattachement</span>
                  <strong class="text-dark fs-6">{{ $user->zone ? $user->zone->nom . ' (' . $user->zone->ville . ')' : 'National (Toute la Tunisie)' }}</strong>
                  <small class="text-muted d-block mt-1">Supervision globale activée</small>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="p-3 rounded-3 bg-light border border-light">
                  <span class="text-muted small d-block mb-1">Création du Compte Admin</span>
                  <strong class="text-dark fs-6">{{ $user->created_at ? $user->created_at->format('d/m/Y à H:i') : 'Actif' }}</strong>
                  <small class="text-muted d-block mt-1">{{ $user->created_at ? $user->created_at->diffForHumans() : '' }}</small>
                </div>
              </div>
            </div>

            {{-- Note d'accréditation --}}
            <div class="mt-4 p-3 rounded-3 border d-flex align-items-center gap-3" style="background: #fef2f2; border-color: #fecaca !important;">
              <div class="text-danger fs-3 flex-shrink-0">
                <i class="fa-solid fa-shield-halved"></i>
              </div>
              <div class="small text-muted">
                <strong class="text-dark d-block">Accréditation Haute Vigilance :</strong>
                Votre compte dispose des droits d'émission d'alertes canicule officielles de niveau rouge/orange et de publication des consignes sanitaires nationales.
              </div>
            </div>

          </div>
        </div>

        {{-- Privilèges & Sécurité --}}
        <div class="col-lg-5">
          <div class="d-flex flex-column gap-4">

            {{-- Carte Droits d'Accès --}}
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
              <div class="d-flex align-items-center gap-3 mb-3 pb-2 border-bottom">
                <div class="rounded-3 p-2 bg-primary bg-opacity-10 text-primary">
                  <i class="fa-solid fa-key fs-4"></i>
                </div>
                <div>
                  <h6 class="fw-bold mb-0 text-dark">Matrice des Privilèges</h6>
                  <small class="text-muted">Droits d'administration accordés</small>
                </div>
              </div>

              <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small">
                <li class="d-flex align-items-center justify-content-between p-2 rounded bg-light">
                  <span class="text-dark fw-semibold"><i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>Gestion des Alertes Météo</span>
                  <span class="badge bg-success">Complet (CRUD)</span>
                </li>
                <li class="d-flex align-items-center justify-content-between p-2 rounded bg-light">
                  <span class="text-dark fw-semibold"><i class="fa-solid fa-map-location-dot text-primary me-2"></i>Cartographie & Zones</span>
                  <span class="badge bg-success">Complet (CRUD)</span>
                </li>
                <li class="d-flex align-items-center justify-content-between p-2 rounded bg-light">
                  <span class="text-dark fw-semibold"><i class="fa-solid fa-heart-pulse text-success me-2"></i>Conseils Sanitaires</span>
                  <span class="badge bg-success">Complet (CRUD)</span>
                </li>
                <li class="d-flex align-items-center justify-content-between p-2 rounded bg-light">
                  <span class="text-dark fw-semibold"><i class="fa-solid fa-chart-line text-warning me-2"></i>Statistiques & Reporting</span>
                  <span class="badge bg-success">Accès Total</span>
                </li>
              </ul>
            </div>

            {{-- Carte Actions Rapides Sécurité --}}
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
              <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded-3 p-2 bg-success bg-opacity-10 text-success">
                  <i class="fa-solid fa-lock fs-4"></i>
                </div>
                <div>
                  <h6 class="fw-bold mb-0 text-dark">Protection du Compte</h6>
                  <small class="text-muted">Mot de passe & Authentification</small>
                </div>
              </div>

              <p class="text-muted small mb-3">
                Il est fortement recommandé de changer régulièrement votre mot de passe administrateur pour garantir l'intégrité de la plateforme HeatAlert.
              </p>

              <button type="button" class="btn btn-outline-danger rounded-pill w-100 fw-semibold" onclick="switchAdminProfileTab('security-tab')">
                <i class="fa-solid fa-key me-2"></i>Changer mon mot de passe
              </button>
            </div>

          </div>
        </div>

      </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         ONGLET 2 : MODIFIER MES INFORMATIONS
    ══════════════════════════════════════════════════════ --}}
    <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5">

            <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom">
              <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2 bg-primary bg-opacity-10 text-primary">
                  <i class="fa-solid fa-user-pen fs-4"></i>
                </div>
                <div>
                  <h4 class="fw-bold mb-0 text-dark">Mettre à jour le Profil</h4>
                  <p class="text-muted mb-0 small">Modifiez vos informations d'identité et vos coordonnées administratives</p>
                </div>
              </div>
              <button type="button" class="m-btn m-btn--ghost m-btn--sm" onclick="switchAdminProfileTab('overview-tab')">
                <i class="fa-solid fa-arrow-left me-1"></i>Retour
              </button>
            </div>

            <form method="POST" action="{{ route('admin.profile.update') }}">
              @csrf
              @method('patch')

              @if ($errors->any())
                <div class="alert alert-danger rounded-4 border-0 p-3 mb-4" role="alert">
                  <div class="fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-2"></i>Veuillez corriger les erreurs ci-dessous :</div>
                  <ul class="mb-0 small ps-3">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              <div class="row g-4">
                {{-- Prénom --}}
                <div class="col-md-6">
                  <label for="admin_prenom" class="form-label fw-bold small text-dark">
                    Prénom <span class="text-danger">*</span>
                  </label>
                  <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted">
                      <i class="fa-solid fa-user"></i>
                    </span>
                    <input type="text"
                           class="form-control bg-light border-start-0 @error('prenom') is-invalid @enderror"
                           id="admin_prenom"
                           name="prenom"
                           value="{{ old('prenom', $user->prenom) }}"
                           required
                           placeholder="Ex: Mohamed">
                  </div>
                  @error('prenom')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                  @enderror
                </div>

                {{-- Nom --}}
                <div class="col-md-6">
                  <label for="admin_nom" class="form-label fw-bold small text-dark">
                    Nom de famille <span class="text-danger">*</span>
                  </label>
                  <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted">
                      <i class="fa-solid fa-user-tag"></i>
                    </span>
                    <input type="text"
                           class="form-control bg-light border-start-0 @error('nom') is-invalid @enderror"
                           id="admin_nom"
                           name="nom"
                           value="{{ old('nom', $user->nom) }}"
                           required
                           placeholder="Ex: Said">
                  </div>
                  @error('nom')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                  @enderror
                </div>

                {{-- Email --}}
                <div class="col-md-6">
                  <label for="admin_email" class="form-label fw-bold small text-dark">
                    Adresse Email Officielle <span class="text-danger">*</span>
                  </label>
                  <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted">
                      <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input type="email"
                           class="form-control bg-light border-start-0 @error('email') is-invalid @enderror"
                           id="admin_email"
                           name="email"
                           value="{{ old('email', $user->email) }}"
                           required
                           placeholder="admin@heatalert.tn">
                  </div>
                  <small class="text-muted" style="font-size: 11.5px;">Identifiant officiel pour l'accès au panneau d'administration.</small>
                  @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                  @enderror
                </div>

                {{-- Téléphone --}}
                <div class="col-md-6">
                  <label for="admin_telephone" class="form-label fw-bold small text-dark">
                    Téléphone d'Urgence
                  </label>
                  <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted">
                      <i class="fa-solid fa-phone"></i>
                    </span>
                    <input type="tel"
                           class="form-control bg-light border-start-0 @error('telephone') is-invalid @enderror"
                           id="admin_telephone"
                           name="telephone"
                           value="{{ old('telephone', $user->telephone) }}"
                           placeholder="Ex: +216 20 123 456">
                  </div>
                  <small class="text-muted" style="font-size: 11.5px;">Utilisé pour la coordination des alertes critiques.</small>
                  @error('telephone')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                  @enderror
                </div>

                {{-- Zone de Rattachement --}}
                <div class="col-12">
                  <label for="admin_zone_id" class="form-label fw-bold small text-dark">
                    Zone de Référence Géographique
                  </label>
                  <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted">
                      <i class="fa-solid fa-location-dot text-danger"></i>
                    </span>
                    <select class="form-select bg-light border-start-0 @error('zone_id') is-invalid @enderror" id="admin_zone_id" name="zone_id">
                      <option value="">-- Niveau National (Aucune restriction régionale) --</option>
                      @php
                        $zonesParGouv = $zones->groupBy('gouvernorat');
                      @endphp
                      @foreach($zonesParGouv as $gouv => $listeZones)
                        <optgroup label="Gouvernorat : {{ $gouv }}">
                          @foreach($listeZones as $z)
                            <option value="{{ $z->id }}" {{ (string) old('zone_id', $user->zone_id) === (string) $z->id ? 'selected' : '' }}>
                              {{ $z->nom }} ({{ $z->ville }}) @if($z->code_postal) - {{ $z->code_postal }} @endif
                            </option>
                          @endforeach
                        </optgroup>
                      @endforeach
                    </select>
                  </div>
                  <small class="text-muted" style="font-size: 11.5px;">Permet d'aligner vos alertes par défaut sur votre zone d'affectation.</small>
                  @error('zone_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              {{-- Boutons d'action --}}
              <div class="d-flex align-items-center justify-content-end gap-3 mt-5 pt-3 border-top">
                <button type="button" class="btn btn-light rounded-pill px-4" onclick="switchAdminProfileTab('overview-tab')">
                  Annuler
                </button>
                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2.5 fw-semibold shadow-sm d-inline-flex align-items-center gap-2">
                  <i class="fa-solid fa-check fs-6"></i>
                  <span>Enregistrer les modifications</span>
                </button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         ONGLET 3 : MOT DE PASSE & SÉCURITÉ
    ══════════════════════════════════════════════════════ --}}
    <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5">

            <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom">
              <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2 bg-success bg-opacity-10 text-success">
                  <i class="fa-solid fa-shield-halved fs-4"></i>
                </div>
                <div>
                  <h4 class="fw-bold mb-0 text-dark">Sécurité & Mot de Passe Admin</h4>
                  <p class="text-muted mb-0 small">Renforcez la protection de vos accès d'administration</p>
                </div>
              </div>
              <button type="button" class="m-btn m-btn--ghost m-btn--sm" onclick="switchAdminProfileTab('overview-tab')">
                <i class="fa-solid fa-arrow-left me-1"></i>Retour
              </button>
            </div>

            <form method="POST" action="{{ route('password.update') }}">
              @csrf
              @method('put')

              {{-- Erreurs éventuelles --}}
              @if ($errors->updatePassword->any())
                <div class="alert alert-danger rounded-4 border-0 p-3 mb-4" role="alert">
                  <div class="fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-2"></i>Erreur lors de la mise à jour du mot de passe :</div>
                  <ul class="mb-0 small ps-3">
                    @foreach ($errors->updatePassword->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              {{-- Mot de passe actuel --}}
              <div class="mb-4">
                <label for="admin_current_password" class="form-label fw-bold small text-dark">
                  Mot de passe actuel <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0 text-muted">
                    <i class="fa-solid fa-lock"></i>
                  </span>
                  <input type="password"
                         class="form-control bg-light border-start-0 border-end-0 @error('current_password', 'updatePassword') is-invalid @enderror"
                         id="admin_current_password"
                         name="current_password"
                         required
                         autocomplete="current-password"
                         placeholder="Entrez votre mot de passe actuel">
                  <button class="btn btn-light border border-start-0 text-muted" type="button" onclick="toggleAdminPassword('admin_current_password', this)">
                    <i class="fa-solid fa-eye"></i>
                  </button>
                </div>
                @error('current_password', 'updatePassword')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div>

              {{-- Nouveau mot de passe --}}
              <div class="mb-4">
                <label for="admin_password" class="form-label fw-bold small text-dark">
                  Nouveau mot de passe <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0 text-muted">
                    <i class="fa-solid fa-key"></i>
                  </span>
                  <input type="password"
                         class="form-control bg-light border-start-0 border-end-0 @error('password', 'updatePassword') is-invalid @enderror"
                         id="admin_password"
                         name="password"
                         required
                         autocomplete="new-password"
                         placeholder="Minimum 8 caractères (chiffres, symboles)"
                         oninput="checkAdminPasswordStrength(this.value)">
                  <button class="btn btn-light border border-start-0 text-muted" type="button" onclick="toggleAdminPassword('admin_password', this)">
                    <i class="fa-solid fa-eye"></i>
                  </button>
                </div>
                @error('password', 'updatePassword')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror

                {{-- Jauge visuelle de robustesse --}}
                <div class="mt-2">
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small text-muted" style="font-size: 11px;">Indice de sécurité du mot de passe :</span>
                    <span class="small fw-bold" id="admin-pwd-strength-text" style="font-size: 11px; color: #94a3b8;">Non renseigné</span>
                  </div>
                  <div class="progress" style="height: 5px;">
                    <div class="progress-bar transition-all" id="admin-pwd-strength-bar" role="progressbar" style="width: 0%; background-color: #cbd5e1;"></div>
                  </div>
                </div>
              </div>

              {{-- Confirmation nouveau mot de passe --}}
              <div class="mb-4">
                <label for="admin_password_confirmation" class="form-label fw-bold small text-dark">
                  Confirmer le nouveau mot de passe <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0 text-muted">
                    <i class="fa-solid fa-shield-check"></i>
                  </span>
                  <input type="password"
                         class="form-control bg-light border-start-0 border-end-0 @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                         id="admin_password_confirmation"
                         name="password_confirmation"
                         required
                         autocomplete="new-password"
                         placeholder="Répétez fidèlement votre nouveau mot de passe">
                  <button class="btn btn-light border border-start-0 text-muted" type="button" onclick="toggleAdminPassword('admin_password_confirmation', this)">
                    <i class="fa-solid fa-eye"></i>
                  </button>
                </div>
                @error('password_confirmation', 'updatePassword')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div>

              {{-- Recommandations administratives --}}
              <div class="p-3 rounded-3 bg-light border mb-4 small text-muted">
                <div class="fw-bold text-dark mb-1"><i class="fa-solid fa-lock text-success me-1"></i>Recommandations de sécurité administration :</div>
                <ul class="mb-0 ps-3">
                  <li>Minimum 8 caractères (recommandé 12+)</li>
                  <li>Combinaison de majuscules, minuscules, chiffres et symboles</li>
                  <li>Ne jamais utiliser le mot de passe sur d'autres services</li>
                </ul>
              </div>

              {{-- Bouton submit --}}
              <div class="d-flex align-items-center justify-content-end gap-3 pt-3 border-top">
                <button type="submit" class="btn btn-success rounded-pill px-5 py-2.5 fw-semibold shadow-sm d-inline-flex align-items-center gap-2">
                  <i class="fa-solid fa-shield-halved fs-6"></i>
                  <span>Mettre à jour le mot de passe</span>
                </button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>

  </div>

</div>
@endsection

@push('styles')
<style>
  #adminProfileTab .nav-link {
    color: #64748b;
    border-bottom: 3px solid transparent !important;
    transition: all 0.25s ease;
  }
  #adminProfileTab .nav-link:hover {
    color: #0f172a;
    background: rgba(0, 0, 0, 0.02);
  }
  #adminProfileTab .nav-link.active {
    color: #dc2626 !important;
    background: transparent;
    border-bottom: 3px solid #dc2626 !important;
  }
  .transition-all {
    transition: all 0.3s ease;
  }

  /* ── Organisation parfaite des Input Groups & Icônes ── */
  body.app .input-group {
    display: flex !important;
    flex-direction: row !important;
    flex-wrap: nowrap !important;
    align-items: stretch !important;
    width: 100% !important;
    border-radius: 8px !important;
    background-color: #f8fafc !important;
    border: 1px solid #cbd5e1 !important;
    overflow: hidden !important;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease !important;
  }
  body.app .input-group:focus-within {
    border-color: #dc2626 !important;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12) !important;
    background-color: #ffffff !important;
  }
  body.app .input-group > .input-group-text {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 44px !important;
    height: 42px !important;
    padding: 0 14px !important;
    background: transparent !important;
    border: none !important;
    color: #64748b !important;
    font-size: 14px !important;
    flex-shrink: 0 !important;
    margin: 0 !important;
  }
  body.app .input-group > input.form-control,
  body.app .input-group > select.form-control,
  body.app .input-group > select.form-select,
  body.app .input-group > .form-control {
    flex: 1 1 auto !important;
    width: 1% !important;
    min-width: 0 !important;
    height: 42px !important;
    padding: 0 14px 0 4px !important;
    background: transparent !important;
    border: none !important;
    border-radius: 0 !important;
    color: #1e293b !important;
    font-size: 14px !important;
    line-height: 42px !important;
    box-shadow: none !important;
    outline: none !important;
  }
  body.app .input-group > .btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    height: 42px !important;
    min-width: 44px !important;
    padding: 0 14px !important;
    background: transparent !important;
    border: none !important;
    color: #64748b !important;
    flex-shrink: 0 !important;
    transition: color 0.15s ease, background-color 0.15s ease !important;
  }
  body.app .input-group > .btn:hover {
    color: #0f172a !important;
    background-color: rgba(0, 0, 0, 0.05) !important;
  }
</style>
@endpush

@push('scripts')
<script>
/**
 * Changement d'onglet
 */
function switchAdminProfileTab(tabId) {
  const triggerEl = document.getElementById(tabId);
  if (triggerEl) {
    const tabInstance = bootstrap.Tab.getOrCreateInstance(triggerEl);
    tabInstance.show();
    window.scrollTo({ top: 250, behavior: 'smooth' });
  }
}

/**
 * Afficher/Masquer le mot de passe
 */
function toggleAdminPassword(inputId, btn) {
  const input = document.getElementById(inputId);
  if (!input) return;
  const icon = btn.querySelector('i');
  if (input.type === 'password') {
    input.type = 'text';
    if (icon) {
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    }
  } else {
    input.type = 'password';
    if (icon) {
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  }
}

/**
 * Robustesse du mot de passe admin
 */
function checkAdminPasswordStrength(password) {
  const bar = document.getElementById('admin-pwd-strength-bar');
  const label = document.getElementById('admin-pwd-strength-text');
  if (!bar || !label) return;

  if (!password) {
    bar.style.width = '0%';
    bar.style.backgroundColor = '#cbd5e1';
    label.textContent = 'Non renseigné';
    label.style.color = '#94a3b8';
    return;
  }

  let score = 0;
  if (password.length >= 8) score++;
  if (password.length >= 12) score++;
  if (/[A-Z]/.test(password)) score++;
  if (/[0-9]/.test(password)) score++;
  if (/[^A-Za-z0-9]/.test(password)) score++;

  if (score <= 2) {
    bar.style.width = '30%';
    bar.style.backgroundColor = '#ef4444';
    label.textContent = 'Faible';
    label.style.color = '#ef4444';
  } else if (score <= 4) {
    bar.style.width = '70%';
    bar.style.backgroundColor = '#f59e0b';
    label.textContent = 'Moyen';
    label.style.color = '#f59e0b';
  } else {
    bar.style.width = '100%';
    bar.style.backgroundColor = '#10b981';
    label.textContent = 'Très Robuste';
    label.style.color = '#10b981';
  }
}

/**
 * Auto-sélection selon validation ou URL hash
 */
document.addEventListener('DOMContentLoaded', function() {
  @if ($errors->updatePassword->any() || session('status') === 'password-updated')
    switchAdminProfileTab('security-tab');
  @elseif ($errors->any() || session('status') === 'profile-updated')
    switchAdminProfileTab('edit-tab');
  @else
    const hash = window.location.hash;
    if (hash === '#edit') {
      switchAdminProfileTab('edit-tab');
    } else if (hash === '#security') {
      switchAdminProfileTab('security-tab');
    }
  @endif
});
</script>
@endpush
