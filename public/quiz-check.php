<?php

/**
 * Quiz module diagnostic for live server.
 * Open: https://training.lcbkp.gov.pk/quiz-check.php
 * Delete after fixing.
 */

header('Content-Type: text/plain; charset=UTF-8');

$root = dirname(__DIR__);
$failed = 0;

echo "TMS LGS Quiz Module Check\n";
echo "=========================\n\n";

$requiredFiles = [
    'app/Http/Controllers/Trainee/QuizController.php',
    'app/Http/Controllers/Trainee/DashboardController.php',
    'app/Support/TraineeQuizData.php',
    'app/Providers/TraineeQuizViewServiceProvider.php',
    'app/Models/Quiz.php',
    'app/Models/QuizQuestion.php',
    'app/Models/QuizOption.php',
    'app/Models/QuizAttempt.php',
    'app/Models/QuizAttemptAnswer.php',
    'resources/views/trainee/quizzes/index.blade.php',
    'resources/views/trainee/quizzes/_card.blade.php',
    'resources/views/trainee/dashboard.blade.php',
    'resources/views/trainee/quizzes/take.blade.php',
    'resources/views/trainee/quizzes/result.blade.php',
    'database/migrations/2026_09_01_000001_create_quizzes_tables.php',
    'routes/web.php',
];

echo "[Required files]\n";
foreach ($requiredFiles as $file) {
    $exists = file_exists($root.'/'.$file);
    echo ($exists ? '[OK] ' : '[FAIL] ').$file."\n";
    if (! $exists) {
        $failed++;
    }
}

if (! file_exists($root.'/vendor/autoload.php')) {
    echo "\n[FAIL] vendor/ missing. Run composer install on server.\n";
    $failed++;
    exit(1);
}

require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n[App config]\n";
echo 'APP_URL: '.config('app.url')."\n";
echo 'APP_TIMEZONE: '.config('app.timezone')."\n";
echo 'APP_DEBUG: '.(config('app.debug') ? 'true' : 'false')."\n";
echo 'now: '.now()."\n";

$tables = ['quizzes', 'quiz_questions', 'quiz_options', 'quiz_attempts', 'quiz_attempt_answers'];
echo "\n[Database tables]\n";
foreach ($tables as $table) {
    $exists = Illuminate\Support\Facades\Schema::hasTable($table);
    echo ($exists ? '[OK] ' : '[FAIL] ').$table."\n";
    if (! $exists) {
        $failed++;
    }
}

if ($failed === 0) {
    echo "\n[Dashboard integration]\n";
    $dashboardController = file_get_contents($root.'/app/Http/Controllers/Trainee/DashboardController.php') ?: '';
    $dashboardView = file_get_contents($root.'/resources/views/trainee/dashboard.blade.php') ?: '';
    $hasControllerQuiz = str_contains($dashboardController, 'TraineeQuizData');
    $hasViewQuiz = str_contains($dashboardView, 'Available Quizzes');
    echo ($hasControllerQuiz ? '[OK] ' : '[FAIL] ')."DashboardController loads quiz data\n";
    echo ($hasViewQuiz ? '[OK] ' : '[FAIL] ')."dashboard.blade.php shows Available Quizzes section\n";
    if (! $hasControllerQuiz || ! $hasViewQuiz) {
        $failed++;
    }

    echo "\n[Quiz data]\n";
    try {
        $total = App\Models\Quiz::count();
        $active = App\Models\Quiz::where('is_active', true)->count();
        $visible = App\Models\Quiz::withCount('questions')->activeForTrainees()->count();
        echo "Total quizzes: {$total}\n";
        echo "Active quizzes: {$active}\n";
        echo "Visible to trainees: {$visible}\n";

        App\Models\Quiz::withCount('questions')->get()->each(function ($quiz) {
            echo '- #'.$quiz->id.' '.$quiz->title
                .' | active:'.($quiz->is_active ? 'Y' : 'N')
                .' | questions:'.$quiz->questions_count
                .' | status:'.$quiz->traineeStatus()
                .($quiz->available_until ? ' | until:'.$quiz->available_until->format('Y-m-d H:i') : '')
                ."\n";
        });

        $openCount = App\Models\Quiz::withCount('questions')->activeForTrainees()->get()
            ->filter(fn ($quiz) => $quiz->traineeStatus() === 'open')->count();
        if ($visible > 0 && $openCount === 0) {
            echo "\n[NOTE] Quiz exists but none are OPEN right now (scheduled/closed window).\n";
            echo "Fix: Admin -> Quizzes -> edit -> clear or extend Available Until date.\n";
        }
    } catch (Throwable $e) {
        echo '[FAIL] Quiz query error: '.$e->getMessage()."\n";
        $failed++;
    }
}

echo "\n[Fix on server]\n";
echo "1) Upload missing files listed above (especially _card.blade.php and Quiz.php)\n";
echo "2) php artisan migrate --force\n";
echo "3) php artisan view:clear && php artisan config:clear && php artisan route:clear\n";
echo "4) Ensure APP_TIMEZONE=Asia/Karachi in .env\n";
echo "5) Activate quiz in Admin and add questions\n";

echo "\n";
if ($failed > 0) {
    echo "RESULT: FIX REQUIRED ({$failed} issue(s))\n";
    http_response_code(500);
} else {
    echo "RESULT: Quiz module files and tables look OK.\n";
    echo "If page is still blank, clear view cache and check storage/logs/laravel.log\n";
}
