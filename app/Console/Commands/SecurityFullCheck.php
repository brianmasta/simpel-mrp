<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Finder\Finder;

class SecurityFullCheck extends Command
{
    protected $signature = 'security:full-check {--output= : use "report" to save results to storage/logs}';
    protected $description = 'Run a full security checklist + heuristic SQL/XSS scan for Laravel + Livewire project';

    // Heuristic patterns for SQL and XSS
    protected array $sqlPatterns = [
        '/\bDB::select\s*\(/i',
        '/\bDB::statement\s*\(/i',
        '/->whereRaw\s*\(/i',
        '/->selectRaw\s*\(/i',
        '/->orderByRaw\s*\(/i',
        '/->havingRaw\s*\(/i',
        '/->orWhereRaw\s*\(/i',
        '/\braw\(/i',
        '/->where\s*\(\s*([\'"]).*\.\$[a-zA-Z0-9_\->\[\]\'"]+/i',
        '/->where\s*\(\s*\$[a-zA-Z0-9_]+\s*\.\s*[\'"]+/i',
        '/["\'].*\{\$[a-zA-Z0-9_]+\}.*["\']/',
        '/->from\s*\(\s*\$[a-zA-Z0-9_]+\s*\)/i',
    ];

    protected array $xssPatternsPhp = [
        '/echo\s+\$[a-zA-Z0-9_\[\]\'\"\->]+;/i',
        '/print\s+\$[a-zA-Z0-9_\[\]\'\"\->]+;/i',
    ];

    protected array $xssPatternsBlade = [
        '/{!!\s*[^}]+\s*!!}/',
        '/{!!\s*\$[a-zA-Z0-9_\->\[\]\'"]+\s*!!}/',
        '/@php\s+echo\s+.+;?\s+@endphp/i',
    ];

    public function handle()
    {
        $start = now()->format('Ymd_His');
        $report = [];
        $this->info("🔒 Starting FULL Security Checklist for SIMPEL-MRP...\n");
        $report[] = "Security check started at: " . now()->toDateTimeString();

        // 1. Env checks
        $this->section("1) Environment");
        $this->checkEnv($report);

        // 2. Composer audit
        $this->section("2) Composer Security Audit");
        $this->composerAudit($report);

        // 3. PHP & packages
        $this->section("3) PHP & Packages");
        $this->checkPhpAndPackages($report);

        // 4. Permissions
        $this->section("4) File Permissions");
        $this->checkPermissions($report);

        // 5. Blade & Livewire hints
        $this->section("5) Blade & Livewire Security Hints");
        $this->bladeLivewireHints($report);

        // 6. HTTPS
        $this->section("6) HTTPS Enforcement");
        $this->checkHttps($report);

        // 7. Routes
        $this->section("7) Routes (admin/auth)");
        $this->checkRoutes($report);

        // 8. Sessions & cookies
        $this->section("8) Session & Cookie Settings");
        $this->checkSessionCookies($report);

        // 9. File upload config
        $this->section("9) File Upload Config");
        $this->checkFileUploadConfig($report);

        // 10. Advanced heuristic scan (SQL / XSS)
        $this->section("10) Advanced Heuristic Scan (Livewire & Blade)");
        $this->advancedScan($report);

        $this->info("\n✅ FULL Security Checklist Completed!");
        $report[] = "Security check completed at: " . now()->toDateTimeString();

        // Save report if requested
        if ($this->option('output') === 'report') {
            $filename = storage_path("logs/security-full-check-{$start}.log");
            try {
                file_put_contents($filename, implode("\n\n", $report));
                $this->info("Report saved to: $filename");
            } catch (\Throwable $e) {
                $this->warn("Failed to write report file: " . $e->getMessage());
            }
        }

        return 0;
    }

    protected function section(string $title)
    {
        $this->info("\n--- $title ---");
    }

    protected function checkEnv(array &$report)
    {
        $debug = config('app.debug') ? 'ON (⚠ disable in production)' : 'OFF';
        $this->line("APP_DEBUG: $debug");
        $report[] = "APP_DEBUG: $debug";

        if (config('app.key')) {
            $this->line("APP_KEY: set ✅");
            $report[] = "APP_KEY: set";
        } else {
            $this->warn("APP_KEY: NOT SET ⚠");
            $report[] = "APP_KEY: NOT SET";
        }
    }

    protected function composerAudit(array &$report)
    {
        $this->line("Running: composer audit (may require network access)");
        $res = $this->runProcess(['composer', 'audit', '--no-interaction'], 45);
        if ($res['ok']) {
            $this->line($res['output']);
            $report[] = "Composer audit output:\n" . $this->shorten($res['output']);
        } else {
            $this->warn("Composer audit failed or timed out. Run `composer audit` manually on a machine with internet.");
            $report[] = "Composer audit failed: " . ($res['error'] ?? 'no output');
        }
    }

