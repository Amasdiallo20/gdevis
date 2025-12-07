<?php

namespace App\Http\Controllers;

use App\Models\Modele;
use App\Services\ImageOptimizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ModeleController extends Controller
{
    protected $imageOptimizationService;

    public function __construct(ImageOptimizationService $imageOptimizationService)
    {
        $this->imageOptimizationService = $imageOptimizationService;
        // Les routes publiques (index, show, addToQuote) sont gérées dans routes/web.php
        // Les routes protégées (create, store, edit, update, destroy) sont dans le middleware auth
    }

    /**
     * Affiche le catalogue de modèles
     */
    public function index(Request $request)
    {
        $query = Modele::query();

        // Filtre par catégorie
        if ($request->filled('categorie')) {
            $query->byCategory($request->categorie);
        }

        // Recherche
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Par défaut, afficher seulement les modèles actifs pour les visiteurs
        // Les admins peuvent voir tous les modèles
        if (!Auth::check() || !Auth::user()->hasPermission('modeles.view')) {
            $query->active();
        }

        // Trier par nom
        $query->orderBy('nom');

        $modeles = $query->paginate(12);
        $categories = Modele::getCategories();
        $settings = \App\Models\Setting::getSettings();

        return view('modeles.index', compact('modeles', 'categories', 'settings'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $user = Auth::user();
        if (!$user || !$user->hasPermission('modeles.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer des modèles.');
        }
        
        $categories = Modele::getCategories();
        $settings = \App\Models\Setting::getSettings();
        
        return view('modeles.create', compact('categories', 'settings'));
    }

    /**
     * Enregistre un nouveau modèle
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->hasPermission('modeles.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer des modèles.');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'categorie' => 'required|string|in:' . implode(',', array_keys(Modele::getCategories())),
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'prix_indicatif' => 'nullable|numeric|min:0',
            'statut' => 'required|in:actif,inactif',
        ]);

        // Gérer l'upload et l'optimisation de l'image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = Str::slug($validated['nom']) . '-' . time();
            
            // Optimiser et redimensionner l'image
            $paths = $this->imageOptimizationService->optimizeAndResize($image, 'modeles', $filename);
            
            // Stocker le chemin de l'image medium (par défaut) ou original
            $validated['image'] = $paths['medium'] ?? $paths['original'] ?? null;
        }

        Modele::create($validated);

        return redirect()->route('modeles.index')
            ->with('success', 'Modèle créé avec succès.');
    }

    /**
     * Affiche les détails d'un modèle
     */
    public function show(Modele $modele)
    {
        // Si le modèle est inactif, vérifier les permissions
        if (!$modele->isActive() && (!Auth::check() || !Auth::user()->hasPermission('modeles.view'))) {
            abort(404);
        }

        $settings = \App\Models\Setting::getSettings();
        $categories = Modele::getCategories();
        $relatedModeles = Modele::where('categorie', $modele->categorie)
            ->where('id', '!=', $modele->id)
            ->active()
            ->limit(4)
            ->get();

        return view('modeles.show', compact('modele', 'settings', 'relatedModeles', 'categories'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(Modele $modele)
    {
        $user = Auth::user();
        if (!$user || !$user->hasPermission('modeles.update')) {
            abort(403, 'Vous n\'avez pas la permission de modifier des modèles.');
        }

        $categories = Modele::getCategories();
        $settings = \App\Models\Setting::getSettings();

        return view('modeles.edit', compact('modele', 'categories', 'settings'));
    }

    /**
     * Met à jour un modèle
     */
    public function update(Request $request, Modele $modele)
    {
        $user = Auth::user();
        if (!$user || !$user->hasPermission('modeles.update')) {
            abort(403, 'Vous n\'avez pas la permission de modifier des modèles.');
        }

        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'categorie' => 'required|string|in:' . implode(',', array_keys(Modele::getCategories())),
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'prix_indicatif' => 'nullable|numeric|min:0',
                'statut' => 'required|in:actif,inactif',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('modeles.edit', $modele)
                ->withErrors($e->errors())
                ->withInput();
        }

        // Gérer l'upload de la nouvelle image
        if ($request->hasFile('image')) {
            // Supprimer toutes les versions de l'ancienne image
            if ($modele->image) {
                $this->imageOptimizationService->deleteImageVersions($modele->image);
            }

            $image = $request->file('image');
            $filename = Str::slug($validated['nom']) . '-' . time();
            
            // Optimiser et redimensionner la nouvelle image
            $paths = $this->imageOptimizationService->optimizeAndResize($image, 'modeles', $filename);
            
            // Stocker le chemin de l'image medium (par défaut) ou original
            $validated['image'] = $paths['medium'] ?? $paths['original'] ?? null;
        } else {
            // Garder l'image existante si aucune nouvelle image n'est fournie
            $validated['image'] = $modele->image;
        }

        $modele->update($validated);

        return redirect()->route('modeles.show', $modele)
            ->with('success', 'Modèle mis à jour avec succès.');
    }

    /**
     * Supprime un modèle
     */
    public function destroy(Modele $modele)
    {
        $user = Auth::user();
        if (!$user || !$user->hasPermission('modeles.delete')) {
            abort(403, 'Vous n\'avez pas la permission de supprimer des modèles.');
        }

        // Supprimer toutes les versions de l'image
        if ($modele->image) {
            $this->imageOptimizationService->deleteImageVersions($modele->image);
        }

        $modele->delete();

        return redirect()->route('modeles.index')
            ->with('success', 'Modèle supprimé avec succès.');
    }

    /**
     * Supprime l'image d'un modèle
     */
    public function deleteImage(Modele $modele)
    {
        $user = Auth::user();
        if (!$user || !$user->hasPermission('modeles.update')) {
            abort(403, 'Vous n\'avez pas la permission de modifier des modèles.');
        }

        // Supprimer toutes les versions de l'image
        if ($modele->image) {
            $this->imageOptimizationService->deleteImageVersions($modele->image);
            $modele->update(['image' => null]);
            
            return redirect()->route('modeles.edit', $modele)
                ->with('success', 'Image supprimée avec succès.');
        }

        return redirect()->route('modeles.edit', $modele)
            ->with('error', 'Aucune image à supprimer.');
    }

    /**
     * Toggle le statut actif/inactif
     */
    public function toggleStatus(Modele $modele)
    {
        $user = Auth::user();
        if (!$user || !$user->hasPermission('modeles.update')) {
            abort(403, 'Vous n\'avez pas la permission de modifier des modèles.');
        }

        $modele->statut = $modele->statut === 'actif' ? 'inactif' : 'actif';
        $modele->save();

        return back()->with('success', 'Statut du modèle mis à jour.');
    }

    /**
     * Ajoute un modèle au devis (associe le modèle au devis)
     */
    public function addToQuote(Request $request, Modele $modele)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour ajouter un modèle au devis.');
        }

        // Si un quote_id est fourni, associer le modèle à ce devis
        if ($request->filled('quote_id')) {
            $quote = \App\Models\Quote::findOrFail($request->quote_id);
            
            // Vérifier les permissions
            if (!$user->hasPermission('quotes.edit')) {
                abort(403, 'Vous n\'avez pas la permission de modifier des devis.');
            }
            
            // Vérifier que le devis n'est pas validé
            if ($quote->status === 'validated') {
                return back()->withErrors(['error' => 'Un devis validé ne peut pas être modifié.']);
            }

            // Associer le modèle au devis
            $quote->update(['model_id' => $modele->id]);

            return redirect()->route('quotes.show', $quote)
                ->with('success', 'Modèle "' . $modele->nom . '" associé au devis avec succès.');
        }

        // Sinon, rediriger vers la création de devis avec le modèle pré-sélectionné
        return redirect()->route('quotes.create')
            ->with('modele_id', $modele->id)
            ->with('modele_data', [
                'nom' => $modele->nom,
                'categorie' => $modele->categorie,
                'description' => $modele->description,
                'prix_indicatif' => $modele->prix_indicatif,
            ]);
    }

}
