@echo off
echo.

:: to know whether the "php" dir exists or not
if exist ..\..\php (
	goto switch1
) else (
	echo BAT The PHP dir "..\..\php" doesn't exist.
	echo BAT So continue to install.
	goto next
)

:switch1
echo BAT "..\..\php" exists!
choice /c yn /t 10 /d n /m "BAT To overwrite the cur PHP path"
REM echo errorlevel=%errorlevel%
if errorlevel 2 (
	echo BAT Press any key to stop installing...
	exit
)
if errorlevel 1 (
	::remove the cur PHP path
	echo y | rd /s ..\..\php
)


:next
::get the unzip exe's path
REM 1.haozipc
REM get HaoZipC.exe's path from the Registry
for /f "skip=2 tokens=2* delims= " %%i in ('reg query "HKEY_LOCAL_MACHINE\SOFTWARE\Microsoft\Windows\CurrentVersion\App Paths\HaoZipC.exe" /ve') do set exe=%%j

REM 2.winrar
REM temporarily omitted.
REM code it when needs.

REM 3.tar
REM ditto (the same as above)

::unzip to install PHP
REM echo "%exe%"
"%exe%" x "php.zip" -r -o../../


:set_env
::set Windows's Environment Variables
(for /r ..\..\. %%i in (.) do @echo %%i) | find "\php\." > php_path.txt
for /f "tokens=1 delims=" %%i in (php_path.txt) do set php_path=%%~fi
del php_path.txt
REM for /r ..\..\php %%i in (.) do set php_path=%%~fi
echo php_path=%php_path%

REM setx PHP "%php_path%\"
setx PATH "%PATH%;%php_path%\"
REM echo errorlevel=%errorlevel%

if errorlevel 1 (
	echo BAT set_env_var failed!
	echo [ pause ] & pause > nul
)
echo BAT set_env_var suc.

echo.
echo If see the sentence below:
echo    "WARNING: The data being saved is truncated to 1024 characters."
echo Please add the PHP path to the Environment Variables manually!

echo [ pause ] & pause > nul