<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhotoTache extends Model
{
    protected $table = 'photos_taches';

    protected $fillable = [
        'tache_id',
        'chemin_fichier',
        'commentaire',
        'uploaded_by',
    ];

    public function tache(): BelongsTo
    {
        return $this->belongsTo(Tache::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

