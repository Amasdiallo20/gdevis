# Documentation - Optimisation des Coupes Aluminium

## 📋 Vue d'ensemble

Cette fonctionnalité permet d'optimiser la découpe de barres d'aluminium de longueur fixe (580 cm) en minimisant les chutes lors de la création de devis.

## 🗄️ Structure de la base de données

### Table `cut_plans`
- `id` : Identifiant unique
- `quote_id` : Référence au devis (clé étrangère)
- `total_bars_used` : Nombre total de barres utilisées
- `total_waste` : Total des chutes en cm
- `created_at` / `updated_at` : Timestamps

### Table `cut_plan_details`
- `id` : Identifiant unique
- `cut_plan_id` : Référence au plan de coupe (clé étrangère)
- `bar_number` : Numéro de la barre
- `sections` : JSON contenant la liste des longueurs coupées
- `used_length` : Longueur utilisée en cm
- `waste` : Chute en cm
- `created_at` / `updated_at` : Timestamps

## 🔧 Architecture

### Service : `CutOptimizationService`

**Méthode principale : `optimize(array $lengths, float $barLength = 580)`**

Algorithme utilisé : **First Fit Decreasing (FFD)** - une variante du problème de bin packing.

**Principe :**
1. Trier les longueurs en ordre décroissant
2. Pour chaque longueur :
   - Essayer de la placer dans une barre existante qui a assez d'espace
   - Si aucune barre ne peut l'accueillir, créer une nouvelle barre

**Retour :**
```php
[
    [
        'bar_number' => 1,
        'sections' => [160, 150, 140, 120],
        'used_length' => 570,
        'waste' => 10
    ],
    // ...
]
```

### Contrôleur : `CutPlanController`

**Méthodes :**
- `generate(Quote $quote)` : Génère un plan de coupe optimisé pour un devis
- `show(CutPlan $cutPlan)` : Affiche un plan de coupe
- `downloadPdf(CutPlan $cutPlan)` : Génère et télécharge le PDF du plan

## 📍 Routes

```php
POST /quotes/{quote}/cut-optimize    → CutPlanController@generate
GET  /cut-plans/{cutPlan}            → CutPlanController@show
GET  /cut-plans/{cutPlan}/pdf        → CutPlanController@downloadPdf
```

## 🎨 Interface utilisateur

### Page du devis (`quotes.show`)

**Bouton d'optimisation :**
- Bouton "Optimiser Coupes" dans la barre d'actions
- Appel AJAX pour générer le plan
- Redirection automatique vers la page du plan après génération

**Section d'affichage :**
- Affiche le dernier plan généré (si disponible)
- Informations : nombre de barres, total des chutes, date de génération
- Lien "Voir le Plan" pour accéder au détail

### Page du plan (`cut_plans.show`)

**Contenu :**
- Résumé avec statistiques (barres utilisées, longueur par barre, total chutes)
- Tableau détaillé de toutes les barres avec :
  - Numéro de barre
  - Liste des coupes (badges)
  - Longueur utilisée
  - Chute
- Bouton "Générer PDF"

### PDF du plan (`cut_plans.pdf`)

**Contenu :**
- En-tête avec titre
- Informations du devis
- Tableau détaillé des barres
- Résumé avec statistiques globales
- Taux d'utilisation

## 🔄 Flux de travail

1. **Création d'un devis** avec des lignes contenant des dimensions (width, height)
2. **Clic sur "Optimiser Coupes"** dans la page du devis
3. **Extraction automatique** des longueurs depuis les lignes du devis
4. **Optimisation** via l'algorithme FFD
5. **Enregistrement** du plan dans la base de données
6. **Affichage** du plan avec possibilité de générer un PDF

## 📊 Extraction des longueurs

Le service extrait automatiquement les longueurs depuis les lignes du devis :

### Pour les fenêtres :
Pour chaque fenêtre, on calcule :
- **2 montants verticaux** = 2 × hauteur
- **2 traverses horizontales** = 2 × largeur

**Exemple :**
- Ligne : Fenêtre 200×110 cm, quantité 1
- Longueurs extraites : [200, 200, 110, 110]
  - 2 traverses de 200 cm
  - 2 montants de 110 cm

### Pour les autres produits :
- Largeur (width) × quantité
- Hauteur (height) × quantité

**Exemple :**
- Ligne : Porte 120×150 cm, quantité 2
- Longueurs extraites : [120, 120, 150, 150]

## 🧪 Test de l'algorithme

### Exemple de test - Fenêtre

**Scénario :** Fenêtre 200×110 cm

**Calcul des coupes nécessaires :**
- 2 montants verticaux = 2 × 110 = 220 cm
- 2 traverses horizontales = 2 × 200 = 400 cm
- **Total = 620 cm** (impossible dans 1 barre de 580 cm)

**Longueurs à couper :** [200, 200, 110, 110]

**Résultat de l'optimisation :**
```
Barre 1 : [200, 200] = 400 cm utilisés, 180 cm de chute
Barre 2 : [110, 110] = 220 cm utilisés, 360 cm de chute

Total : 2 barres utilisées, 540 cm de chute
```

### Exemple de test - Multiple longueurs

```php
use App\Services\CutOptimizationService;

$service = new CutOptimizationService();

// Test avec des longueurs
$lengths = [200, 200, 110, 110]; // Fenêtre 200×110

$result = $service->optimize($lengths);

// Résultat attendu :
// - Barre 1 : [200, 200] = 400 cm utilisés, 180 cm de chute
// - Barre 2 : [110, 110] = 220 cm utilisés, 360 cm de chute
```

### Test via Tinker

```bash
php artisan tinker
```

```php
$service = new App\Services\CutOptimizationService();
$lengths = [120, 140, 160, 150, 130, 110, 100, 90, 80, 200, 180, 170];
$result = $service->optimize($lengths);
print_r($result);
```

## 📝 Notes importantes

1. **Longueur fixe** : Les barres ont une longueur fixe de 580 cm (définie dans `CutOptimizationService::BAR_LENGTH`)

2. **Validation** : Les longueurs > 580 cm sont automatiquement exclues

3. **Tri décroissant** : L'algorithme fonctionne mieux avec un tri décroissant (FFD)

4. **Performance** : L'algorithme est en O(n²) dans le pire cas, mais très rapide en pratique

5. **Optimisation** : L'algorithme FFD donne généralement de bons résultats (proche de l'optimal) pour ce type de problème

## 🚀 Utilisation

1. **Créer un devis** avec des lignes contenant des dimensions
2. **Aller sur la page du devis**
3. **Cliquer sur "Optimiser Coupes"**
4. **Consulter le plan généré**
5. **Générer le PDF** si nécessaire

## 🔍 Dépannage

### Aucune dimension trouvée
- Vérifier que les lignes du devis ont des dimensions (width, height)
- Vérifier que le type de ligne est "product"

### Erreur lors de la génération
- Vérifier les logs dans `storage/logs/laravel.log`
- Vérifier que les migrations ont été exécutées
- Vérifier les permissions de la base de données

## 📚 Références

- **Bin Packing Problem** : https://en.wikipedia.org/wiki/Bin_packing_problem
- **First Fit Decreasing** : Algorithme d'approximation pour le bin packing

---

**Développé pour A2 VitraDevis**

