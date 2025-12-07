# Script pour installer les dépendances et lancer le serveur
Write-Host "Installation des dependances Composer..." -ForegroundColor Yellow
php composer.phar install --no-interaction

if (Test-Path vendor\autoload.php) {
    Write-Host "Dependances installees avec succes!" -ForegroundColor Green
    Write-Host "Lancement du serveur Laravel..." -ForegroundColor Yellow
    php artisan serve
} else {
    Write-Host "Erreur: Les dependances n'ont pas ete installees correctement." -ForegroundColor Red
    Write-Host "Veuillez executer manuellement: php composer.phar install" -ForegroundColor Yellow
}



















