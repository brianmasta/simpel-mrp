<!DOCTYPE html>

<html lang="en">
  <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
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



    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    @livewireStyles
    
  </head>
  <body>
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
        <li class="nav-item"><a class="nav-link {{ request()->is('profil') ? 'active' : '' }}" wire:navigate href="/profil">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="nav-icon bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z"/>
          </svg>
             Profil</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->is('surat-oap') ? 'active' : '' }}" wire:navigate href="/surat-oap">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="nav-icon bi bi-clock-fill" viewBox="0 0 16 16">
            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z"/>
          </svg>
            Surat OAP</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->is('verifikasi') ? 'active' : '' }}" wire:navigate href="/verifikasi">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="nav-icon bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z"/>
          </svg>
             Verifikasi</a></li>
                     <li class="nav-item"><a class="nav-link {{ request()->is('verifikasi-berkas') ? 'active' : '' }}" wire:navigate href="/verifikasi-berkas">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="nav-icon bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z"/>
          </svg>
             Verifikasi Berkas</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->is('pengajuan-marga') ? 'active' : '' }}" wire:navigate href="/pengajuan-marga">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="nav-icon bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z"/>
          </svg>
             Pengajuan Marga</a></li>
        <li class="nav-title">Master Data</li>
      <li class="nav-item"><a class="nav-link {{ request()->is('format-surat') ? 'active' : '' }}" wire:navigate href="/format-surat">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="nav-icon bi bi-envelope-fill" viewBox="0 0 16 16">
            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
          </svg>
             Format Surat</a>
          <ul class="nav-group-items compact">
            {{-- <li class="nav-item"><a class="nav-link" href="base/accordion.html"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Surat OAP Umum</a></li>
            <li class="nav-item"><a class="nav-link" href="base/breadcrumb.html"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Surat OAP IPDN</a></li> --}}
        </li> 
      </ul>
      {{-- <li class="nav-item"><a class="nav-link" wire:navigate href="/pengajuan-permohonan">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="nav-icon bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z"/>
          </svg>
             Tanda Tangan Digital</a></li> --}}
            <li class="nav-item"><a class="nav-link {{ request()->is('data-marga') ? 'active' : '' }}" wire:navigate href="/data-marga">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="nav-icon bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z"/>
          </svg>
             Data Marga</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->is('data-surat-oap') ? 'active' : '' }}" wire:navigate href="/data-surat-oap">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="nav-icon bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z"/>
          </svg>
             Data Surat OAP</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->is('admin/manage-users') ? 'active' : '' }}" wire:navigate href="/admin/manage-users">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="nav-icon bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z"/>
          </svg>
             Akun</a></li>

        @endif

        

        {{-- Petugas --}}
       @if(auth()->check() && auth()->user()->role === 'petugas')

        <li class="nav-item"><a class="nav-link {{ request()->is('profil') ? 'active' : '' }}" wire:navigate href="/profil">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="nav-icon bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z"/>
          </svg>
             Profil</a></li>
                     <li class="nav-item"><a class="nav-link {{ request()->is('verifikasi') ? 'active' : '' }}" wire:navigate href="/verifikasi">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="nav-icon bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z"/>
          </svg>
             Verifikasi</a></li>
                     <li class="nav-item"><a class="nav-link {{ request()->is('verifikasi-berkas') ? 'active' : '' }}" wire:navigate href="/verifikasi-berkas">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="nav-icon bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z"/>
          </svg>
             Verifikasi Berkas</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->is('data-marga') ? 'active' : '' }}" wire:navigate href="/data-marga">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="nav-icon bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z"/>
          </svg>
             Data Marga</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->is('data-surat-oap') ? 'active' : '' }}" wire:navigate href="/data-surat-oap">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="nav-icon bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z"/>
          </svg>
             Data Surat OAP</a></li>

        @endif

      {{-- pengguna --}}
       @if(auth()->check() && auth()->user()->role === 'pengguna')
        <li class="nav-item"><a class="nav-link {{ request()->is('profil') ? 'active' : '' }}" wire:navigate href="/profil">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="nav-icon bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z"/>
          </svg>
             Profil</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->is('surat-oap') ? 'active' : '' }}" wire:navigate href="/surat-oap">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="nav-icon bi bi-clock-fill" viewBox="0 0 16 16">
            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z"/>
          </svg>
            Surat OAP</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->is('pengajuan-marga') ? 'active' : '' }}" wire:navigate href="/pengajuan-marga">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="nav-icon bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z"/>
          </svg>
             Pengajuan Marga</a></li>
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
            <li class="nav-item">
              {{-- <a class="nav-link">Brian Marandof</a> --}}
              <a class="nav-link">{{ Auth::user()->name }}</a>
            </li>
            <li class="nav-item py-1">
              <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>
<li class="nav-item dropdown">
  <a class="nav-link py-0 pe-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
    <div class="avatar avatar-md">
      <img class="avatar-img" src="{{ asset('assets/img/profil.jpg') }}" alt="{{ Auth::user()->email }}">
    </div>
  </a>

  <div class="dropdown-menu dropdown-menu-end pt-0">
    <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2">
      Akun
    </div>

    <!-- Profil -->
    <a class="dropdown-item" href="/profil">
      <svg class="icon me-2">
        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
      </svg>
      Profile
    </a>

    <!-- Logout -->
    <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
      @csrf
      <button type="submit" class="dropdown-item d-flex align-items-center">
        <svg class="icon me-2">
          <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
        </svg>
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
        <div><a href="https://mrp.papuatengahprov.go.id/">SIMPEL MRP </a> &copy; 2025 By MRP Provinsi Papua Tengah.</div>
        <div class="ms-auto">Powered by&nbsp;<a href="#">Sub Bag. Umum & Humas</a></div>
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
document.addEventListener('livewire:load', function () {
    // Saat Livewire selesai update DOM
    Livewire.hook('message.processed', (message, component) => {
        document.querySelectorAll('[data-coreui-toggle="dropdown"]').forEach(dropdownToggle => {
            new coreui.Dropdown(dropdownToggle);
        });
    });
});
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
</html>

