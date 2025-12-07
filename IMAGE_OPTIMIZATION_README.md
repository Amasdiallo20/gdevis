# Optimisation des Images - Documentation

## Vue d'ensemble

Le système d'optimisation des images génère automatiquement plusieurs tailles d'images optimisées pour le web et mobile lors de l'upload, réduisant ainsi la taille des fichiers et améliorant les performances.

## Fonctionnalités

### Tailles générées

Lors de l'upload d'une image, le système génère automatiquement 4 versions :

1. **Thumbnail** (300x300px, qualité 80%)
   - Utilisé pour : Liste des modèles, cartes, miniatures
   - Format : WebP (si supporté) ou format original

2. **Medium** (800x800px, qualité 85%)
   - Utilisé par défaut : Affichage général
   - Format : WebP (si supporté) ou format original

3. **Large** (1200x1200px, qualité 90%)
   - Utilisé pour : Pages de détails, images principales
   - Format : WebP (si supporté) ou format original

4. **Original** (max 2000x2000px, qualité 95%)
   - Version optimisée de l'original
   - Limité à 2000px pour éviter les fichiers trop volumineux

### Structure des fichiers

Les images sont organisées comme suit :
```
storage/app/public/modeles/
├── thumbnail/
│   └── nom-modele-1234567890.webp
├── medium/
│   └── nom-modele-1234567890.webp
├── large/
│   └── nom-modele-1234567890.webp
└── original/
    └── nom-modele-1234567890.webp
```

## Utilisation dans les vues

### Liste des modèles (Catalogue)
```blade
<img src="{{ $modele->thumbnail_url ?? $modele->image_url }}" 
     alt="{{ $modele->nom }}"
     loading="lazy">
```

### Page de détails
```blade
<img src="{{ $modele->large_image_url ?? $modele->image_url }}" 
     alt="{{ $modele->nom }}"
     loading="eager">
```

### Modèles similaires
```blade
<img src="{{ $related->thumbnail_url ?? $related->image_url }}" 
     alt="{{ $related->nom }}"
     loading="lazy">
```

## Accesseurs disponibles

Le modèle `Modele` fournit plusieurs accesseurs :

- `$modele->image_url` : Image medium (par défaut)
- `$modele->thumbnail_url` : Miniature (300x300px)
- `$modele->large_image_url` : Grande image (1200x1200px)
- `$modele->getImageUrl('original')` : Image originale optimisée

## Avantages

1. **Performance** : Images plus légères = chargement plus rapide
2. **Mobile** : Tailles adaptées pour économiser la bande passante
3. **SEO** : Meilleur temps de chargement = meilleur référencement
4. **Expérience utilisateur** : Chargement progressif avec `loading="lazy"`

## Format WebP

Le système convertit automatiquement les images JPG/PNG en WebP quand c'est possible :
- **Réduction de taille** : 25-35% plus léger que JPG
- **Qualité** : Meilleure compression avec qualité visuelle équivalente
- **Fallback** : Si WebP n'est pas disponible, utilise le format original

## Configuration

Les tailles et qualités peuvent être modifiées dans `app/Services/ImageOptimizationService.php` :

```php
const SIZES = [
    'thumbnail' => ['width' => 300, 'height' => 300, 'quality' => 80],
    'medium' => ['width' => 800, 'height' => 800, 'quality' => 85],
    'large' => ['width' => 1200, 'height' => 1200, 'quality' => 90],
    'original' => ['quality' => 95],
];
```

## Notes techniques

- Utilise **Intervention Image v3** avec le driver GD
- Fallback sur les fonctions natives PHP GD si Intervention Image n'est pas disponible
- Suppression automatique de toutes les versions lors de la suppression d'un modèle
- Compatible avec les images existantes (fallback sur l'original si les versions optimisées n'existent pas)

