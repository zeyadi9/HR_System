@echo off
:: Setup Windows Task Scheduler for Hourly Database Backup
set TASK_NAME=DatabaseBackup-TK-Hourly
set SCRIPT_PATH=D:\xampp\htdocs\tk\run_db_backup.bat

echo ---------------------------------------------------
echo Creating Task: %TASK_NAME%
echo This will run %SCRIPT_PATH% every hour.
echo ---------------------------------------------------

:: Create the task
schtasks /create /sc hourly /mo 1 /tn "%TASK_NAME%" /tr "%SCRIPT_PATH%" /f /ru System

if %ERRORLEVEL% equ 0 (
    echo.
    echo [SUCCESS] The backup task has been scheduled successfully.
    echo It will run every hour starting from now.
    echo You can check it in 'Task Scheduler' (search for it in the Start menu).
) else (
    echo.
    echo [ERROR] Failed to create the task. Please make sure you are running this script as Administrator.
)

echo ---------------------------------------------------
pause
