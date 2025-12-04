Write-Host "Installation des dependances Laravel..." -ForegroundColor Cyan
Write-Host ""
Write-Host "Cette operation peut prendre plusieurs minutes..." -ForegroundColor Yellow
Write-Host ""

php composer.phar install

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Green
    Write-Host "Installation terminee avec succes !" -ForegroundColor Green
    Write-Host "========================================" -ForegroundColor Green
    Write-Host ""
    Write-Host "Prochaines etapes :" -ForegroundColor Cyan
    Write-Host "1. Copiez .env.example vers .env"
    Write-Host "2. Executez: php artisan key:generate"
    Write-Host "3. Configurez votre base de donnees dans .env"
    Write-Host "4. Executez: php artisan migrate"
    Write-Host "5. Executez: php artisan serve"
    Write-Host ""
} else {
    Write-Host ""
    Write-Host "Erreur lors de l'installation !" -ForegroundColor Red
    Write-Host ""
}

Read-Host "Appuyez sur Entree pour continuer"









