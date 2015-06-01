@echo off
::=====================================
::This File should be encoded in GB2312
::=====================================

setlocal

::::::::	set vars
echo.
echo BAT [temp vars list]:

::tmp vars - date0, time0
REM set date0=
for /f "tokens=1,2,3 delims=-" %%i in ("%date%") do set date0=%%i/%%j/%%k
echo BAT date0=%date0%

REM set time0=
for /f "tokens=1,2,3 delims=:." %%i in ("%time%") do set time0=%%i%%j%%k
echo BAT time0=%time0%

::now datetime
REM set now=
for /f "tokens=1,2,3 delims=-" %%i in ("%date%") do set now=20%%i%%j%%k%time0%
echo BAT now=%now%

::note_title
set title=%date0% stu
echo BAT note_title=%title%

::notebook_name
REM set nb_name=
for /f "tokens=1,2 delims=-" %%i in ("%date%") do set nb_name=Dairy - 20%%i/%%j
echo BAT notebook_name=%nb_name%

::note_tags
set tags=REC_day
echo BAT note_tags=%tags%

::evernote search query
set note_qry=intitle:15/02/d stu tag:TPL
echo BAT note_qry=%note_qry%

::generate tmp_file_path
REM set tmpd=
for /f "tokens=1,2,3 delims=-" %%i in ("%date%") do set tmpd=%%i%%j%%k

REM set tmpt=
for /f "tokens=1,2,3,4 delims=:." %%i in ("%time%") do set tmpt=%%i%%j_%%k%%l

set tmp_f=tmp_4edit_d%tmpd%_t%tmpt%_r%RANDOM%.enex
echo BAT tmp_file_path=%tmp_f%



::::::::	start to process
echo.
echo BAT [process]:

::export note's tpl
call enscript exportNotes /q "%note_qry%" /f "%tmpo%\%tmp_f%"
(call ./lib/err_lv_hint.bat %errorlevel% ^
	"BAT enscript exportNotes - %tmp_f%" ^
	) || exit /b


::edit note's cont

echo BAT php edit %tmp_f% - runs.
call php "%script%\edit_enex.php" "%tmpo%\%tmp_f%" /i "%title%" /t "%tags%" 
:: /c "%now%"
:: /u %now%
(call ./lib/err_lv_hint.bat %errorlevel% ^
	"BAT php edit - %tmp_f%" ^
	) || exit /b


::convert note's encoding

REM echo BAT php iconv %tmp_f% - runs.
REM call php %script%\iconv.php
REM (call ./lib/err_lv_hint.bat %errorlevel% ^
	REM "BAT php iconv - %tmp_f%" ^
	REM ) || exit /b


::create note

REM call enscript createNote /s "%tmpo%\%tmp_f%" /n "%nb_name%t" /t %tags% /i "%title%"
REM (call ./lib/err_lv_hint.bat %errorlevel% ^
	REM "BAT enscript createNote - %tmp_f%" ^
	REM ) || exit /b


::import note

call enscript importNotes /s "%tmpo%\%tmp_f%" /n "%nb_name%"
(call ./lib/err_lv_hint.bat %errorlevel% ^
	"BAT enscript importNote - %tmp_f%" ^
	) || exit /b


::delete tmp_file

REM set p=%tmpo%
REM if not defined p (
	REM echo "BAT chdir to the dir (where tmp_file saved) failed!"
	REM exit /b 1
REM )
REM else (
	REM ::若以下几行指令，想现在一样，包括在括号里，
	REM ::那么以下输出的 %errorlevel% 将都只会是 0 ！
	REM ::原因未知！
	REM call ./test_lib/errorlevel_1.bat
	REM echo errlv=%errorlevel%
	REM call ./test_lib/errorlevel_0.bat
	REM echo errlv=%errorlevel%
REM )

call %script%/lib/del_with_err_lv.bat "%tmpo%\%tmp_f%"
(call ./lib/err_lv_hint.bat %errorlevel% ^
	"BAT delete tmp_file - %tmp_f%" ^
	) || exit /b
)

endlocal

REM pause > nul