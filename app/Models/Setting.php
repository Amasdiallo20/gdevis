<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'company_name',
        'activity',
        'address',
        'phone',
        'email',
        'rccm',
        'logo',
        'primary_color',
        'secondary_color',
        'print_header_color',
        'print_text_color',
    ];

    /**
     * Récupère les paramètres (singleton)
     */
    public static function getSettings()
    {
        $settings = self::first();
        if (!$settings) {
            $settings = self::create([]);
        }
        return $settings;
    }
}
