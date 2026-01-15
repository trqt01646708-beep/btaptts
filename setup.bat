@echo off
REM Laravel Queue + Mail - Setup & Run Script (Windows)

cls
echo.
echo ╔════════════════════════════════════════════════════════════╗
echo ║   Laravel Queue + Mail - Bai Tap 8 Setup ^& Run (Windows) ║
echo ╚════════════════════════════════════════════════════════════╝
echo.

REM Step 1: Check PHP
echo [1/6] Checking PHP installation...
php -v >nul 2>&1
if errorlevel 1 (
    echo ERROR: PHP not found! Install PHP first.
    pause
    exit /b 1
)
php -v | findstr /R "PHP"
echo OK - PHP found
echo.

REM Step 2: Check Composer
echo [2/6] Checking Composer...
composer --version >nul 2>&1
if errorlevel 1 (
    echo ERROR: Composer not found!
    pause
    exit /b 1
)
composer --version
echo OK - Composer found
echo.

REM Step 3: Install dependencies
echo [3/6] Installing dependencies...
call composer install --no-interaction
if errorlevel 1 (
    echo ERROR: Composer install failed!
    pause
    exit /b 1
)
echo OK - Dependencies installed
echo.

REM Step 4: Setup .env
echo [4/6] Setting up .env...
if not exist .env (
    copy .env.example .env >nul
    echo OK - .env created from .env.example
) else (
    echo OK - .env already exists
)

findstr /M "APP_KEY=base64" .env >nul
if errorlevel 1 (
    call php artisan key:generate
    echo OK - APP_KEY generated
) else (
    echo OK - APP_KEY already set
)
echo.

REM Step 5: Run migrations
echo [5/6] Running migrations...
call php artisan migrate --force
if errorlevel 1 (
    echo ERROR: Migration failed!
    pause
    exit /b 1
)
echo OK - Migrations completed
echo.

REM Step 6: Clear cache
echo [6/6] Clearing cache...
call php artisan cache:clear >nul 2>&1
call php artisan config:clear >nul 2>&1
call php artisan view:clear >nul 2>&1
echo OK - Cache cleared
echo.

echo ╔════════════════════════════════════════════════════════════╗
echo ║                    SETUP COMPLETE                         ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo NEXT STEPS:
echo.
echo 1. Terminal 1 - Start Laravel Server:
echo    php artisan serve
echo.
echo 2. Terminal 2 - Start Queue Worker:
echo    php artisan queue:work
echo.
echo 3. Open Browser:
echo    - Register: http://localhost:8000/register
echo    - Dashboard: http://localhost:8000/dashboard
echo    - Job Logs: http://localhost:8000/job-logs
echo.
echo TESTING (in new terminal):
echo    php artisan tinker
echo    ^> App\Jobs\SendWelcomeEmailJob::dispatch('test@example.com', 'Test');
echo.
echo DOCUMENTATION:
echo    - QUICKSTART.md - 5 minute guide
echo    - IMPLEMENTATION_GUIDE.md - Detailed setup
echo    - QUEUE_GUIDE.md - Complete reference
echo    - COMPLETION_SUMMARY.md - What was done
echo.
pause
