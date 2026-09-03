<?php

/**
 * One-time server diagnostic. Delete this file after deployment is working.
 * Open: https://training.lcbkp.gov.pk/check-server.php
 */

header('Content-Type: text/plain; charset=UTF-8');

$root = dirname(__DIR__);

$checks = [
    'PHP version >= 8.1' => version_compare(PHP_VERSION, '8.1.0', '>='),
    'vendor/autoload.php' => file_exists($root.'/vendor/autoload.php'),
    'BoundMethod class file' => file_exists($root.'/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php'),
    '.env file' => file_exists($root.'/.env'),
    'user_photos directory' => is_dir($root.'/public/user_photos'),
    'user_photos writable' => is_dir($root.'/public/user_photos') && is_writable($root.'/public/user_photos'),
    'storage writable' => is_writable($root.'/storage'),
    'bootstrap/cache writable' => is_writable($root.'/bootstrap/cache'),
    'upload_max_filesize >= 5M' => parseUploadLimit(ini_get('upload_max_filesize')) >= 5 * 1024 * 1024,
    'post_max_size >= 6M' => parseUploadLimit(ini_get('post_max_size')) >= 6 * 1024 * 1024,
];

function parseUploadLimit(string $value): int
{
    $value = trim($value);
    if ($value === '') {
        return 0;
    }

    $unit = strtolower(substr($value, -1));
    $number = (float) $value;

    return match ($unit) {
        'g' => (int) ($number * 1024 * 1024 * 1024),
        'm' => (int) ($number * 1024 * 1024),
        'k' => (int) ($number * 1024),
        default => (int) $number,
    };
}

echo "TMS LGS Server Check\n";
echo "==================\n";
echo "Project root: {$root}\n";
echo "PHP: ".PHP_VERSION."\n";
echo "upload_max_filesize: ".ini_get('upload_max_filesize')."\n";
echo "post_max_size: ".ini_get('post_max_size')."\n\n";

$failed = 0;
foreach ($checks as $label => $ok) {
    echo ($ok ? '[OK] ' : '[FAIL] ').$label."\n";
    if (! $ok) {
        $failed++;
    }
}

if (file_exists($root.'/vendor/autoload.php')) {
    require $root.'/vendor/autoload.php';

    echo (class_exists('Illuminate\\Container\\BoundMethod') ? '[OK] ' : '[FAIL] ')."BoundMethod autoload\n";
    if (! class_exists('Illuminate\\Container\\BoundMethod')) {
        $failed++;
    }

    if (file_exists($root.'/bootstrap/app.php')) {
        $app = require_once $root.'/bootstrap/app.php';
        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        echo '[INFO] APP_URL: '.config('app.url')."\n";
        echo '[INFO] Mailer: '.config('mail.default')."\n";
        echo '[INFO] SMTP host: '.config('mail.mailers.smtp.host').':'.config('mail.mailers.smtp.port')."\n";
        echo '[INFO] Failover: '.implode(' -> ', config('mail.mailers.failover.mailers', []))."\n";
        echo '[INFO] From address: '.config('mail.from.address')."\n";
        echo (str_starts_with((string) config('app.url'), 'https://') ? '[OK] ' : '[WARN] ')."APP_URL uses HTTPS\n";

        $failover = config('mail.mailers.failover.mailers', []);
        if (in_array('log', $failover, true)) {
            echo "[FAIL] Failover includes 'log' — emails are NOT sent, only logged!\n";
            echo "       Set MAIL_FAILOVER_MAILERS=smtp,cpanel_smtp,sendmail in .env\n";
            $failed++;
        }

        echo "\n[Mail connectivity]\n";
        checkSmtpPort(config('mail.mailers.smtp.host'), (int) config('mail.mailers.smtp.port'));
        checkSmtpPort('localhost', 587);
        checkSmtpPort('localhost', 465);

        $sendmail = trim(strtok((string) config('mail.mailers.sendmail.path'), ' '));
        if ($sendmail && file_exists($sendmail)) {
            echo "[OK] Sendmail: {$sendmail}\n";
        } else {
            echo "[WARN] Sendmail not found at {$sendmail}\n";
            echo "       On cPanel use cPanel email (MAIL_CPANEL_HOST=localhost)\n";
        }

        if (str_contains((string) config('mail.from.address'), '@gmail.com')) {
            echo "[WARN] Gmail FROM on live server often fails. Use training@lcbkp.gov.pk\n";
        }

        echo "\nRun on server: php artisan mail:diagnose\n";
        echo "Test email: php artisan mail:test your@email.com\n";
    }
}

function checkSmtpPort(string $host, int $port): void
{
    if ($host === '' || $port <= 0) {
        return;
    }

    $connection = @fsockopen($host, $port, $errno, $errstr, 5);
    if ($connection) {
        fclose($connection);
        echo "[OK] {$host}:{$port} reachable\n";
    } else {
        echo "[FAIL] {$host}:{$port} blocked ({$errstr})\n";
    }
}

echo "\n";
if ($failed > 0) {
    echo "Fix required before registration and email will work reliably.\n";
    http_response_code(500);
} else {
    echo "Server looks ready. Delete public/check-server.php after verification.\n";
}
