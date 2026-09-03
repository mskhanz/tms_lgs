# Fix trainee quizzes page on training.lcbkp.gov.pk

If https://training.lcbkp.gov.pk/trainee/quizzes is blank or shows nothing, upload these files and run migrations.

## Step 1 — Open diagnostic

Upload `public/quiz-check.php` then open:

https://training.lcbkp.gov.pk/quiz-check.php

It shows missing files, missing DB tables, and quiz counts.

## Step 2 — Upload these files (minimum)

```
app/Http/Controllers/Trainee/QuizController.php
app/Http/Controllers/Trainee/DashboardController.php
app/Support/TraineeQuizData.php
app/Providers/TraineeQuizViewServiceProvider.php
config/app.php
app/Models/Quiz.php
app/Models/QuizQuestion.php
app/Models/QuizOption.php
app/Models/QuizAttempt.php
app/Models/QuizAttemptAnswer.php
resources/views/trainee/quizzes/index.blade.php
resources/views/trainee/quizzes/_card.blade.php
resources/views/trainee/dashboard.blade.php
resources/views/trainee/quizzes/take.blade.php
resources/views/trainee/quizzes/result.blade.php
resources/views/layouts/admin.blade.php
public/css/admin-style.css
routes/web.php
database/migrations/2026_09_01_000001_create_quizzes_tables.php
```

Or upload full package: `deploy/tms_lgs_deploy.zip`

## Step 3 — Run on server (SSH / cPanel Terminal)

```bash
cd /path/to/tms_lgs
php artisan migrate --force
php artisan view:clear
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

Or upload `public/clear-cache.php` and open:

https://training.lcbkp.gov.pk/clear-cache.php

Then delete `clear-cache.php`.

## Step 4 — .env on server

```env
APP_URL=https://training.lcbkp.gov.pk
APP_TIMEZONE=Asia/Karachi
```

## Step 5 — Admin panel

1. Login as admin → Quizzes
2. Ensure quiz is **Active**
3. Ensure quiz has **questions** added
4. Check **Available From / Until** dates (Pakistan time) or leave empty for always open

## Step 6 — Check error log

If still blank:

```bash
tail -50 storage/logs/laravel.log
```

Common errors:
- `View [trainee.quizzes._card] not found` → upload `_card.blade.php`
- `Call to undefined method activeForTrainees()` → upload latest `Quiz.php`
- `Table 'quizzes' doesn't exist` → run `php artisan migrate --force`

Delete `public/quiz-check.php` after fixing.
