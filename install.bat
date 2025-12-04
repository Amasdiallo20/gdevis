@echo off
echo Installation des dependances Laravel...
echo.
echo Cette operation peut prendre plusieurs minutes...
echo.

php composer.phar install

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo Installation terminee avec succes !
    echo ========================================
    echo.
    echo Prochaines etapes :
    echo 1. Copiez .env.example vers .env
    echo 2. Executez: php artisan key:generate
    echo 3. Configurez votre base de donnees dans .env
    echo 4. Executez: php artisan migrate
    echo 5. Executez: php artisan serve
    echo.
) else (
    echo.
    echo Erreur lors de l'installation !
    echo.
)

pause









