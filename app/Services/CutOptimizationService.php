<?php

namespace App\Services;

/**
 * Service d'optimisation des coupes d'aluminium
 * 
 * Implémente l'algorithme "First Fit Decreasing" (FFD) pour le problème
 * de bin packing afin de minimiser les chutes lors de la découpe
 * de barres d'aluminium de longueur fixe (580 cm).
 */
class CutOptimizationService
{
    /**
     * Longueur fixe d'une barre d'aluminium en cm
     */
    private const BAR_LENGTH = 580;

    /**
     * Optimise la découpe des barres d'aluminium
     * 
     * Algorithme amélioré : First Fit Decreasing avec recherche de la meilleure combinaison
     * 
     * @param array $lengths Liste des longueurs à couper (en cm)
     * @param float $barLength Longueur d'une barre (par défaut 580 cm)
     * @return array Structure avec bar_number, sections, used_length, waste
     */
    public function optimize(array $lengths, float $barLength = self::BAR_LENGTH): array
    {
        // Filtrer les longueurs valides (> 0 et <= barLength)
        $validLengths = array_filter($lengths, function($length) use ($barLength) {
            return is_numeric($length) && $length > 0 && $length <= $barLength;
        });

        if (empty($validLengths)) {
            return [];
        }

        // Trier les longueurs en ordre décroissant (First Fit Decreasing)
        rsort($validLengths);

        $bars = [];
        $barNumber = 1;
        $remainingLengths = $validLengths;

        // Tant qu'il reste des longueurs à placer
        while (!empty($remainingLengths)) {
            $bestCombination = $this->findBestCombination($remainingLengths, $barLength);
            
            if (empty($bestCombination)) {
                // Si aucune combinaison n'est possible, prendre la plus grande longueur
                $length = array_shift($remainingLengths);
                $bars[] = [
                    'bar_number' => $barNumber++,
                    'sections' => [$length],
                    'used_length' => $length,
                    'waste' => $barLength - $length,
                ];
            } else {
                // Créer une nouvelle barre avec la meilleure combinaison
                $bars[] = [
                    'bar_number' => $barNumber++,
                    'sections' => $bestCombination,
                    'used_length' => array_sum($bestCombination),
                    'waste' => $barLength - array_sum($bestCombination),
                ];

                // Retirer les longueurs utilisées
                foreach ($bestCombination as $usedLength) {
                    $key = array_search($usedLength, $remainingLengths);
                    if ($key !== false) {
                        unset($remainingLengths[$key]);
                    }
                }
                $remainingLengths = array_values($remainingLengths);
            }
        }

        // Trier les sections dans chaque barre par ordre décroissant pour l'affichage
        foreach ($bars as &$bar) {
            rsort($bar['sections']);
        }

        return $bars;
    }

    /**
     * Trouve la meilleure combinaison de longueurs qui maximise l'utilisation d'une barre
     * 
     * Utilise une approche gloutonne améliorée avec recherche limitée
     * 
     * @param array $lengths Liste des longueurs disponibles
     * @param float $barLength Longueur de la barre
     * @return array Meilleure combinaison de longueurs
     */
    private function findBestCombination(array $lengths, float $barLength): array
    {
        $bestCombination = [];
        $bestUsed = 0;
        $n = count($lengths);

        // Limiter à 15 éléments pour éviter les problèmes de performance
        if ($n > 15) {
            $lengths = array_slice($lengths, 0, 15);
            $n = 15;
        }

        // Utiliser une approche de programmation dynamique simplifiée
        // On essaie les meilleures combinaisons en priorité
        $maxCombinations = min(5000, pow(2, $n)); // Limiter à 5000 combinaisons max

        for ($i = 1; $i < $maxCombinations && $i < (1 << $n); $i++) {
            $combination = [];
            $total = 0;

            for ($j = 0; $j < $n; $j++) {
                if ($i & (1 << $j)) {
                    $length = $lengths[$j];
                    if ($total + $length <= $barLength) {
                        $combination[] = $length;
                        $total += $length;
                    } else {
                        // Si on dépasse, arrêter cette combinaison
                        $combination = [];
                        break;
                    }
                }
            }

            // Si cette combinaison est meilleure que la précédente
            if (!empty($combination) && $total > $bestUsed) {
                $bestUsed = $total;
                $bestCombination = $combination;

                // Si on a trouvé une combinaison parfaite (chute < 5 cm), on peut s'arrêter
                if (($barLength - $total) < 5) {
                    break;
                }
            }
        }

        // Si aucune combinaison n'a été trouvée, utiliser une approche gloutonne simple
        if (empty($bestCombination)) {
            $combination = [];
            $total = 0;
            foreach ($lengths as $length) {
                if ($total + $length <= $barLength) {
                    $combination[] = $length;
                    $total += $length;
                } else {
                    break;
                }
            }
            return $combination;
        }

        return $bestCombination;
    }

