<?php

/**
 * Emergency server diagnostic — works even when Laravel is broken.
 * Open: https://training.lcbkp.gov.pk/emergency-check.php
 * Delete after fixing.
 */

header('Content-Type: text/plain; charset=UTF-8');

$root = dirname(__DIR__);
$failed = 0;

echo "TMS LGS Emergency Server Check\n";
echo "==============================\n\n";

$critical = [
    'routes/web.php' => 'Site routes — site 500 if missing',
    'app/Providers/AppServiceProvider.php' => 'App bootstrap',
    'config/app.php' => 'App config',
    'vendor/autoload.php' => 'Composer packages',
    'bootstrap/app.php' => 'Laravel bootstrap',
    '.env' => 'Environment config',
];

echo "[CRITICAL — site will not work if missing]\n";
foreach ($critical as $file => $note) {
    $exists = file_exists($root.'/'.$file);
    echo ($exists ? '[OK] ' : '[FAIL] ').$file.' — '.$note."\n";
    if (! $exists) {
        $failed++;
    }
}

$quiz = [
    'app/Support/TraineeQuizData.php',
    'app/Http/Controllers/Trainee/QuizController.php',
    'app/Models/Quiz.php',
    'resources/views/trainee/quizzes/index.blade.php',
    'resources/views/trainee/quizzes/_card.blade.php',
];

echo "\n[QUIZ FILES]\n";
foreach ($quiz as $file) {
    $exists = file_exists($root.'/'.$file);
    echo ($exists ? '[OK] ' : '[FAIL] ').$file."\n";
    if (! $exists) {
        $failed++;
    }
}

echo "\n[PHP]\n";
echo 'PHP version: '.PHP_VERSION."\n";

if (file_exists($root.'/.env')) {
    echo "\n[.env check]\n";
    $env = file_get_contents($root.'/.env');
    echo 'APP_DEBUG: '.(preg_match('/^APP_DEBUG\s*=\s*true/mi', $env) ? 'true' : 'false')."\n";
    echo 'APP_KEY set: '.(preg_match('/^APP_KEY\s*=\s*\S+/m', $env) && ! preg_match('/^APP_KEY\s*=\s*$/m', $env) ? 'yes' : 'NO — run php artisan key:generate')."\n";
}

if (file_exists($root.'/storage/logs/laravel.log')) {
    echo "\n[Last 30 lines of laravel.log]\n";
    $lines = file($root.'/storage/logs/laravel.log', FILE_IGNORE_NEW_LINES);
    foreach (array_slice($lines ?: [], -30) as $line) {
        echo $line."\n";
    }
}

echo "\n[QUICK FIX]\n";
echo "1. Re-upload routes/web.php first (most common 500 cause)\n";
echo "2. Re-upload all app/ files from your local project\n";
echo "3. Do NOT delete files on server — only overwrite with local copies\n";
echo "4. Run clear-cache.php after upload\n";

echo "\n";
if ($failed > 0) {
    echo "RESULT: {$failed} missing file(s) — re-upload from local project.\n";
    http_response_code(500);
} else {
    echo "RESULT: Critical files present. Check laravel.log above for error details.\n";
}
