<?php

/**
 * One-time Laravel cache clear for live server.
 * Open: https://training.lcbkp.gov.pk/clear-cache.php
 * Delete after use.
 */

header('Content-Type: text/plain; charset=UTF-8');

$root = dirname(__DIR__);

if (! file_exists($root.'/vendor/autoload.php')) {
    echo "FAIL: vendor/ missing.\n";
    http_response_code(500);
    exit(1);
}

require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$commands = [
    'view:clear',
    'config:clear',
    'route:clear',
    'cache:clear',
];

echo "TMS LGS Cache Clear\n";
echo "===================\n\n";

foreach ($commands as $command) {
    $kernel->call($command);
    echo "[OK] php artisan {$command}\n";
}

echo "\nDone. Delete public/clear-cache.php after use.\n";
