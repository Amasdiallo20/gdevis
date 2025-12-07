# Checklist de Déploiement - A2 VitraDevis

## ✅ Optimisations Réalisées

### Performance
- ✅ Compression GZIP activée (.htaccess)
- ✅ Cache des fichiers statiques configuré (1 an pour images, 1 mois pour CSS/JS)
- ✅ Preconnect et DNS-prefetch pour les CDN
- ✅ Lazy loading pour les images
- ✅ Srcset pour les images responsive
- ✅ Font Awesome chargé avec preload
- ✅ Alpine.js chargé avec defer

### Responsivité
- ✅ Viewport meta tag optimisé
- ✅ Media queries pour mobile (640px, 768px, 1024px)
- ✅ Menu mobile optimisé avec animations fluides
- ✅ Sidebar mobile avec overlay
- ✅ Tableaux responsive avec version mobile
- ✅ Touch targets optimisés (min 44px)
- ✅ Scroll horizontal optimisé pour tableaux

### SEO et Accessibilité
- ✅ Meta description et keywords
- ✅ Meta robots
- ✅ Theme color
- ✅ Alt text pour toutes les images
- ✅ Support prefers-reduced-motion

### Sécurité
- ✅ Headers de sécurité (XSS, Content-Type, Frame Options)
- ✅ Protection des fichiers sensibles
- ✅ Désactivation du listing des répertoires

## 📋 Checklist Avant Déploiement

### Configuration Laravel
- [ ] Vérifier que `APP_ENV=production` dans `.env`
- [ ] Vérifier que `APP_DEBUG=false` dans `.env`
- [ ] Configurer `APP_URL` avec l'URL de production
- [ ] Générer la clé d'application : `php artisan key:generate`
- [ ] Vérifier que `APP_KEY` est défini dans `.env`

### Base de Données
- [ ] Configurer les variables `DB_*` dans `.env`
- [ ] Exécuter les migrations : `php artisan migrate --force`
- [ ] Vérifier les seeders si nécessaire

### Optimisations Laravel
- [ ] Optimiser l'autoloader : `composer install --optimize-autoloader --no-dev`
- [ ] Cache de configuration : `php artisan config:cache`
- [ ] Cache des routes : `php artisan route:cache`
- [ ] Cache des vues : `php artisan view:cache`
- [ ] Cache des événements : `php artisan event:cache`

### Permissions
- [ ] Vérifier les permissions sur `storage/` : `chmod -R 775 storage`
- [ ] Vérifier les permissions sur `bootstrap/cache/` : `chmod -R 775 bootstrap/cache`
- [ ] Créer le lien symbolique : `php artisan storage:link`

### Serveur Web
- [ ] Vérifier que mod_rewrite est activé (Apache)
- [ ] Vérifier que mod_deflate est activé (Apache)
- [ ] Vérifier que mod_expires est activé (Apache)
- [ ] Vérifier que mod_headers est activé (Apache)
- [ ] Configurer le document root vers `/public`

### Tests
- [ ] Tester l'application sur mobile
- [ ] Tester la responsivité sur différentes tailles d'écran
- [ ] Vérifier le chargement des images
- [ ] Tester les performances avec Google PageSpeed Insights
- [ ] Vérifier la console du navigateur pour les erreurs

## 🚀 Commandes de Déploiement

```bash
# 1. Mettre à jour le code
git pull origin main

# 2. Installer les dépendances (production)
composer install --optimize-autoloader --no-dev

# 3. Optimiser Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 4. Migrations (si nécessaire)
php artisan migrate --force

# 5. Lien symbolique storage
php artisan storage:link

# 6. Permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 7. Vider les caches si problème
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 📊 Vérifications Post-Déploiement

### Performance
- [ ] Tester avec Google PageSpeed Insights (objectif: >80)
- [ ] Vérifier le temps de chargement initial
- [ ] Vérifier la taille des ressources chargées
- [ ] Tester sur connexion lente (3G)

### Responsivité
- [ ] Tester sur iPhone (Safari)
- [ ] Tester sur Android (Chrome)
- [ ] Tester sur tablette
- [ ] Vérifier les tableaux sur mobile
- [ ] Vérifier le menu mobile

### Fonctionnalités
- [ ] Tester la connexion
- [ ] Tester la création de devis
- [ ] Tester l'upload d'images
- [ ] Tester l'impression PDF
- [ ] Tester toutes les fonctionnalités principales

## 🔧 Configuration .env de Production

```env
APP_NAME="A2 VitraDevis"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://votre-domaine.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nom_base
DB_USERNAME=utilisateur
DB_PASSWORD=mot_de_passe

SESSION_DRIVER=file
SESSION_LIFETIME=120

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

## 📝 Notes Importantes

1. **Ne jamais** commiter le fichier `.env`
2. **Toujours** utiliser `APP_DEBUG=false` en production
3. **Vérifier** les permissions des fichiers et dossiers
4. **Tester** avant de mettre en production
5. **Sauvegarder** la base de données régulièrement

## 🐛 En cas de Problème

1. Vérifier les logs : `storage/logs/laravel.log`
2. Vider les caches : `php artisan cache:clear`
3. Vérifier les permissions
4. Vérifier la configuration `.env`
5. Vérifier les erreurs PHP dans les logs du serveur

