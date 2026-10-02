<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription — HeatAlert</title>
  <meta name="description" content="Créez votre compte HeatAlert pour recevoir des alertes météo en Tunisie.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="{{ asset('assets/front/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/front/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; font-family: 'Inter', sans-serif; }

    /* ── SPLIT (inverse: form left, photo right) ── */
    .auth-split { display: grid; grid-template-columns: 1fr 1fr; min-height: 100vh; }

    /* ── LEFT PANEL (form) ── */
    .auth-form-panel {
      background: #fff; display: flex; flex-direction: column;
      justify-content: center; padding: 50px 60px;
      overflow-y: auto;
    }
    .auth-form-title {
      font-family: 'Montserrat', sans-serif;
      font-size: 1.8rem; font-weight: 800; color: #0f172a; margin-bottom: 6px;
    }
    .auth-form-sub { color: #64748b; font-size: .88rem; margin-bottom: 30px; }

    /* ── STEP PROGRESS ── */
    .step-progress {
      display: flex; align-items: center; gap: 0; margin-bottom: 34px;
    }
    .step-item {
      display: flex; align-items: center; gap: 10px; flex: 1;
    }
    .step-dot {
      width: 34px; height: 34px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: .82rem; font-weight: 700; flex-shrink: 0;
      border: 2px solid #e5e7eb; color: #9ca3af; background: #fff;
      transition: .3s;
    }
    .step-dot.active { border-color: #dc2626; background: #dc2626; color: #fff; box-shadow: 0 4px 12px rgba(220,38,38,.3); }
    .step-dot.done   { border-color: #dc2626; background: #fff; color: #dc2626; }
    .step-label { font-size: .8rem; font-weight: 600; color: #9ca3af; white-space: nowrap; }
    .step-label.active { color: #dc2626; }
    .step-line { flex: 1; height: 2px; background: #e5e7eb; margin: 0 8px; }
    .step-line.done { background: #dc2626; }

    /* ── STEPS ── */
    .step-pane { display: none; animation: fadeSlide .3s ease; }
    .step-pane.active { display: block; }
    @keyframes fadeSlide { from { opacity:0; transform:translateX(20px); } to { opacity:1; transform:none; } }

    /* ── FORM ELEMENTS ── */
    .form-group { margin-bottom: 18px; }
    .form-label { font-weight: 600; font-size: .82rem; color: #374151; margin-bottom: 7px; display: block; }
    .form-control, .form-select {
      width: 100%; border: 2px solid #e5e7eb; border-radius: 12px;
      padding: .65rem 1rem .65rem 2.7rem;
      font-size: .92rem; font-family: 'Inter', sans-serif;
      transition: border-color .2s, box-shadow .2s;
      background: #f9fafb; color: #0f172a;
    }
    .form-select { padding-left: 1rem; appearance: auto; }
    .form-control:focus, .form-select:focus {
      outline: none; border-color: #dc2626;
      box-shadow: 0 0 0 3px rgba(220,38,38,.12); background: #fff;
    }
    .form-control.is-invalid { border-color: #ef4444; }
    .invalid-feedback { color: #ef4444; font-size: .79rem; margin-top: 5px; display: block; }
    .input-wrap { position: relative; }
    .input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: .88rem; pointer-events: none; }

    .btn-next, .btn-submit {
      width: 100%; border: none; border-radius: 12px;
      padding: .78rem; font-size: .97rem; font-weight: 700;
      font-family: 'Inter', sans-serif; cursor: pointer; transition: .25s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-next {
      background: linear-gradient(135deg, #dc2626, #b91c1c); color: #fff;
      box-shadow: 0 6px 20px rgba(220,38,38,.3);
    }
    .btn-next:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(220,38,38,.5); }
    .btn-submit {
      background: linear-gradient(135deg, #16a34a, #15803d); color: #fff;
      box-shadow: 0 6px 20px rgba(22,163,74,.3);
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(22,163,74,.5); }
    .btn-back {
      background: none; border: 2px solid #e5e7eb; color: #6b7280;
      border-radius: 12px; padding: .7rem; font-size: .9rem; font-weight: 600;
      cursor: pointer; transition: .2s; width: 100%; font-family:'Inter',sans-serif;
      display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-back:hover { border-color: #dc2626; color: #dc2626; }

    .password-strength {
      height: 4px; background: #e5e7eb; border-radius: 4px; margin-top: 8px; overflow: hidden;
    }
    .password-strength-bar { height: 100%; border-radius: 4px; width: 0%; transition: width .3s, background .3s; }

    .auth-switch { text-align: center; margin-top: 22px; font-size: .88rem; color: #6b7280; }
    .auth-switch a { color: #dc2626; font-weight: 700; text-decoration: none; }
    .auth-switch a:hover { text-decoration: underline; }

    /* ── RIGHT PANEL (photo) ── */
    .auth-photo {
      position: relative; overflow: hidden; background: #0f172a;
    }
    .auth-photo img {
      position: absolute; inset: 0; width: 100%; height: 100%;
      object-fit: cover; opacity: .55;
      animation: subtleZoom 8s ease infinite alternate;
    }
    .auth-photo-overlay {
      position: absolute; inset: 0;
      background: linear-gradient(225deg, rgba(220,38,38,.65) 0%, rgba(15,23,42,.85) 100%);
    }
    .auth-photo-content {
      position: relative; z-index: 2; height: 100%;
      display: flex; flex-direction: column; justify-content: center;
      padding: 60px 50px;
    }
    .auth-step-visual { margin-bottom: 40px; }
    .step-visual-title {
      font-family: 'Montserrat', sans-serif;
      font-size: 2.2rem; font-weight: 900; color: #fff; line-height: 1.2; margin-bottom: 14px;
    }
    .step-visual-sub { color: rgba(255,255,255,.65); font-size: .92rem; line-height: 1.7; }
    .benefit-list { list-style: none; margin-top: 30px; }
    .benefit-list li {
      display: flex; align-items: center; gap: 12px;
      color: rgba(255,255,255,.8); font-size: .9rem; margin-bottom: 14px;
    }
    .benefit-icon {
      width: 32px; height: 32px; border-radius: 8px;
      background: rgba(255,255,255,.15); backdrop-filter: blur(10px);
      display: flex; align-items: center; justify-content: center;
      font-size: .9rem; color: #fff; flex-shrink: 0;
    }
    .auth-photo-logo {
      position: absolute; top: 36px; left: 50px;
      display: flex; align-items: center; gap: 12px; text-decoration: none;
    }
    .auth-logo-mark {
      width: 42px; height: 42px;
      background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
      border-radius: 12px; display: flex; align-items: center; justify-content: center;
      font-size: 1.2rem; color: #fff;
    }
    .auth-logo-text { font-family:'Montserrat',sans-serif; font-weight:800; font-size:1.2rem; color:#fff; }

    @keyframes subtleZoom { from { transform: scale(1.03); } to { transform: scale(1.1); } }

    @media (max-width: 768px) {
      .auth-split { grid-template-columns: 1fr; }
      .auth-photo { min-height: 200px; display: none; }
      .auth-form-panel { padding: 40px 26px; }
    }
  </style>
</head>
<body>
<div class="auth-split">

  {{-- ── LEFT: FORM (2 étapes) ── --}}
  <div class="auth-form-panel">
    <h1 class="auth-form-title">Créer un compte</h1>
    <p class="auth-form-sub">Rejoignez HeatAlert en 2 étapes rapides.</p>

    {{-- Barre de progression --}}
    <div class="step-progress">
      <div class="step-item">
        <div class="step-dot active" id="dot-1">1</div>
        <div class="step-label active" id="lbl-1">Identité</div>
      </div>
      <div class="step-line" id="line-12"></div>
      <div class="step-item">
        <div class="step-dot" id="dot-2">2</div>
        <div class="step-label" id="lbl-2">Sécurité</div>
      </div>
    </div>

    {{-- Erreurs serveur --}}
    @if($errors->any())
      <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:.85rem;">
        <i class="fa-solid fa-circle-exclamation me-2"></i>
        <strong>Veuillez corriger les erreurs suivantes :</strong>
        <ul style="margin-top:6px;padding-left:16px;">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('register') }}" novalidate id="register-form">
      @csrf

      {{-- ════ ÉTAPE 1 : Identité ════ --}}
      <div class="step-pane active" id="step-1">

        <div class="row g-3 mb-0">
          <div class="col-6">
            <div class="form-group">
              <label for="prenom" class="form-label">Prénom <span style="color:#ef4444">*</span></label>
              <div class="input-wrap">
                <span class="input-icon"><i class="fa-solid fa-user"></i></span>
                <input id="prenom" type="text" name="prenom"
                       class="form-control @error('prenom') is-invalid @enderror"
                       value="{{ old('prenom') }}"
                       placeholder="Mohamed" maxlength="60" autocomplete="given-name">
              </div>
              @error('prenom')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="nom" class="form-label">Nom <span style="color:#ef4444">*</span></label>
              <div class="input-wrap">
                <span class="input-icon"><i class="fa-solid fa-user"></i></span>
                <input id="nom" type="text" name="nom"
                       class="form-control @error('nom') is-invalid @enderror"
                       value="{{ old('nom') }}"
                       placeholder="Ben Ali" maxlength="60" autocomplete="family-name">
              </div>
              @error('nom')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="email" class="form-label">Email <span style="color:#ef4444">*</span></label>
          <div class="input-wrap">
            <span class="input-icon"><i class="fa-solid fa-envelope"></i></span>
            <input id="email" type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}"
                   placeholder="votre@email.com" autocomplete="email">
          </div>
          @error('email')
            <span class="invalid-feedback">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="telephone" class="form-label">Téléphone</label>
          <div class="input-wrap">
            <span class="input-icon"><i class="fa-solid fa-phone"></i></span>
            <input id="telephone" type="tel" name="telephone"
                   class="form-control @error('telephone') is-invalid @enderror"
                   value="{{ old('telephone') }}"
                   placeholder="+216 20 000 000">
          </div>
          @error('telephone')
            <span class="invalid-feedback">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="zone_id" class="form-label">Gouvernorat <span style="color:#dc2626">🌍</span></label>
          <select id="zone_id" name="zone_id" class="form-select @error('zone_id') is-invalid @enderror">
            <option value="">— Choisir votre région —</option>
            @foreach($zones as $zone)
              <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                {{ $zone->ville }} — {{ $zone->gouvernorat }}
              </option>
            @endforeach
          </select>
          @error('zone_id')
            <span class="invalid-feedback">{{ $message }}</span>
          @enderror
          <p style="font-size:.75rem;color:#9ca3af;margin-top:5px;">Pour des alertes adaptées à votre zone.</p>
        </div>

        <button type="button" class="btn-next" id="btn-next">
          Continuer <i class="fa-solid fa-arrow-right"></i>
        </button>

        <div class="auth-switch">
          Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a>
        </div>
      </div>

      {{-- ════ ÉTAPE 2 : Sécurité ════ --}}
      <div class="step-pane" id="step-2">

        <div class="form-group">
          <label for="password" class="form-label">Mot de passe <span style="color:#ef4444">*</span></label>
          <div class="input-wrap">
            <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Min. 8 caractères" autocomplete="new-password">
          </div>
          <div class="password-strength">
            <div class="password-strength-bar" id="pwd-bar"></div>
          </div>
          <p id="pwd-hint" style="font-size:.75rem;color:#9ca3af;margin-top:5px;">Utilisez lettres, chiffres et symboles.</p>
          @error('password')
            <span class="invalid-feedback">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group" style="margin-bottom:28px;">
          <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span style="color:#ef4444">*</span></label>
          <div class="input-wrap">
            <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="form-control"
                   placeholder="Répétez le mot de passe" autocomplete="new-password">
          </div>
          <p id="pwd-match-hint" style="font-size:.75rem;color:#9ca3af;margin-top:5px;"></p>
        </div>

        {{-- Résumé Étape 1 --}}
        <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:12px;padding:16px;margin-bottom:24px;">
          <p style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#9ca3af;margin-bottom:10px;font-weight:700;">Vos informations</p>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:.85rem;color:#374151;">
            <div><i class="fa-solid fa-user me-2 text-muted"></i><span id="summary-name">—</span></div>
            <div><i class="fa-solid fa-envelope me-2 text-muted"></i><span id="summary-email">—</span></div>
            <div><i class="fa-solid fa-phone me-2 text-muted"></i><span id="summary-phone">—</span></div>
            <div><i class="fa-solid fa-map-marker-alt me-2 text-muted"></i><span id="summary-zone">—</span></div>
          </div>
        </div>

        <div class="d-grid gap-2">
          <button type="submit" class="btn-submit">
            <i class="fa-solid fa-check-circle"></i>Créer mon compte
          </button>
          <button type="button" class="btn-back" id="btn-back">
            <i class="fa-solid fa-arrow-left"></i>Retour
          </button>
        </div>

        <div class="auth-switch" style="margin-top:18px;">
          Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a>
        </div>
      </div>

    </form>
  </div>

  {{-- ── RIGHT: PHOTO ── --}}
  <div class="auth-photo">
    <img src="{{ asset('assets/front/img/health/emergency-2.webp') }}" alt="Alerte canicule Tunisie">
    <div class="auth-photo-overlay"></div>

    <a href="{{ route('home') }}" class="auth-photo-logo">
      <div class="auth-logo-mark"><i class="fa-solid fa-temperature-high"></i></div>
      <span class="auth-logo-text">HeatAlert</span>
    </a>

    <div class="auth-photo-content">
      <div class="auth-step-visual" id="photo-step-1">
        <h2 class="step-visual-title">Bienvenue sur<br>HeatAlert !</h2>
        <p class="step-visual-sub">Renseignez vos informations personnelles pour créer votre compte de surveillance météo.</p>
        <ul class="benefit-list">
          <li>
            <div class="benefit-icon"><i class="fa-solid fa-bell"></i></div>
            Alertes en temps réel pour votre région
          </li>
          <li>
            <div class="benefit-icon"><i class="fa-solid fa-lightbulb"></i></div>
            Conseils santé adaptés à chaque alerte
          </li>
          <li>
            <div class="benefit-icon"><i class="fa-solid fa-map-location-dot"></i></div>
            Couverture de 24 gouvernorats tunisiens
          </li>
          <li>
            <div class="benefit-icon"><i class="fa-solid fa-shield-heart"></i></div>
            Protection des personnes vulnérables
          </li>
        </ul>
      </div>

      <div class="auth-step-visual" id="photo-step-2" style="display:none;">
        <h2 class="step-visual-title">Presque<br>terminé !</h2>
        <p class="step-visual-sub">Sécurisez votre compte avec un mot de passe robuste pour protéger vos données.</p>
        <ul class="benefit-list">
          <li>
            <div class="benefit-icon"><i class="fa-solid fa-lock"></i></div>
            Données chiffrées et sécurisées
          </li>
          <li>
            <div class="benefit-icon"><i class="fa-solid fa-user-shield"></i></div>
            Compte personnel protégé
          </li>
          <li>
            <div class="benefit-icon"><i class="fa-solid fa-check-circle"></i></div>
            Accès immédiat après inscription
          </li>
        </ul>
      </div>
    </div>
  </div>

</div>

<script src="{{ asset('assets/front/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script>
  // ── Éléments DOM
  const step1 = document.getElementById('step-1');
  const step2 = document.getElementById('step-2');
  const dot1 = document.getElementById('dot-1');  const lbl1 = document.getElementById('lbl-1');
  const dot2 = document.getElementById('dot-2');  const lbl2 = document.getElementById('lbl-2');
  const line12 = document.getElementById('line-12');
  const photoStep1 = document.getElementById('photo-step-1');
  const photoStep2 = document.getElementById('photo-step-2');

  // ── Champs étape 1
  const fPrenom = document.getElementById('prenom');
  const fNom    = document.getElementById('nom');
  const fEmail  = document.getElementById('email');
  const fPhone  = document.getElementById('telephone');
  const fZone   = document.getElementById('zone_id');

  // ── Aller à l'étape 2
  document.getElementById('btn-next').addEventListener('click', function () {
    let valid = true;

    [fPrenom, fNom, fEmail].forEach(function(f) {
      f.classList.remove('is-invalid');
      if (!f.value.trim()) { f.classList.add('is-invalid'); valid = false; }
    });
    if (fEmail.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(fEmail.value)) {
      fEmail.classList.add('is-invalid'); valid = false;
    }

    if (!valid) return;

    // Mise à jour du résumé
    document.getElementById('summary-name').textContent  = fPrenom.value + ' ' + fNom.value;
    document.getElementById('summary-email').textContent = fEmail.value;
    document.getElementById('summary-phone').textContent = fPhone.value || '—';
    const zoneOption = fZone.options[fZone.selectedIndex];
    document.getElementById('summary-zone').textContent  = zoneOption.value ? zoneOption.text : '—';

    // Transition vers étape 2
    step1.classList.remove('active'); step2.classList.add('active');
    dot1.classList.remove('active'); dot1.classList.add('done'); dot1.innerHTML = '<i class="fa-solid fa-check" style="font-size:.7rem;"></i>';
    dot2.classList.add('active'); lbl2.classList.add('active');
    line12.classList.add('done');
    photoStep1.style.display = 'none'; photoStep2.style.display = 'block';
    document.getElementById('password').focus();
  });

  // ── Retour étape 1
  document.getElementById('btn-back').addEventListener('click', function () {
    step2.classList.remove('active'); step1.classList.add('active');
    dot1.classList.remove('done'); dot1.classList.add('active'); dot1.innerHTML = '1';
    dot2.classList.remove('active'); lbl2.classList.remove('active');
    line12.classList.remove('done');
    photoStep2.style.display = 'none'; photoStep1.style.display = 'block';
  });

  // ── Force du mot de passe
  document.getElementById('password').addEventListener('input', function () {
    const v = this.value;
    const bar = document.getElementById('pwd-bar');
    const hint = document.getElementById('pwd-hint');
    let score = 0;
    if (v.length >= 8) score++;
    if (/[A-Z]/.test(v)) score++;
    if (/[0-9]/.test(v)) score++;
    if (/[^A-Za-z0-9]/.test(v)) score++;

    const widths = ['0%','30%','55%','80%','100%'];
    const colors = ['#e5e7eb','#ef4444','#f97316','#fbbf24','#22c55e'];
    const labels = ['','Faible','Moyen','Fort','Très fort'];
    bar.style.width = widths[score];
    bar.style.background = colors[score];
    hint.textContent = labels[score] ? 'Force : ' + labels[score] : 'Utilisez lettres, chiffres et symboles.';
    hint.style.color = colors[score];
  });

  // ── Confirmation mot de passe
  document.getElementById('password_confirmation').addEventListener('input', function () {
    const hint = document.getElementById('pwd-match-hint');
    const match = this.value === document.getElementById('password').value;
    hint.textContent = this.value ? (match ? '✓ Les mots de passe correspondent.' : '✗ Les mots de passe ne correspondent pas.') : '';
    hint.style.color = match ? '#22c55e' : '#ef4444';
  });

  // ── Si erreur serveur, montrer étape 2 si erreur de password
  @if($errors->hasAny(['password']))
    document.getElementById('btn-next').click();
  @endif
</script>
</body>
</html>
