Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Configuration et Lancement du Projet" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Étape 1: Installation des dépendances
Write-Host "[1/5] Installation des dependances Composer..." -ForegroundColor Yellow
Write-Host "Cette etape peut prendre 5-10 minutes, veuillez patienter..." -ForegroundColor Gray
php composer.phar install --no-interaction --quiet

if (-not (Test-Path "vendor")) {
    Write-Host "Erreur: L'installation des dependances a echoue !" -ForegroundColor Red
    Write-Host "Essayez d'executer manuellement: php composer.phar install" -ForegroundColor Yellow
    exit 1
}

Write-Host "✓ Dependances installees avec succes" -ForegroundColor Green
Write-Host ""

# Étape 2: Créer le fichier .env
Write-Host "[2/5] Configuration du fichier .env..." -ForegroundColor Yellow
if (-not (Test-Path ".env")) {
    if (Test-Path ".env.example") {
        Copy-Item ".env.example" ".env"
        Write-Host "✓ Fichier .env cree" -ForegroundColor Green
    } else {
        Write-Host "Attention: Fichier .env.example introuvable" -ForegroundColor Yellow
    }
} else {
    Write-Host "✓ Fichier .env existe deja" -ForegroundColor Green
}
Write-Host ""

# Étape 3: Générer la clé d'application
Write-Host "[3/5] Generation de la cle d'application..." -ForegroundColor Yellow
php artisan key:generate --force 2>$null
if ($LASTEXITCODE -eq 0) {
    Write-Host "✓ Cle d'application generee" -ForegroundColor Green
} else {
    Write-Host "Attention: Impossible de generer la cle (peut-etre deja generee)" -ForegroundColor Yellow
}
Write-Host ""

# Étape 4: Vérifier la base de données
Write-Host "[4/5] Verification de la configuration..." -ForegroundColor Yellow
Write-Host "IMPORTANT: Assurez-vous d'avoir:" -ForegroundColor Cyan
Write-Host "  1. Cree la base de donnees 'gdevis' dans phpMyAdmin" -ForegroundColor White
Write-Host "  2. Configure les parametres DB dans le fichier .env" -ForegroundColor White
Write-Host ""

# Étape 5: Exécuter les migrations
Write-Host "[5/5] Execution des migrations..." -ForegroundColor Yellow
$runMigrations = Read-Host "Voulez-vous executer les migrations maintenant ? (O/N)"
if ($runMigrations -eq "O" -or $runMigrations -eq "o") {
    php artisan migrate --force
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✓ Migrations executees avec succes" -ForegroundColor Green
    } else {
        Write-Host "Erreur lors des migrations. Verifiez votre configuration de base de donnees." -ForegroundColor Red
    }
} else {
    Write-Host "Migrations ignorees. Executez 'php artisan migrate' plus tard." -ForegroundColor Yellow
}
Write-Host ""

# Lancer le serveur
Write-Host "========================================" -ForegroundColor Green
Write-Host "  Demarrage du serveur de developpement" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Le serveur sera accessible sur: http://localhost:8000" -ForegroundColor Cyan
Write-Host "Appuyez sur Ctrl+C pour arreter le serveur" -ForegroundColor Yellow
Write-Host ""

php artisan serve






















