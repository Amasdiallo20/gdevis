# Documentation - Catalogue de Modèles

## 📋 Vue d'ensemble

Le Catalogue de Modèles permet d'afficher et de gérer une collection de modèles de fenêtres, portes, garde-corps, vitres, vitrines, etc. Les utilisateurs peuvent visualiser les modèles et les ajouter directement à un devis.

## 🗄️ Structure de la base de données

### Table `modeles`
- `id` : Identifiant unique
- `nom` : Nom du modèle
- `categorie` : Catégorie (fenetre, porte, garde-corps, vitre, vitrine, baie-vitree, porte-fenetre, autre)
- `description` : Description détaillée (nullable)
- `image` : Chemin vers l'image (nullable)
- `prix_indicatif` : Prix indicatif en GNF (nullable)
- `statut` : Statut (actif/inactif) - par défaut 'actif'
- `created_at` / `updated_at` : Timestamps

## 🔧 Architecture

### Modèle : `Modele`

**Méthodes principales :**
- `getImageUrlAttribute()` : Retourne l'URL complète de l'image
- `isActive()` : Vérifie si le modèle est actif
- `scopeActive()` : Scope pour filtrer les modèles actifs
- `scopeByCategory()` : Scope pour filtrer par catégorie
- `scopeSearch()` : Scope pour la recherche
- `getCategories()` : Retourne la liste des catégories disponibles

### Contrôleur : `ModeleController`

**Méthodes :**
- `index()` : Affiche le catalogue avec filtres et recherche
- `create()` : Affiche le formulaire de création
- `store()` : Enregistre un nouveau modèle avec upload d'image
- `show()` : Affiche les détails d'un modèle
- `edit()` : Affiche le formulaire d'édition
- `update()` : Met à jour un modèle
- `destroy()` : Supprime un modèle
- `toggleStatus()` : Active/désactive un modèle
- `addToQuote()` : Ajoute un modèle au devis (pré-remplit le formulaire)

## 📍 Routes

### Routes publiques (catalogue)
```php
GET  /catalogue              → ModeleController@index
GET  /catalogue/{modele}      → ModeleController@show
GET  /catalogue/{modele}/ajouter-devis → ModeleController@addToQuote
```

### Routes admin (gestion)
```php
GET    /modeles/create                    → ModeleController@create
POST   /modeles                           → ModeleController@store
GET    /modeles/{modele}/edit             → ModeleController@edit
PUT    /modeles/{modele}                  → ModeleController@update
DELETE /modeles/{modele}                  → ModeleController@destroy
POST   /modeles/{modele}/toggle-status    → ModeleController@toggleStatus
```

## 🎨 Interface utilisateur

### Page Catalogue (`modeles.index`)

