@echo off
:: Setup Schtask for Laravel Scheduler
set TASK_NAME=LaravelScheduler-TK
set PHP_BIN=D:\xampp\php\php.exe
set ARTISAN_PATH=D:\xampp\htdocs\tk\artisan

echo Creating Task: %TASK_NAME%
schtasks /create /sc minute /mo 1 /tn "%TASK_NAME%" /tr "%PHP_BIN% %ARTISAN_PATH% schedule:run" /f /ru System
echo ---------------------------------------
echo Done. You can check it in Task Scheduler GUI.
pause
