@echo off

SET TAR=
SET ZIP=
SET EXT=
SET CMD=
FOR /f "delims=" %%i IN ('where tar') DO SET TAR=%%i
FOR /f "delims=" %%i IN ('where zip') DO SET ZIP=%%i

IF NOT "%TAR" == "" (
    SET EXT=.tar.gz
    SET CMD=tar -C ../vendor/Popcorn/src/Pop -xpf
) ELSE IF NOT "%ZIP" == "" (
    SET EXT=.zip
    SET CMD=unzip -d ../vendor/Popcorn/src/Pop
) ELSE (
    echo You need at least the TAR or ZIP program to install the components.
    exit /b
)

SET SCRIPT_DIR=%~dp0
php %SCRIPT_DIR%pop.php %EXT% %*

if "%1" == "install" (
    FOR /f "delims=" %%i IN ('dir /B ..\vendor\Popcorn\src\Pop\*%EXT%') DO (
        echo Unpacking %%i...
        %CMD% ../vendor/Popcorn/src/Pop/%%i
        del ..\vendor\Popcorn\src\Pop\%%i
    )

    echo Complete!
)

