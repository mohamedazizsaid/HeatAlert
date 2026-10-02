<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | HeatAlert Admin</title>
    <meta name="description" content="HeatAlert - Panneau d'administration des alertes météo en Tunisie"/>
    <link href="{{ asset('assets/admin/css/font-face.css') }}" rel="stylesheet" media="all"/>
    <link rel="preconnect" href="https://rsms.me/"/>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css"/>
    <link href="{{ asset('assets/admin/vendor/fontawesome-7.3.1/css/all.min.css') }}" rel="stylesheet" media="all"/>
    <link href="{{ asset('assets/admin/vendor/bootstrap-5.3.8.min.css') }}" rel="stylesheet" media="all"/>
    <link href="{{ asset('assets/admin/vendor/css-hamburgers/hamburgers.min.css') }}" rel="stylesheet" media="all"/>
    <link href="{{ asset('assets/admin/css/theme.css') }}" rel="stylesheet" media="all"/>
    <link href="{{ asset('assets/admin/css/app.css') }}" rel="stylesheet" media="all"/>
    @stack('styles')
  </head>
  <body class="app">
    <a class="visually-hidden-focusable skip-link" href="#main-content">Aller au contenu principal</a>
    <div class="page-wrapper">

      @include('layouts.admin.components.headeradmin')

      <div class="page-container">

        @include('layouts.admin.components.asideadmin')

        <main class="main-content" id="main-content">
          <div class="section__content section__content--p30">
            <div class="container-fluid">

              {{-- Toast de succès --}}
              @if(session('success'))
              <div id="toast-success" class="position-fixed bottom-0 end-0 p-3" style="z-index:9999">
                <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive">
                  <div class="d-flex">
                    <div class="toast-body">
                      <i class="fa-solid fa-circle-check me-2"></i>
                      {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
                  </div>
                </div>
              </div>
              @endif

              {{-- Toast de suppression --}}
              @if(session('deleted'))
              <div id="toast-deleted" class="position-fixed bottom-0 end-0 p-3" style="z-index:9999">
                <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive">
                  <div class="d-flex">
                    <div class="toast-body">
                      <i class="fa-solid fa-trash me-2"></i>
                      {{ session('deleted') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
                  </div>
                </div>
              </div>
              @endif

              @yield('content')

              {{-- Footer --}}
              <div class="row" style="margin-top: 28px;">
                <div class="col-md-12">
                  <div class="copyright">
                    <p>Copyright &copy; {{ date('Y') }} HeatAlert. Tous droits réservés.</p>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </main>
      </div>
    </div>

    <script src="{{ asset('assets/admin/js/vanilla-utils.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/bootstrap-5.3.8.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/chartjs/chart.umd.js-4.5.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/bootstrap5-init.js') }}"></script>
    <script src="{{ asset('assets/admin/js/main-vanilla.js') }}"></script>
    <script src="{{ asset('assets/admin/js/modern-plugins.js') }}"></script>

    {{-- Auto-dismiss toasts after 4 seconds --}}
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toast').forEach(function (toastEl) {
          var t = new bootstrap.Toast(toastEl, { delay: 4000 });
          t.show();
        });
      });
    </script>

    @stack('scripts')
  </body>
</html>