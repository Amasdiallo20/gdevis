<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasPermission('products.view')) {
            abort(403, 'Vous n\'avez pas la permission de voir les produits.');
        }

        $query = Product::query();

        // Recherche par nom
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->hasPermission('products.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer des produits.');
        }

        return view('products.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasPermission('products.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer des produits.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $user = Auth::user();
        if (!$user->hasPermission('products.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les produits.');
        }

        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $user = Auth::user();
        if (!$user->hasPermission('products.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les produits.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy(Product $product)
    {
        $user = Auth::user();
        if (!$user->hasPermission('products.delete')) {
            abort(403, 'Vous n\'avez pas la permission de supprimer les produits.');
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produit supprimé avec succès.');
    }
}

