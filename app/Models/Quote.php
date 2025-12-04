<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
    protected $fillable = [
        'client_id',
        'quote_number',
        'date',
        'valid_until',
        'status',
        'notes',
        'final_amount',
    ];

    protected $casts = [
        'date' => 'date',
        'valid_until' => 'date',
        'final_amount' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function lines(): HasMany
    {
        return $this->hasMany(QuoteLine::class)->orderBy('id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class)->orderBy('payment_date', 'desc');
    }

    public function getSubtotalAttribute()
    {
        return $this->lines->sum(function ($line) {
            // Utiliser le montant si disponible, sinon calculer avec unit_price
            if ($line->amount) {
                return $line->amount;
            }
            return $line->quantity * $line->unit_price;
        });
    }

    public function getTotalAttribute()
    {
        // Si le devis est validé et a un montant final, utiliser le montant final
        if ($this->status === 'validated' && $this->final_amount !== null) {
            return $this->final_amount;
        }
        return $this->subtotal; // Total = Sous-total (sans TVA)
    }

    public function getPaidAmountAttribute(): float
    {
        return $this->payments->sum('amount');
    }

    public function getRemainingAmountAttribute(): float
    {
        // Utiliser le montant final si validé, sinon le total calculé
        $totalAmount = ($this->status === 'validated' && $this->final_amount !== null) 
            ? $this->final_amount 
            : $this->subtotal;
        return max(0, $totalAmount - $this->paid_amount);
    }

    public function getIsFullyPaidAttribute(): bool
    {
        return $this->remaining_amount <= 0;
    }
}

