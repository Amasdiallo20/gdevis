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
        $userPermissions = $user->permissions->pluck('id')->toArray();
        return view('users.edit', compact('user', 'permissions', 'userPermissions'));
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

