<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Client;
use App\Models\Product;
use App\Models\QuoteLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QuoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasPermission('quotes.view')) {
            abort(403, 'Vous n\'avez pas la permission de voir les devis.');
        }

        $query = Quote::with(['client', 'creator', 'payments']);

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtre par client
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // Filtre par date (de)
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        // Filtre par date (à)
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Recherche par numéro de devis
        if ($request->filled('search')) {
            $query->where('quote_number', 'like', '%' . $request->search . '%');
        }

        $quotes = $query->latest()->paginate(15)->withQueryString();
        $clients = Client::orderBy('name')->get();

        return view('quotes.index', compact('quotes', 'clients'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->hasPermission('quotes.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer des devis.');
        }

        $clients = Client::orderBy('name')->get();
        return view('quotes.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasPermission('quotes.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer des devis.');
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'valid_until' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $validated['quote_number'] = $this->generateQuoteNumber();
        $validated['status'] = 'draft';
        $validated['created_by'] = Auth::id();

        $quote = Quote::create($validated);

        return redirect()->route('quotes.edit', $quote)
            ->with('success', 'Devis créé avec succès. Ajoutez maintenant des lignes.');
    }

    public function show(Quote $quote)
    {
        $user = Auth::user();
        if (!$user->hasPermission('quotes.view')) {
            abort(403, 'Vous n\'avez pas la permission de voir les devis.');
        }

        $quote->load(['client', 'creator', 'lines.product', 'payments.creator']);
        return view('quotes.show', compact('quote'));
    }

    public function showValidation(Quote $quote)
    {
        // Vérifier que le devis est accepté ou annulé (pour permettre de re-valider un devis annulé)
        if (!in_array($quote->status, ['accepted', 'cancelled'])) {
            return redirect()->route('quotes.show', $quote)
                ->with('error', 'Seuls les devis acceptés ou annulés peuvent être validés.');
        }

        $quote->load(['client', 'lines.product']);
        return view('quotes.validate', compact('quote'));
    }

    public function edit(Quote $quote)
    {
        $user = Auth::user();
        if (!$user->hasPermission('quotes.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les devis.');
        }

        // Empêcher la modification d'un devis validé
        if ($quote->status === 'validated') {
            return redirect()->route('quotes.show', $quote)
                ->with('error', 'Un devis validé ne peut pas être modifié. Veuillez d\'abord annuler la validation.');
        }

        $quote->load(['client', 'lines.product']);
        $products = Product::orderBy('name')->get();
        $clients = Client::orderBy('name')->get();
        return view('quotes.edit', compact('quote', 'products', 'clients'));
    }

    public function update(Request $request, Quote $quote)
    {
        $user = Auth::user();
        if (!$user->hasPermission('quotes.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les devis.');
        }

        // Empêcher la modification d'un devis validé
        if ($quote->status === 'validated') {
            return redirect()->route('quotes.show', $quote)
                ->with('error', 'Un devis validé ne peut pas être modifié. Veuillez d\'abord annuler la validation.');
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'valid_until' => 'nullable|date',
            'status' => 'required|in:draft,sent,accepted,rejected,validated,cancelled',
            'notes' => 'nullable|string',
            'final_amount' => 'nullable|numeric|min:0',
        ]);

        $quote->update($validated);

        return redirect()->route('quotes.edit', $quote)
            ->with('success', 'Devis mis à jour avec succès.');
    }

    public function updateStatus(Request $request, Quote $quote)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,sent,accepted,rejected,validated,cancelled',
        ]);

        $quote->update(['status' => $validated['status']]);

        $statusLabels = [
            'draft' => 'Brouillon',
            'sent' => 'Envoyé',
            'accepted' => 'Accepté',
            'rejected' => 'Refusé',
            'validated' => 'Validé',
            'cancelled' => 'Annulé',
        ];

        return redirect()->route('quotes.edit', $quote)
            ->with('success', 'Statut changé en "' . $statusLabels[$validated['status']] . '" avec succès.');
    }

    public function validateQuote(Request $request, Quote $quote)
    {
        $user = Auth::user();
        if (!$user->hasPermission('quotes.validate')) {
            abort(403, 'Vous n\'avez pas la permission de valider les devis.');
        }

        // Vérifier que le devis est accepté ou annulé (pour permettre de re-valider un devis annulé)
        if (!in_array($quote->status, ['accepted', 'cancelled'])) {
            return redirect()->route('quotes.show', $quote)
                ->with('error', 'Seuls les devis acceptés ou annulés peuvent être validés.');
        }

        $validated = $request->validate([
            'final_amount' => 'required|numeric|min:0',
        ]);

        $quote->update([
            'final_amount' => $validated['final_amount'],
            'status' => 'validated',
        ]);

        return redirect()->route('quotes.show', $quote)
            ->with('success', 'Devis validé avec succès. Le montant final de ' . number_format($validated['final_amount'], 0, ',', ' ') . ' GNF a été enregistré.');
    }

    public function cancelQuote(Quote $quote)
    {
        $user = Auth::user();
        if (!$user->hasPermission('quotes.cancel')) {
            abort(403, 'Vous n\'avez pas la permission d\'annuler les devis.');
        }

        // Vérifier que le devis est validé
        if ($quote->status !== 'validated') {
            return redirect()->route('quotes.index')
                ->with('error', 'Seuls les devis validés peuvent être annulés.');
        }

        // Empêcher l'annulation d'un devis qui a des paiements
        if ($quote->payments()->count() > 0) {
            return redirect()->route('quotes.show', $quote)
                ->with('error', 'Un devis avec des paiements ne peut pas être annulé. Veuillez d\'abord supprimer ou rembourser les paiements.');
        }

        $quote->update([
            'status' => 'cancelled',
        ]);

        return redirect()->route('quotes.index')
            ->with('success', 'Devis annulé avec succès.');
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();

        return redirect()->route('quotes.index')
            ->with('success', 'Devis supprimé avec succès.');
    }

    public function addLine(Request $request, Quote $quote)
    {
        // Empêcher l'ajout de lignes à un devis validé
        if ($quote->status === 'validated') {
            return back()->withErrors(['error' => 'Un devis validé ne peut pas être modifié. Veuillez d\'abord annuler la validation.']);
        }

        $validated = $request->validate([
            'line_type' => 'required|in:product,transport,labor,material',
            'product_id' => 'nullable|exists:products,id',
            'description' => 'nullable|string|max:255',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'nullable|numeric|min:0',
            'price_per_m2' => 'nullable|numeric|min:0',
            'surface' => 'nullable|numeric|min:0',
            'amount' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
        ]);
        
        // Si c'est un produit, product_id est requis
        if ($validated['line_type'] === 'product' && !$request->product_id) {
            return back()->withErrors(['product_id' => 'Le produit est requis pour une ligne de type produit.']);
        }
        
        // Si c'est un produit, price_per_m2 est requis
        if ($validated['line_type'] === 'product' && !$request->price_per_m2) {
            return back()->withErrors(['price_per_m2' => 'Le prix M² est requis pour une ligne de type produit.']);
        }
        
        // Pour les autres types, description est requise
        if ($validated['line_type'] !== 'product' && !$request->description) {
            return back()->withErrors(['description' => 'La description est requise pour ce type de ligne.']);
        }
        
        // Pour les autres types, pas besoin de product_id
        if ($validated['line_type'] !== 'product') {
            $validated['product_id'] = null;
            $validated['width'] = null;
            $validated['height'] = null;
            $validated['price_per_m2'] = null;
            $validated['surface'] = null;
            
            // Définir l'unité par défaut selon le type
            if (!$validated['unit']) {
                switch ($validated['line_type']) {
                    case 'transport':
                        $validated['unit'] = 'frais';
                        break;
                    case 'labor':
                        $validated['unit'] = 'heure';
                        break;
                    case 'material':
                        $validated['unit'] = 'unité';
                        break;
                }
            }
            
            // Calculer le montant si quantité et unit_price sont fournis
            if ($validated['quantity'] && $validated['unit_price']) {
                $validated['amount'] = $validated['quantity'] * $validated['unit_price'];
            }
        } else {
            // Logique pour les produits
            // S'assurer que unit_price est null si on utilise price_per_m2
            if (isset($validated['price_per_m2']) && $validated['price_per_m2'] > 0) {
                $validated['unit_price'] = null;
            }

            // Définir l'unité à "unité" pour les produits
            $validated['unit'] = 'unité';

            // Récupérer le nom du produit comme description
            if ($request->product_id) {
                $product = Product::find($request->product_id);
                if ($product) {
                    $validated['description'] = $product->name;
                }
            }
            
            // Calculer la surface = (Largeur x Hauteur) / 10000 x Quantité
            if ($request->width && $request->height && $request->quantity) {
                $validated['surface'] = (($request->width * $request->height) / 10000) * $request->quantity;
            }
            
            // Calculer le montant = Surface x Prix M²
            if (isset($validated['surface']) && $validated['surface'] > 0 && $request->price_per_m2) {
                $validated['amount'] = $validated['surface'] * $request->price_per_m2;
            }
        }

        try {
            $quote->lines()->create($validated);

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ligne ajoutée avec succès.'
                ]);
            }

            return redirect()->route('quotes.edit', $quote)
                ->with('success', 'Ligne ajoutée avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'ajout de ligne: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de l\'ajout de la ligne: ' . $e->getMessage()
                ], 422);
            }

            return back()->withErrors(['error' => 'Une erreur est survenue lors de l\'ajout de la ligne: ' . $e->getMessage()]);
        }
    }

    public function updateAllPrices(Request $request, Quote $quote)
    {
        // Empêcher la modification des prix d'un devis validé
        if ($quote->status === 'validated') {
            return back()->withErrors(['error' => 'Un devis validé ne peut pas être modifié. Veuillez d\'abord annuler la validation.']);
        }

        $validated = $request->validate([
            'price_per_m2' => 'required|numeric|min:0',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $pricePerM2 = $validated['price_per_m2'];
        $productId = $validated['product_id'] ?? null;

        // Filtrer les lignes selon le produit sélectionné
        $linesToUpdate = $quote->lines;
        if ($productId) {
            $linesToUpdate = $linesToUpdate->where('product_id', $productId);
        }

        $updatedCount = 0;
        // Mettre à jour les lignes filtrées
        foreach ($linesToUpdate as $line) {
            if ($line->surface && $line->surface > 0) {
                // Recalculer le montant = Surface x Prix M²
                // (La quantité est déjà incluse dans la surface)
                $line->price_per_m2 = $pricePerM2;
                $line->amount = $line->surface * $pricePerM2;
                $line->save();
                $updatedCount++;
            }
        }

        $message = $productId 
            ? "Prix M² mis à jour pour {$updatedCount} ligne(s) du produit sélectionné. Les montants ont été recalculés."
            : "Prix M² mis à jour pour {$updatedCount} ligne(s). Les montants ont été recalculés.";

        return redirect()->route('quotes.edit', $quote)
            ->with('success', $message);
    }

    public function editLine(Quote $quote, QuoteLine $line)
    {
        if ($line->quote_id !== $quote->id) {
            abort(404);
        }

        return response()->json([
            'success' => true,
            'line' => [
                'id' => $line->id,
                'line_type' => $line->line_type ?? 'product',
                'product_id' => $line->product_id,
                'description' => $line->description,
                'width' => $line->width,
                'height' => $line->height,
                'quantity' => $line->quantity,
                'unit_price' => $line->unit_price,
                'price_per_m2' => $line->price_per_m2,
                'surface' => $line->surface,
                'amount' => $line->amount,
                'unit' => $line->unit,
            ]
        ]);
    }

    public function updateLine(Request $request, Quote $quote, QuoteLine $line)
    {
        if ($line->quote_id !== $quote->id) {
            abort(404);
        }

        // Empêcher la modification de lignes d'un devis validé
        if ($quote->status === 'validated') {
            return back()->withErrors(['error' => 'Un devis validé ne peut pas être modifié. Veuillez d\'abord annuler la validation.']);
        }

        $validated = $request->validate([
            'line_type' => 'required|in:product,transport,labor,material',
            'product_id' => 'nullable|exists:products,id',
            'description' => 'required|string|max:255',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'nullable|numeric|min:0',
            'price_per_m2' => 'nullable|numeric|min:0',
            'surface' => 'nullable|numeric|min:0',
            'amount' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
        ]);
        
        // Si c'est un produit, product_id est requis
        if ($validated['line_type'] === 'product' && !$request->product_id) {
            return back()->withErrors(['product_id' => 'Le produit est requis pour une ligne de type produit.']);
        }
        
        // Pour les autres types, pas besoin de product_id
        if ($validated['line_type'] !== 'product') {
            $validated['product_id'] = null;
            $validated['width'] = null;
            $validated['height'] = null;
            $validated['price_per_m2'] = null;
            $validated['surface'] = null;
            
            // Définir l'unité par défaut selon le type
            if (!$validated['unit']) {
                switch ($validated['line_type']) {
                    case 'transport':
                        $validated['unit'] = 'frais';
                        break;
                    case 'labor':
                        $validated['unit'] = 'heure';
                        break;
                    case 'material':
                        $validated['unit'] = 'unité';
                        break;
                }
            }
            
            // Calculer le montant si quantité et unit_price sont fournis
            if ($validated['quantity'] && $validated['unit_price']) {
                $validated['amount'] = $validated['quantity'] * $validated['unit_price'];
            }
        } else {
            // Logique pour les produits
            // S'assurer que unit_price est null si on utilise price_per_m2
            if (isset($validated['price_per_m2']) && $validated['price_per_m2'] > 0) {
                $validated['unit_price'] = null;
            }

            // Définir l'unité à "unité" pour les produits
            $validated['unit'] = 'unité';

            // Récupérer le nom du produit comme description
            if ($request->product_id) {
                $product = Product::find($request->product_id);
                if ($product) {
                    $validated['description'] = $product->name;
                }
            }
            
            // Calculer la surface = (Largeur x Hauteur) / 10000 x Quantité
            if ($request->width && $request->height && $request->quantity) {
                $validated['surface'] = (($request->width * $request->height) / 10000) * $request->quantity;
            }
            
            // Calculer le montant = Surface x Prix M²
            if (isset($validated['surface']) && $validated['surface'] > 0 && $request->price_per_m2) {
                $validated['amount'] = $validated['surface'] * $request->price_per_m2;
            }
        }

        $line->update($validated);

        return redirect()->route('quotes.edit', $quote)
            ->with('success', 'Ligne modifiée avec succès.');
    }

    public function removeLine(Quote $quote, QuoteLine $line)
    {
        if ($line->quote_id !== $quote->id) {
            abort(404);
        }

        // Empêcher la suppression de lignes d'un devis validé
        if ($quote->status === 'validated') {
            return back()->withErrors(['error' => 'Un devis validé ne peut pas être modifié. Veuillez d\'abord annuler la validation.']);
        }

        $line->delete();

        return redirect()->route('quotes.edit', $quote)
            ->with('success', 'Ligne supprimée avec succès.');
    }

    public function duplicateLine(Quote $quote, QuoteLine $line)
    {
        if ($line->quote_id !== $quote->id) {
            abort(404);
        }

        // Empêcher la duplication de lignes d'un devis validé
        if ($quote->status === 'validated') {
            return back()->withErrors(['error' => 'Un devis validé ne peut pas être modifié. Veuillez d\'abord annuler la validation.']);
        }

        // Créer une copie de la ligne
        $newLine = $line->replicate();
        $newLine->save();

        return redirect()->route('quotes.edit', $quote)
            ->with('success', 'Ligne dupliquée avec succès.');
    }

    public function print(Quote $quote)
    {
        $quote->load(['client', 'lines.product']);
        $settings = \App\Models\Setting::getSettings();
        
        if (request()->has('pdf')) {
            try {
                if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('quotes.print', compact('quote', 'settings'));
                    $pdf->setPaper('a4', 'portrait');
                    $pdf->setOption('enable-local-file-access', true);
                    $pdf->setOption('isHtml5ParserEnabled', true);
                    $pdf->setOption('isRemoteEnabled', true);
                    $pdf->setOption('defaultFont', 'DejaVu Sans');
                    $pdf->setOption('enable_php', true);
                    
                    if (request()->has('download')) {
                        return $pdf->download('devis-' . $quote->quote_number . '.pdf');
                    }
                    
                    return $pdf->stream('devis-' . $quote->quote_number . '.pdf');
                }
            } catch (\Exception $e) {
                \Log::error('Erreur DOMPDF: ' . $e->getMessage());
            }
        }
        
        return view('quotes.print', compact('quote', 'settings'));
    }

    public function calculateMaterials(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasPermission('quotes.calculate-materials')) {
            abort(403, 'Vous n\'avez pas la permission de calculer les matériaux.');
        }

        $quotes = Quote::orderBy('quote_number', 'desc')->get();
        $selectedQuote = null;
        $materials = null;

        if ($request->filled('quote_id')) {
            $selectedQuote = Quote::with(['client', 'lines.product'])->findOrFail($request->quote_id);
            
            // Calculer les matériaux pour toutes les fenêtres du devis
            $materials = $this->calculateMaterialsForQuote($selectedQuote);
        }

        return view('quotes.calculate-materials', compact('quotes', 'selectedQuote', 'materials'));
    }

    public function printMaterials(Quote $quote)
    {
        $quote->load(['client', 'lines.product']);
        $settings = \App\Models\Setting::getSettings();
        $materials = $this->calculateMaterialsForQuote($quote);
        
        if (request()->has('pdf')) {
            try {
                if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('quotes.print-materials', compact('quote', 'settings', 'materials'));
                    $pdf->setPaper('a4', 'portrait');
                    $pdf->setOption('enable-local-file-access', true);
                    $pdf->setOption('isHtml5ParserEnabled', true);
                    $pdf->setOption('isRemoteEnabled', true);
                    $pdf->setOption('defaultFont', 'DejaVu Sans');
                    $pdf->setOption('enable_php', true);
                    
                    if (request()->has('download')) {
                        return $pdf->download('materiaux-' . $quote->quote_number . '.pdf');
                    }
                    
                    return $pdf->stream('materiaux-' . $quote->quote_number . '.pdf');
                }
            } catch (\Exception $e) {
                \Log::error('Erreur DOMPDF: ' . $e->getMessage());
            }
        }
        
        return view('quotes.print-materials', compact('quote', 'settings', 'materials'));
    }

    private function calculateMaterialsForQuote(Quote $quote)
    {
        $totalCadre = 0;
        $totalVento = 0;
        $totalSikane = 0;
        $totalMoustiquaire = 0;
        $totalCadrePorte = 0;
        $totalBattantPorte = 0;
        $totalDivision = 0;
        $fenetresDetails = [];
        $portesDetails = [];

        // Parcourir toutes les lignes du devis
        foreach ($quote->lines as $line) {
            // Vérifier si c'est une ligne de produit
            if ($line->line_type === 'product') {
                // Vérifier que les dimensions sont présentes et > 0
                if ($line->width && $line->width > 0 && $line->height && $line->height > 0) {
                    $productName = '';
                    if ($line->product) {
                        $productName = strtolower($line->product->name);
                    }
                    $description = strtolower($line->description ?? '');
                    
                    // Les dimensions sont en cm, on les utilise directement
                    $largeur = $line->width;
                    $hauteur = $line->height;
                    $nombrePortes = (int) $line->quantity;
                    
                    // Vérifier si c'est une porte
                    $porteKeywords = [
                        'porte 1 battant', 'porte 1 battants', 'porte un battant',
                        'porte 2 battants', 'porte 2 battant', 'porte deux battants',
                        'porte', 'portes',
                    ];
                    
                    $isPorte = false;
                    $porteType = null; // '1_battant' ou '2_battants'
                    
                    foreach ($porteKeywords as $keyword) {
                        if (strpos($productName, $keyword) !== false || strpos($description, $keyword) !== false) {
                            $isPorte = true;
                            // Déterminer le type de porte
                            if (strpos($productName, '2 battants') !== false || strpos($description, '2 battants') !== false ||
                                strpos($productName, '2 battant') !== false || strpos($description, '2 battant') !== false ||
                                strpos($productName, 'deux battants') !== false || strpos($description, 'deux battants') !== false) {
                                $porteType = '2_battants';
                            } else {
                                $porteType = '1_battant';
                            }
                            break;
                        }
                    }
                    
                    // Calculs pour les portes
                    if ($isPorte) {
                        // Cadre porte = ((Largeur+Hauteur)*2)+40)/580 (identique pour les deux types)
                        $cadrePorteUnitaire = ((($largeur + $hauteur) * 2) + 40) / 580;
                        
                        // Battant porte selon le type
                        if ($porteType === '1_battant') {
                            // PORTE 1 BATTANT: Battant porte = ((Largeur+Hauteur)*2)/580
                            $battantPorteUnitaire = (($largeur + $hauteur) * 2) / 580;
                        } else {
                            // PORTE 2 BATTANTS: Battant porte = (((Largeur*2)+(Hauteur*4))-32)/580
                            $battantPorteUnitaire = ((($largeur * 2) + ($hauteur * 4)) - 32) / 580;
                        }
                        
                        // Division = (largeur*Nombre porte trouvé)/580
                        $division = ($largeur * $nombrePortes) / 580;
                        
                        // Calculer les totaux pour cette ligne (multiplier par nombre de portes)
                        $cadrePorteLigne = $cadrePorteUnitaire * $nombrePortes;
                        $battantPorteLigne = $battantPorteUnitaire * $nombrePortes;
                        
                        // Ajouter aux totaux globaux
                        $totalCadrePorte += $cadrePorteLigne;
                        $totalBattantPorte += $battantPorteLigne;
                        $totalDivision += $division;
                        
                        // Stocker les détails
                        $portesDetails[] = [
                            'line' => $line,
                            'cadre_porte' => $cadrePorteLigne,
                            'battant_porte' => $battantPorteLigne,
                            'division' => $division,
                            'nombre_portes' => $nombrePortes,
                            'type' => $porteType,
                        ];
                    } else {
                        // Vérifier si c'est une fenêtre - chercher dans le nom du produit ou la description
                        $fenetreKeywords = [
                            'fenêtre', 'fenetre', 'fenêtres', 'fenetres',
                            'fentres', 'fentre', 'fentr', // Variantes sans accent
                            'fenetr', 'fenet', 'fent', // Autres variantes
                        ];
                        
                        $isFenetre = false;
                        foreach ($fenetreKeywords as $keyword) {
                            if (strpos($productName, $keyword) !== false || strpos($description, $keyword) !== false) {
                                $isFenetre = true;
                                break;
                            }
                        }
                        
                        if ($isFenetre) {
                            // Utiliser la quantité comme nombre de fenêtres
                            $nombreFenetres = (int) $line->quantity;
                            
                            // Calcul CADRE pour UNE fenêtre = ((largeur + hauteur) * 2 + 50) / 580
                            // Formule: ((largeur + hauteur) * 2 + 50) / 580
                            // Exemple: ((200 + 110) * 2 + 50) / 580 = ((310) * 2 + 50) / 580 = (620 + 50) / 580 = 670 / 580 = 1,155
                            $cadreUnitaire = (($largeur + $hauteur) * 2 + 50) / 580;
                            
                            // Calcul VENTO pour UNE fenêtre = nombre_cadre * 1.3
                            $ventoUnitaire = $cadreUnitaire * 1.3;
                            
                            // Calcul SIKANE = (hauteur * 2 * nombre_total_fenetres) / 580
                            // Note: SIKANE est déjà calculé avec le nombre total de fenêtres
                            $sikane = ($hauteur * 2 * $nombreFenetres) / 580;
                            
                            // Calcul MOUSTIQUAIRE pour UNE fenêtre = nombre_vento / 2
                            $moustiquaireUnitaire = $ventoUnitaire / 2;
                            
                            // Calculer les totaux pour cette ligne (multiplier par nombre de fenêtres)
                            $cadreLigne = $cadreUnitaire * $nombreFenetres;
                            $ventoLigne = $ventoUnitaire * $nombreFenetres;
                            $moustiquaireLigne = $moustiquaireUnitaire * $nombreFenetres;
                            
                            // Ajouter aux totaux globaux
                            $totalCadre += $cadreLigne;
                            $totalVento += $ventoLigne;
                            $totalSikane += $sikane;
                            $totalMoustiquaire += $moustiquaireLigne;
                            
                            // Stocker les détails
                            $fenetresDetails[] = [
                                'line' => $line,
                                'cadre' => $cadreLigne,
                                'vento' => $ventoLigne,
                                'sikane' => $sikane,
                                'moustiquaire' => $moustiquaireLigne,
                                'nombre_fenetres' => $nombreFenetres,
                            ];
                        }
                    }
                }
            }
        }

        // Informations de debug
        $debugInfo = [
            'total_lines' => $quote->lines->count(),
            'product_lines' => $quote->lines->where('line_type', 'product')->count(),
            'lines_checked' => [],
        ];
        
        // Ajouter des infos sur chaque ligne pour le debug
        foreach ($quote->lines as $line) {
            if ($line->line_type === 'product') {
                $productName = $line->product ? strtolower($line->product->name) : '';
                $description = strtolower($line->description ?? '');
                
                $fenetreKeywords = [
                    'fenêtre', 'fenetre', 'fenêtres', 'fenetres',
                    'fentres', 'fentre', 'fentr',
                    'fenetr', 'fenet', 'fent',
                ];
                
                $porteKeywords = [
                    'porte 1 battant', 'porte 1 battants', 'porte un battant',
                    'porte 2 battants', 'porte 2 battant', 'porte deux battants',
                    'porte', 'portes',
                ];
                
                $hasFenetre = false;
                foreach ($fenetreKeywords as $keyword) {
                    if (strpos($productName, $keyword) !== false || strpos($description, $keyword) !== false) {
                        $hasFenetre = true;
                        break;
                    }
                }
                
                $hasPorte = false;
                foreach ($porteKeywords as $keyword) {
                    if (strpos($productName, $keyword) !== false || strpos($description, $keyword) !== false) {
                        $hasPorte = true;
                        break;
                    }
                }
                
                $debugInfo['lines_checked'][] = [
                    'id' => $line->id,
                    'product_name' => $line->product ? $line->product->name : 'N/A',
                    'description' => $line->description,
                    'has_fenetre' => $hasFenetre,
                    'has_porte' => $hasPorte,
                    'has_dimensions' => ($line->width && $line->width > 0 && $line->height && $line->height > 0),
                    'width' => $line->width,
                    'height' => $line->height,
                ];
            }
        }

        return [
            'total_cadre' => $totalCadre,
            'total_vento' => $totalVento,
            'total_sikane' => $totalSikane,
            'total_moustiquaire' => $totalMoustiquaire,
            'total_cadre_porte' => $totalCadrePorte,
            'total_battant_porte' => $totalBattantPorte,
            'total_division' => $totalDivision,
            'fenetres_details' => $fenetresDetails,
            'portes_details' => $portesDetails,
            'debug' => $debugInfo,
        ];
    }

    private function generateQuoteNumber(): string
    {
        $year = Carbon::now()->year;
        $lastQuote = Quote::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastQuote ? (int) substr($lastQuote->quote_number, -4) + 1 : 1;

        return 'DEV-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}

