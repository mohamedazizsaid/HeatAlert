<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion — HeatAlert</title>
  <meta name="description" content="Connectez-vous à HeatAlert pour accéder aux alertes météo en Tunisie.">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
  <link href="{{ asset('assets/front/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/front/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/front/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/front/css/main.css') }}" rel="stylesheet">
  <style>
    body { min-height: 100vh; background: linear-gradient(135deg, #fff5f5 0%, #fecaca 100%); display:flex; align-items:center; }
    .auth-card { background:#fff; border-radius:20px; box-shadow:0 20px 60px rgba(220,38,38,.15); overflow:hidden; max-width:460px; width:100%; }
    .auth-header { background:linear-gradient(135deg, #dc2626, #b91c1c); padding:40px 36px 30px; color:#fff; }
    .auth-header h1 { font-family:'Montserrat',sans-serif; font-weight:800; font-size:1.8rem; margin:0; }
    .auth-header p { opacity:.85; margin:6px 0 0; font-size:.93rem; }
    .auth-body { padding:36px; }
    .auth-logo { display:inline-flex; align-items:center; gap:10px; text-decoration:none; color:#fff; margin-bottom:20px; }
    .auth-logo-mark { width:42px; height:42px; background:rgba(255,255,255,.2); border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1.3rem; }
    .form-label { font-weight:600; font-size:.88rem; color:#374151; }
    .form-control { border:2px solid #e5e7eb; border-radius:10px; padding:.65rem 1rem; font-size:.94rem; transition:.2s; }
    .form-control:focus { border-color:#dc2626; box-shadow:0 0 0 .2rem rgba(220,38,38,.15); }
    .btn-auth { background:linear-gradient(135deg,#dc2626,#b91c1c); border:none; border-radius:10px; color:#fff; font-weight:700; padding:.75rem; font-size:1rem; width:100%; letter-spacing:.3px; transition:.2s; }
    .btn-auth:hover { background:linear-gradient(135deg,#b91c1c,#991b1b); color:#fff; transform:translateY(-1px); }
    .auth-link { color:#dc2626; font-weight:600; }
    .auth-link:hover { color:#b91c1c; }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="d-flex justify-content-center">
      <div class="auth-card">
        <div class="auth-header">
          <a href="/" class="auth-logo">
            <div class="auth-logo-mark"><i class="fa-solid fa-temperature-high"></i></div>
            <span style="font-family:'Montserrat',sans-serif;font-weight:800;font-size:1.3rem;">HeatAlert</span>
          </a>
          <h1>Connexion</h1>
          <p>Accédez à votre espace de gestion des alertes météo.</p>
        </div>

        <div class="auth-body">
          {{-- Session status --}}
          @if(session('status'))
            <div class="alert alert-success d-flex align-items-center mb-3">
              <i class="fa-solid fa-circle-check me-2"></i>{{ session('status') }}
            </div>
          @endif

          <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            {{-- Email --}}
            <div class="mb-3">
              <label for="email" class="form-label">
                <i class="fa-solid fa-envelope me-1" style="color:#dc2626"></i>Adresse email
              </label>
              <input id="email" type="email" name="email"
                     class="form-control @error('email') is-invalid @enderror"
                     value="{{ old('email') }}"
                     placeholder="votre@email.com"
                     required autofocus autocomplete="username">
              @error('email')
                <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
              @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
              <label for="password" class="form-label">
                <i class="fa-solid fa-lock me-1" style="color:#dc2626"></i>Mot de passe
              </label>
              <input id="password" type="password" name="password"
                     class="form-control @error('password') is-invalid @enderror"
                     placeholder="••••••••"
                     required autocomplete="current-password">
              @error('password')
                <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
              @enderror
            </div>

            {{-- Remember me --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                <label class="form-check-label" for="remember_me" style="font-size:.88rem;">Se souvenir de moi</label>
              </div>
              @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link" style="font-size:.88rem;">
                  Mot de passe oublié ?
                </a>
              @endif
            </div>

            <button type="submit" class="btn btn-auth">
              <i class="fa-solid fa-right-to-bracket me-2"></i>Se connecter
            </button>

            <p class="text-center mt-4 mb-0" style="font-size:.9rem;color:#6b7280;">
              Pas encore de compte ?
              <a href="{{ route('register') }}" class="auth-link">Créer un compte</a>
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/front/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
