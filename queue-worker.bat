@echo off
REM Laravel Queue Worker Script for Windows

echo ========================================
echo  Laravel Queue Worker
echo ========================================
echo.

echo Choose an option:
echo 1. Run queue worker (continuous)
echo 2. Run queue worker (single job only)
echo 3. Retry failed jobs
echo 4. View failed jobs
echo 5. Clear all failed jobs
echo 6. Test queue (create 5 test users)
echo.

set /p choice="Enter your choice (1-6): "

if "%choice%"=="1" (
    echo Starting queue worker (continuous mode)...
    echo Press Ctrl+C to stop
    php artisan queue:work
) else if "%choice%"=="2" (
    echo Running one job only...
    php artisan queue:work --once
) else if "%choice%"=="3" (
    echo Retrying all failed jobs...
    php artisan queue:retry all
) else if "%choice%"=="4" (
    echo Failed jobs:
    php artisan queue:failed
) else if "%choice%"=="5" (
    echo Clearing all failed jobs...
    php artisan queue:flush
) else if "%choice%"=="6" (
    echo Testing queue with 5 users...
    php artisan app:test-queue --count=5
) else (
    echo Invalid choice!
)

echo.
pause
