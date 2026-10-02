  <header id="header" class="header fixed-top">

    <div class="topbar d-flex align-items-center dark-background">
      <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="contact-info d-flex align-items-center">
          <i class="bi bi-envelope d-flex align-items-center">
            <a href="mailto:contact@heatalert.tn">contact@heatalert.tn</a>
          </i>
          <i class="bi bi-phone d-flex align-items-center ms-4">
            <span>+216 71 000 000</span>
          </i>
        </div>
        <div class="social-links d-none d-md-flex align-items-center">
          @auth
            <span class="text-white me-3" style="font-size:.85rem;">
              <i class="bi bi-person-circle me-1"></i>
              {{ auth()->user()->prenom }} {{ auth()->user()->nom }}
            </span>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
              @csrf
              <a href="{{ route('logout') }}"
                 onclick="event.preventDefault(); this.closest('form').submit();"
                 class="text-white text-decoration-none"
                 style="font-size:.85rem;">
                <i class="bi bi-box-arrow-right me-1"></i>Déconnexion
              </a>
            </form>
          @else
            <a href="{{ route('login') }}" class="text-white text-decoration-none me-3" style="font-size:.85rem;">
              <i class="bi bi-box-arrow-in-right me-1"></i>Connexion
            </a>
            <a href="{{ route('register') }}" class="text-white text-decoration-none" style="font-size:.85rem;">
              <i class="bi bi-person-plus me-1"></i>Inscription
            </a>
          @endauth
        </div>
      </div>
    </div><!-- End Top Bar -->

    <div class="branding d-flex align-items-center">

      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="{{ route('front.home') }}" class="logo d-flex align-items-center">
          <h1 class="sitename">HeatAlert</h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="{{ route('front.home') }}" class="{{ request()->routeIs('front.home') ? 'active' : '' }}">Accueil</a></li>
            <li><a href="#about">À propos</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#contact">Contact</a></li>
            @auth
            <li class="dropdown">
              <a href="#"><span>Mon compte</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
              <ul>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();">
                      <i class="bi bi-box-arrow-right me-1"></i>Déconnexion
                    </a>
                  </form>
                </li>
              </ul>
            </li>
            @else
            <li><a href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i>Connexion</a></li>
            @endauth
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

      </div>

    </div>

  </header>