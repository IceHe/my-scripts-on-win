@echo off

setlocal

:: del_with_err_lv	-	delete a file with errorlevel
:: 
:: Function:
:: 1. delete a file 
:: 2. judge whether deleted the file successfully or not
:: 3. exit with errorlevel according to the result

set file_path=%1
if not defined file_path (
	echo BAT del_with_err_lv.bat get param1 - failed!
	exit /b 1
)

for %%i in ("%file_path%") do set formated_path=%%~fi
REM echo file_path=[%file_path%]
REM echo formated_path=[%formated_path%]
REM pause > nul

set tmp_f=tmp_%RANDOM%.txt
erase "%formated_path%" 2> %tmp_f%
for /f "delims=" %%n in ("%tmp_f%") do set result=%%n

REM echo result[%result%]
REM echo result:~0,14[%result:~0,14%]

erase %tmp_f%

endlocal

if "%result:~0,14%" == "Could Not Find" (
	exit /b 1
) else (
	REM echo BAT erase %formated_path% - ok.
	exit /b 0
)