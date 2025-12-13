<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Chantier extends Model
{
    protected $fillable = [
        'quote_id',
        'chantier_number',
        'status',
        'progress',
        'date_debut',
        'date_fin_prevue',
        'date_fin_reelle',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin_prevue' => 'date',
        'date_fin_reelle' => 'date',
        'progress' => 'integer',
    ];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function taches(): HasMany
    {
        return $this->hasMany(Tache::class)->orderBy('ordre');
    }

    public function materiaux(): HasMany
    {
        return $this->hasMany(MateriauChantier::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(PhotoChantier::class)->latest();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'planifié' => 'Planifié',
            'en_cours' => 'En cours',
            'suspendu' => 'Suspendu',
            'terminé' => 'Terminé',
            'facturé' => 'Facturé',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'planifié' => 'blue',
            'en_cours' => 'green',
            'suspendu' => 'yellow',
            'terminé' => 'gray',
            'facturé' => 'purple',
            default => 'gray',
        };
    }
}

