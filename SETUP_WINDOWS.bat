@echo off
REM =========================================
REM   Laravel Queue + Mail - Windows Setup
REM =========================================

echo.
echo ===== Laravel Queue System - Setup Guide =====
echo.
echo Step 1: Setup Database
echo -------
php artisan migrate
echo.

echo Step 2: Choose Queue Mode
echo -------
echo Option A: Run continuous worker
echo   Command: php artisan queue:work
echo.
echo Option B: Run single job only
echo   Command: php artisan queue:work --once
echo.
echo.

echo Step 3: Open another terminal and run server
echo -------
echo   Command: php artisan serve
echo.
echo.

echo Step 4: Test
echo -------
echo   1. Go to http://localhost:8000/register
echo   2. Register new user
echo   3. Check http://localhost:8000/job-logs
echo   4. Check http://localhost:8000/dashboard
echo.
echo.

echo Step 5: View Logs
echo -------
echo   Command: tail -f storage/logs/laravel.log
echo.
echo.

echo ===== Quick Commands =====
echo php artisan queue:work              - Run worker (continuous)
echo php artisan queue:work --once       - Process 1 job only
echo php artisan queue:failed            - View failed jobs
echo php artisan queue:retry all         - Retry all failed
echo php artisan app:test-queue --count=5  - Test with 5 users
echo php artisan queue:monitor           - Monitor real-time
echo.

pause
