@echo off
setlocal
cd /d "%~dp0.."

echo ============================================
echo  TMS LGS - Server Deployment Package Builder
echo ============================================
echo.

where composer >nul 2>nul
if errorlevel 1 (
    echo ERROR: Composer not found. Install Composer first.
    exit /b 1
)

echo [1/4] Installing production dependencies...
call composer install --no-dev --optimize-autoloader
if errorlevel 1 (
    echo ERROR: composer install failed.
    exit /b 1
)

echo [2/4] Clearing caches...
php artisan optimize:clear

echo [3/4] Creating deployment package...
set OUT=deploy\tms_lgs_deploy.zip
if not exist deploy mkdir deploy
if exist "%OUT%" del "%OUT%"

powershell -NoProfile -Command ^
  "$root = (Get-Location).Path; ^
   $items = @('app','bootstrap','config','database','public','resources','routes','storage','vendor','artisan','composer.json','composer.lock','.htaccess'); ^
   $existing = $items | Where-Object { Test-Path (Join-Path $root $_) }; ^
   Compress-Archive -Path ($existing | ForEach-Object { Join-Path $root $_ }) -DestinationPath (Join-Path $root 'deploy\tms_lgs_deploy.zip') -Force"

if errorlevel 1 (
    echo ERROR: Failed to create zip package.
    exit /b 1
)

echo [4/4] Done.
echo.
echo Upload and extract: deploy\tms_lgs_deploy.zip
echo On server:
echo   - Point domain/subdomain document root to: .../tms_lgs/public
echo   - Copy .env.example to .env and configure database/mail
echo   - Run: php artisan key:generate
echo   - Run: php artisan migrate --force
echo   - Run: php artisan view:clear
echo   - Run: php artisan config:clear
echo   - Run: php artisan route:clear
echo   - Open: https://yourdomain/quiz-check.php to verify quiz module
echo   - chmod 775 storage bootstrap/cache
echo.
pause
