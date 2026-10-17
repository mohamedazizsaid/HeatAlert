<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Authentification à deux facteurs — HeatAlert</title>
  <meta name="description" content="Saisissez le code de votre application d'authentification pour accéder à HeatAlert.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="{{ asset('assets/front/img/favicon-heatalert.jpg') }}" rel="icon" type="image/jpeg">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="{{ asset('assets/front/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/front/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; font-family: 'Inter', sans-serif; }

    .twofa-wrap {
      min-height: 100vh;
      display: flex;
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #0d1117 100%);
    }

    /* LEFT: branding panel */
    .twofa-brand {
      flex: 0 0 420px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 60px 50px;
      position: relative;
      overflow: hidden;
    }
    .twofa-brand::before {
      content: '';
      position: absolute; inset: 0;
      background: radial-gradient(ellipse at 30% 50%, rgba(220,38,38,0.15) 0%, transparent 70%);
    }
    .twofa-brand-logo {
      display: flex; align-items: center; gap: 14px;
      margin-bottom: 50px; position: relative;
    }
    .brand-logo-mark {
      width: 50px; height: 50px;
      background: linear-gradient(135deg, #dc2626, #b91c1c);
      border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.4rem; color: #fff;
      box-shadow: 0 8px 24px rgba(220,38,38,.4);
    }
    .brand-logo-text { font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 1.4rem; color: #fff; }
    .brand-title {
      font-family: 'Montserrat', sans-serif;
      font-size: 2rem; font-weight: 900; color: #fff;
      line-height: 1.2; margin-bottom: 16px; position: relative;
    }
    .brand-sub { color: rgba(255,255,255,.6); font-size: .92rem; line-height: 1.7; position: relative; }
    .shield-visual {
      margin-top: 44px;
      background: rgba(255,255,255,.05);
      border: 1px solid rgba(255,255,255,.1);
      border-radius: 20px;
      padding: 28px;
      position: relative;
    }
    .shield-icon {
      font-size: 3.5rem; text-align: center; display: block;
      margin-bottom: 16px;
      animation: pulse 2.5s ease-in-out infinite;
    }
    @keyframes pulse { 0%,100% { transform: scale(1); } 50% { transform: scale(1.08); } }
    .shield-list { list-style: none; }
    .shield-list li {
      display: flex; align-items: center; gap: 10px;
      color: rgba(255,255,255,.75); font-size: .87rem; margin-bottom: 10px;
    }
    .shield-list li i { color: #22c55e; }

    /* RIGHT: form panel */
    .twofa-form-panel {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 60px 40px;
    }
    .twofa-card {
      background: #fff;
      border-radius: 24px;
      padding: 52px 48px;
      width: 100%;
      max-width: 440px;
      box-shadow: 0 24px 80px rgba(0,0,0,.4);
      position: relative;
      overflow: hidden;
    }
    .twofa-card::before {
      content: '';
      position: absolute; top: 0; left: 0; right: 0;
      height: 5px;
      background: linear-gradient(90deg, #dc2626, #f59e0b, #22c55e, #3b82f6, #dc2626);
      background-size: 300% 100%;
      animation: gradMove 4s linear infinite;
    }
    @keyframes gradMove { 0% { background-position: 0% 0; } 100% { background-position: 300% 0; } }

    .twofa-heading-icon {
      width: 68px; height: 68px;
      border-radius: 18px;
      background: linear-gradient(135deg, #1e293b, #0f172a);
      display: flex; align-items: center; justify-content: center;
      font-size: 1.8rem; margin: 0 auto 20px;
      box-shadow: 0 8px 24px rgba(15,23,42,.25);
    }
    .twofa-title {
      font-family: 'Montserrat', sans-serif;
      font-size: 1.5rem; font-weight: 800; color: #0f172a;
      text-align: center; margin-bottom: 8px;
    }
    .twofa-sub {
      color: #64748b; font-size: .875rem; text-align: center;
      line-height: 1.6; margin-bottom: 32px;
    }

    /* OTP digits */
    .otp-inputs {
      display: flex; gap: 10px; justify-content: center;
      margin-bottom: 28px;
    }
    .otp-digit {
      width: 52px; height: 62px;
      border: 2.5px solid #e5e7eb;
      border-radius: 14px;
      text-align: center;
      font-size: 1.5rem; font-weight: 800; color: #0f172a;
      font-family: 'Montserrat', monospace;
      background: #f9fafb;
      transition: all .2s;
      outline: none;
    }
    .otp-digit:focus {
      border-color: #dc2626;
      box-shadow: 0 0 0 4px rgba(220,38,38,.12);
      background: #fff;
    }
    .otp-digit.filled { border-color: #3b82f6; background: #eff6ff; color: #1d4ed8; }
    .otp-digit.error  { border-color: #ef4444; background: #fef2f2; }

    .btn-twofa {
      width: 100%; border: none; border-radius: 14px;
      padding: .85rem; font-size: 1rem; font-weight: 700;
      font-family: 'Inter', sans-serif; cursor: pointer;
      background: linear-gradient(135deg, #1e293b, #0f172a); color: #fff;
      box-shadow: 0 6px 24px rgba(15,23,42,.3);
      transition: .25s; display: flex; align-items: center; justify-content: center; gap: 8px;
      margin-bottom: 16px;
    }
    .btn-twofa:hover { transform: translateY(-2px); box-shadow: 0 12px 36px rgba(15,23,42,.5); }
    .btn-twofa:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }

    .alert-error {
      background: #fef2f2; border: 1px solid #fecaca; color: #991b1b;
      border-radius: 12px; padding: 12px 16px; font-size: .85rem;
      margin-bottom: 20px; display: flex; align-items: flex-start; gap: 8px;
    }
    .app-badge {
      display: inline-flex; align-items: center; gap: 8px;
      background: #f8fafc; border: 1px solid #e2e8f0;
      border-radius: 10px; padding: 10px 14px;
      font-size: .82rem; color: #475569;
      margin-bottom: 20px; width: 100%;
    }
    .app-badge i { color: #dc2626; font-size: 1rem; }

    .back-link {
      display: flex; align-items: center; gap: 6px;
      color: #94a3b8; font-size: .83rem; text-decoration: none;
      justify-content: center; margin-top: 12px;
      transition: color .2s;
    }
    .back-link:hover { color: #dc2626; }

    @media (max-width: 768px) {
      .twofa-wrap { flex-direction: column; }
      .twofa-brand { display: none; }
      .twofa-card { padding: 40px 28px; }
    }
  </style>
</head>
<body>
<div class="twofa-wrap">

  {{-- LEFT: branding --}}
  <div class="twofa-brand">
    <a href="{{ route('home') }}" class="twofa-brand-logo" style="text-decoration:none;">
      <div class="brand-logo-mark"><i class="fa-solid fa-temperature-high"></i></div>
      <span class="brand-logo-text">HeatAlert</span>
    </a>

    <h2 class="brand-title">Authentification<br>à deux facteurs</h2>
    <p class="brand-sub">
      Votre compte est protégé par une couche de sécurité supplémentaire. Saisissez le code affiché dans votre application d'authentification.
    </p>

    <div class="shield-visual">
      <span class="shield-icon">🛡️</span>
      <ul class="shield-list">
        <li><i class="fa-solid fa-check-circle"></i> Code TOTP valide 30 secondes</li>
        <li><i class="fa-solid fa-check-circle"></i> Compatible Google Authenticator</li>
        <li><i class="fa-solid fa-check-circle"></i> Compatible Authy & Microsoft Auth</li>
        <li><i class="fa-solid fa-check-circle"></i> Aucun SMS requis — 100% hors-ligne</li>
      </ul>
    </div>
  </div>

  {{-- RIGHT: form --}}
  <div class="twofa-form-panel">
    <div class="twofa-card">

      <div class="twofa-heading-icon">🔐</div>
      <h1 class="twofa-title">Code de vérification</h1>
      <p class="twofa-sub">
        Ouvrez votre application d'authentification<br>et saisissez le code à 6 chiffres.
      </p>

      <div class="app-badge">
        <i class="fa-brands fa-google"></i>
        Google Authenticator · Authy · Microsoft Authenticator
      </div>

      @if($errors->any())
        <div class="alert-error">
          <i class="fa-solid fa-circle-exclamation" style="margin-top:2px;flex-shrink:0;"></i>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('2fa.verify') }}" id="twofa-form">
        @csrf
        <input type="hidden" name="code" id="code-hidden">

        <div class="otp-inputs" id="otp-inputs">
          @for($i = 0; $i < 6; $i++)
            <input type="text"
                   class="otp-digit @if($errors->any()) error @endif"
                   maxlength="1"
                   inputmode="numeric"
                   pattern="[0-9]"
                   id="digit-{{ $i }}"
                   autocomplete="off"
                   aria-label="Chiffre {{ $i + 1 }}">
          @endfor
        </div>

        <button type="submit" class="btn-twofa" id="btn-submit" disabled>
          <i class="fa-solid fa-unlock-keyhole"></i> Valider et se connecter
        </button>
      </form>

      <a href="{{ route('login') }}" class="back-link">
        <i class="fa-solid fa-arrow-left"></i> Retour à la connexion
      </a>

    </div>
  </div>

</div>

<script src="{{ asset('assets/front/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script>
  const digits   = Array.from(document.querySelectorAll('.otp-digit'));
  const hidden   = document.getElementById('code-hidden');
  const btnSub   = document.getElementById('btn-submit');

  function updateHidden() {
    const code = digits.map(d => d.value).join('');
    hidden.value = code;
    btnSub.disabled = code.length !== 6;
    digits.forEach(d => d.classList.toggle('filled', d.value !== '' && !d.classList.contains('error')));
  }

  digits.forEach((d, i) => {
    d.addEventListener('keydown', e => {
      if (e.key === 'Backspace' && !d.value && i > 0) {
        digits[i - 1].focus();
        digits[i - 1].select();
      }
    });
    d.addEventListener('input', () => {
      d.value = d.value.replace(/\D/g, '').slice(-1);
      d.classList.remove('error');
      updateHidden();
      if (d.value && i < 5) digits[i + 1].focus();
    });
    d.addEventListener('paste', e => {
      e.preventDefault();
      const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
      pasted.split('').forEach((c, j) => { if (digits[j]) { digits[j].value = c; digits[j].classList.remove('error'); } });
      updateHidden();
      digits[Math.min(pasted.length, 5)].focus();
    });
  });

  // Auto-focus
  digits[0].focus();

  // Shake on error
  @if($errors->any())
    digits.forEach(d => { d.value = ''; });
    digits[0].focus();
  @endif
</script>
</body>
</html>
