@echo off

setlocal

:: err_lv_hint	- 	Errorlevel Hint
::
:: Params:
:: %1	%errorlevel% of the caller (from batch)
:: %2	The hint to print. 
::		It must be enclosed in double quotation marks.
:: %3	[optional] Whether print %errorlevel% or not ?
::		Value type: integer
::		Value range: 1 , 0 ( mean True and False)

set hint=%2
if %1 GEQ 1 (
	echo %hint:~1,-1% - failed!
) else (
	echo %hint:~1,-1% - ok.
)


set /a is_print=0%3
:: echo p=%p3%
if %is_print% EQU 1 (
	echo BAT errorlevel = %1
) else if %is_print% EQU 0 (
	rem do nothing // 此处rem，不能用::去代替。因为rem算一条指令，::只是纯注释，此处若无指令会报错 “ ) was unexpected at this time. ”
) else (
	echo BAT the 3rd param which passed to err_lv_hint.bat is wrong. 
)

endlocal

exit /b %1