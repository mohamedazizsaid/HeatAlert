<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion — HeatAlert</title>
  <meta name="description" content="Connectez-vous à HeatAlert pour accéder aux alertes météo tunisiennes.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="{{ asset('assets/front/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/front/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; font-family: 'Inter', sans-serif; }

    /* ── SPLIT LAYOUT ── */
    .auth-split { display: grid; grid-template-columns: 1fr 1fr; min-height: 100vh; }

    /* ── LEFT PANEL (photo) ── */
    .auth-photo {
      position: relative; overflow: hidden;
      background: #0f172a;
    }
    .auth-photo img {
      position: absolute; inset: 0; width: 100%; height: 100%;
      object-fit: cover; opacity: .6;
      transform: scale(1.05);
      animation: subtleZoom 8s ease infinite alternate;
    }
    .auth-photo-overlay {
      position: absolute; inset: 0;
      background: linear-gradient(135deg, rgba(220,38,38,.7) 0%, rgba(15,23,42,.8) 100%);
    }
    .auth-photo-content {
      position: relative; z-index: 2;
      height: 100%; display: flex; flex-direction: column;
      justify-content: flex-end; padding: 60px 50px;
    }
    .auth-photo-logo {
      position: absolute; top: 40px; left: 50px;
      display: flex; align-items: center; gap: 14px;
      text-decoration: none;
    }
    .auth-logo-mark {
      width: 46px; height: 46px;
      background: rgba(255,255,255,.15); backdrop-filter: blur(10px);
      border: 1px solid rgba(255,255,255,.25);
      border-radius: 14px; display: flex; align-items: center; justify-content: center;
      font-size: 1.3rem; color: #fff;
    }
    .auth-logo-text { font-family:'Montserrat',sans-serif; font-weight:800; font-size:1.3rem; color:#fff; }
    .auth-photo-title {
      font-family: 'Montserrat', sans-serif;
      font-size: 2.5rem; font-weight: 900; color: #fff;
      line-height: 1.15; margin-bottom: 16px;
    }
    .auth-photo-sub { color: rgba(255,255,255,.7); font-size: .95rem; line-height: 1.7; max-width: 360px; }
    .auth-stat-row { display: flex; gap: 30px; margin-top: 36px; }
    .auth-stat-val { font-family:'Montserrat',sans-serif; font-size: 1.8rem; font-weight: 800; color: #fff; }
    .auth-stat-lbl { font-size: .75rem; color: rgba(255,255,255,.5); text-transform: uppercase; letter-spacing: 1px; }

    /* ── RIGHT PANEL (form) ── */
    .auth-form-panel {
      background: #fff; display: flex; flex-direction: column;
      justify-content: center; padding: 60px 70px;
      overflow-y: auto;
    }
    .auth-form-title {
      font-family: 'Montserrat', sans-serif;
      font-size: 2rem; font-weight: 800; color: #0f172a;
      margin-bottom: 6px;
    }
    .auth-form-sub { color: #64748b; font-size: .9rem; margin-bottom: 36px; }

    .form-group { margin-bottom: 20px; }
    .form-label { font-weight: 600; font-size: .83rem; color: #374151; margin-bottom: 7px; display: block; }
    .form-control {
      width: 100%; border: 2px solid #e5e7eb; border-radius: 12px;
      padding: .7rem 1rem .7rem 2.8rem;
      font-size: .93rem; font-family:'Inter',sans-serif;
      transition: border-color .2s, box-shadow .2s;
      background: #f9fafb;
    }
    .form-control:focus {
      outline: none; border-color: #dc2626;
      box-shadow: 0 0 0 3px rgba(220,38,38,.12);
      background: #fff;
    }
    .form-control.is-invalid { border-color: #ef4444; }
    .invalid-feedback { color: #ef4444; font-size: .8rem; margin-top: 5px; display: block; }
    .input-wrap { position: relative; }
    .input-icon {
      position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
      color: #9ca3af; font-size: .9rem; pointer-events: none;
    }

    .btn-login {
      width: 100%; background: linear-gradient(135deg, #dc2626, #b91c1c);
      color: #fff; border: none; border-radius: 12px;
      padding: .8rem; font-size: 1rem; font-weight: 700;
      font-family: 'Inter', sans-serif;
      cursor: pointer; transition: .25s;
      box-shadow: 0 6px 20px rgba(220,38,38,.35);
      display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-login:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(220,38,38,.5); }

    .auth-divider { text-align: center; margin: 20px 0; color: #d1d5db; font-size: .8rem; position: relative; }
    .auth-divider::before, .auth-divider::after {
      content: ''; position: absolute; top: 50%; width: 40%; height: 1px; background: #e5e7eb;
    }
    .auth-divider::before { left: 0; }
    .auth-divider::after { right: 0; }

    .auth-switch { text-align: center; margin-top: 28px; font-size: .9rem; color: #6b7280; }
    .auth-switch a { color: #dc2626; font-weight: 700; text-decoration: none; }
    .auth-switch a:hover { text-decoration: underline; }

    .remember-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
    .form-check-label { font-size: .85rem; color: #6b7280; margin-left: 6px; }
    .forgot-link { font-size: .84rem; color: #dc2626; text-decoration: none; font-weight: 600; }
    .forgot-link:hover { text-decoration: underline; }

    @keyframes subtleZoom { from { transform: scale(1.05); } to { transform: scale(1.12); } }

    @media (max-width: 768px) {
      .auth-split { grid-template-columns: 1fr; }
      .auth-photo { min-height: 220px; }
      .auth-form-panel { padding: 40px 28px; }
      .auth-photo-content { padding: 30px; }
      .auth-photo-title { font-size: 1.6rem; }
    }
  </style>
</head>
<body>
  <div class="auth-split">

    {{-- ── LEFT: PHOTO ── --}}
    <div class="auth-photo">
      <img src="{{ asset('assets/front/img/health/emergency-1.webp') }}" alt="HeatAlert - Protection contre la canicule">
      <div class="auth-photo-overlay"></div>

      <a href="{{ route('home') }}" class="auth-photo-logo">
        <div class="auth-logo-mark"><i class="fa-solid fa-temperature-high"></i></div>
        <span class="auth-logo-text">HeatAlert</span>
      </a>

      <div class="auth-photo-content">
        <h2 class="auth-photo-title">
          La météo,<br>votre santé,<br>votre priorité.
        </h2>
        <p class="auth-photo-sub">
          HeatAlert surveille les 24 gouvernorats tunisiens et vous protège face aux vagues de chaleur extrême.
        </p>
        <div class="auth-stat-row">
          <div>
            <div class="auth-stat-val">24</div>
            <div class="auth-stat-lbl">Gouvernorats</div>
          </div>
          <div>
            <div class="auth-stat-val">INM</div>
            <div class="auth-stat-lbl">Source officielle</div>
          </div>
          <div>
            <div class="auth-stat-val">24/7</div>
            <div class="auth-stat-lbl">Surveillance</div>
          </div>
        </div>
      </div>
    </div>

    {{-- ── RIGHT: FORM ── --}}
    <div class="auth-form-panel">
      <h1 class="auth-form-title">Bon retour !</h1>
      <p class="auth-form-sub">Connectez-vous pour accéder à votre espace de surveillance météo.</p>

      @if(session('status'))
        <div style="background:#f0fdf4;border:1px solid #86efac;color:#166534;border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:.88rem;">
          <i class="fa-solid fa-circle-check me-2"></i>{{ session('status') }}
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        {{-- Email --}}
        <div class="form-group">
          <label for="email" class="form-label">Adresse email</label>
          <div class="input-wrap">
            <span class="input-icon"><i class="fa-solid fa-envelope"></i></span>
            <input id="email" type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}"
                   placeholder="votre@email.com"
                   required autofocus autocomplete="username">
          </div>
          @error('email')
            <span class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</span>
          @enderror
        </div>

        {{-- Password --}}
        <div class="form-group">
          <label for="password" class="form-label">Mot de passe</label>
          <div class="input-wrap">
            <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="••••••••"
                   required autocomplete="current-password">
          </div>
          @error('password')
            <span class="invalid-feedback"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</span>
          @enderror
        </div>

        {{-- Remember / Forgot --}}
        <div class="remember-row">
          <label style="display:flex;align-items:center;cursor:pointer;">
            <input type="checkbox" name="remember" id="remember_me" style="accent-color:#dc2626;width:15px;height:15px;">
            <span class="form-check-label">Se souvenir de moi</span>
          </label>
          @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
          @endif
        </div>

        <button type="submit" class="btn-login">
          <i class="fa-solid fa-right-to-bracket"></i>Se connecter
        </button>

        <div class="auth-switch">
          Pas encore de compte ? <a href="{{ route('register') }}">Créer un compte</a>
        </div>
      </form>
    </div>

  </div>
  <script src="{{ asset('assets/front/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
