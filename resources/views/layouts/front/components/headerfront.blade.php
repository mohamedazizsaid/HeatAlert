  <header id="header" class="header fixed-top" style="background-color: #ffffff; box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);">

    <div class="topbar d-flex align-items-center">
      <div class="container d-flex justify-content-center justify-content-md-between align-items-center">
        <div class="contact-info d-flex align-items-center">
          <i class="bi bi-telephone-fill d-flex align-items-center me-3">
            <span class="text-white-50 me-1">Urgences:</span>
            <a href="tel:198" class="fw-bold text-white">198</a>
            <span class="mx-2 text-white-50">|</span>
            <a href="tel:190" class="fw-bold text-white">190</a>
          </i>
          <i class="bi bi-envelope d-flex align-items-center ms-3 d-none d-sm-flex">
            <a href="mailto:contact@heatalert.tn" class="text-white">contact@heatalert.tn</a>
          </i>
        </div>
        <div class="social-links d-none d-md-flex align-items-center gap-3">
          @auth
            <span class="text-white fw-semibold" style="font-size: 13px;">
              <i class="bi bi-person-circle me-1"></i>
              {{ auth()->user()->prenom ?? '' }} {{ auth()->user()->nom }}
            </span>
            @if(auth()->user()->isAdmin())
              <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none" style="font-size: 12px; background: rgba(255,255,255,0.2); padding: 3px 10px; border-radius: 20px;">
                <i class="bi bi-speedometer2 me-1"></i>Admin
              </a>
            @endif
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
              @csrf
              <a href="{{ route('logout') }}"
                 onclick="event.preventDefault(); this.closest('form').submit();"
                 class="text-white text-decoration-none"
                 style="font-size: 12px; background: rgba(0,0,0,0.2); padding: 3px 10px; border-radius: 20px;">
                <i class="bi bi-box-arrow-right me-1"></i>Déconnexion
              </a>
            </form>
          @else
            <a href="{{ route('login') }}" class="text-white text-decoration-none" style="font-size: 13px;">
              <i class="bi bi-box-arrow-in-right me-1"></i>Connexion
            </a>
            <a href="{{ route('register') }}" class="text-white text-decoration-none" style="font-size: 13px; background: rgba(255,255,255,0.25); padding: 3px 12px; border-radius: 20px;">
              Inscription
            </a>
          @endauth
        </div>
      </div>
    </div><!-- End Top Bar -->

    <div class="branding d-flex align-items-center" style="background-color: #ffffff;">

      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="{{ route('front.home') }}" class="logo d-flex align-items-center text-decoration-none">
          <i class="bi bi-thermometer-sun me-2" style="font-size: 1.8rem; color: #dc2626;"></i>
          <h1 class="sitename mb-0" style="font-size: 26px; font-weight: 700; color: #1e3a5f;">Heat<span style="color: #dc2626;">Alert</span></h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li>
              <a href="{{ route('front.home') }}" class="{{ request()->routeIs('front.home') ? 'active' : '' }}">
                Accueil
              </a>
            </li>
            <li>
              <a href="{{ route('front.zones.index') }}" class="{{ request()->routeIs('front.zones.*') ? 'active' : '' }}">
                Zones
              </a>
            </li>
            <li>
              <a href="{{ route('front.alertes.index') }}" class="{{ request()->routeIs('front.alertes.*') ? 'active' : '' }}">
                Alertes
                @php $nbAlertes = \App\Models\AlerteMeteo::where('statut','active')->whereIn('niveau', ['rouge', 'orange'])->count(); @endphp
                @if($nbAlertes > 0)
                  <span class="badge bg-danger rounded-pill ms-1" style="font-size: 11px; padding: 2px 7px;">{{ $nbAlertes }}</span>
                @endif
              </a>
            </li>
            <li>
              <a href="{{ route('front.conseils.index') }}" class="{{ request()->routeIs('front.conseils.*') ? 'active' : '' }}">
                Conseils Santé
              </a>
            </li>
            <li>
              <a href="{{ route('front.home') }}#call-to-action">
                Urgences
              </a>
            </li>
            @auth
            <li class="dropdown">
              <a href="#"><span>Mon Compte</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
              <ul>
                <li class="px-3 py-2 text-muted border-bottom" style="font-size: 13px;">
                  Connecté : <strong>{{ auth()->user()->prenom ?? '' }} {{ auth()->user()->nom }}</strong>
                </li>
                @if(auth()->user()->isAdmin())
                  <li><a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Administration</a></li>
                @endif
                <li><a href="{{ route('front.zones.index') }}"><i class="bi bi-geo-alt me-2"></i>Mes Zones</a></li>
                <li><a href="{{ route('front.alertes.index') }}"><i class="bi bi-bell me-2"></i>Alertes Actives</a></li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();">
                      <i class="bi bi-box-arrow-right me-2 text-danger"></i>Déconnexion
                    </a>
                  </form>
                </li>
              </ul>
            </li>
            @else
            <li><a href="{{ route('login') }}">Connexion</a></li>
            @endauth
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <a class="cta-btn d-none d-lg-inline-flex align-items-center" href="tel:198" style="background: #dc2626; color: #ffffff; padding: 8px 18px; border-radius: 50px; font-weight: 600; font-size: 13px; text-decoration: none; transition: 0.3s; box-shadow: 0 4px 10px rgba(220,38,38,0.25);">
          <i class="bi bi-telephone-outbound-fill me-2"></i> Urgence 198
        </a>

      </div>

    </div>

  </header>