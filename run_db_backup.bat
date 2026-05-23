@echo off
setlocal enabledelayedexpansion

:: Get current date and time in a safe format
for /f "tokens=2 delims==" %%I in ('wmic os get localdatetime /value') do set datetime=%%I
set TIMESTAMP=!datetime:~0,4!-!datetime:~4,2!-!datetime:~6,2!_!datetime:~8,2!-!datetime:~10,2!

:: Define paths
set BACKUP_DIR=D:\xampp\htdocs\tk\DB BK
set BACKUP_FILE=%BACKUP_DIR%\backup_tk_%TIMESTAMP%.sql
set MYSQLDUMP=D:\xampp\mysql\bin\mysqldump.exe

:: Ensure backup directory exists
if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

:: Run backup
echo Creating backup at %TIMESTAMP%...
"%MYSQLDUMP%" -u root tk > "%BACKUP_FILE%"

if %ERRORLEVEL% equ 0 (
    echo Backup successful: %BACKUP_FILE%
) else (
    echo Backup failed!
)
