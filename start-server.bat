@echo off
setlocal
set PROJECT_DIR=%~dp0
cd /d "%PROJECT_DIR%"
set APP_PORT=8743

netstat -ano | findstr ":%APP_PORT%" | findstr "LISTENING" >nul 2>&1
if %errorlevel%==0 (
    exit /b 0
)

start "TLC Server" /min cmd /c "php artisan serve --port=%APP_PORT%"
exit /b 0
