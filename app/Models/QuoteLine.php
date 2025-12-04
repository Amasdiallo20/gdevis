<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteLine extends Model
{
    protected $fillable = [
        'quote_id',
        'product_id',
        'line_type',
        'description',
        'width',
        'height',
        'quantity',
        'unit_price',
        'price_per_m2',
        'surface',
        'amount',
        'unit',
    ];

    protected $casts = [
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'price_per_m2' => 'decimal:2',
        'surface' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getSubtotalAttribute()
    {
        // Utiliser le montant si disponible, sinon calculer avec unit_price
        if ($this->amount) {
            return $this->amount;
        }
        return $this->quantity * $this->unit_price;
    }
}

