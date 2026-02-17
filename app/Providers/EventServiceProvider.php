<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, function ($event) {
            logActivity('Login ke sistem');
        });

        Event::listen(Logout::class, function ($event) {
            logActivity('Logout dari sistem');
        });

        Event::listen(Registered::class, function ($event) {
            logActivity('Registrasi akun baru');
        });

        // ✅ EMAIL VERIFIKASI DIKIRIM
        Event::listen(Registered::class, function ($event) {
            logActivity('Mengirim email verifikasi');
        });

        // ✅ EMAIL BERHASIL DIVERIFIKASI
        Event::listen(Verified::class, function ($event) {
            logActivity('Verifikasi email berhasil');
        });
    }
}
