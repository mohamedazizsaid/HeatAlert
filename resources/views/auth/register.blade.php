<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription — HeatAlert</title>
  <meta name="description" content="Créez votre compte HeatAlert pour recevoir des alertes météo personnalisées en Tunisie.">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
  <link href="{{ asset('assets/front/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/front/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/front/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/front/css/main.css') }}" rel="stylesheet">
  <style>
    body { min-height: 100vh; background: linear-gradient(135deg, #fff5f5 0%, #fecaca 100%); display:flex; align-items:center; }
    .auth-card { background:#fff; border-radius:20px; box-shadow:0 20px 60px rgba(220,38,38,.15); overflow:hidden; max-width:580px; width:100%; }
    .auth-header { background:linear-gradient(135deg, #dc2626, #b91c1c); padding:36px; color:#fff; }
    .auth-header h1 { font-family:'Montserrat',sans-serif; font-weight:800; font-size:1.7rem; margin:0; }
    .auth-header p { opacity:.85; margin:6px 0 0; font-size:.9rem; }
    .auth-body { padding:36px; }
    .auth-logo { display:inline-flex; align-items:center; gap:10px; text-decoration:none; color:#fff; margin-bottom:16px; }
    .auth-logo-mark { width:40px; height:40px; background:rgba(255,255,255,.2); border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1.2rem; }
    .form-label { font-weight:600; font-size:.87rem; color:#374151; }
    .form-control, .form-select { border:2px solid #e5e7eb; border-radius:10px; padding:.6rem .9rem; font-size:.93rem; transition:.2s; }
    .form-control:focus, .form-select:focus { border-color:#dc2626; box-shadow:0 0 0 .2rem rgba(220,38,38,.15); }
    .btn-auth { background:linear-gradient(135deg,#dc2626,#b91c1c); border:none; border-radius:10px; color:#fff; font-weight:700; padding:.7rem; font-size:.98rem; width:100%; letter-spacing:.3px; transition:.2s; }
    .btn-auth:hover { background:linear-gradient(135deg,#b91c1c,#991b1b); color:#fff; transform:translateY(-1px); }
    .auth-link { color:#dc2626; font-weight:600; }
    .auth-link:hover { color:#b91c1c; }
    .section-divider { font-size:.8rem; color:#9ca3af; text-transform:uppercase; letter-spacing:.5px; margin:20px 0 14px; font-weight:600; }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="d-flex justify-content-center">
      <div class="auth-card">
        <div class="auth-header">
          <a href="/" class="auth-logo">
            <div class="auth-logo-mark"><i class="fa-solid fa-temperature-high"></i></div>
            <span style="font-family:'Montserrat',sans-serif;font-weight:800;font-size:1.2rem;">HeatAlert</span>
          </a>
          <h1>Créer un compte</h1>
          <p>Rejoignez HeatAlert et soyez alerté en temps réel des vagues de chaleur en Tunisie.</p>
        </div>

        <div class="auth-body">
          <form method="POST" action="{{ route('register') }}" novalidate>
            @csrf

            <p class="section-divider"><i class="fa-solid fa-user me-1"></i>Informations personnelles</p>

            {{-- Nom & Prénom --}}
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                <input id="prenom" type="text" name="prenom"
                       class="form-control @error('prenom') is-invalid @enderror"
                       value="{{ old('prenom') }}"
                       placeholder="Ex: Mohamed" maxlength="60" required autofocus>
                @error('prenom')
                  <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                <input id="nom" type="text" name="nom"
                       class="form-control @error('nom') is-invalid @enderror"
                       value="{{ old('nom') }}"
                       placeholder="Ex: Ben Ali" maxlength="60" required>
                @error('nom')
                  <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- Email --}}
            <div class="mb-3">
              <label for="email" class="form-label">Adresse email <span class="text-danger">*</span></label>
              <input id="email" type="email" name="email"
                     class="form-control @error('email') is-invalid @enderror"
                     value="{{ old('email') }}"
                     placeholder="votre@email.com" required autocomplete="username">
              @error('email')
                <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
              @enderror
            </div>

            {{-- Téléphone --}}
            <div class="mb-3">
              <label for="telephone" class="form-label">Téléphone</label>
              <input id="telephone" type="tel" name="telephone"
                     class="form-control @error('telephone') is-invalid @enderror"
                     value="{{ old('telephone') }}"
                     placeholder="Ex: +216 20 000 000">
              @error('telephone')
                <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
              @enderror
            </div>

            {{-- Zone --}}
            <div class="mb-3">
              <label for="zone_id" class="form-label">Zone géographique (Tunisie)</label>
              <select id="zone_id" name="zone_id"
                      class="form-select @error('zone_id') is-invalid @enderror">
                <option value="">— Choisir votre zone —</option>
                @foreach($zones as $zone)
                  <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                    {{ $zone->ville }} — {{ $zone->gouvernorat }}
                  </option>
                @endforeach
              </select>
              @error('zone_id')
                <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
              @enderror
              <div class="form-text">Pour recevoir des alertes adaptées à votre région.</div>
            </div>

            <p class="section-divider"><i class="fa-solid fa-lock me-1"></i>Sécurité</p>

            {{-- Password --}}
            <div class="mb-3">
              <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
              <input id="password" type="password" name="password"
                     class="form-control @error('password') is-invalid @enderror"
                     placeholder="Min. 8 caractères" required autocomplete="new-password">
              @error('password')
                <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
              @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="mb-4">
              <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
              <input id="password_confirmation" type="password" name="password_confirmation"
                     class="form-control"
                     placeholder="Répétez le mot de passe" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-auth">
              <i class="fa-solid fa-user-plus me-2"></i>Créer mon compte
            </button>

            <p class="text-center mt-3 mb-0" style="font-size:.9rem;color:#6b7280;">
              Déjà inscrit ?
              <a href="{{ route('login') }}" class="auth-link">Se connecter</a>
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/front/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