    protected function checkPhpAndPackages(array &$report)
    {
        $php = $this->runProcess(['php', '-v'], 10);
        if ($php['ok']) {
            $this->line($php['output']);
            $report[] = "PHP Version:\n" . $php['output'];
        } else {
            $this->warn("Failed to detect PHP version");
            $report[] = "PHP detect failed";
        }

        $packages = [
            'phpoffice/math',
            'phpoffice/phpword',
            'phpoffice/phpspreadsheet',
            'barryvdh/laravel-dompdf',
            'livewire/livewire',
            'livewire/flux',
            'laravel/framework',
            'laravel/fortify',
        ];

        foreach ($packages as $pkg) {
            $this->line("Checking package: $pkg");
            $res = $this->runProcess(['composer', 'show', $pkg, '--no-interaction', '--no-ansi'], 15);
            if ($res['ok']) {
                $this->line($this->shorten($res['output'], 600));
                $report[] = "Package $pkg:\n" . $this->shorten($res['output'], 2000);
            } else {
                $this->warn("Package $pkg not found or composer show failed.");
                $report[] = "Package $pkg: not found / composer show failed";
            }
        }
    }

    protected function checkPermissions(array &$report)
    {
        $paths = [
            'storage' => storage_path(),
            'bootstrap/cache' => base_path('bootstrap/cache'),
        ];
        foreach ($paths as $name => $path) {
            if (File::isDirectory($path) && File::isWritable($path)) {
                $this->line("✅ $name is writable: $path");
                $report[] = "$name writable: $path";
            } else {
                $this->warn("⚠ $name is NOT writable: $path");
                $report[] = "$name NOT writable: $path";
            }
        }
    }

    protected function bladeLivewireHints(array &$report)
    {
        $this->line("✅ Ensure all forms use @csrf");
        $this->line("✅ Escape output in Blade with {{ }}");
        $this->line("✅ Use authorize() or policies in Livewire components for role checks");
        $report[] = "Blade/Livewire hints: @csrf, use {{ }}, use authorize()/policies";
    }

    protected function checkHttps(array &$report)
    {
        $appUrl = config('app.url') ?: 'not set';
        $this->line("APP_URL: $appUrl");
        $report[] = "APP_URL: $appUrl";
        if (str_starts_with($appUrl, 'https://')) {
            $this->line("✅ HTTPS enforced");
            $report[] = "HTTPS: enforced";
        } else {
            $this->warn("⚠ HTTPS not enforced (recommend forcing HTTPS in production)");
            $report[] = "HTTPS: not enforced";
        }
    }

    protected function checkRoutes(array &$report)
    {
        $res = $this->runProcess(['php', 'artisan', 'route:list', '--columns=method,uri,name,middleware', '--no-interaction', '--no-ansi'], 20);
        if ($res['ok']) {
            $routesOutput = $res['output'];
            $filtered = $this->filterRoutes($routesOutput);
            if ($filtered) {
                $this->line($filtered);
                $report[] = "Filtered routes (admin/auth):\n" . $filtered;
            } else {
                $this->line("No admin/auth routes filtered in route:list output.");
                $report[] = "Filtered routes: none";
            }
        } else {
            $this->warn("Failed to list routes. Run `php artisan route:list` manually.");
            $report[] = "route:list failed";
        }
    }

    protected function checkSessionCookies(array &$report)
    {
        $secure = config('session.secure') ? 'true' : 'false';
        $httpOnly = config('session.http_only') ? 'true' : 'false';
        $sameSite = config('session.same_site') ?: 'not set';
        $this->line("SESSION_SECURE_COOKIE: $secure");
        $this->line("SESSION_HTTP_ONLY: $httpOnly");
        $this->line("SESSION_SAME_SITE: $sameSite");
        $report[] = "SESSION_SECURE_COOKIE: $secure, SESSION_HTTP_ONLY: $httpOnly, SESSION_SAME_SITE: $sameSite";
    }

    protected function checkFileUploadConfig(array &$report)
    {
        $maxSize = ini_get('upload_max_filesize');
        $this->line("PHP upload_max_filesize: $maxSize");
        $this->line("Ensure file type validation (mimes) and store uploads outside public dir.");
        $report[] = "upload_max_filesize: $maxSize";
    }

