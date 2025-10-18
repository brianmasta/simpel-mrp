<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SecurityCheck extends Command
{
    protected $signature = 'security:check';
    protected $description = 'Run full security checklist for Laravel + Livewire project';

    public function handle()
    {
        $this->info("🔒 Starting Security Checklist for SIMPEL-MRP...\n");

        // A. Environment Check
        $this->info("1️⃣ Checking .env and APP_DEBUG...");
        $debug = config('app.debug') ? 'ON' : 'OFF';
        $this->line("APP_DEBUG: $debug");
        if(config('app.key') === null) {
            $this->error("❌ APP_KEY is not set!");
        } else {
            $this->line("APP_KEY: set ✅");
        }

        // B. Composer Security Audit
        $this->info("\n2️⃣ Running Composer Security Audit...");
        $this->runShellCommand('composer audit');

        // C. Package Versions
        $this->info("\n3️⃣ Checking PHP and Package Versions...");
        $this->runShellCommand('php -v');
        $this->runShellCommand('composer show phpoffice/math phpoffice/phpword phpoffice/phpspreadsheet barryvdh/laravel-dompdf livewire/livewire livewire/flux laravel/framework laravel/fortify');

        // D. File Permissions
        $this->info("\n4️⃣ Checking Storage & Bootstrap Permissions...");
        $storage = storage_path();
        $bootstrap = base_path('bootstrap/cache');
        $this->checkWritable($storage, 'storage');
        $this->checkWritable($bootstrap, 'bootstrap/cache');

        // E. CSRF & XSS (basic check)
        // E. CSRF & XSS (basic check)
        $this->info("\n5️⃣ Checking Blade & Livewire security hints...");
        $this->line("✅ Ensure all forms use @csrf");
        $this->line("✅ Escape output in Blade with {{ }}");
        $this->line("✅ Use authorize() in Livewire components for role checks");

        // F. HTTPS
        $this->info("\n6️⃣ Checking APP_URL for HTTPS...");
        $appUrl = config('app.url');
        $this->line("APP_URL: $appUrl");
        if(str_starts_with($appUrl, 'https://')) {
            $this->line("✅ HTTPS enabled");
        } else {
            $this->warn("⚠ HTTPS not enforced");
        }

        // G. Routes Security
        $this->info("\n7️⃣ Listing web routes with auth middleware...");
        $this->runShellCommand('php artisan route:list --path=admin');

        $this->info("\n✅ Security Checklist Completed!");
        return 0;
    }

    private function runShellCommand($command)
    {
        $this->line("🔹 Running: $command");
        passthru($command);
    }

    private function checkWritable($path, $name)
    {
        if(File::isWritable($path)) {
            $this->line("✅ $name is writable: $path");
        } else {
            $this->warn("⚠ $name is NOT writable: $path");
        }
    }
}
