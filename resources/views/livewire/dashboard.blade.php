<div>
@if (!$lengkap)
<div class="bg-warning bg-opacity-10 border border-2 border-warning rounded mb-4 shadow-sm">
  <div class="row align-items-center p-3 p-xl-4 g-3">

    <!-- Ikon -->
    <div class="col-xl-auto col-12 text-center text-xl-start">
      <i class="bi bi-exclamation-triangle-fill text-warning fs-1"></i>
    </div>

    <!-- Teks -->
    <div class="col-md col-12 text-center text-xl-start">
      <h5 class="fw-bold text-dark mb-2">
        Profil Belum Lengkap
      </h5>
      <p class="text-muted mb-0">
        Anda belum melengkapi data profil.  
        Lengkapi profil Anda terlebih dahulu sebelum melanjutkan ke proses <strong>pengajuan surat OAP</strong>.
      </p>
    </div>

    <!-- Tombol -->
    <div class="col-md-auto col-12 text-center text-xl-end mt-3 mt-md-0">
      <a href="{{ route('profil') }}" 
         class="btn btn-warning fw-semibold text-white shadow-sm px-4 py-2" 
         target="_blank" 
         rel="noopener noreferrer">
        <i class="bi bi-pencil-square me-1"></i> Lengkapi Profil
      </a>
    </div>

  </div>
</div>
@endif

          
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header bg-primary text-white">
    <h5 class="mb-0 fw-bold">🧾 Tahapan Pengajuan Surat Keterangan Orang Asli Papua</h5>
  </div>

  <div class="card-body">
    <div class="row gy-4 text-center">

      <!-- Tahap 1 -->
      <div class="col-md-4">
        <div class="p-4 bg-white rounded-3 border border-primary border-opacity-25 h-100 shadow-sm">
          <div class="mb-3">
            <i class="bi bi-person-vcard text-primary fs-1"></i>
          </div>
          <h5 class="fw-semibold text-dark">1. Lengkapi Data Profil</h5>
          <p class="text-muted small mb-0">
            Pastikan semua data profil telah lengkap. Jika muncul peringatan bahwa <strong>marga tidak ditemukan</strong>, silakan lanjutkan dengan <strong>pengajuan marga</strong>.  
            Jika marga ditemukan, Anda dapat langsung melanjutkan ke proses <strong>pengajuan surat OAP</strong>.
          </p>
        </div>
      </div>

      <!-- Tahap 2 -->
      <div class="col-md-4">
        <div class="p-4 bg-white rounded-3 border border-warning border-opacity-25 h-100 shadow-sm">
          <div class="mb-3">
            <i class="bi bi-file-earmark-text-fill text-warning fs-1"></i>
          </div>
          <h5 class="fw-semibold text-dark">2. Pengajuan Surat OAP</h5>
          <p class="text-muted small mb-0">
            Setelah data profil dan marga tervalidasi, sistem akan memproses <strong>pengajuan surat OAP secara otomatis</strong>.  
            Jika marga Anda telah ada dalam <strong>database resmi OAP</strong>, surat akan langsung diterbitkan oleh sistem.
          </p>
        </div>
      </div>

      <!-- Tahap 3 -->
      <div class="col-md-4">
        <div class="p-4 bg-white rounded-3 border border-success border-opacity-25 h-100 shadow-sm">
          <div class="mb-3">
            <i class="bi bi-qr-code text-success fs-1"></i>
          </div>
          <h5 class="fw-semibold text-dark">3. Verifikasi Keaslian Surat</h5>
          <p class="text-muted small mb-0">
            Surat OAP yang telah diterbitkan akan dilengkapi dengan <strong>QR Code autentikasi</strong>.  
            Keaslian surat dapat dicek langsung melalui sistem <strong>SIMPEL-MRP</strong> dengan memindai QR Code tersebut.
          </p>
        </div>
      </div>

    </div>
  </div>
</div>


            
</div>
