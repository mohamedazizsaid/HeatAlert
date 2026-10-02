  <footer id="footer" class="footer-16 footer position-relative">

    <div class="container">

      <div class="footer-main" data-aos="fade-up" data-aos-delay="100">
        <div class="row align-items-start">

          <div class="col-lg-5">
            <div class="brand-section">
              <a href="{{ route('front.home') }}" class="logo d-flex align-items-center mb-3">
                <i class="bi bi-thermometer-sun me-2" style="font-size:1.8rem; color:#e63946;"></i>
                <span class="sitename">HeatAlert</span>
              </a>
              <p class="brand-description">
                Plateforme nationale de surveillance météorologique et d'alerte précoce aux vagues de chaleur en Tunisie. Nous protégeons citoyens, familles et professionnels face aux risques thermiques extrêmes.
              </p>

              <div class="contact-info mt-4">
                <div class="contact-item">
                  <i class="bi bi-geo-alt"></i>
                  <span>Tunis, République Tunisienne</span>
                </div>
                <div class="contact-item">
                  <i class="bi bi-telephone-fill text-danger"></i>
                  <span><strong>Urgences :</strong> <a href="tel:198" style="color:inherit;">198</a> (Protection Civile) · <a href="tel:190" style="color:inherit;">190</a> (SAMU)</span>
                </div>
                <div class="contact-item">
                  <i class="bi bi-envelope"></i>
                  <span><a href="mailto:contact@heatalert.tn" style="color:inherit;">contact@heatalert.tn</a></span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-7">
            <div class="footer-nav-wrapper">
              <div class="row">

                <div class="col-6 col-lg-3">
                  <div class="nav-column">
                    <h6>Plateforme</h6>
                    <nav class="footer-nav">
                      <a href="{{ route('front.home') }}">Accueil</a>
                      <a href="{{ route('front.alertes.index') }}">Alertes Météo</a>
                      <a href="{{ route('front.zones.index') }}">Carte des Zones</a>
                      <a href="{{ route('front.conseils.index') }}">Conseils Santé</a>
                    </nav>
                  </div>
                </div>

                <div class="col-6 col-lg-3">
                  <div class="nav-column">
                    <h6>Risques Surveillés</h6>
                    <nav class="footer-nav">
                      <a href="{{ route('front.alertes.index') }}">Canicule & Pics de Chaleur</a>
                      <a href="{{ route('front.home') }}#featured-departments">Indice UV</a>
                      <a href="{{ route('front.home') }}#featured-departments">Risque STEG</a>
                      <a href="{{ route('front.home') }}#featured-departments">Vents Sirocco</a>
                      <a href="{{ route('front.home') }}#featured-departments">Feux de Forêt</a>
                    </nav>
                  </div>
                </div>

                <div class="col-6 col-lg-3">
                  <div class="nav-column">
                    <h6>Urgences</h6>
                    <nav class="footer-nav">
                      <a href="tel:198"><i class="bi bi-telephone-fill text-danger me-1"></i>Protection Civile : 198</a>
                      <a href="tel:190"><i class="bi bi-hospital me-1"></i>SAMU : 190</a>
                      <a href="tel:193"><i class="bi bi-shield me-1"></i>Garde Nationale : 193</a>
                      <a href="tel:197"><i class="bi bi-badge-po me-1"></i>Police Secours : 197</a>
                    </nav>
                  </div>
                </div>

                <div class="col-6 col-lg-3">
                  <div class="nav-column">
                    <h6>Partenaires</h6>
                    <nav class="footer-nav">
                      <a href="https://www.meteo.tn" target="_blank" rel="noopener">INM — Institut Nat. Météo</a>
                      <a href="https://www.santetunisie.rns.tn" target="_blank" rel="noopener">Ministère de la Santé</a>
                      <a href="https://www.protection-civile.tn" target="_blank" rel="noopener">Protection Civile</a>
                      <a href="https://www.steg.com.tn" target="_blank" rel="noopener">STEG</a>
                    </nav>
                  </div>
                </div>

              </div>
            </div>
          </div>

        </div>
      </div>

    </div>

    <div class="footer-bottom">
      <div class="container">
        <div class="bottom-content" data-aos="fade-up" data-aos-delay="300">
          <div class="row align-items-center">

            <div class="col-lg-6">
              <div class="copyright">
                <p>© {{ date('Y') }} <span class="sitename">HeatAlert</span> Tunisie — Tous droits réservés.</p>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="legal-links">
                <a href="#">Politique de Confidentialité</a>
                <a href="#">Conditions d'Utilisation</a>
                <a href="#">Accessibilité</a>
                <div class="credits">
                  <!-- All the links in the footer should remain intact. -->
                  <!-- You can delete the links only if you've purchased the pro version. -->
                  <!-- Licensing information: https://bootstrapmade.com/license/ -->
                  Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>. Distributed by <a href="https://themewagon.com" target="_blank">ThemeWagon</a>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

  </footer>
