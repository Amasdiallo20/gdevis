<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Permissions pour les devis
            ['name' => 'Voir les devis', 'slug' => 'quotes.view', 'description' => 'Permet de voir la liste des devis', 'category' => 'quotes'],
            ['name' => 'Créer des devis', 'slug' => 'quotes.create', 'description' => 'Permet de créer de nouveaux devis', 'category' => 'quotes'],
            ['name' => 'Modifier les devis', 'slug' => 'quotes.edit', 'description' => 'Permet de modifier les devis', 'category' => 'quotes'],
            ['name' => 'Supprimer les devis', 'slug' => 'quotes.delete', 'description' => 'Permet de supprimer les devis', 'category' => 'quotes'],
            ['name' => 'Valider les devis', 'slug' => 'quotes.validate', 'description' => 'Permet de valider les devis', 'category' => 'quotes'],
            ['name' => 'Annuler les devis', 'slug' => 'quotes.cancel', 'description' => 'Permet d\'annuler les devis validés', 'category' => 'quotes'],
            ['name' => 'Imprimer les devis', 'slug' => 'quotes.print', 'description' => 'Permet d\'imprimer les devis', 'category' => 'quotes'],
            ['name' => 'Calculer les matériaux', 'slug' => 'quotes.calculate-materials', 'description' => 'Permet de calculer les matériaux pour les devis', 'category' => 'quotes'],

            // Permissions pour les clients
            ['name' => 'Voir les clients', 'slug' => 'clients.view', 'description' => 'Permet de voir la liste des clients', 'category' => 'clients'],
            ['name' => 'Créer des clients', 'slug' => 'clients.create', 'description' => 'Permet de créer de nouveaux clients', 'category' => 'clients'],
            ['name' => 'Modifier les clients', 'slug' => 'clients.edit', 'description' => 'Permet de modifier les clients', 'category' => 'clients'],
            ['name' => 'Supprimer les clients', 'slug' => 'clients.delete', 'description' => 'Permet de supprimer les clients', 'category' => 'clients'],

            // Permissions pour les produits
            ['name' => 'Voir les produits', 'slug' => 'products.view', 'description' => 'Permet de voir la liste des produits', 'category' => 'products'],
            ['name' => 'Créer des produits', 'slug' => 'products.create', 'description' => 'Permet de créer de nouveaux produits', 'category' => 'products'],
            ['name' => 'Modifier les produits', 'slug' => 'products.edit', 'description' => 'Permet de modifier les produits', 'category' => 'products'],
            ['name' => 'Supprimer les produits', 'slug' => 'products.delete', 'description' => 'Permet de supprimer les produits', 'category' => 'products'],

            // Permissions pour les paiements
            ['name' => 'Voir les paiements', 'slug' => 'payments.view', 'description' => 'Permet de voir la liste des paiements', 'category' => 'payments'],
            ['name' => 'Créer des paiements', 'slug' => 'payments.create', 'description' => 'Permet de créer de nouveaux paiements', 'category' => 'payments'],
            ['name' => 'Modifier les paiements', 'slug' => 'payments.edit', 'description' => 'Permet de modifier les paiements', 'category' => 'payments'],
            ['name' => 'Supprimer les paiements', 'slug' => 'payments.delete', 'description' => 'Permet de supprimer les paiements', 'category' => 'payments'],
            ['name' => 'Imprimer les paiements', 'slug' => 'payments.print', 'description' => 'Permet d\'imprimer les paiements', 'category' => 'payments'],

            // Permissions pour les modèles
            ['name' => 'Voir les modèles', 'slug' => 'modeles.view', 'description' => 'Permet de voir la liste des modèles', 'category' => 'modeles'],
            ['name' => 'Créer des modèles', 'slug' => 'modeles.create', 'description' => 'Permet de créer de nouveaux modèles', 'category' => 'modeles'],
            ['name' => 'Modifier les modèles', 'slug' => 'modeles.update', 'description' => 'Permet de modifier les modèles', 'category' => 'modeles'],
            ['name' => 'Supprimer les modèles', 'slug' => 'modeles.delete', 'description' => 'Permet de supprimer les modèles', 'category' => 'modeles'],

            // Permissions pour les utilisateurs
            ['name' => 'Voir les utilisateurs', 'slug' => 'users.view', 'description' => 'Permet de voir la liste des utilisateurs', 'category' => 'users'],
            ['name' => 'Créer des utilisateurs', 'slug' => 'users.create', 'description' => 'Permet de créer de nouveaux utilisateurs', 'category' => 'users'],
            ['name' => 'Modifier les utilisateurs', 'slug' => 'users.edit', 'description' => 'Permet de modifier les utilisateurs', 'category' => 'users'],
            ['name' => 'Supprimer les utilisateurs', 'slug' => 'users.delete', 'description' => 'Permet de supprimer les utilisateurs', 'category' => 'users'],
            ['name' => 'Gérer les permissions', 'slug' => 'users.manage-permissions', 'description' => 'Permet de gérer les permissions des utilisateurs', 'category' => 'users'],

            // Permissions pour les chantiers
            ['name' => 'Voir les chantiers', 'slug' => 'chantiers.view', 'description' => 'Permet de voir la liste des chantiers', 'category' => 'chantiers'],
            ['name' => 'Modifier les chantiers', 'slug' => 'chantiers.edit', 'description' => 'Permet de modifier les chantiers', 'category' => 'chantiers'],

            // Permissions générales
            ['name' => 'Voir le tableau de bord', 'slug' => 'dashboard.view', 'description' => 'Permet d\'accéder au tableau de bord', 'category' => 'general'],
            ['name' => 'Gérer les paramètres', 'slug' => 'settings.manage', 'description' => 'Permet de gérer les paramètres de l\'application', 'category' => 'general'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }
    }
}





