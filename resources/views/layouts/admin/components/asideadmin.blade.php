      
        <header class="header-desktop">
          <div class="section__content section__content--p30">
            <div class="container-fluid">
              <div class="header-wrap">
                <button class="sidebar-toggle js-sidebar-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false" aria-controls="main-sidebar"><i class="fa-solid fa-bars" aria-hidden="true"></i></button>
                <form class="form-header" role="search" onsubmit="return false"><i class="fa-solid fa-magnifying-glass form-header__icon" aria-hidden="true"></i>
                  <input class="au-input au-input--xl" type="search" name="search" placeholder="Rechercher (Zones, Alertes, Conseils, Dashboard)…" aria-label="Rechercher dans le menu"><kbd class="form-header__hint" aria-hidden="true">⌘K</kbd>
                </form>
                <div class="header-button">
                
                  <div class="account-wrap">
                    <div class="account-item clearfix js-item-menu" role="button" tabindex="0" aria-haspopup="true" aria-label="Account menu">
                      <div class="image"><img src={{ asset("assets/admin/images/icon/avatar-01.jpg") }} alt="{{ auth()->user()->prenom ?? 'Admin' }}"></div>
                      <div class="content"><a class="js-acc-btn" href="#">{{ auth()->user()->prenom ?? 'Admin' }}</a></div>
                      <div class="account-dropdown js-dropdown">
                        <div class="info clearfix">
                          <div class="image"><a href="#"><img src={{ asset("assets/admin/images/icon/avatar-01.jpg") }} alt="{{ auth()->user()->nom ?? 'Admin' }}"></a></div>
                          <div class="content">
                            <h5 class="name"><a href="{{ route('admin.profile.edit') }}">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</a></h5><span class="email">{{ auth()->user()->email }}</span>
                          </div>
                        </div>
                        <div class="account-dropdown__body">
                          <div class="account-dropdown__item"><a href="{{ route('admin.profile.edit') }}"><i class="fa-solid fa-user-gear"></i>Mon Profil</a></div>
                          <div class="account-dropdown__item"><a href="{{ route('admin.profile.edit') }}#security"><i class="fa-solid fa-shield-halved"></i>Sécurité & Mot de passe</a></div>
                          <div class="account-dropdown__item"><a href="{{ route('admin.statistiques') }}"><i class="fa-solid fa-chart-line"></i>Statistiques</a></div>
                          <div class="account-dropdown__item"><a href="{{ route('front.home') }}" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i>Voir le Site Public</a></div>
                        </div>
                        <div class="account-dropdown__footer">
                          <form method="POST" action="{{ route('logout') }}" style="display:inline">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                              <i class="fa-solid fa-power-off"></i>Déconnexion
                            </a>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </header>

