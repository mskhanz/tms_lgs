<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailCommand extends Command
{
    protected $signature = 'mail:test
                            {email : Recipient email address}
                            {--mailer= : Force a specific mailer (smtp, sendmail, failover)}';

    protected $description = 'Send a test email and diagnose mail configuration';

    public function handle(): int
    {
        $email = $this->argument('email');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email address.');

            return self::FAILURE;
        }

        $this->printMailConfig();

        $mailer = $this->option('mailer') ?: config('mail.default');
        $mailersToTry = $this->resolveMailersToTry($mailer);

        foreach ($mailersToTry as $tryMailer) {
            $this->newLine();
            $this->line("Trying mailer: {$tryMailer}");

            try {
                Mail::mailer($tryMailer)->html(
                    $this->buildTestBody($tryMailer),
                    function ($message) use ($email, $tryMailer) {
                        $message->to($email)
                            ->subject(config('app.name') . ' Mail Test [' . $tryMailer . ']');
                    }
                );

                $this->info("SUCCESS: Email sent via \"{$tryMailer}\" to {$email}");
                $this->line('Set MAIL_MAILER=' . $tryMailer . ' in server .env if not already.');

                return self::SUCCESS;
            } catch (\Throwable $e) {
                $this->error("FAILED ({$tryMailer}): " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->warn('All mailers failed. Run: php artisan mail:diagnose');
        $this->line('On cPanel, Gmail SMTP is often blocked. Use cPanel email or sendmail instead.');

        return self::FAILURE;
    }

    private function resolveMailersToTry(string $mailer): array
    {
        if ($mailer !== 'failover') {
            return [$mailer];
        }

        $configured = config('mail.mailers.failover.mailers', ['smtp', 'sendmail']);

        return array_values(array_unique(array_merge($configured, ['smtp', 'sendmail'])));
    }

    private function printMailConfig(): void
    {
        $this->line('Current mail settings:');
        $this->line('  Default mailer : ' . config('mail.default'));
        $this->line('  From address   : ' . config('mail.from.address'));
        $this->line('  From name      : ' . config('mail.from.name'));
        $this->line('  SMTP host      : ' . config('mail.mailers.smtp.host'));
        $this->line('  SMTP port      : ' . config('mail.mailers.smtp.port'));
        $this->line('  SMTP encryption: ' . (config('mail.mailers.smtp.encryption') ?: 'none'));
        $this->line('  SMTP username  : ' . (config('mail.mailers.smtp.username') ?: '(empty)'));
        $this->line('  Sendmail path  : ' . config('mail.mailers.sendmail.path'));
        $this->line('  APP_URL        : ' . config('app.url'));
    }

    private function buildTestBody(string $mailer): string
    {
        return '<p>This is a test email from <strong>' . e(config('app.name')) . '</strong>.</p>'
            . '<p>Mailer used: <strong>' . e($mailer) . '</strong></p>'
            . '<p>Sent at: ' . now()->format('Y-m-d H:i:s T') . '</p>'
            . '<p>If you received this, server email is working.</p>';
    }
}
