@echo off
echo ========================================
echo ITLG Lab Management System - Stopping Services
echo ========================================

REM Kill Laravel Server
taskkill /FI "WINDOWTITLE eq Laravel Server*" /F 2>nul
if %errorlevel% == 0 (
    echo  Laravel Server stopped
) else (
    echo  Laravel Server not running
)

REM Kill Vite Dev Server
taskkill /FI "WINDOWTITLE eq Vite Dev*" /F 2>nul
if %errorlevel% == 0 (
    echo  Vite Dev Server stopped
) else (
    echo  Vite Dev not running
)

REM Kill Queue Worker
taskkill /FI "WINDOWTITLE eq Queue Worker*" /F 2>nul
if %errorlevel% == 0 (
    echo  Queue Worker stopped
) else (
    echo  Queue Worker not running
)

REM Kill Scheduler
taskkill /FI "WINDOWTITLE eq Scheduler*" /F 2>nul
if %errorlevel% == 0 (
    echo  Scheduler stopped
) else (
    echo  Scheduler not running
)

echo.
echo ========================================
echo  All services stopped
echo ========================================
echo.
pause