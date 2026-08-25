@echo off
setlocal
set PROJECT_DIR=%~dp0
cd /d "%PROJECT_DIR%"
set APP_PORT=8743
set CHROME_EXE=C:\Program Files\Google\Chrome\Application\chrome.exe
set APP_PROFILE=%LOCALAPPDATA%\TLC-app-profile

rem Start the PHP server hidden in the background (only if not already running)
netstat -ano | findstr ":%APP_PORT%" | findstr "LISTENING" >nul 2>&1
if not %errorlevel%==0 (
    start "" /min cmd /c "php artisan serve --port=%APP_PORT%"
    timeout /t 2 /nobreak >nul
)

rem Open the app in its own dedicated Chrome window and WAIT until it's closed
start "" /wait "%CHROME_EXE%" --app=http://127.0.0.1:%APP_PORT% --user-data-dir="%APP_PROFILE%" --no-first-run --no-default-browser-check

rem App window closed -> stop the PHP server
for /f "tokens=5" %%p in ('netstat -ano ^| findstr ":%APP_PORT%" ^| findstr "LISTENING"') do (
    taskkill /PID %%p /F >nul 2>&1
)

exit /b 0
