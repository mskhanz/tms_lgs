<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DiagnoseMailCommand extends Command
{
    protected $signature = 'mail:diagnose';

    protected $description = 'Diagnose mail configuration without sending email';

    public function handle(): int
    {
        $this->info('Mail diagnostics for ' . config('app.name'));
        $this->newLine();

        $this->line('[Config]');
        $this->line('  APP_ENV        : ' . config('app.env'));
        $this->line('  APP_URL        : ' . config('app.url'));
        $this->line('  Default mailer : ' . config('mail.default'));
        $this->line('  From address   : ' . config('mail.from.address'));
        $this->line('  Failover chain : ' . implode(' -> ', config('mail.mailers.failover.mailers', [])));

        $this->newLine();
        $this->line('[SMTP connectivity]');
        $host = (string) config('mail.mailers.smtp.host');
        $port = (int) config('mail.mailers.smtp.port');
        $this->checkPort($host, $port);

        if ($host !== 'localhost' && $host !== '127.0.0.1') {
            $this->checkPort('localhost', 587);
            $this->checkPort('localhost', 465);
            $this->checkPort('localhost', 25);
        }

        $this->newLine();
        $this->line('[Sendmail]');
        $sendmailPath = (string) config('mail.mailers.sendmail.path');
        $binary = trim(strtok($sendmailPath, ' '));
        if ($binary && file_exists($binary)) {
            $this->info("  OK: {$binary} exists");
        } else {
            $this->error("  FAIL: sendmail binary not found at {$binary}");
            foreach (['/usr/sbin/sendmail', '/usr/bin/sendmail'] as $candidate) {
                if (file_exists($candidate)) {
                    $this->warn("  Found alternative: {$candidate}");
                    $this->warn('  Set MAIL_SENDMAIL_PATH="' . $candidate . ' -t -i" in .env');
                }
            }
        }

        $this->newLine();
        $this->line('[Recommendations]');
        $from = (string) config('mail.from.address');
        if (str_contains($from, '@gmail.com')) {
            $this->warn('  Gmail FROM address on live server may be rejected by hosting.');
            $this->warn('  Use a cPanel email like training@lcbkp.gov.pk as MAIL_FROM_ADDRESS.');
        }

        if (config('mail.default') === 'failover' && in_array('log', config('mail.mailers.failover.mailers', []), true)) {
            $this->error('  FAIL: failover includes "log" — emails may be logged but never sent.');
            $this->warn('  Set MAIL_FAILOVER_MAILERS=smtp,sendmail in .env');
        }

        $this->newLine();
        $this->line('Test send: php artisan mail:test your@email.com');
        $this->line('Force sendmail: php artisan mail:test your@email.com --mailer=sendmail');

        return self::SUCCESS;
    }

    private function checkPort(string $host, int $port): void
    {
        $timeout = 5;
        $errno = 0;
        $errstr = '';
        $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);

        if ($connection) {
            fclose($connection);
            $this->info("  OK: {$host}:{$port} is reachable");
        } else {
            $this->error("  FAIL: {$host}:{$port} blocked or unreachable ({$errstr})");
        }
    }
}
