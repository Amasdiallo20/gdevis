<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    protected $fillable = [
        'user_id',
        'tache_id',
        'chantier_id',
        'type',
        'description',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tache(): BelongsTo
    {
        return $this->belongsTo(Tache::class);
    }

    public function chantier(): BelongsTo
    {
        return $this->belongsTo(Chantier::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'progress_updated' => 'Progression mise à jour',
            'status_changed' => 'Statut modifié',
            'photo_uploaded' => 'Photo uploadée',
            'photo_comment_added' => 'Commentaire ajouté',
            default => $this->type,
        };
    }

    public function getIconAttribute(): string
    {
        return match($this->type) {
            'progress_updated' => 'fa-chart-line',
            'status_changed' => 'fa-exchange-alt',
            'photo_uploaded' => 'fa-camera',
            'photo_comment_added' => 'fa-comment',
            default => 'fa-circle',
        };
    }
}

