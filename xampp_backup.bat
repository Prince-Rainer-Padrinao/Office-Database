@echo off
:: --- CONFIGURATION ---
set DB_NAME=peso_database
set BACKUP_PATH=C:\xampp\backups
set MYSQL_BIN=C:\xampp\mysql\bin\mysqldump.exe
set LOG_FILE=C:\xampp\backups\backup_log.txt
:: ---------------------

:: Create the backup folder if it doesn't exist
if not exist "%BACKUP_PATH%" mkdir "%BACKUP_PATH%"

:: SAFE DATE/TIME LOGIC
for /f "tokens=2-4 delims=/ " %%a in ('echo %DATE%') do set mydate=%%c-%%a-%%b
for /f "tokens=1-2 delims=: " %%a in ('echo %TIME%') do set mytime=%%a%%b
set TIMESTAMP=%mydate%_%mytime%

:: Run the export
"%MYSQL_BIN%" -u root %DB_NAME% > "%BACKUP_PATH%\db_backup_%TIMESTAMP%.sql"

:: Check if the file was actually created and write to the silent log file
if exist "%BACKUP_PATH%\db_backup_%TIMESTAMP%.sql" (
    echo [%DATE% %TIME%] SUCCESS: Backup created at db_backup_%TIMESTAMP%.sql >> "%LOG_FILE%"
) else (
    echo [%DATE% %TIME%] ERROR: Backup failed. Check if MySQL is running. >> "%LOG_FILE%"
)

:: Clean up files older than 7 days
forfiles /p "%BACKUP_PATH%" /s /m *.sql /d -7 /c "cmd /c del @path" 2>nul

exit
