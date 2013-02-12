@echo off
REM
REM Popcorn Micro-Framework Windows CLI script
REM

SETLOCAL ENABLEDELAYEDEXPANSION

SET TAR=
SET ZIP=
SET EXT=
SET CMD=
FOR /f "delims=" %%i IN ('where tar') DO SET TAR=%%i
FOR /f "delims=" %%i IN ('where zip') DO SET ZIP=%%i

IF NOT "!TAR!" == "" (
    SET EXT=.tar.gz
    SET CMD=tar -C ../vendor/Popcorn/src/Pop -xpzf
) ELSE IF NOT "!ZIP!" == "" (
    SET EXT=.zip
    SET CMD=unzip -q -d ../vendor/Popcorn/src/Pop
) ELSE (
    echo You need at least the TAR or ZIP program to install the components.
    exit /b
)

SET SCRIPT_DIR=%~dp0
php %SCRIPT_DIR%pop.php !EXT! %*

IF "%1" == "install" (
    IF NOT "%2" == "" (
        IF "%2" == "all" (
            SET TESTFILE=..\vendor\Popcorn\src\Pop\Archive!EXT!
        ) ELSE (
            SET TEMPFILE=%2%
            SET TESTFILE=..\vendor\Popcorn\src\Pop\!TEMPFILE!!EXT!
        )

        IF EXIST "!TESTFILE!" (
            FOR /f "delims=" %%i IN ('dir /B ..\vendor\Popcorn\src\Pop\*!EXT!') DO (
                echo Unpacking %%i...
                !CMD! ../vendor/Popcorn/src/Pop/%%i
                del ..\vendor\Popcorn\src\Pop\%%i
            )
            echo Complete!
        ) ELSE (
            echo Downloaded files could not be found.
        )
    )
)

ENDLOCAL