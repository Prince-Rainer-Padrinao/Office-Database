@echo off
echo Finding your current IPv4 Address...

:: Grab the IPv4 address securely
for /f "tokens=2 delims=:" %%a in ('ipconfig ^| findstr "IPv4 Address"') do set IP=%%a

:: Remove any hidden spaces
set IP=%IP: =%

echo Your IP is %IP%
echo Opening Gmail in Chrome...

:: Recipients (You can add more by separating them with a comma)
set TO=________@gmail.com,________@gmail.com

:: Set Subject and Body (Using %%20 to act as spaces for the web URL)
set SUBJECT=New%%20Office%%20Database%%20Link
set BODY=Hey%%20team,%%20the%%20server%%20IP%%20changed.%%20The%%20new%%20link%%20to%%20access%%20the%%20OFW%%20Database%%20is:%%20http://%IP%/PESOoffice_db/

:: Fire up Chrome directly to the Gmail compose window
start chrome "https://mail.google.com/mail/?view=cm&fs=1&to=%TO%&su=%SUBJECT%&body=%BODY%"

exit