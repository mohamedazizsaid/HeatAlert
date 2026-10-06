<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HeatAlert — Alertes Météo & Canicule en Tunisie</title>
  <meta name="description" content="HeatAlert surveille les vagues de chaleur et alertes météo en Tunisie en temps réel. Protégez-vous avec nos conseils santé personnalisés.">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700;800;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Favicons — Logo HeatAlert (Thermomètre-Soleil) -->
  <link href="{{ asset('assets/front/img/favicon-heatalert.jpg') }}" rel="icon" type="image/jpeg">
  <link href="{{ asset('assets/front/img/favicon-heatalert.jpg') }}" rel="apple-touch-icon">

  <link href="{{ asset('assets/front/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/front/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/front/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/front/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/front/css/main.css') }}" rel="stylesheet">

  <style>
    :root {
      --ha-red:    #dc2626;
      --ha-dark:   #0f172a;
      --ha-mid:    #1e293b;
      --ha-accent: #ef4444;
      --ha-orange: #f97316;
      --ha-yellow: #fbbf24;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; overflow-x: hidden; background: var(--ha-dark); }

    /* ── SPLASH SCREEN ─────────────────────────────────── */
    #splash {
      position: fixed; inset: 0; z-index: 9999;
      background: var(--ha-dark);
      display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      transition: opacity .6s ease, transform .6s ease;
    }
    #splash.hidden { opacity: 0; pointer-events: none; transform: scale(1.04); }
    .splash-logo {
      display: flex; align-items: center; gap: 18px;
      animation: splashFadeDown .8s ease .2s both;
    }
    .splash-icon {
      width: 80px; height: 80px;
      background: linear-gradient(135deg, var(--ha-red), var(--ha-orange));
      border-radius: 22px;
      display: flex; align-items: center; justify-content: center;
      font-size: 2.6rem; color: #fff;
      box-shadow: 0 0 40px rgba(220,38,38,.5);
      animation: pulse 2s infinite;
    }
    .splash-wordmark { color: #fff; font-family: 'Montserrat', sans-serif; }
    .splash-wordmark h1 { font-size: 3rem; font-weight: 900; letter-spacing: -1px; line-height: 1; }
    .splash-wordmark span { font-size: .95rem; color: rgba(255,255,255,.5); letter-spacing: 3px; text-transform: uppercase; }
    .splash-bar {
      width: 280px; height: 4px; background: rgba(255,255,255,.1);
      border-radius: 4px; margin-top: 50px;
      animation: splashFadeDown .8s ease .5s both;
      overflow: hidden;
    }
    .splash-bar-fill {
      height: 100%;
      background: linear-gradient(90deg, var(--ha-red), var(--ha-orange), var(--ha-yellow));
      border-radius: 4px;
      animation: loadBar 1.8s ease .6s both;
    }
    .splash-tagline {
      margin-top: 18px; color: rgba(255,255,255,.4);
      font-size: .82rem; letter-spacing: 1.5px; text-transform: uppercase;
      animation: splashFadeDown .8s ease .7s both;
    }

    @keyframes splashFadeDown { from { opacity:0; transform: translateY(-20px); } to { opacity:1; transform: none; } }
    @keyframes loadBar { from { width: 0 } to { width: 100% } }
    @keyframes pulse {
      0%,100% { box-shadow: 0 0 30px rgba(220,38,38,.4); }
      50%      { box-shadow: 0 0 60px rgba(220,38,38,.8); }
    }

    /* ── SITE CONTENT ──────────────────────────────────── */
    #site-content { opacity: 0; transition: opacity .8s ease; }
    #site-content.visible { opacity: 1; }

    /* ── NAVBAR ─────────────────────────────────────────── */
    .ha-navbar {
      position: fixed; top: 0; left: 0; right: 0; z-index: 999;
      padding: 0 0;
      background: rgba(15,23,42,.0);
      backdrop-filter: blur(0px);
      transition: background .4s, backdrop-filter .4s, box-shadow .4s;
    }
    .ha-navbar.scrolled {
      background: rgba(15,23,42,.95);
      backdrop-filter: blur(20px);
      box-shadow: 0 4px 30px rgba(0,0,0,.4);
    }
    .nav-inner {
      max-width: 1200px; margin: 0 auto;
      padding: 18px 24px;
      display: flex; align-items: center; justify-content: space-between;
    }
    .nav-logo { display:flex; align-items:center; gap:12px; text-decoration:none; }
    .nav-logo-mark {
      width:38px; height:38px;
      background: linear-gradient(135deg,var(--ha-red),var(--ha-orange));
      border-radius:10px;
      display:flex; align-items:center; justify-content:center;
      font-size:1.1rem; color:#fff;
    }
    .nav-logo-text { font-family:'Montserrat',sans-serif; font-weight:800; font-size:1.25rem; color:#ffff; }
    .nav-links { display:flex; align-items:center; gap:32px; list-style:none; }
    .nav-links a { color:rgba(255,255,255,.8); text-decoration:none; font-size:.9rem; font-weight:500; transition:.2s; }
    .nav-links a:hover { color:#fff; }
    .nav-cta {
      display:flex; gap:10px;
    }
    .btn-outline-white {
      border:2px solid rgba(255,255,255,.3); color:#fff;
      padding:.45rem 1.1rem; border-radius:8px; font-size:.88rem; font-weight:600;
      text-decoration:none; transition:.2s;
    }
    .btn-outline-white:hover { background:rgba(255,255,255,.1); color:#fff; border-color:rgba(255,255,255,.6); }
    .btn-red {
      background:linear-gradient(135deg,var(--ha-red),var(--ha-accent));
      color:#fff; padding:.45rem 1.3rem; border-radius:8px;
      font-size:.88rem; font-weight:700; text-decoration:none; transition:.2s;
      box-shadow:0 4px 15px rgba(220,38,38,.3);
    }
    .btn-red:hover { transform:translateY(-2px); box-shadow:0 8px 25px rgba(220,38,38,.5); color:#fff; }

    /* ── HERO ────────────────────────────────────────────── */
    .hero-section {
      min-height: 100vh;
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
      position: relative; overflow: hidden;
      display: flex; align-items: center;
    }
    .hero-bg-pattern {
      position:absolute; inset:0;
      background-image:
        radial-gradient(circle at 20% 50%, rgba(220,38,38,.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(249,115,22,.1) 0%, transparent 40%),
        radial-gradient(circle at 60% 80%, rgba(251,191,36,.08) 0%, transparent 40%);
    }
    .hero-grid {
      position:absolute; inset:0;
      background-image: linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
      background-size: 60px 60px;
    }
    .hero-content {
      position: relative; z-index: 2;
      max-width: 1200px; margin: 0 auto;
      padding: 120px 24px 80px;
      display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center;
    }
    .hero-badge {
      display:inline-flex; align-items:center; gap:8px;
      background:rgba(220,38,38,.15); border:1px solid rgba(220,38,38,.3);
      color:#fca5a5; border-radius:50px; padding:.4rem 1rem; font-size:.8rem;
      font-weight:600; letter-spacing:.5px; margin-bottom:20px;
      animation: hero-in .8s ease 2.2s both;
    }
    .hero-badge .dot { width:8px; height:8px; background:var(--ha-red); border-radius:50%; animation:pulse 1.5s infinite; }
    .hero-title {
      font-family: 'Montserrat', sans-serif;
      font-size: clamp(2.5rem, 5vw, 3.8rem);
      font-weight: 900; color: #fff; line-height: 1.1;
      margin-bottom: 20px;
      animation: hero-in .8s ease 2.4s both;
    }
    .hero-title .grad {
      background: linear-gradient(135deg, var(--ha-red), var(--ha-orange), var(--ha-yellow));
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .hero-sub {
      color: rgba(255,255,255,.6); font-size: 1.05rem; line-height: 1.7;
      margin-bottom: 36px;
      animation: hero-in .8s ease 2.6s both;
    }
    .hero-actions {
      display:flex; gap:14px; flex-wrap:wrap;
      animation: hero-in .8s ease 2.8s both;
    }
    .hero-btn-primary {
      background: linear-gradient(135deg, var(--ha-red), #b91c1c);
      color:#fff; padding:.75rem 2rem; border-radius:12px;
      font-size:1rem; font-weight:700; text-decoration:none;
      display:inline-flex; align-items:center; gap:8px;
      box-shadow: 0 8px 30px rgba(220,38,38,.4);
      transition: .25s;
    }
    .hero-btn-primary:hover { transform:translateY(-3px); box-shadow:0 14px 40px rgba(220,38,38,.6); color:#fff; }
    .hero-btn-secondary {
      border:2px solid rgba(255,255,255,.25); color:#fff;
      padding:.7rem 1.8rem; border-radius:12px;
      font-size:1rem; font-weight:600; text-decoration:none;
      display:inline-flex; align-items:center; gap:8px;
      transition: .25s;
    }
    .hero-btn-secondary:hover { background:rgba(255,255,255,.1); color:#fff; border-color:rgba(255,255,255,.5); }

    /* ── HERO MAP CARD ───────────────────────────────────── */
    .hero-visual { animation: hero-in .8s ease 2.5s both; }
    .map-card {
      background: rgba(255,255,255,.05);
      border: 1px solid rgba(255,255,255,.1);
      border-radius: 24px; padding: 28px;
      backdrop-filter: blur(10px);
      position: relative; overflow: hidden;
    }
    .map-card::before {
      content:''; position:absolute; top:-1px; left:20%; right:20%; height:2px;
      background: linear-gradient(90deg, transparent, var(--ha-red), transparent);
    }
    .map-title { color:rgba(255,255,255,.6); font-size:.75rem; text-transform:uppercase; letter-spacing:2px; margin-bottom:16px; }
    .alert-item {
      display:flex; align-items:center; gap:14px;
      background:rgba(255,255,255,.04); border-radius:12px;
      padding:14px 16px; margin-bottom:10px;
      border-left:3px solid transparent;
      transition: .2s;
    }
    .alert-item.rouge  { border-color:var(--ha-red); }
    .alert-item.orange { border-color:var(--ha-orange); }
    .alert-item.jaune  { border-color:var(--ha-yellow); }
    .alert-icon { width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
    .alert-icon.rouge  { background:rgba(220,38,38,.2);  color:var(--ha-red);    }
    .alert-icon.orange { background:rgba(249,115,22,.2); color:var(--ha-orange); }
    .alert-icon.jaune  { background:rgba(251,191,36,.2); color:var(--ha-yellow); }
    .alert-meta { flex:1; min-width:0; }
    .alert-ville { color:#fff; font-size:.88rem; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .alert-type  { color:rgba(255,255,255,.4); font-size:.75rem; margin-top:2px; }
    .alert-temp  { color:rgba(255,255,255,.7); font-size:1rem; font-weight:700; }
    .map-stat-row { display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; margin-top:18px; }
    .map-stat { text-align:center; background:rgba(255,255,255,.03); border-radius:10px; padding:12px 8px; }
    .map-stat-val { font-size:1.5rem; font-weight:800; color:#fff; font-family:'Montserrat',sans-serif; }
    .map-stat-lbl { font-size:.72rem; color:rgba(255,255,255,.4); margin-top:4px; text-transform:uppercase; letter-spacing:.5px; }
    .live-dot { width:8px; height:8px; background:#22c55e; border-radius:50%; display:inline-block; animation:pulse 1.2s infinite; margin-right:6px; }

    /* ── FEATURES ────────────────────────────────────────── */
    .features-section { background:#fff; padding:100px 24px; }
    .section-badge { display:inline-block; background:rgba(220,38,38,.1); color:var(--ha-red); border-radius:50px; padding:.35rem 1rem; font-size:.8rem; font-weight:700; letter-spacing:1px; text-transform:uppercase; margin-bottom:14px; }
    .section-title { font-family:'Montserrat',sans-serif; font-size:clamp(1.8rem,3.5vw,2.6rem); font-weight:800; color:var(--ha-dark); line-height:1.2; margin-bottom:16px; }
    .section-sub { color:#64748b; font-size:1rem; max-width:520px; }
    .feature-card {
      border-radius:20px; padding:36px 28px;
      border:1px solid #f1f5f9; transition:.3s;
      height:100%;
    }
    .feature-card:hover { transform:translateY(-6px); box-shadow:0 20px 50px rgba(0,0,0,.08); border-color:rgba(220,38,38,.2); }
    .feature-icon {
      width:56px; height:56px; border-radius:14px;
      display:flex; align-items:center; justify-content:center;
      font-size:1.5rem; margin-bottom:20px;
    }

    /* ── ALERTS BAND ─────────────────────────────────────── */
    .alerts-band { background:linear-gradient(135deg,#0f172a,#1e293b); padding:80px 24px; }
    .alert-card-public {
      background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08);
      border-radius:16px; padding:24px; height:100%;
      transition:.3s;
    }
    .alert-card-public:hover { background:rgba(255,255,255,.08); transform:translateY(-4px); }
    .level-pill { display:inline-block; padding:.25rem .8rem; border-radius:50px; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; }
    .level-rouge  { background:rgba(220,38,38,.2);  color:#fca5a5; }
    .level-orange { background:rgba(249,115,22,.2); color:#fdba74; }
    .level-jaune  { background:rgba(251,191,36,.2); color:#fde68a; }
    .level-vert   { background:rgba(34,197,94,.2);  color:#86efac; }

    /* ── CTA BAND ────────────────────────────────────────── */
    .cta-section {
      background:linear-gradient(135deg, var(--ha-red) 0%, #b91c1c 50%, #7f1d1d 100%);
      padding:100px 24px; text-align:center; position:relative; overflow:hidden;
    }
    .cta-section::before {
      content:''; position:absolute; inset:0;
      background-image: radial-gradient(circle at 30% 50%, rgba(255,255,255,.08) 0%, transparent 50%),
                        radial-gradient(circle at 70% 50%, rgba(255,255,255,.05) 0%, transparent 50%);
    }

    /* ── FOOTER ─────────────────────────────────────────── */
    .ha-footer { background:var(--ha-dark); padding:60px 24px 30px; }
    .footer-inner { max-width:1200px; margin:0 auto; }
    .footer-logo { font-family:'Montserrat',sans-serif; font-size:1.4rem; font-weight:800; color:#fff; margin-bottom:10px; }
    .footer-desc { color:rgba(255,255,255,.4); font-size:.88rem; line-height:1.6; max-width:300px; }
    .footer-heading { color:rgba(255,255,255,.7); font-weight:700; font-size:.85rem; text-transform:uppercase; letter-spacing:1px; margin-bottom:16px; }
    .footer-links { list-style:none; }
    .footer-links li { margin-bottom:10px; }
    .footer-links a { color:rgba(255,255,255,.4); text-decoration:none; font-size:.88rem; transition:.2s; }
    .footer-links a:hover { color:#fff; }
    .footer-divider { border-color:rgba(255,255,255,.08); margin:30px 0; }
    .footer-copy { color:rgba(255,255,255,.3); font-size:.82rem; }

    @keyframes hero-in { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:none; } }

    @media(max-width:768px) {
      .hero-content { grid-template-columns:1fr; gap:40px; }
      .nav-links { display:none; }
    }
  </style>
</head>
<body>

  {{-- ═══════════════════════════════════════════ --}}
  {{-- SPLASH SCREEN --}}
  {{-- ═══════════════════════════════════════════ --}}
  <div id="splash">
    <div class="splash-logo">
      <div class="splash-icon">
        <i class="fa-solid fa-temperature-high"></i>
      </div>
      <div class="splash-wordmark">
        <h1 style="color:white">HeatAlert</h1>
        <span>Tunisia · Météo & Canicule</span>
      </div>
    </div>
    <div class="splash-bar">
      <div class="splash-bar-fill"></div>
    </div>
    <p class="splash-tagline">Chargement en cours…</p>
  </div>

  {{-- ═══════════════════════════════════════════ --}}
  {{-- CONTENU DU SITE --}}
  {{-- ═══════════════════════════════════════════ --}}
  <div id="site-content">

    {{-- NAVBAR --}}
    <nav class="ha-navbar" id="haNav">
      <div class="nav-inner">
        <a href="{{ route('home') }}" class="nav-logo">
          <div class="nav-logo-mark"><i class="fa-solid fa-temperature-high"></i></div>
          <span class="nav-logo-text">HeatAlert</span>
        </a>

        <ul class="nav-links">
          <li><a href="#features">Fonctionnalités</a></li>
          <li><a href="#alertes">Alertes</a></li>
          <li><a href="#footer">Contact</a></li>
        </ul>

        <div class="nav-cta">
          @auth
            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('front.home') }}" class="btn-red">
              <i class="fa-solid fa-gauge-high me-1"></i>Tableau de bord
            </a>
          @else
            <a href="{{ route('login') }}" class="btn-outline-white">Connexion</a>
            <a href="{{ route('register') }}" class="btn-red">S'inscrire</a>
          @endauth
        </div>
      </div>
    </nav>

    {{-- ─── HERO ─── --}}
    <section class="hero-section">
      <div class="hero-bg-pattern"></div>
      <div class="hero-grid"></div>

      <div class="hero-content container-fluid">
        <div>
          <div class="hero-badge">
            <span class="dot"></span>
            Tunisie · Surveillance météo en temps réel
          </div>

          <h1 class="hero-title">
            Restez protégé face aux<br>
            <span class="grad">vagues de chaleur</span>
          </h1>

          <p class="hero-sub">
            HeatAlert surveille la météo dans les 24 gouvernorats tunisiens et vous alerte instantanément en cas de canicule, sécheresse ou risque extrême.
          </p>

          <div class="hero-actions">
            @auth
              <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('front.home') }}" class="hero-btn-primary">
                <i class="fa-solid fa-gauge-high"></i>Mon tableau de bord
              </a>
            @else
              <a href="{{ route('register') }}" class="hero-btn-primary">
                <i class="fa-solid fa-user-plus"></i>Créer un compte gratuit
              </a>
              <a href="{{ route('login') }}" class="hero-btn-secondary">
                <i class="fa-solid fa-right-to-bracket"></i>Se connecter
              </a>
            @endauth
          </div>
        </div>

        {{-- Carte Live --}}
        <div class="hero-visual">
          <div class="map-card">
            <p class="map-title">
              <span class="live-dot"></span>
              Alertes actives — Tunisie
            </p>

            @forelse($alertesActives as $alerte)
            <div class="alert-item {{ $alerte->niveau }}">
              <div class="alert-icon {{ $alerte->niveau }}">
                <i class="fa-solid fa-{{ $alerte->type === 'canicule' ? 'sun' : ($alerte->type === 'secheresse' ? 'droplet-slash' : 'wind') }}"></i>
              </div>
              <div class="alert-meta">
                <div class="alert-ville">{{ $alerte->zone?->ville ?? 'Tunisie' }}</div>
                <div class="alert-type">{{ ucfirst(str_replace('_', ' ', $alerte->type)) }}</div>
              </div>
              <div class="alert-temp">
                {{ $alerte->temperature_max ? $alerte->temperature_max . '°' : '—' }}
              </div>
            </div>
            @empty
            <div class="alert-item vert">
              <div class="alert-icon vert" style="background:rgba(34,197,94,.2);color:#22c55e;">
                <i class="fa-solid fa-check-circle"></i>
              </div>
              <div class="alert-meta">
                <div class="alert-ville">Tunisie</div>
                <div class="alert-type">Aucune alerte active</div>
              </div>
            </div>
            @endforelse

            <div class="map-stat-row">
              <div class="map-stat">
                <div class="map-stat-val">{{ $stats['zones'] }}</div>
                <div class="map-stat-lbl">Zones</div>
              </div>
              <div class="map-stat">
                <div class="map-stat-val" style="color:var(--ha-accent);">{{ $stats['alertes'] }}</div>
                <div class="map-stat-lbl">Alertes</div>
              </div>
              <div class="map-stat">
                <div class="map-stat-val" style="color:var(--ha-red);">{{ $stats['rouge'] }}</div>
                <div class="map-stat-lbl">Rouges</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- ─── FEATURES ─── --}}
    <section class="features-section" id="features">
      <div class="container">
        <div class="row align-items-center mb-5" data-aos="fade-up">
          <div class="col-lg-5">
            <span class="section-badge">Fonctionnalités</span>
            <h2 class="section-title">Pourquoi choisir HeatAlert ?</h2>
          </div>
          <div class="col-lg-6 offset-lg-1">
            <p class="section-sub">Une plateforme complète pour surveiller, alerter et conseiller face aux risques météorologiques en Tunisie.</p>
          </div>
        </div>

        <div class="row g-4">
          <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="feature-card">
              <div class="feature-icon" style="background:rgba(220,38,38,.1);">
                <i class="fa-solid fa-triangle-exclamation" style="color:var(--ha-red);"></i>
              </div>
              <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:10px;">Alertes en temps réel</h3>
              <p style="color:#64748b;font-size:.9rem;line-height:1.7;">Recevez des alertes canicule, sécheresse et vents extrêmes dès leur déclenchement par l'INM Tunisie.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="feature-card">
              <div class="feature-icon" style="background:rgba(249,115,22,.1);">
                <i class="fa-solid fa-map-location-dot" style="color:var(--ha-orange);"></i>
              </div>
              <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:10px;">24 gouvernorats couverts</h3>
              <p style="color:#64748b;font-size:.9rem;line-height:1.7;">Couverture complète de tous les gouvernorats tunisiens avec données météo localisées et coordonnées GPS précises.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="feature-card">
              <div class="feature-icon" style="background:rgba(251,191,36,.1);">
                <i class="fa-solid fa-lightbulb" style="color:var(--ha-yellow);"></i>
              </div>
              <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:10px;">Conseils santé personnalisés</h3>
              <p style="color:#64748b;font-size:.9rem;line-height:1.7;">Des recommandations adaptées à chaque niveau d'alerte : hydratation, équipements, habitat, déplacement.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="feature-card">
              <div class="feature-icon" style="background:rgba(34,197,94,.1);">
                <i class="fa-solid fa-thermometer-half" style="color:#22c55e;"></i>
              </div>
              <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:10px;">Données météo complètes</h3>
              <p style="color:#64748b;font-size:.9rem;line-height:1.7;">Température min/max, ressentie, humidité, indice UV, vitesse du vent, risque de coupure électrique.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="feature-card">
              <div class="feature-icon" style="background:rgba(139,92,246,.1);">
                <i class="fa-solid fa-shield-heart" style="color:#8b5cf6;"></i>
              </div>
              <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:10px;">Protection des vulnérables</h3>
              <p style="color:#64748b;font-size:.9rem;line-height:1.7;">Conseils ciblés pour les enfants, personnes âgées, malades chroniques et sportifs en période de canicule.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="feature-card">
              <div class="feature-icon" style="background:rgba(6,182,212,.1);">
                <i class="fa-solid fa-bell" style="color:#06b6d4;"></i>
              </div>
              <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:10px;">Tableau de bord admin</h3>
              <p style="color:#64748b;font-size:.9rem;line-height:1.7;">Interface d'administration complète pour gérer les zones, les alertes et les conseils avec CRUD avancé.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- ─── ALERTES BAND ─── --}}
    <section class="alerts-band" id="alertes">
      <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
          <span class="section-badge" style="background:rgba(220,38,38,.2);color:#fca5a5;">En Direct</span>
          <h2 class="section-title" style="color:#fff;margin-top:10px;">Alertes météo actives</h2>
          <p style="color:rgba(255,255,255,.5);">Situation actuelle sur le territoire tunisien</p>
        </div>

        <div class="row g-4">
          @forelse($alertesActives as $alerte)
          <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
            <div class="alert-card-public">
              <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="level-pill level-{{ $alerte->niveau }}">
                  <i class="fa-solid fa-circle me-1" style="font-size:.5rem;"></i>
                  {{ ucfirst($alerte->niveau) }}
                </span>
                @if($alerte->temperature_max)
                <span style="color:#fff;font-size:1.3rem;font-weight:800;font-family:'Montserrat',sans-serif;">
                  {{ $alerte->temperature_max }}°C
                </span>
                @endif
              </div>
              <h3 style="color:#fff;font-size:1rem;font-weight:700;margin-bottom:8px;line-height:1.4;">
                {{ Str::limit($alerte->titre, 50) }}
              </h3>
              <p style="color:rgba(255,255,255,.5);font-size:.85rem;line-height:1.6;margin-bottom:16px;">
                {{ Str::limit($alerte->description, 100) }}
              </p>
              <div class="d-flex align-items-center gap-3">
                <span style="color:rgba(255,255,255,.4);font-size:.8rem;">
                  <i class="fa-solid fa-map-marker-alt me-1"></i>
                  {{ $alerte->zone?->ville ?? 'National' }}
                </span>
                <span style="color:rgba(255,255,255,.4);font-size:.8rem;">
                  <i class="fa-solid fa-calendar me-1"></i>
                  {{ $alerte->date_debut?->format('d/m/Y') }}
                </span>
              </div>
            </div>
          </div>
          @empty
          <div class="col-12 text-center py-5">
            <i class="fa-solid fa-check-circle fa-3x mb-3" style="color:#22c55e;opacity:.7;"></i>
            <p style="color:rgba(255,255,255,.4);">Aucune alerte active en ce moment.</p>
          </div>
          @endforelse
        </div>
      </div>
    </section>

    {{-- ─── CTA ─── --}}
    <section class="cta-section">
      <div class="container position-relative">
        <div data-aos="zoom-in">
          <h2 style="font-family:'Montserrat',sans-serif;font-size:clamp(1.8rem,4vw,3rem);font-weight:900;color:#fff;margin-bottom:16px;">
            Protégez-vous dès maintenant
          </h2>
          <p style="color:rgba(255,255,255,.75);font-size:1.1rem;max-width:500px;margin:0 auto 36px;">
            Inscrivez-vous gratuitement et recevez les alertes météo pour votre région tunisienne.
          </p>
          @guest
          <a href="{{ route('register') }}" class="btn btn-light btn-lg" style="border-radius:12px;font-weight:700;padding:.9rem 2.5rem;font-size:1.05rem;box-shadow:0 10px 30px rgba(0,0,0,.2);">
            <i class="fa-solid fa-user-plus me-2"></i>Créer mon compte gratuit
          </a>
          @endguest
        </div>
      </div>
    </section>

    {{-- ─── FOOTER ─── --}}
    <footer class="ha-footer" id="footer">
      <div class="footer-inner">
        <div class="row">
          <div class="col-md-4 mb-4">
            <div class="footer-logo"><i class="fa-solid fa-temperature-high me-2" style="color:var(--ha-red);"></i>HeatAlert</div>
            <p class="footer-desc">Plateforme tunisienne de surveillance des vagues de chaleur et alertes météo en temps réel.</p>
          </div>
          <div class="col-md-2 offset-md-2 mb-4">
            <p class="footer-heading">Navigation</p>
            <ul class="footer-links">
              <li><a href="{{ route('login') }}">Connexion</a></li>
              <li><a href="{{ route('register') }}">Inscription</a></li>
            </ul>
          </div>
          <div class="col-md-3 mb-4">
            <p class="footer-heading">Gouvernorats</p>
            <p style="color:rgba(255,255,255,.4);font-size:.85rem;line-height:1.8;">
              Tunis · Sfax · Sousse · Monastir · Gabès · Tozeur · Djerba-Médenine
            </p>
          </div>
        </div>
        <hr class="footer-divider">
        <p class="footer-copy text-center">
          &copy; {{ date('Y') }} HeatAlert — Tous droits réservés. Données météo : INM Tunisie.
        </p>
      </div>
    </footer>

  </div>{{-- / site-content --}}

  <script src="{{ asset('assets/front/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/front/vendor/aos/aos.js') }}"></script>
  <script>
    AOS.init({ once: true, duration: 700, offset: 60 });

    // Splash screen
    window.addEventListener('load', function () {
      setTimeout(function () {
        var splash = document.getElementById('splash');
        var content = document.getElementById('site-content');
        splash.classList.add('hidden');
        content.classList.add('visible');
        setTimeout(function () { splash.remove(); }, 700);
      }, 2000);
    });

    // Navbar scroll effect
    window.addEventListener('scroll', function () {
      document.getElementById('haNav').classList.toggle('scrolled', window.scrollY > 50);
    });
  </script>
</body>
</html>