    /**
     * Calcule les statistiques globales d'un plan de coupe
     * 
     * @param array $bars Résultat de la méthode optimize()
     * @return array ['total_bars' => int, 'total_waste' => float]
     */
    public function calculateStatistics(array $bars): array
    {
        $totalBars = count($bars);
        $totalWaste = array_sum(array_column($bars, 'waste'));

        return [
            'total_bars' => $totalBars,
            'total_waste' => round($totalWaste, 2),
        ];
    }

    /**
     * Extrait toutes les longueurs nécessaires depuis les lignes d'un devis
     * 
     * Pour les fenêtres : 2 montants verticaux (hauteur) + 2 traverses horizontales (largeur)
     * Pour les autres produits : largeur et hauteur selon le besoin
     * 
     * @param \Illuminate\Database\Eloquent\Collection $quoteLines
     * @return array Liste des longueurs en cm
     */
    public function extractLengthsFromQuote($quoteLines): array
    {
        $lengths = [];

        foreach ($quoteLines as $line) {
            // Seulement les lignes de type "product" avec dimensions
            if ($line->line_type !== 'product') {
                continue;
            }

            // Vérifier que les dimensions sont présentes
            if (!$line->width || $line->width <= 0 || !$line->height || $line->height <= 0) {
                continue;
            }

            $quantity = (int) $line->quantity;
            $width = (float) $line->width;
            $height = (float) $line->height;

            // Détecter si c'est une fenêtre
            $isWindow = $this->isWindow($line);

            if ($isWindow) {
                // Pour une fenêtre : 2 montants verticaux (hauteur) + 2 traverses horizontales (largeur)
                // Pour chaque fenêtre, on a besoin de :
                // - 2 × hauteur (montants verticaux)
                // - 2 × largeur (traverses horizontales)
                for ($i = 0; $i < $quantity; $i++) {
                    // 2 montants verticaux
                    $lengths[] = $height;
                    $lengths[] = $height;
                    // 2 traverses horizontales
                    $lengths[] = $width;
                    $lengths[] = $width;
                }
            } else {
                // Pour les autres produits, extraire largeur et hauteur
                for ($i = 0; $i < $quantity; $i++) {
                    $lengths[] = $width;
                    $lengths[] = $height;
                }
            }
        }

        return $lengths;
    }

    /**
     * Détermine si une ligne de devis correspond à une fenêtre
     * 
     * @param \App\Models\QuoteLine $line
     * @return bool
     */
    private function isWindow($line): bool
    {
        $productName = '';
        if ($line->product) {
            $productName = strtolower($line->product->name);
        }
        $description = strtolower($line->description ?? '');

        // Mots-clés pour identifier une fenêtre
        $windowKeywords = [
            'fenêtre', 'fenetre', 'fenêtres', 'fenetres',
            'fentres', 'fentre', 'fentr',
            'fenetr', 'fenet', 'fent',
            'window', 'windows',
        ];

        foreach ($windowKeywords as $keyword) {
            if (strpos($productName, $keyword) !== false || strpos($description, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }
}

