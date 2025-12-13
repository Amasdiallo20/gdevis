<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhotoChantier extends Model
{
    protected $table = 'photos_chantier';

    protected $fillable = [
        'chantier_id',
        'chemin_fichier',
        'commentaire',
        'uploaded_by',
    ];

    public function chantier(): BelongsTo
    {
        return $this->belongsTo(Chantier::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

