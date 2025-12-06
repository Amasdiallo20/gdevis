<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'valid_until',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'valid_until' => 'date',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    /**
     * Vérifie si l'utilisateur a une permission spécifique
     */
    public function hasPermission($permissionSlug)
    {
        // Les administrateurs ont toutes les permissions
        if ($this->role === 'admin') {
            return true;
        }

        return $this->permissions()->where('slug', $permissionSlug)->exists();
    }

    /**
     * Vérifie si l'utilisateur a au moins une des permissions
     */
    public function hasAnyPermission(array $permissionSlugs)
    {
        // Les administrateurs ont toutes les permissions
        if ($this->role === 'admin') {
            return true;
        }

        return $this->permissions()->whereIn('slug', $permissionSlugs)->exists();
    }

    /**
     * Vérifie si l'utilisateur a toutes les permissions
     */
    public function hasAllPermissions(array $permissionSlugs)
    {
        // Les administrateurs ont toutes les permissions
        if ($this->role === 'admin') {
            return true;
        }

        $userPermissionCount = $this->permissions()->whereIn('slug', $permissionSlugs)->count();
        return $userPermissionCount === count($permissionSlugs);
    }

    /**
     * Vérifie si le compte utilisateur est toujours valide
     */
    public function isValid()
    {
        // Si pas de date de validité, le compte est valide indéfiniment
        if (!$this->valid_until) {
            return true;
        }

        // Vérifier si la date de validité n'est pas dépassée (aujourd'hui inclus)
        return $this->valid_until->greaterThanOrEqualTo(now()->startOfDay());
    }

    /**
     * Vérifie si le compte utilisateur est expiré
     */
    public function isExpired()
    {
        return !$this->isValid();
    }
}


















