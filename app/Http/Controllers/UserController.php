<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Seuls les administrateurs peuvent gérer les utilisateurs
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!$user || $user->role !== 'admin') {
                abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent gérer les utilisateurs.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = User::query();

        // Recherche par nom ou email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filtre par rôle
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();
        
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('category')->orderBy('name')->get()->groupBy('category');
        
        // Vérifier si les permissions existent, sinon les créer automatiquement
        if ($permissions->isEmpty()) {
            $this->seedPermissions();
            $permissions = Permission::orderBy('category')->orderBy('name')->get()->groupBy('category');
        }
        
        return view('users.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'valid_until' => 'nullable|date|after_or_equal:today',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'valid_until' => $validated['valid_until'] ?? null,
        ]);

        // Attacher les permissions si fournies
        if (isset($validated['permissions'])) {
            $user->permissions()->sync($validated['permissions']);
        }

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user)
    {
        $user->load('permissions');
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $permissions = Permission::orderBy('category')->orderBy('name')->get()->groupBy('category');
        
        // Vérifier si les permissions existent, sinon les créer automatiquement
        if ($permissions->isEmpty()) {
            $this->seedPermissions();
            $permissions = Permission::orderBy('category')->orderBy('name')->get()->groupBy('category');
        }
        
        $userPermissions = $user->permissions->pluck('id')->toArray();
        return view('users.edit', compact('user', 'permissions', 'userPermissions'));
    }
    
    /**
     * Crée les permissions si elles n'existent pas
     */
    private function seedPermissions()
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

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'valid_until' => 'nullable|date',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'valid_until' => $validated['valid_until'] ?? null,
        ];

        // Mettre à jour le mot de passe seulement s'il est fourni
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Synchroniser les permissions
        if (isset($validated['permissions'])) {
            $user->permissions()->sync($validated['permissions']);
        } else {
            $user->permissions()->detach();
        }

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        // Empêcher la suppression de son propre compte
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}

