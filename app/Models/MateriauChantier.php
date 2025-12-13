<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MateriauChantier extends Model
{
    protected $table = 'materiaux_chantier';

    protected $fillable = [
        'chantier_id',
        'nom_materiau',
        'unite',
        'quantite_prevue',
        'quantite_utilisee',
        'notes',
    ];

    protected $casts = [
        'quantite_prevue' => 'decimal:3',
        'quantite_utilisee' => 'decimal:3',
    ];

    public function chantier(): BelongsTo
    {
        return $this->belongsTo(Chantier::class);
    }

    public function getDifferenceAttribute(): float
    {
        return $this->quantite_prevue - $this->quantite_utilisee;
    }

    public function getDifferencePercentAttribute(): float
    {
        if ($this->quantite_prevue == 0) {
            return 0;
        }
        return ($this->quantite_utilisee / $this->quantite_prevue) * 100;
    }
}

