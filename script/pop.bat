@echo off

SET TAR=1
SET ZIP=1
FOR /f "delims=" %%i in ('where tar') do set TAR=%%i
FOR /f "delims=" %%i in ('where zip') do set ZIP=%%i

REM echo %TAR%
REM echo %ZIP%

REM SET SCRIPT_DIR=%~dp0
REM php %SCRIPT_DIR%pop.php %*

