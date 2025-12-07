# Guide d'Installation Rapide

## Étapes d'installation

1. **Installer Composer** (si pas déjà installé)
   - Téléchargez depuis https://getcomposer.org/

2. **Installer les dépendances**
   ```bash
   composer install
   ```

3. **Créer le fichier .env**
   ```bash
   copy .env.example .env
   ```
   (Sur Linux/Mac : `cp .env.example .env`)

4. **Générer la clé d'application**
   ```bash
   php artisan key:generate
   ```

5. **Configurer la base de données dans .env**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=gdevis
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Créer la base de données MySQL**
   - Ouvrez phpMyAdmin ou votre client MySQL
   - Créez une base de données nommée `gdevis`

7. **Exécuter les migrations**
   ```bash
   php artisan migrate
   ```

8. **Démarrer le serveur**
   ```bash
   php artisan serve
   ```

9. **Accéder à l'application**
   - Ouvrez votre navigateur à : http://localhost:8000

## Configuration XAMPP

Si vous utilisez XAMPP :

1. Placez le projet dans `C:\xampp\htdocs\gdevis`
2. Configurez un VirtualHost (optionnel) ou utilisez `php artisan serve`
3. Assurez-vous que MySQL est démarré dans XAMPP

## Première utilisation

1. Créez quelques clients
2. Créez quelques produits
3. Créez un devis et ajoutez des lignes
4. Testez l'impression






















