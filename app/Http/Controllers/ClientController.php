<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasPermission('clients.view')) {
            abort(403, 'Vous n\'avez pas la permission de voir les clients.');
        }

        $query = Client::query();

        // Recherche par nom, email ou ville
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        $clients = $query->latest()->paginate(15)->withQueryString();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->hasPermission('clients.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer des clients.');
        }

        return view('clients.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasPermission('clients.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer des clients.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client créé avec succès.');
    }

    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        $user = Auth::user();
        if (!$user->hasPermission('clients.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les clients.');
        }

        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $user = Auth::user();
        if (!$user->hasPermission('clients.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les clients.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client mis à jour avec succès.');
    }

    public function destroy(Client $client)
    {
        $user = Auth::user();
        if (!$user->hasPermission('clients.delete')) {
            abort(403, 'Vous n\'avez pas la permission de supprimer les clients.');
        }

        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé avec succès.');
    }
}












