<div>
<style>
  .icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .step-card {
    transition: all .2s ease-in-out;
  }
  .step-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 .75rem 1.5rem rgba(0,0,0,.08);
  }
</style>
@if (!$lengkap)
<div class="card border-0 shadow-sm mb-4">
  <div class="card-body">
    <div class="d-flex flex-column flex-md-row align-items-center gap-3">

      <!-- Icon -->
      <div class="flex-shrink-0">
        <div class="icon icon-xxl bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center">
          <i class="cil-warning fs-3"></i>
        </div>
      </div>

      <!-- Text -->
      <div class="flex-fill text-center text-md-start">
        <h5 class="fw-semibold mb-1">Profil Belum Lengkap</h5>
        <p class="text-muted mb-0">
          Lengkapi data profil Anda sebelum melanjutkan ke
          <strong>pengajuan Surat Keterangan OAP</strong>.
        </p>
      </div>

      <!-- Action -->
      <div>
        <a href="{{ route('profil') }}"
           class="btn btn-warning text-white fw-semibold px-4">
          <i class="cil-pencil me-1"></i> Lengkapi Profil
        </a>
      </div>

    </div>
  </div>
</div>
@endif

          
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header bg-white border-0 pb-0">
    <h5 class="fw-bold mb-1">
      <i class="cil-list me-2 text-primary"></i>
      Tahapan Pengajuan Surat Keterangan OAP
    </h5>
    <p class="text-muted small mb-0">
      Ikuti tahapan berikut untuk mengajukan Surat OAP secara online
    </p>
  </div>

  <div class="card-body pt-4">
    <div class="row g-4">

      <!-- STEP 1 -->
      <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm step-card">
          <div class="card-body text-center">
            <div class="icon icon-xxl bg-primary bg-opacity-10 text-primary rounded-circled-flex align-items-center justify-content-center">
              <i class="cil-user fs-4"></i>
            </div>
            <h6 class="fw-semibold">1. Lengkapi Data Profil</h6>
            <p class="text-muted small mb-0">
              Lengkapi seluruh data profil Anda.
              Jika <strong>marga belum tersedia</strong>,
              silakan ajukan marga terlebih dahulu.
            </p>
          </div>
        </div>
      </div>

      <!-- STEP 2 -->
      <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm step-card">
          <div class="card-body text-center">
            <div class="icon icon-xxl bg-warning bg-opacity-10 text-warning rounded-circled-flex align-items-center justify-content-center">
              <i class="cil-file fs-4"></i>
            </div>
            <h6 class="fw-semibold">2. Pengajuan Surat OAP</h6>
            <p class="text-muted small mb-0">
              Setelah profil dan marga tervalidasi,
              sistem akan memproses pengajuan
              <strong>secara otomatis</strong>.
            </p>
          </div>
        </div>
      </div>

      <!-- STEP 3 -->
      <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm step-card">
          <div class="card-body text-center">
            <div class="icon icon-xxl bg-success bg-opacity-10 text-success rounded-circled-flex align-items-center justify-content-center">
              <i class="cil-qr-code fs-4"></i>
            </div>
            <h6 class="fw-semibold">3. Verifikasi Keaslian</h6>
            <p class="text-muted small mb-0">
              Surat OAP diterbitkan dengan
              <strong>QR Code autentikasi</strong>
              dan dapat diverifikasi melalui SIMPEL-MRP.
            </p>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

</div>
