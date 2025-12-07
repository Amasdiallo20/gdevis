<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materiaux';

    protected $fillable = [
        'nom',
        'prix_unitaire',
        'unite',
        'date_mise_a_jour',
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'date_mise_a_jour' => 'datetime',
    ];

    /**
     * Mettre à jour automatiquement la date de mise à jour
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($material) {
            $material->date_mise_a_jour = now();
        });
    }

    /**
     * Récupérer tous les matériaux avec cache
     */
    public static function getAllCached()
    {
        return \Cache::remember('materials_all', 3600, function () {
            return self::pluck('prix_unitaire', 'nom')->toArray();
        });
    }

    /**
     * Récupérer un matériau par son nom (avec cache)
     */
    public static function getByName(string $nom)
    {
        $cacheKey = 'material_' . md5($nom);
        return \Cache::remember($cacheKey, 3600, function () use ($nom) {
            return self::where('nom', $nom)->first();
        });
    }

    /**
     * Récupérer le prix unitaire d'un matériau par son nom (avec cache)
     */
    public static function getPriceByName(string $nom): float
    {
        $materials = self::getAllCached();
        return isset($materials[$nom]) ? (float) $materials[$nom] : 0;
    }

    /**
     * Clear material cache
     */
    public static function clearCache()
    {
        \Cache::forget('materials_all');
        // Clear individual material caches
        $materials = self::pluck('nom')->toArray();
        foreach ($materials as $nom) {
            \Cache::forget('material_' . md5($nom));
        }
    }
}
