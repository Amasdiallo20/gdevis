<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CutPlan extends Model
{
    protected $fillable = [
        'quote_id',
        'total_bars_used',
        'total_waste',
    ];

    protected $casts = [
        'total_bars_used' => 'integer',
        'total_waste' => 'decimal:2',
    ];

    /**
     * Relation avec le devis
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    /**
     * Relation avec les détails du plan de coupe
     */
    public function details(): HasMany
    {
        return $this->hasMany(CutPlanDetail::class)->orderBy('bar_number');
    }
}