**Fonctionnalités :**
- Grille responsive de cartes (1-4 colonnes selon l'écran)
- Filtre par catégorie
- Recherche par nom
- Pagination
- Affichage de l'image, nom, catégorie, prix indicatif
- Badge "Inactif" pour les modèles désactivés
- Sauvegarde des filtres dans localStorage (mode hors-ligne)

**Design :**
- Cartes avec effet hover
- Images avec fallback si absentes
- Badges de catégorie
- Design moderne et responsive

### Page Détails (`modeles.show`)

**Contenu :**
- Grande image du modèle
- Nom et catégorie
- Description complète
- Prix indicatif (si disponible)
- Bouton "Ajouter au Devis" (si connecté)
- Modèles similaires (même catégorie)
- Bouton "Générer PDF" (si admin)

**Fonctionnalités :**
- Sauvegarde automatique dans localStorage pour mode hors-ligne
- Affichage conditionnel selon les permissions

### Pages Admin (create/edit)

**Formulaire :**
- Nom (obligatoire)
- Catégorie (obligatoire, select)
- Description (textarea)
- Image (upload avec aperçu)
- Prix indicatif (nombre)
- Statut (actif/inactif)

**Upload d'image :**
- Formats acceptés : JPEG, PNG, JPG, GIF, WEBP
- Taille max : 5MB
- Stockage : `storage/app/public/modeles/`
- Nom de fichier : `nom-modele-timestamp.extension`

## 🔄 Intégration "Ajouter au Devis"

### Flux de travail

1. **Utilisateur clique sur "Ajouter au Devis"** dans la page d'un modèle
2. **Redirection** vers `quotes.create` avec les données du modèle en session
3. **Création du devis** → redirection vers `quotes.edit`
4. **Pré-remplissage automatique** du formulaire d'ajout de ligne :
   - Description = nom du modèle
   - Prix unitaire = prix indicatif (si disponible)
   - Message d'alerte pour informer l'utilisateur

### Code JavaScript

Le code dans `quotes/edit.blade.php` détecte automatiquement les données du modèle et pré-remplit les champs appropriés.

## 📸 Gestion des images

### Upload
- Validation : formats et taille
- Stockage sécurisé dans `storage/app/public/modeles/`
- Génération de noms uniques avec timestamp
- Suppression automatique de l'ancienne image lors de la mise à jour

### Affichage
- URL générée via `Storage::url()`
- Fallback si image absente (icône placeholder)
- Support des URLs externes (si image est une URL complète)

## 🔐 Permissions

Les permissions suivantes sont utilisées (à créer dans votre système de permissions) :
- `modeles.view` : Voir les modèles
- `modeles.create` : Créer des modèles
- `modeles.update` : Modifier des modèles
- `modeles.delete` : Supprimer des modèles

**Note :** Le catalogue est accessible publiquement, mais seuls les modèles actifs sont visibles pour les visiteurs non authentifiés.

## 📱 Mode hors-ligne (localStorage)

### Fonctionnalités implémentées

1. **Sauvegarde des filtres** : Les filtres de recherche sont sauvegardés dans localStorage
2. **Cache des modèles** : Les modèles consultés sont sauvegardés (max 50)
3. **Affichage hors-ligne** : Les données en cache peuvent être utilisées si l'application est en mode hors-ligne

### Structure localStorage

```javascript
// Filtres
localStorage.setItem('modele_filters', JSON.stringify({
    search: '...',
    categorie: '...'
}));

// Cache des modèles
localStorage.setItem('modeles_cache', JSON.stringify([
    {
        id: 1,
        nom: '...',
        categorie: '...',
        description: '...',
        prix_indicatif: 0,
        image_url: '...'
    },
    // ...
]));
```

## 🎨 Catégories disponibles

- **fenetre** : Fenêtre
- **porte** : Porte
- **garde-corps** : Garde-corps
- **vitre** : Vitre
- **vitrine** : Vitrine
- **baie-vitree** : Baie vitrée
- **porte-fenetre** : Porte-fenêtre
- **autre** : Autre

## 🚀 Utilisation

### Pour les visiteurs

1. Accéder au **Catalogue** depuis le menu
2. Parcourir les modèles ou utiliser les filtres
3. Cliquer sur un modèle pour voir les détails
4. Cliquer sur **"Ajouter au Devis"** (nécessite une connexion)

### Pour les administrateurs

1. Accéder au **Catalogue**
2. Cliquer sur **"Nouveau Modèle"**
3. Remplir le formulaire et uploader une image
4. Enregistrer
5. Gérer les modèles (modifier, supprimer, activer/désactiver)

## 🔍 Recherche et filtres

- **Recherche par nom** : Recherche dans le nom et la description
- **Filtre par catégorie** : Affiche uniquement les modèles d'une catégorie
- **Combinaison** : Les filtres peuvent être combinés
- **Pagination** : 12 modèles par page

## 📝 Notes importantes

1. **Images** : Assurez-vous que le lien symbolique `storage` est créé (`php artisan storage:link`)
2. **Permissions** : Les permissions doivent être configurées dans votre système
3. **Mode hors-ligne** : Le cache localStorage est limité à 50 modèles
4. **Statut** : Seuls les modèles actifs sont visibles pour les visiteurs
5. **Prix indicatif** : Peut être laissé vide, c'est optionnel

## 🐛 Dépannage

### Images ne s'affichent pas
- Vérifier que `php artisan storage:link` a été exécuté
- Vérifier les permissions du dossier `storage/app/public/modeles/`
- Vérifier que le fichier existe bien

### Erreur 403 lors de la création
- Vérifier que l'utilisateur a la permission `modeles.create`
- Vérifier que l'utilisateur est bien connecté

### "Ajouter au Devis" ne fonctionne pas
- Vérifier que l'utilisateur est connecté
- Vérifier que les données sont bien passées en session
- Vérifier les logs dans `storage/logs/laravel.log`

## 📚 Améliorations futures possibles

- Système de tags pour les modèles
- Galerie d'images multiples par modèle
- Favoris pour les utilisateurs
- Comparaison de modèles
- Export PDF du catalogue
- Mode hors-ligne complet avec IndexedDB
- Recherche avancée (prix, dimensions, etc.)

---

**Développé pour A2 VitraDevis**

