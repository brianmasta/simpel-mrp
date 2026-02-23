<!DOCTYPE html>

<html lang="en">
  <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="Platform resmi MRP Papua Tengah untuk pengajuan dan verifikasi Surat Orang Asli Papua (OAP) secara online. Cepat, sah, dan terverifikasi.">
    <meta name="keyword" content="SIMPEL MRP, Surat OAP, Pelayanan OAP, MRP Papua Tengah, Surat Orang Asli Papua Online">
    <title>Simpel MRP</title>
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/img/logo.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/img/logo.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/img/logo.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/logo.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/img/logo.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/img/logo.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/img/logo.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/img/logo.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/logo.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/img/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/logo.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/img/logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/logo.png') }}">
    <link rel="manifest" href="{{ asset('assets/favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('assets/img/logo.png') }}">
    <meta name="theme-color" content="#ffffff">
    <!-- Vendors styles-->
    <link rel="stylesheet" href="{{ asset('assets/css/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/simplebars.css') }}">
    <!-- Main styles for this application-->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <!-- We use those styles to show code examples, you should remove them in your application.-->
    <link href="{{ asset('assets/css/examples.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@coreui/icons/css/all.min.css">


    <!-- Include stylesheet -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" /> --}}
<!-- include libraries(jQuery, bootstrap) -->
{{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet"> --}}
    <script src="{{ asset('assets/js/config.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/color-modes.js') }}"></script> --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> --}}

    {{-- <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script> --}}


    <!-- Bootstrap 5 CSS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

    <!-- Bootstrap 5 JS Bundle (include Popper) -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}

<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>

<style>
  .online-dot {
    width: 10px;
    height: 10px;
    background-color: #28a745; /* hijau */
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 2px rgba(40, 167, 69, 0.3);
}
</style>

<style>
/* ===============================
   SIMPEL LOADER
================================ */
.simpel-loader {
  position: fixed;
  inset: 0;
  background: rgba(255, 255, 255, 0.9);
  z-index: 99999;
  display: none;
  align-items: center;
  justify-content: center;
}

.simpel-loader.show {
  display: flex;
}

.simpel-loader-logo {
  width: 90px;
  animation: simpelPulse 1.5s ease-in-out infinite;
}

@keyframes simpelPulse {
  0% { transform: scale(1); opacity: .85; }
  50% { transform: scale(1.08); opacity: 1; }
  100% { transform: scale(1); opacity: .85; }
}

/* ===============================
   BADGE NOTIFIKASI CHAT
================================ */
.badgechat {
  background: #dc3545;
  color: #fff;
  font-size: 0.65rem;
  padding: 4px 6px;
  border-radius: 10px;
  animation: badgePulse 1.2s infinite;
}

@keyframes badgePulse {
  0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220,53,69,.7); }
  70% { transform: scale(1.15); box-shadow: 0 0 0 8px rgba(220,53,69,0); }
  100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220,53,69,0); }
}

/* ===============================
   LIVE CHAT KEKINIAN
================================ */
.live-chat-container {
  max-width: 420px;
  margin: auto;
  font-family: system-ui, -apple-system, BlinkMacSystemFont;
}

.chat-header {
  padding: 10px 14px;
  background: #ffffff;
  border-radius: 10px;
  border: 1px solid #dee2e6;
}

.chat-body {
  height: 320px;
  overflow-y: auto;
  background: #f4f6f9;
  border-radius: 10px;
}

.chat-bubble {
  max-width: 75%;
  padding: 10px 14px;
  border-radius: 16px;
  font-size: 0.9rem;
  position: relative;
  line-height: 1.4;
}

.chat-bubble.user {
  background: #0d6efd;
  color: #fff;
  border-bottom-right-radius: 4px;
}

.chat-bubble.petugas {
  background: #ffffff;
  border: 1px solid #dee2e6;
  border-bottom-left-radius: 4px;
}

.chat-time {
  font-size: 0.7rem;
  opacity: 0.7;
  margin-top: 4px;
  text-align: right;
}

/* ===============================
   SYSTEM MESSAGE (NOTIFIKASI)
================================ */
.system-chip {
  background: #e7f1ff;
  color: #0d6efd;
  padding: 6px 14px;
  border-radius: 20px;
  font-size: 0.75rem;
  display: inline-block;
  box-shadow: 0 2px 4px rgba(0,0,0,.05);
}

/* ===============================
   CHAT INPUT
================================ */
.chat-input textarea {
  resize: none;
  border-radius: 8px;
}

.chat-input button {
  border-radius: 8px;
}

/* ===============================
   SMOOTH SCROLL CHAT
================================ */
.chat-body {
  scroll-behavior: smooth;
}

