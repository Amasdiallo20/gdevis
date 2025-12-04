<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
    ];

    public function quoteLines(): HasMany
    {
        return $this->hasMany(QuoteLine::class);
    }
}

