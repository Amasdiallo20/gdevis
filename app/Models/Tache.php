<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tache extends Model
{
    protected $fillable = [
        'chantier_id',
        'nom',
        'description',
        'type',
        'status',
        'progress',
        'date_debut_prevue',
        'date_fin_prevue',
        'date_debut_reelle',
        'date_fin_reelle',
        'ordre',
    ];

    protected $casts = [
        'date_debut_prevue' => 'date',
        'date_fin_prevue' => 'date',
        'date_debut_reelle' => 'date',
        'date_fin_reelle' => 'date',
        'progress' => 'integer',
        'ordre' => 'integer',
    ];

    public function chantier(): BelongsTo
    {
        return $this->belongsTo(Chantier::class);
    }

    public function techniciens(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'technicien_tache', 'tache_id', 'technicien_id')
            ->withTimestamps();
    }

    public function photos(): HasMany
    {
        return $this->hasMany(PhotoTache::class)->latest();
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'a_faire' => 'À faire',
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'bloque' => 'Bloqué',
            default => $this->status,
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'coupe' => 'Coupe',
            'assemblage' => 'Assemblage',
            'decoupe_vitres' => 'Découpe vitres',
            'pose' => 'Pose',
            'finitions' => 'Finitions',
            'autre' => 'Autre',
            default => $this->type,
        };
    }
}

