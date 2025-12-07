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
     * Récupère les paramètres (singleton avec cache)
     */
    public static function getSettings()
    {
        return \Cache::remember('app_settings', 3600, function () {
            $settings = self::first();
            if (!$settings) {
                $settings = self::create([]);
            }
            return $settings;
        });
    }

    /**
     * Clear settings cache
     */
    public static function clearCache()
    {
        \Cache::forget('app_settings');
    }
}
