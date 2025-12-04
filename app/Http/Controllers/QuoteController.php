<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Client;
use App\Models\Product;
use App\Models\QuoteLine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::with(['client', 'payments'])->latest()->paginate(15);
        return view('quotes.index', compact('quotes'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        return view('quotes.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'valid_until' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $validated['quote_number'] = $this->generateQuoteNumber();
        $validated['status'] = 'draft';

        $quote = Quote::create($validated);

        return redirect()->route('quotes.edit', $quote)
            ->with('success', 'Devis créé avec succès. Ajoutez maintenant des lignes.');
    }

    public function show(Quote $quote)
    {
        $quote->load(['client', 'lines.product', 'payments']);
        return view('quotes.show', compact('quote'));
    }

    public function showValidation(Quote $quote)
    {
        // Vérifier que le devis est accepté
        if ($quote->status !== 'accepted') {
            return redirect()->route('quotes.show', $quote)
                ->with('error', 'Seuls les devis acceptés peuvent être validés.');
        }

        $quote->load(['client', 'lines.product']);
        return view('quotes.validate', compact('quote'));
    }

    public function edit(Quote $quote)
    {
        $quote->load(['client', 'lines.product']);
        $products = Product::orderBy('name')->get();
        $clients = Client::orderBy('name')->get();
        return view('quotes.edit', compact('quote', 'products', 'clients'));
    }

    public function update(Request $request, Quote $quote)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'valid_until' => 'nullable|date',
            'status' => 'required|in:draft,sent,accepted,rejected,validated',
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
            'status' => 'required|in:draft,sent,accepted,rejected,validated',
        ]);

        $quote->update(['status' => $validated['status']]);

        $statusLabels = [
            'draft' => 'Brouillon',
            'sent' => 'Envoyé',
            'accepted' => 'Accepté',
            'rejected' => 'Refusé',
            'validated' => 'Validé',
        ];

        return redirect()->route('quotes.edit', $quote)
            ->with('success', 'Statut changé en "' . $statusLabels[$validated['status']] . '" avec succès.');
    }

    public function validateQuote(Request $request, Quote $quote)
    {
        // Vérifier que le devis est accepté
        if ($quote->status !== 'accepted') {
            return redirect()->route('quotes.show', $quote)
                ->with('error', 'Seuls les devis acceptés peuvent être validés.');
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

    public function destroy(Quote $quote)
    {
        $quote->delete();

        return redirect()->route('quotes.index')
            ->with('success', 'Devis supprimé avec succès.');
    }

    public function addLine(Request $request, Quote $quote)
    {
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
        $validated = $request->validate([
            'price_per_m2' => 'required|numeric|min:0',
        ]);

        $pricePerM2 = $validated['price_per_m2'];

        // Mettre à jour toutes les lignes du devis
        foreach ($quote->lines as $line) {
            if ($line->surface && $line->surface > 0) {
                // Recalculer le montant = Surface x Prix M²
                // (La quantité est déjà incluse dans la surface)
                $line->price_per_m2 = $pricePerM2;
                $line->amount = $line->surface * $pricePerM2;
                $line->save();
            }
        }

        return redirect()->route('quotes.edit', $quote)
            ->with('success', 'Prix M² mis à jour pour toutes les lignes. Les montants ont été recalculés.');
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

        $line->delete();

        return redirect()->route('quotes.edit', $quote)
            ->with('success', 'Ligne supprimée avec succès.');
    }

    public function duplicateLine(Quote $quote, QuoteLine $line)
    {
        if ($line->quote_id !== $quote->id) {
            abort(404);
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

