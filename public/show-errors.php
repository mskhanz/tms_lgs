<?php

/**
 * View Laravel debug status and recent errors on the live server.
 * Open: https://training.lcbkp.gov.pk/show-errors.php
 * Delete after debugging.
 */

header('Content-Type: text/plain; charset=UTF-8');

$root = dirname(__DIR__);

echo "TMS LGS Error Reporting Check\n";
echo "==============================\n\n";

if (! file_exists($root.'/vendor/autoload.php')) {
    echo "FAIL: vendor/ missing.\n";
    http_response_code(500);
    exit(1);
}

require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "[Config]\n";
echo 'APP_ENV: '.config('app.env')."\n";
echo 'APP_DEBUG: '.(config('app.debug') ? 'true (ON)' : 'false (OFF)')."\n";
echo 'SHOW_ERRORS (.env): '.(filter_var(env('SHOW_ERRORS', false), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false')."\n";
echo 'LOG_LEVEL: '.config('logging.channels.stack.level', 'default')."\n";
echo 'display_errors (PHP): '.ini_get('display_errors')."\n";
echo 'error_reporting (PHP): '.error_reporting()."\n\n";

if (! config('app.debug')) {
    echo "[ACTION REQUIRED]\n";
    echo "Set these in .env on the server, then run clear-cache.php:\n";
    echo "APP_DEBUG=true\n";
    echo "SHOW_ERRORS=true\n";
    echo "LOG_LEVEL=debug\n\n";
}

$logFile = $root.'/storage/logs/laravel.log';

echo "[Last errors from laravel.log]\n";

if (! file_exists($logFile)) {
    echo "No log file yet: storage/logs/laravel.log\n";
    exit(0);
}

$lines = file($logFile, FILE_IGNORE_NEW_LINES);
$tail = array_slice($lines ?: [], -80);

if ($tail === []) {
    echo "Log file is empty.\n";
    exit(0);
}

foreach ($tail as $line) {
    echo $line."\n";
}

echo "\nDelete public/show-errors.php after fixing.\n";
