<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        // Vérifier s'il y a déjà des utilisateurs dans la base
        $hasUsers = User::count() > 0;
        return view('auth.login', compact('hasUsers'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => 'Les identifiants fournis sont incorrects.',
        ]);
    }

    public function showRegisterForm()
    {
        // Vérifier s'il y a déjà des utilisateurs
        $hasUsers = User::count() > 0;
        
        // Si des utilisateurs existent, seul un admin peut créer des comptes
        if ($hasUsers) {
            if (!Auth::check() || !Auth::user()->hasPermission('users.create')) {
                abort(403, 'Seul un administrateur peut créer des comptes utilisateurs.');
            }
        }
        
        return view('auth.register', compact('hasUsers'));
    }

    public function register(Request $request)
    {
        // Vérifier s'il y a déjà des utilisateurs
        $hasUsers = User::count() > 0;
        
        // Si des utilisateurs existent, seul un admin peut créer des comptes
        if ($hasUsers) {
            if (!Auth::check() || !Auth::user()->hasPermission('users.create')) {
                abort(403, 'Seul un administrateur peut créer des comptes utilisateurs.');
            }
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Si c'est le premier utilisateur, le créer en tant qu'admin
        // Sinon, créer un utilisateur normal (seul un admin peut accéder à cette méthode)
        $role = $hasUsers ? 'user' : 'admin';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $role,
        ]);

        // Si c'est le premier utilisateur, le connecter automatiquement
        if (!$hasUsers) {
            Auth::login($user);
            return redirect()->route('dashboard')
                ->with('success', 'Compte administrateur créé avec succès !');
        }

        // Si un admin crée un compte, rediriger vers la liste des utilisateurs
        return redirect()->route('users.index')
            ->with('success', 'Compte utilisateur créé avec succès !');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('modeles.index');
    }

    public function showProfile()
    {
        return view('auth.profile');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Vérifier que le mot de passe actuel est correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        // Mettre à jour le mot de passe
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile')
            ->with('success', 'Votre mot de passe a été modifié avec succès.');
    }
}
