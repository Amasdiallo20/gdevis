<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user->hasPermission('materials.view')) {
            abort(403, 'Vous n\'avez pas la permission de voir les matériaux.');
        }

        $materiaux = Material::orderBy('nom')->get();
        $settings = \App\Models\Setting::getSettings();

        return view('materials.index', compact('materiaux', 'settings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        if (!$user->hasPermission('materials.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer des matériaux.');
        }

        $settings = \App\Models\Setting::getSettings();
        return view('materials.create', compact('settings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasPermission('materials.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer des matériaux.');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:materiaux,nom',
            'prix_unitaire' => 'required|numeric|min:0',
            'unite' => 'required|string|max:50',
        ]);

        $validated['date_mise_a_jour'] = now();

        Material::create($validated);
        
        // Clear cache après création
        Material::clearCache();

        return redirect()->route('materials.index')
            ->with('success', 'Matériau créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        $user = Auth::user();
        if (!$user->hasPermission('materials.view')) {
            abort(403, 'Vous n\'avez pas la permission de voir les matériaux.');
        }

        $settings = \App\Models\Setting::getSettings();
        return view('materials.show', compact('material', 'settings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material)
    {
        $user = Auth::user();
        if (!$user->hasPermission('materials.update')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les matériaux.');
        }

        $settings = \App\Models\Setting::getSettings();
        return view('materials.edit', compact('material', 'settings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        $user = Auth::user();
        if (!$user->hasPermission('materials.update')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les matériaux.');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:materiaux,nom,' . $material->id,
            'prix_unitaire' => 'required|numeric|min:0',
            'unite' => 'required|string|max:50',
        ]);

        $validated['date_mise_a_jour'] = now();

        $material->update($validated);
        
        // Clear cache après mise à jour
        Material::clearCache();

        return redirect()->route('materials.index')
            ->with('success', 'Matériau mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        $user = Auth::user();
        if (!$user->hasPermission('materials.delete')) {
            abort(403, 'Vous n\'avez pas la permission de supprimer les matériaux.');
        }

        $material->delete();
        
        // Clear cache après suppression
        Material::clearCache();

        return redirect()->route('materials.index')
            ->with('success', 'Matériau supprimé avec succès.');
    }
}
