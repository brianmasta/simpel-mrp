<!DOCTYPE html>

<html lang="en">
  <head>
  <title>SIMPEL-MRP - Sistem Pelayanan Surat OAP Papua Tengah</title>
  <meta name="description" content="Platform resmi MRP Papua Tengah untuk pengajuan dan verifikasi Surat Orang Asli Papua (OAP) secara online. Cepat, sah, dan terverifikasi.">
  <meta name="keywords" content="SIMPEL MRP, Surat OAP, Pelayanan OAP, MRP Papua Tengah, Surat Orang Asli Papua Online">

  <!-- OPEN GRAPH / SHARE PREVIEW -->
  <meta property="og:title" content="SIMPEL-MRP - Layanan Surat OAP Papua Tengah">
  <meta property="og:description" content="Pengajuan surat OAP online secara resmi dan terverifikasi oleh MRP Papua Tengah.">
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://simpelmrp.com">
  <meta property="og:site_name" content="MRP Papua Tengah">
  <meta property="og:image" content="{{ asset('assets/img/logo.png') }}" />

  <!-- TWITTER CARD -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="SIMPEL-MRP - Layanan Surat OAP Papua Tengah">
  <meta name="twitter:description" content="Pengajuan surat OAP online secara resmi dan terverifikasi oleh MRP Papua Tengah.">
  <meta name="twitter:image" content="{{ asset('assets/img/logo.png') }}">


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
    <script src="{{ asset('assets/js/config.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/color-modes.js') }}"></script> --}}

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    @livewireStyles
    
  </head>
  <body>
        {{ $slot }} 
        
        @livewireScripts
        
    <!-- CoreUI and necessary plugins-->
    <script src="{{ asset('assets/js/coreui.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/simplebar.min.js') }}"></script>
    <script>
      const header = document.querySelector('header.header');
      
      document.addEventListener('scroll', () => {
        if (header) {
          header.classList.toggle('shadow-sm', document.documentElement.scrollTop > 0);
        }
      });
      
    </script>
  </body>
</html>
