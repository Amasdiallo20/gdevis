<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        
        // Partager les paramètres avec toutes les vues
        View::composer('*', function ($view) {
            $view->with('settings', Setting::getSettings());
        });

        // Directive Blade pour vérifier les permissions
        Blade::if('hasPermission', function ($permissionSlug) {
            $user = Auth::user();
            return $user && $user->hasPermission($permissionSlug);
        });

        Blade::if('hasAnyPermission', function (array $permissionSlugs) {
            $user = Auth::user();
            return $user && $user->hasAnyPermission($permissionSlugs);
        });

        Blade::if('hasAllPermissions', function (array $permissionSlugs) {
            $user = Auth::user();
            return $user && $user->hasAllPermissions($permissionSlugs);
        });
    }
}

