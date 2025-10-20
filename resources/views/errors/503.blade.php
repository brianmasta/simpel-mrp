<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIMPEL-MRP | Sedang Dalam Pemeliharaan</title>
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



    <!-- Favicons -->
    <link href="{{ asset('assets/img/logo.png') }}" rel="icon">
    <link href="{{ asset('assets/img/simpel/apple-touch-icon.png') }}" rel="apple-touch-icon">

  {{-- CoreUI CSS --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.1.0/dist/css/coreui.min.css">

  <style>
    body {
      background: linear-gradient(135deg, #e3f2fd, #ffffff);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: "Nunito", sans-serif;
      overflow: hidden;
    }
    .card {
      border-radius: 1rem;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
      max-width: 460px;
      text-align: center;
      background: #fff;
      padding: 2rem 1.5rem;
    }
    .logo {
      width: 150px;
      height: auto;
      margin-bottom: 1rem;
      animation: fadeInDown 1s ease-in-out;
    }
    .spinner {
      width: 60px;
      height: 60px;
      border: 6px solid #f3f3f3;
      border-top: 6px solid #0d6efd;
      border-radius: 50%;
      animation: spin 1.5s linear infinite;
      margin: 1.5rem auto;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    @keyframes fadeInDown {
      0% { opacity: 0; transform: translateY(-20px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    h1 {
      font-size: 1.4rem;
      color: #333;
      font-weight: 700;
      margin-bottom: 0.75rem;
    }
    p {
      color: #555;
      font-size: 0.95rem;
      line-height: 1.5;
    }
    .small-text {
      font-size: 0.85rem;
      color: #777;
      margin-top: 1.5rem;
    }
  </style>
</head>

<body>
  <div class="card d-flex flex-column align-items-center">
    {{-- Logo MRP --}}
    <img src="{{ asset('assets/img/simpel.svg') }}" alt="Logo SIMPEL-MRP" class="logo">

    <h1>🚧 Sistem Sedang Dalam Pemeliharaan</h1>
    <p>
      Kami sedang melakukan pembaruan dan peningkatan sistem 
      <strong>SIMPEL-MRP</strong> untuk memastikan layanan berjalan lebih baik dan cepat.
    </p>

    <div class="spinner"></div>

    <p class="small-text">
      Mohon maaf atas ketidaknyamanannya.<br>
      Silakan coba kembali beberapa saat lagi.<br>
      — Tim Pengembang SIMPEL-MRP
    </p>
  </div>

  {{-- CoreUI JS --}}
  <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.1.0/dist/js/coreui.bundle.min.js"></script>
</body>
</html>
