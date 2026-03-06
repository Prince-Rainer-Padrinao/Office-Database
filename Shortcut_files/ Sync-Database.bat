@echo off
color 0A
title OFW Database Synchronizer

echo =======================================
echo    HUNTING FOR LATEST DOWNLOAD...
echo =======================================

:: Change directory to your Downloads folder
cd /d "%USERPROFILE%\Downloads"

:: Find the newest file that starts with "OFW Profiling Form" and is a .csv
set "NewestFile="
for /f "delims=" %%I in ('dir "OFW Profiling Form*.csv" /b /o:d /t:c 2^>nul') do set "NewestFile=%%I"

:: If it can't find one, abort
if "%NewestFile%"=="" (
    color 0C
    echo [ERROR] Could not find any OFW CSV files in your Downloads folder!
    echo Please make sure you downloaded it from Google Sheets.
    echo.
    pause
    exit
)

echo Found: "%NewestFile%"
echo Moving file to XAMPP Server...

:: Copy and rename it to the exact file PHP is looking for
copy /y "%NewestFile%" "C:\xampp\htdocs\PESOoffice_db\import_data.csv" >nul

echo.
echo =======================================
echo    UPDATING DATABASE...
echo =======================================

:: Use curl to trigger your local PHP script and show the results in the command window
curl -s http://localhost/PESOoffice_db/auto_import.php

echo Press any key to close this window...
pause >nul
