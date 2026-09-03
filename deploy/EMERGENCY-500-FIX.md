# Emergency fix — HTTP 500 on training.lcbkp.gov.pk

## Cause
The live server is missing critical files (partial upload or accidental delete).
`quiz-check.php` shows many `[FAIL]` files including `routes/web.php`.

## Fix in this order

### Step 1 — Upload these FIRST (site will work again)

```
routes/web.php
app/Providers/AppServiceProvider.php
app/Providers/TraineeQuizViewServiceProvider.php
config/app.php
```

### Step 2 — Upload quiz files

```
app/Support/TraineeQuizData.php
app/Http/Controllers/Trainee/QuizController.php
app/Http/Controllers/Trainee/DashboardController.php
app/Models/Quiz.php
app/Models/QuizQuestion.php
app/Models/QuizOption.php
app/Models/QuizAttempt.php
app/Models/QuizAttemptAnswer.php
resources/views/trainee/quizzes/index.blade.php
resources/views/trainee/quizzes/_card.blade.php
resources/views/trainee/quizzes/take.blade.php
resources/views/trainee/quizzes/result.blade.php
resources/views/trainee/dashboard.blade.php
```

### Step 3 — Upload helpers

```
public/clear-cache.php
public/emergency-check.php
public/quiz-check.php
```

### Step 4 — Clear cache

Open: https://training.lcbkp.gov.pk/clear-cache.php

### Step 5 — Verify

Open: https://training.lcbkp.gov.pk/emergency-check.php

All should show `[OK]`.

## Easiest option

Upload the full project zip from `deploy/build-cpanel-package.bat` — do not delete server files first, only overwrite.

## .env on server (do not break syntax)

```env
APP_ENV=production
APP_DEBUG=true
SHOW_ERRORS=true
APP_URL=https://training.lcbkp.gov.pk
APP_TIMEZONE=Asia/Karachi
```

After site works, set `APP_DEBUG=false`.

## Important

- Do **not** upload only `config/app.php` without the PHP files it references
- Do **not** delete `routes/web.php` on server
- `vendor/` folder must exist — if missing run `composer install` on server
