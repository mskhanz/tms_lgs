# Email setup for training.lcbkp.gov.pk (live server)

Gmail SMTP works on localhost but is **usually blocked** on government/cPanel hosting.
The app was also falling back to `log` mailer — emails looked sent but were never delivered.

## Quick fix on server

### Step 1 — Upload latest code and clear config cache

```bash
cd /path/to/tms_lgs
php artisan config:clear
php artisan cache:clear
```

### Step 2 — Create cPanel email account

In cPanel → **Email Accounts**, create e.g.:

- `training@lcbkp.gov.pk` (or `noreply@training.lcbkp.gov.pk`)

### Step 3 — Edit server `.env`

```env
APP_URL=https://training.lcbkp.gov.pk

MAIL_MAILER=failover
MAIL_FAILOVER_MAILERS=cpanel_smtp,sendmail

MAIL_CPANEL_HOST=localhost
MAIL_CPANEL_PORT=587
MAIL_CPANEL_ENCRYPTION=tls
MAIL_CPANEL_USERNAME=training@lcbkp.gov.pk
MAIL_CPANEL_PASSWORD=your-cpanel-email-password

MAIL_FROM_ADDRESS=training@lcbkp.gov.pk
MAIL_FROM_NAME="LGS Training Management System"

MAIL_SENDMAIL_PATH="/usr/sbin/sendmail -t -i"
```

Optional: copy `config/mail_local.php.example` → `config/mail_local.php`

### Step 4 — Test

```bash
php artisan mail:diagnose
php artisan mail:test your@email.com
```

Or open in browser: `https://training.lcbkp.gov.pk/check-server.php`

---

## If cPanel localhost SMTP fails

Try domain mail server instead:

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.lcbkp.gov.pk
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USERNAME=training@lcbkp.gov.pk
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS=training@lcbkp.gov.pk
MAIL_VERIFY_PEER=false
```

---

## If you must use Gmail

Some servers block port 587 entirely. Gmail will not work unless your host allows outbound SMTP.
Use cPanel email instead — it is the standard fix for `.gov.pk` hosting.
