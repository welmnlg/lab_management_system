@echo off
echo ========================================
echo ITLG Lab Management System - Starting Services
echo ========================================

REM Start Laravel Server
start "Laravel Server" cmd /k "php artisan serve"
timeout /t 2

REM Start Vite Dev Server (Frontend)
start "Vite Dev" cmd /k "npm run dev"
timeout /t 2

REM Start Queue Worker
start "Queue Worker" cmd /k "php artisan queue:work --queue=notifications,default --tries=3 --timeout=300"
timeout /t 2

REM Start Scheduler Worker (with verbose output)
start "Scheduler" cmd /k "php artisan schedule:work --verbose"

echo.
echo ========================================
echo ✅ All services started successfully!
echo ========================================
echo.
echo Services running:
echo 1. Laravel Server: http://127.0.0.1:8000
echo 2. Vite Dev Server: http://localhost:5173
echo 3. Queue Worker (Notifications + Default)
echo 4. Scheduler (Running every minute)
echo.
echo Press any key to exit...
pause