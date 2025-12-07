<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Modele extends Model
{
    protected $fillable = [
        'nom',
        'categorie',
        'description',
        'image',
        'prix_indicatif',
        'statut',
    ];

    protected $casts = [
        'prix_indicatif' => 'decimal:2',
    ];

    /**
     * Obtenir l'URL complète de l'image (taille medium par défaut)
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->getImageUrl('medium');
    }

    /**
     * Obtenir l'URL de l'image avec une taille spécifique
     * 
     * @param string $size 'thumbnail', 'medium', 'large', 'original'
     * @return string|null
     */
    public function getImageUrl(string $size = 'medium'): ?string
    {
        if (!$this->image) {
            return null;
        }

        // Si c'est une URL complète, la retourner telle quelle
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // Utiliser le service d'optimisation pour obtenir l'URL optimisée
        $imageService = app(\App\Services\ImageOptimizationService::class);
        return $imageService->getOptimizedUrl($this->image, $size);
    }

    /**
     * Obtenir l'URL de la miniature (thumbnail)
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->getImageUrl('thumbnail');
    }

    /**
     * Obtenir l'URL de l'image large
     */
    public function getLargeImageUrlAttribute(): ?string
    {
        return $this->getImageUrl('large');
    }

    /**
     * Obtenir le chemin complet du fichier image
     */
    public function getImagePathAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        return storage_path('app/public/' . $this->image);
    }

    /**
     * Vérifier si le modèle est actif
     */
    public function isActive(): bool
    {
        return $this->statut === 'actif';
    }

    /**
     * Scope pour les modèles actifs
     */
    public function scopeActive($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour filtrer par catégorie
     */
    public function scopeByCategory($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    /**
     * Scope pour la recherche
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nom', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
    }

    /**
     * Obtenir toutes les catégories disponibles
     */
    public static function getCategories(): array
    {
        return [
            'fenetre' => 'Fenêtre',
            'porte' => 'Porte',
            'garde-corps' => 'Garde-corps',
            'vitre' => 'Vitre',
            'vitrine' => 'Vitrine',
            'baie-vitree' => 'Baie vitrée',
            'porte-fenetre' => 'Porte-fenêtre',
            'autre' => 'Autre',
        ];
    }

    /**
     * Relation avec les devis
     */
    public function quotes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Quote::class, 'model_id');
    }
}
