<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vérification de l'email — HeatAlert</title>
  <meta name="description" content="Vérifiez votre adresse email pour activer l'ensemble des services HeatAlert.">
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
      background: linear-gradient(135deg, rgba(220,38,38,.75) 0%, rgba(15,23,42,.85) 100%);
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
    .auth-photo-sub { color: rgba(255,255,255,.75); font-size: .95rem; line-height: 1.7; max-width: 380px; }
    .auth-stat-row { display: flex; gap: 30px; margin-top: 36px; }
    .auth-stat-val { font-family:'Montserrat',sans-serif; font-size: 1.8rem; font-weight: 800; color: #fff; }
    .auth-stat-lbl { font-size: .75rem; color: rgba(255,255,255,.5); text-transform: uppercase; letter-spacing: 1px; }

    /* ── RIGHT PANEL (form) ── */
    .auth-form-panel {
      background: #fff; display: flex; flex-direction: column;
      justify-content: center; padding: 60px 70px;
      overflow-y: auto;
    }
    .verify-badge {
      display: inline-flex; align-items: center; gap: 8px;
      background: #fee2e2; color: #dc2626; font-size: .82rem;
      font-weight: 700; padding: 6px 14px; border-radius: 20px;
      margin-bottom: 18px; width: fit-content;
    }
    .auth-form-title {
      font-family: 'Montserrat', sans-serif;
      font-size: 2rem; font-weight: 800; color: #0f172a;
      margin-bottom: 10px;
    }
    .auth-form-sub { color: #64748b; font-size: .95rem; margin-bottom: 26px; line-height: 1.6; }

    .btn-login {
      width: 100%; background: linear-gradient(135deg, #dc2626, #b91c1c);
      color: #fff; border: none; border-radius: 12px;
      padding: .85rem; font-size: 1rem; font-weight: 700;
      font-family: 'Inter', sans-serif;
      cursor: pointer; transition: .25s;
      box-shadow: 0 6px 20px rgba(220,38,38,.35);
      display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-login:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(220,38,38,.5); }

    .auth-switch { text-align: center; margin-top: 24px; font-size: .9rem; color: #6b7280; }
    .btn-logout-link {
      background: none; border: none; color: #64748b; font-weight: 600;
      font-size: .9rem; cursor: pointer; text-decoration: underline;
      transition: color .2s;
    }
    .btn-logout-link:hover { color: #dc2626; }

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
      <img src="{{ asset('assets/front/img/health/emergency-1.webp') }}" alt="HeatAlert - Vérification de l'adresse email">
      <div class="auth-photo-overlay"></div>

      <a href="{{ route('home') }}" class="auth-photo-logo">
        <div class="auth-logo-mark"><i class="fa-solid fa-temperature-high"></i></div>
        <span class="auth-logo-text">HeatAlert</span>
      </a>

      <div class="auth-photo-content">
        <h2 class="auth-photo-title">
          Vérification &<br>activation du compte.
        </h2>
        <p class="auth-photo-sub">
          Confirmez votre adresse email afin de recevoir vos alertes canicule en temps réel et sécuriser vos préférences régionales.
        </p>
        <div class="auth-stat-row">
          <div>
            <div class="auth-stat-val">24/7</div>
            <div class="auth-stat-lbl">Alertes directes</div>
          </div>
          <div>
            <div class="auth-stat-val">100%</div>
            <div class="auth-stat-lbl">Sécurisé</div>
          </div>
          <div>
            <div class="auth-stat-val">INM</div>
            <div class="auth-stat-lbl">Données fiables</div>
          </div>
        </div>
      </div>
    </div>

    {{-- ── RIGHT: FORM ── --}}
    <div class="auth-form-panel">
      <div class="verify-badge">
        <i class="fa-solid fa-envelope-circle-check"></i> Étape requise
      </div>

      <h1 class="auth-form-title">Vérifiez votre email</h1>
      <p class="auth-form-sub">
        Merci pour votre inscription ! Avant de commencer, veuillez vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer. Si vous ne l'avez pas reçu, nous pouvons vous en envoyer un nouveau avec plaisir.
      </p>

      @if (session('status') == 'verification-link-sent')
        <div style="background:#f0fdf4;border:1px solid #86efac;color:#166534;border-radius:12px;padding:14px 18px;margin-bottom:24px;font-size:.9rem;line-height:1.5;">
          <i class="fa-solid fa-circle-check me-2 text-success"></i>Un nouveau lien de vérification a été envoyé à l'adresse email que vous avez fournie lors de votre inscription.
        </div>
      @endif

      <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn-login">
          <i class="fa-solid fa-paper-plane me-1"></i>Renvoyer l'email de vérification
        </button>
      </form>

      <div class="auth-switch">
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
          @csrf
          <button type="submit" class="btn-logout-link">
            <i class="fa-solid fa-arrow-right-from-bracket me-1"></i>Se déconnecter
          </button>
        </form>
      </div>
    </div>

  </div>
  <script src="{{ asset('assets/front/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
