# Instructions pour ajouter un Favicon

## Méthode 1 : Utiliser le logo des paramètres (Recommandé)

Le favicon utilisera automatiquement le logo que vous avez configuré dans les paramètres de l'application.

1. Allez dans **Paramètres** de l'application
2. Uploadez votre logo
3. Le favicon sera automatiquement mis à jour

## Méthode 2 : Ajouter un fichier favicon dans le répertoire public

Si vous préférez utiliser un fichier favicon spécifique :

1. Placez votre image dans le répertoire `public/` avec l'un de ces noms :
   - `favicon.ico` (format ICO recommandé)
   - `favicon.png` (format PNG)

2. Formats supportés :
   - **ICO** : Format classique pour favicon (recommandé)
   - **PNG** : Format moderne, supporté par tous les navigateurs

3. Tailles recommandées :
   - **favicon.ico** : 16x16, 32x32, 48x48 pixels (multi-taille dans un seul fichier)
   - **favicon.png** : 32x32 ou 64x64 pixels

## Conversion d'image en favicon

Si vous avez une image (JPG, PNG, etc.) et que vous voulez la convertir en favicon :

### En ligne (gratuit) :
- https://favicon.io/favicon-converter/
- https://realfavicongenerator.net/
- https://www.favicon-generator.org/

### Étapes :
1. Uploadez votre image
2. Téléchargez le fichier `favicon.ico` généré
3. Placez-le dans le répertoire `public/` de votre application

## Vérification

Après avoir ajouté le favicon :
1. Videz le cache de votre navigateur (Ctrl+F5 ou Cmd+Shift+R)
2. Rechargez la page
3. Le favicon devrait apparaître dans l'onglet du navigateur

## Note

L'application cherche le favicon dans cet ordre :
1. Logo configuré dans les paramètres (priorité)
2. `public/favicon.ico`
3. `public/favicon.png`
4. Favicon par défaut (si aucun n'est trouvé)

