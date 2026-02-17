<?php

use App\Livewire\Admin\ActivityLog;
use App\Livewire\Admin\ManageUsers;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Logout;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\Dashboard;
use App\Livewire\DashboardRekapan;
use App\Livewire\DataAkun;
use App\Livewire\DataMarga;
use App\Livewire\DataSuratOap;
use App\Livewire\FormatSurat;
use App\Livewire\PengajuanMarga;
use App\Livewire\PerbaikanBerkasOap;
use App\Livewire\Profil;
use App\Livewire\SuratOap;
use App\Livewire\Verifikasi;
use App\Livewire\VerifikasiBerkas;
use App\Livewire\VerifikasiSurat;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;


Route::get('/login', Login::class)->name('login')->middleware('guest');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

Route::get('/register', Register::class)->name('register')->middleware('guest');;

Route::get('/verifikasi-surat/{kode}', VerifikasiSurat::class)
    ->middleware(['throttle:20,1']) // 20x per menit
    ->name('verifikasi.surat');


// Halaman utama (home)
Route::get('/', function () {
    return view('welcome');
});


// Route untuk notifikasi verifikasi
Route::get('/email/verify', VerifyEmail::class)
    ->middleware('auth')
    ->name('verification.notice');

// Route untuk verifikasi link (yang dikirim via email)
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Route untuk resend verifikasi
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi sudah dikirim ke email Anda!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/profil', Profil::class)->name('profil');
});

Route::middleware('auth', 'verified','role:admin')->group(function () {

    Route::get('/admin/manage-users', ManageUsers::class)->name('admin.manage-users');
    Route::get('/format-surat', FormatSurat::class)->name('format-surat');
    Route::get('/data-akun', DataAkun::class)->name('data-akun');
    Route::get('/admin/log-aktivitas', ActivityLog::class)->name('admin.activity-log');

});

Route::middleware(['auth', 'verified', 'role:pengguna,admin'])->group(function () {

    Route::get('/surat-oap', SuratOap::class)->name('surat-oap');
    Route::get('/pengajuan-marga', PengajuanMarga::class)->name('pengajuan-marga');
    Route::get('/perbaikan-berkas/{id}', PerbaikanBerkasOap::class)->name('perbaikan-berkas');

});

Route::middleware(['auth', 'verified', 'role:admin,petugas'])->group(function () {

    Route::get('/verifikasi', Verifikasi::class)->name('verifikasi');
    Route::get('/data-marga', DataMarga::class)->name('data-marga');
    Route::get('/data-surat-oap', DataSuratOap::class)->name('data-surat-oap');
    Route::get('/verifikasi-berkas', VerifikasiBerkas::class)->name('verifikasi-berkas');
    Route::get('/dashboard-rekapan', DashboardRekapan::class)->name('dashboard-rekapan');
    
});

Route::middleware('auth')->group(function () {
    Route::get('/verify-email', VerifyEmail::class)->name('verification.notice');
});

Route::get('/forgot-password', ForgotPassword::class)
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [ForgotPassword::class, 'sendResetLink'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', ResetPassword::class)
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [ResetPassword::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');

Route::get('/view-private/{folder}/{filename}', function ($folder, $filename) {
    $path = storage_path("app/private/public/surat_oap/{$folder}/{$filename}");

    if (!file_exists($path)) {
        abort(404, 'File tidak ditemukan.');
    }

    $mime = mime_content_type($path);
    return response()->file($path, [
        'Content-Type' => $mime,
        'Cache-Control' => 'no-cache, must-revalidate',
    ]);
})->middleware(['auth', 'role:admin,petugas'])->name('view.private');