</style>



    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    @livewireStyles
    
  </head>
  <body>
<!-- SIMPEL-MRP Page Loader -->
<div id="simpel-loader" class="simpel-loader">
  <div class="text-center">
    <img
      src="{{ asset('assets/img/logo.png') }}"
      alt="SIMPEL-MRP"
      class="simpel-loader-logo"
    >
    <div class="mt-3 text-muted fw-semibold">
      Memuat layanan SIMPEL-MRP…
    </div>
  </div>
</div>
    <div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar">
      <div class="sidebar-header border-bottom">
        <div class="sidebar-brand">

            <img width="200" height="60" alt="CoreUI Logo" src="{{ asset('assets/img/simpel4.svg') }}">

          {{-- <svg class="sidebar-brand-full" >

            <use xlink:href="{{ asset('assets/img/simpel2.svg') }}"></use>
          </svg> --}}
          <svg class="sidebar-brand-narrow" width="32" height="32" alt="CoreUI Logo">
            {{-- <use xlink:href="assets/brand/coreui.svg#signet"></use> --}}
          </svg>
        </div>
        <button class="btn-close d-lg-none" type="button" data-coreui-theme="dark" aria-label="Close" onclick="coreui.Sidebar.getInstance(document.querySelector(&quot;#sidebar&quot;)).toggle()"></button>
      </div>
      <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
        <li class="nav-item"><a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="nav-icon bi bi-house-door-fill" viewBox="0 0 16 16">
            <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5"/>
          </svg>
            Dashboard
            {{-- <span class="badge badge-sm bg-info ms-auto">NEW</span> --}}
          </a></li>
        <li class="nav-title">Menu</li>
        
        {{-- Admin bisa lihat semua --}}
        @if(auth()->check() && auth()->user()->role === 'admin')
        <li class="nav-item">
          <a class="nav-link {{ request()->is('profil') ? 'active' : '' }}" wire:navigate href="/profil">
            <i class="nav-icon cil-user"></i>
            Profil
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('surat-oap') ? 'active' : '' }}" wire:navigate href="/surat-oap">
            <i class="nav-icon cil-envelope-open"></i>
            Surat OAP
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('verifikasi') ? 'active' : '' }}" wire:navigate href="/verifikasi">
            <i class="nav-icon cil-task"></i>
            Verifikasi Marga
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('verifikasi-berkas') ? 'active' : '' }}" wire:navigate href="/verifikasi-berkas">
            <i class="nav-icon cil-clipboard"></i>
            Verifikasi Berkas
          </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('petugas/verifikasi/surat-oap*') ? 'active' : '' }}"
              wire:navigate
              href="{{ route('petugas.verifikasi.surat-oap') }}">
                <i class="nav-icon cil-task"></i>
                Verifikasi Surat OAP
            </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('pengajuan-marga') ? 'active' : '' }}" wire:navigate href="/pengajuan-marga">
            <i class="nav-icon cil-people"></i>
            Pengajuan Marga
          </a>
        </li>
        <li class="nav-title">Master Data</li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('format-surat') ? 'active' : '' }}" wire:navigate href="/format-surat">
            <i class="nav-icon cil-description"></i>
            Format Surat
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('data-marga') ? 'active' : '' }}" wire:navigate href="/data-marga">
            <i class="nav-icon cil-sitemap"></i>
            Data Marga
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('data-surat-oap') ? 'active' : '' }}" wire:navigate href="/data-surat-oap">
            <i class="nav-icon cil-folder-open"></i>
            Data Surat OAP
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('dashboard-rekapan') ? 'active' : '' }}" wire:navigate href="/dashboard-rekapan">
            <i class="nav-icon cil-folder-open"></i>
            Data Rekapan
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('/petugas/kirim-email') ? 'active' : '' }}" wire:navigate href="/petugas/kirim-email">
            <i class="nav-icon cil-folder-open"></i>
            Email
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('admin.activity-log') ? 'active' : '' }}" wire:navigate href="/admin/log-aktivitas">
            <i class="nav-icon cil-folder-open"></i>
            Activity Log
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('admin/manage-users') ? 'active' : '' }}" wire:navigate href="/admin/manage-users">
            <i class="nav-icon cil-people"></i>
            Akun
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('admin/live-chat*') ? 'active' : '' }}"
            wire:navigate
            href="{{ route('admin.livechat.index') }}">
            <i class="nav-icon cil-chat-bubble"></i>
            Live Chat Bantuan

            {{-- 🔴 BADGE NOTIFIKASI --}}
             @livewire('live-chat-badge')
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('admin/tickets*') ? 'active' : '' }}"
            wire:navigate
            href="{{ route('admin.tickets.index') }}">
            <i class="nav-icon cil-clipboard"></i>
            Tiket Kendala
          </a>
        </li>

        @endif

        {{-- Petugas --}}
       @if(auth()->check() && auth()->user()->role === 'petugas')

        <li class="nav-item">
          <a class="nav-link {{ request()->is('profil') ? 'active' : '' }}" wire:navigate href="/profil">
            <i class="nav-icon cil-user"></i>
            Profil
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('verifikasi') ? 'active' : '' }}" wire:navigate href="/verifikasi">
            <i class="nav-icon cil-task"></i>
            Verifikasi Marga
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('verifikasi-berkas') ? 'active' : '' }}" wire:navigate href="/verifikasi-berkas">
            <i class="nav-icon cil-clipboard"></i>
            Verifikasi Berkas
          </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('petugas/verifikasi/surat-oap*') ? 'active' : '' }}"
              wire:navigate
              href="{{ route('petugas.verifikasi.surat-oap') }}">
                <i class="nav-icon cil-task"></i>
                Verifikasi Surat OAP
            </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('data-marga') ? 'active' : '' }}" wire:navigate href="/data-marga">
            <i class="nav-icon cil-sitemap"></i>
            Data Marga
          </a>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('data-surat-oap') ? 'active' : '' }}" wire:navigate href="/data-surat-oap">
            <i class="nav-icon cil-folder-open"></i>
            Data Surat OAP
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('dashboard-rekapan') ? 'active' : '' }}" wire:navigate href="/dashboard-rekapan">
            <i class="nav-icon cil-folder-open"></i>
            Data Rekapan
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('/petugas/kirim-email') ? 'active' : '' }}" wire:navigate href="/petugas/kirim-email">
            <i class="nav-icon cil-folder-open"></i>
            Email
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('admin/live-chat*') ? 'active' : '' }}"
            wire:navigate
            href="{{ route('admin.livechat.index') }}">
            <i class="nav-icon cil-chat-bubble"></i>
            Live Chat Bantuan

          {{-- 🔴 BADGE NOTIFIKASI --}}
          @livewire('live-chat-badge')
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('admin/tickets*') ? 'active' : '' }}"
            wire:navigate
            href="{{ route('admin.tickets.index') }}">
            <i class="nav-icon cil-clipboard"></i>
            Tiket Kendala
          </a>
        </li>

        @endif

      {{-- pengguna --}}
       @if(auth()->check() && auth()->user()->role === 'pengguna')
        <li class="nav-item">
          <a class="nav-link {{ request()->is('profil') ? 'active' : '' }}" wire:navigate href="/profil">
            <i class="nav-icon cil-user"></i>
            Profil
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('surat-oap') ? 'active' : '' }}" wire:navigate href="/surat-oap">
            <i class="nav-icon cil-envelope-open"></i>
            Surat OAP
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('pengajuan-marga') ? 'active' : '' }}" wire:navigate href="/pengajuan-marga">
            <i class="nav-icon cil-people"></i>
            Pengajuan Marga
          </a>
        </li>
       @endif

      <div class="sidebar-footer border-top d-none d-md-flex">     
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
      </div>
    </div>

    <div class="wrapper d-flex flex-column min-vh-100">
      <header class="header header-sticky p-0 mb-4">
        <div class="container-fluid border-bottom px-4">
          <button class="header-toggler" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()" style="margin-inline-start: -14px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="icon icon-lg bi bi-envelope-fill" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
            </svg>
          </button>
          <ul class="header-nav">
            <li class="nav-item d-flex align-items-center">
                <span class="online-dot me-2"></span>
                <a class="nav-link p-0">{{ Auth::user()->name }}</a>
            </li>
            <li class="nav-item py-1">
              <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>
            <li class="nav-item dropdown" wire:ignore>
            <button
              type="button"
              class="nav-link py-0 pe-0 bg-transparent border-0"
              data-coreui-toggle="dropdown"
              aria-expanded="false">
                          <div class="avatar avatar-md">
                            <img class="avatar-img"
                                src="{{ asset('assets/img/profil.jpg') }}"
                                alt="{{ Auth::user()->email }}">
                          </div>
            </button>

              <div class="dropdown-menu dropdown-menu-end pt-0">
                <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2">
                  Akun
                </div>

                <!-- Profil -->
                <a class="dropdown-item d-flex align-items-center" href="/profil">
                  <i class="icon me-2 cil-user"></i>
                  Profil
                </a>

                <!-- Logout -->
                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                  @csrf
                  <button type="submit" class="dropdown-item d-flex align-items-center">
                    <i class="icon me-2 cil-account-logout"></i>
                    Logout
                  </button>
                </form>
              </div>
            </li>

          </ul>
        </div>
        <div class="container-fluid px-4">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb my-0">
              @php
                $routeName = Route::currentRouteName();
                $current = ucwords(str_replace('-', ' ', $routeName));
              @endphp

              @if ($routeName === 'dashboard' || $routeName === null)
                {{-- Kalau di halaman dashboard --}}
                <li class="breadcrumb-item active">
                  <span>Dashboard</span>
                </li>
              @else
                {{-- Kalau di halaman lain --}}
                <li class="breadcrumb-item">
                  <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">
                  <span>{{ $current }}</span>
                </li>
              @endif
            </ol>
          </nav>
        </div>
      </header>
    
        <div class="container-lg px-4">
        {{ $slot }}
        </div>
      </div> 
            @livewireScripts
      <footer class="footer px-4">
        <div><a href="https://simpelmrp.com">SIMPEL MRP </a> &copy; 2026 By MRP Provinsi Papua Tengah.</div>
        <div class="ms-auto">Powered by&nbsp;<a href="#">Bag. Umum & Humas Sekretariat MRP-PPT</a></div>
      </footer>
    </div>

    <!-- CoreUI and necessary plugins-->
    <script src="{{ asset('assets/js/coreui.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/simplebar.min.js') }}"></script>

        <!-- Sidebar toggle script -->
    <script>
        function initSidebar() {
            const sidebarEl = document.getElementById('sidebar');
            if (!sidebarEl) return;

            const sidebar = new coreui.Sidebar(sidebarEl, { unfoldable: false });

            const headerToggler = document.querySelector('.header-toggler');
            if (headerToggler) {
                headerToggler.onclick = () => sidebar.toggle();
            }

            const footerToggler = document.querySelector('.sidebar-toggler');
            if (footerToggler) {
                footerToggler.onclick = () => sidebar.toggle();
            }
        }

        document.addEventListener('DOMContentLoaded', initSidebar);
        document.addEventListener('livewire:load', initSidebar);
        document.addEventListener('livewire:navigated', initSidebar);
    </script>




