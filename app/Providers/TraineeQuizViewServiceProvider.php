<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class TraineeQuizViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! class_exists(\App\Support\TraineeQuizData::class)) {
            return;
        }

        View::composer('trainee.dashboard', function ($view) {
            if (! Auth::check()) {
                return;
            }

            $data = \App\Support\TraineeQuizData::load();
            $existing = $view->getData();

            foreach ($data as $key => $value) {
                if (! array_key_exists($key, $existing)) {
                    $view->with($key, $value);
                }
            }
        });

        View::composer('trainee.quizzes.index', function ($view) {
            if (! Auth::check()) {
                return;
            }

            $data = \App\Support\TraineeQuizData::forQuizIndex();
            $existing = $view->getData();

            foreach ($data as $key => $value) {
                if (! array_key_exists($key, $existing)) {
                    $view->with($key, $value);
                }
            }
        });
    }
}
