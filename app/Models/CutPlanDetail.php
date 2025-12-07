<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CutPlanDetail extends Model
{
    protected $fillable = [
        'cut_plan_id',
        'bar_number',
        'sections',
        'used_length',
        'waste',
    ];

    protected $casts = [
        'bar_number' => 'integer',
        'sections' => 'array',
        'used_length' => 'decimal:2',
        'waste' => 'decimal:2',
    ];

    /**
     * Relation avec le plan de coupe
     */
    public function cutPlan(): BelongsTo
    {
        return $this->belongsTo(CutPlan::class);
    }
}