    protected function advancedScan(array &$report)
    {
        $livewireDir = app_path('Http/Livewire');
        $viewsDir = resource_path('views');

        $sqlFindings = [];
        $xssFindings = [];

        $finder = new Finder();

        // Scan Livewire PHP files for SQL patterns
        if (is_dir($livewireDir)) {
            $this->line("Scanning Livewire components in: $livewireDir");
            $finder->files()->in($livewireDir)->name('*.php');
            foreach ($finder as $file) {
                $path = $file->getRealPath();
                $lines = file($path);
                foreach ($lines as $num => $line) {
                    foreach ($this->sqlPatterns as $pattern) {
                        if (preg_match($pattern, $line)) {
                            $sqlFindings[] = $this->makeFinding('SQL', $path, $num + 1, trim($line), $pattern);
                        }
                    }
                    if (preg_match('/->(where|select|having|orderBy)\s*\(/i', $line) && preg_match('/\./', $line) && preg_match('/\$[a-zA-Z0-9_]+/', $line)) {
                        $sqlFindings[] = $this->makeFinding('SQL', $path, $num + 1, trim($line), 'heuristic-concat');
                    }
                    // PHP echo/print XSS pattern
                    foreach ($this->xssPatternsPhp as $pattern) {
                        if (preg_match($pattern, $line)) {
                            $xssFindings[] = $this->makeFinding('XSS', $path, $num + 1, trim($line), $pattern);
                        }
                    }
                }
            }
        } else {
            $this->warn("Livewire dir not found: $livewireDir — skipping Livewire scan.");
            $report[] = "Livewire scan skipped: dir not found";
        }

        // Scan Blade views
        if (is_dir($viewsDir)) {
            $this->line("Scanning Blade views in: $viewsDir");
            $finder->files()->in($viewsDir)->name('*.blade.php');
            foreach ($finder as $file) {
                $path = $file->getRealPath();
                $lines = file($path);
                foreach ($lines as $num => $line) {
                    foreach ($this->xssPatternsBlade as $pattern) {
                        if (preg_match($pattern, $line)) {
                            $xssFindings[] = $this->makeFinding('XSS', $path, $num + 1, trim($line), $pattern);
                        }
                    }
                    if (str_contains($line, '{!!')) {
                        $xssFindings[] = $this->makeFinding('XSS', $path, $num + 1, trim($line), 'contains-{!!}');
                    }
                }
            }
        } else {
            $this->warn("Views dir not found: $viewsDir — skipping Blade scan.");
            $report[] = "Blade scan skipped: dir not found";
        }

        // Summarize
        $this->line("\nScan summary:");
        $this->line(" - SQL heuristic findings: " . count($sqlFindings));
        $this->line(" - XSS heuristic findings: " . count($xssFindings));
        $report[] = "SQL findings: " . count($sqlFindings);
        $report[] = "XSS findings: " . count($xssFindings);

        if ($sqlFindings) {
            $this->warn("\nPotential SQL Injection Findings (heuristic):");
            foreach ($sqlFindings as $f) {
                $this->printFinding($f);
                $report[] = "SQL: {$f['file']}:{$f['line']} => {$f['snippet']} (pattern: {$f['pattern']})";
            }
        } else {
            $this->line("No obvious SQL raw/query patterns found in Livewire components.");
            $report[] = "No obvious SQL raw/query patterns found";
        }

        if ($xssFindings) {
            $this->warn("\nPotential XSS / Unescaped Output Findings (heuristic):");
            foreach ($xssFindings as $f) {
                $this->printFinding($f);
                $report[] = "XSS: {$f['file']}:{$f['line']} => {$f['snippet']} (pattern: {$f['pattern']})";
            }
        } else {
            $this->line("No obvious unescaped Blade output ({!! !!}) or raw echo/print found.");
            $report[] = "No obvious unescaped Blade output or raw echo/print found";
        }

        // Guidance
        $this->line("\nRemediation guidance:");
        $this->line(" - For SQL: use Eloquent/Query Builder with parameter binding; avoid whereRaw and string concatenation.");
        $this->line(" - For XSS: replace {!! \$var !!} with {{ \$var }} or sanitize HTML with Purify/HTMLPurifier.");
        $report[] = "Remediation guidance: use bindings, sanitize HTML, validate inputs.";
    }

    protected function makeFinding($type, $file, $line, $snippet, $pattern)
    {
        return compact('type', 'file', 'line', 'snippet', 'pattern');
    }

    protected function printFinding(array $f)
    {
        $this->line("\n------------------------------");
        $this->line("Type    : {$f['type']}");
        $this->line("File    : {$f['file']}:{$f['line']}");
        $this->line("Pattern : {$f['pattern']}");
        $this->line("Code    : {$f['snippet']}");
    }

    protected function runProcess(array $command, int $timeout = 60): array
    {
        try {
            $process = new Process($command);
            $process->setTimeout($timeout);
            $process->run();
            if (!$process->isSuccessful()) {
                return [
                    'ok' => false,
                    'output' => $process->getOutput(),
                    'error' => $process->getErrorOutput() ?: $process->getOutput(),
                ];
            }
            return ['ok' => true, 'output' => $process->getOutput(), 'error' => null];
        } catch (\Throwable $e) {
            return ['ok' => false, 'output' => null, 'error' => $e->getMessage()];
        }
    }

    protected function shorten(string $text, int $limit = 800): string
    {
        $t = trim($text);
        return strlen($t) > $limit ? substr($t, 0, $limit) . "\n... (truncated) ..." : $t;
    }

    protected function filterRoutes(string $routesOutput): ?string
    {
        $lines = preg_split("/\r\n|\n|\r/", $routesOutput);
        $filtered = [];
        foreach ($lines as $line) {
            $low = strtolower($line);
            if (strpos($low, 'admin') !== false || strpos($low, 'auth') !== false) {
                $filtered[] = $line;
            }
        }
        return $filtered ? implode("\n", $filtered) : null;
    }
}
