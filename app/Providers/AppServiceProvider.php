<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->configureErrorReporting();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        $this->shareAdminLayoutData();

        if (is_file(config_path('mail_local.php'))) {
            $localMail = require config_path('mail_local.php');

            foreach ($localMail as $key => $value) {
                if (is_array($value) && is_array(config("mail.{$key}"))) {
                    config(["mail.{$key}" => array_replace_recursive(config("mail.{$key}"), $value)]);
                } else {
                    config(["mail.{$key}" => $value]);
                }
            }
        }

        $this->normalizeMailCredentials();
        $this->configureAppUrlAndSession();

        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $resetUrl = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Password Reset Request - ' . config('app.name'))
                ->view('emails.password-reset', [
                    'resetUrl' => $resetUrl,
                    'user' => $notifiable,
                    'expiryMinutes' => Config::get('auth.passwords.users.expire', 60),
                ]);
        });
    }

    private function shareAdminLayoutData(): void
    {
        View::composer('layouts.admin', function ($view) {
            $user = Auth::user();
            if (! $user) {
                $view->with([
                    'navUnreadCount' => 0,
                    'navNotifications' => collect(),
                ]);
                return;
            }

            $user->loadMissing(['roles', 'traineeProfile']);

            $view->with([
                'navUnreadCount' => $user->notifications()->unread()->count(),
                'navNotifications' => $user->notifications()->latest()->limit(5)->get(),
            ]);
        });
    }

    private function normalizeMailCredentials(): void
    {
        foreach (['smtp', 'cpanel_smtp'] as $mailer) {
            $password = config("mail.mailers.{$mailer}.password");

            if (is_string($password) && $password !== '') {
                config([
                    "mail.mailers.{$mailer}.password" => str_replace(' ', '', trim($password, " \t\n\r\0\x0B\"'")),
                ]);
            }
        }

        if (empty(config('mail.mailers.smtp.local_domain'))) {
            $host = parse_url((string) config('app.url'), PHP_URL_HOST);

            if (is_string($host) && $host !== '') {
                config(['mail.mailers.smtp.local_domain' => $host]);
                config(['mail.mailers.cpanel_smtp.local_domain' => $host]);
            }
        }
    }

    private function configureAppUrlAndSession(): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        $request = request();
        if (! $request || ! $request->getHost()) {
            return;
        }

        $isSecure = $request->isSecure()
            || $request->header('X-Forwarded-Proto') === 'https'
            || $request->header('X-Forwarded-Ssl') === 'on';

        if ($isSecure) {
            URL::forceScheme('https');
            config(['session.secure' => true]);
        } elseif (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        if ($this->app->environment('production')) {
            URL::forceRootUrl($request->getSchemeAndHttpHost());
        }
    }

    private function configureErrorReporting(): void
    {
        $showErrors = filter_var(env('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN)
            || filter_var(env('SHOW_ERRORS', false), FILTER_VALIDATE_BOOLEAN);

        if (! $showErrors) {
            return;
        }

        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);
    }
}
