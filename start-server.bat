@echo off
setlocal
set PROJECT_DIR=%~dp0
cd /d "%PROJECT_DIR%"

netstat -ano | findstr ":8000" | findstr "LISTENING" >nul 2>&1
if %errorlevel%==0 (
    exit /b 0
)

start "TLC Server" /min cmd /c "php artisan serve"
exit /b 0