<script>
document.addEventListener("livewire:init", () => {
    Livewire.on("toast", (data) => {
        const payload = data[0]; // Livewire v3 kirim array

        const toastEl = document.getElementById("toastProfil");
        const toastMessage = document.getElementById("toastMessage");
        if (!toastEl || !toastMessage) return;

        // Reset warna
        toastEl.classList.remove("bg-success","bg-warning","bg-info","bg-danger");

        // Tipe toast
        switch (payload.type) {
            case "success": toastEl.classList.add("bg-success"); break;
            case "warning": toastEl.classList.add("bg-warning"); break;
            case "info": toastEl.classList.add("bg-info"); break;
            default: toastEl.classList.add("bg-danger");
        }

        // Pesan masuk
        toastMessage.innerHTML = payload.message;

        // Tampilkan toast
        const toast = coreui.Toast.getOrCreateInstance(toastEl);
        toast.show();
    });
});
</script>



<!-- ✅ CoreUI Toast Container -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="toastProfil"
        class="toast align-items-center text-white fade"
        role="alert"
        aria-live="assertive"
        aria-atomic="true"
        data-coreui-autohide="true"
        data-coreui-delay="6000">
        <div class="d-flex">
            <div id="toastMessage" class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                data-coreui-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

  </body>
<script>
(function () {
  // cegah register ulang saat Livewire navigate
  if (window.__simpelLoaderInitialized) return;
  window.__simpelLoaderInitialized = true;

  window.__simpelLoaderStart = null;
  const MIN_LOADING_TIME = 600; // ms

  document.addEventListener('livewire:navigating', () => {
    window.__simpelLoaderStart = Date.now();

    setTimeout(() => {
      document.getElementById('simpel-loader')?.classList.add('show');
    }, 150);
  });

  document.addEventListener('livewire:navigated', () => {
    const loader = document.getElementById('simpel-loader');
    if (!loader || !window.__simpelLoaderStart) return;

    const elapsed = Date.now() - window.__simpelLoaderStart;
    const remaining = MIN_LOADING_TIME - elapsed;

    setTimeout(() => {
      loader.classList.remove('show');
      window.__simpelLoaderStart = null;
    }, remaining > 0 ? remaining : 0);
  });
})();
</script>

<script>
function initCoreUI() {
    document
        .querySelectorAll('[data-coreui-toggle="dropdown"]')
        .forEach(el => {
            if (el._coreuiDropdown) {
                el._coreuiDropdown.dispose();
            }
            el._coreuiDropdown = new coreui.Dropdown(el);
        });
}

document.addEventListener('DOMContentLoaded', initCoreUI);
document.addEventListener('livewire:navigated', initCoreUI);
</script>
</html>

