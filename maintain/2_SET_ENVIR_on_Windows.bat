@echo off
echo.

if exist ..\..\tmpo (
	echo 1 BAT ..\..\tmpo exists!
) else (
	md ..\..\tmpo
	echo 1 BAT md ..\..\tmpo
)
echo 2 BAT setx tmpo = [..\..\tmpo]
for /r ..\..\tmpo %%i in (.) do setx tmpo %%~fi
echo.
echo.


if exist ..\..\logs (
	echo 3 BAT ..\..\logs exists!
) else (
	md ..\..\logs
	echo 3 BAT md ..\..\logs
)
echo 4 BAT setx logs = [..\..\logs]
REM for /r ..\..\logs %%i in (.) do setx logs %%~fi
(for /r ..\..\. %%i in (.) do @echo %%i) | find "\logs\." > logs_path.txt
for /f "tokens=1 delims=" %%i in (logs_path.txt) do set logs_path=%%~dpi
del logs_path.txt
echo %logs_path:~0,-1%
setx logs "%logs_path:~0,-1%"
echo.
echo.


if exist ..\..\logs\history (
	echo 5 BAT ..\..\logs\history exists!
) else (
	md ..\..\logs\history
	echo 5 BAT md ..\..\logs\history
)
echo. 
echo.


echo 6 BAT setx scripts = [..\..\scripts]
(for /r ..\. %%i in (.) do @echo %%i) | find "\scripts\." > scripts_path.txt
for /f "tokens=1 delims=" %%i in (scripts_path.txt) do set scripts_path=%%~dpi
del scripts_path.txt
REM echo %scripts_path:~0,-1%
setx scripts "%scripts_path:~0,-1%"
echo. 
echo.


echo [ pause ] & pause > nul