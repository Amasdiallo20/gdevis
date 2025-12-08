<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('quote.client');

        // Filtre par devis
        if ($request->filled('quote_id')) {
            $query->where('quote_id', $request->quote_id);
        }

        // Filtre par client (via le devis)
        if ($request->filled('client_id')) {
            $query->whereHas('quote', function($q) use ($request) {
                $q->where('client_id', $request->client_id);
            });
        }

        // Filtre par date (de)
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        // Filtre par date (à)
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        // Filtre par méthode de paiement
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Recherche par référence ou numéro de devis
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', '%' . $search . '%')
                  ->orWhereHas('quote', function($quoteQuery) use ($search) {
                      $quoteQuery->where('quote_number', 'like', '%' . $search . '%');
                  });
            });
        }

        $payments = $query->latest('payment_date')->paginate(15)->withQueryString();
        
        // Calculer le montant total restant non payé
        $quotesWithPayments = Quote::whereIn('status', ['accepted', 'validated'])
            ->with(['payments', 'lines'])
            ->get();
        
        $totalRemainingAmount = $quotesWithPayments->sum(function ($quote) {
            return $quote->remaining_amount;
        });

        // Pour les filtres
        $quotes = Quote::whereIn('status', ['accepted', 'validated'])->orderBy('quote_number')->get();
        $clients = \App\Models\Client::orderBy('name')->get();
        
        return view('payments.index', compact('payments', 'totalRemainingAmount', 'quotes', 'clients'));
    }

    public function create(Quote $quote)
    {
        // Vérifier que le devis est accepté ou validé
        if (!in_array($quote->status, ['accepted', 'validated'])) {
            return redirect()->route('quotes.show', $quote)
                ->with('error', 'Seuls les devis acceptés ou validés peuvent avoir des paiements.');
        }

        // Empêcher l'ajout de paiements si le devis est déjà soldé
        if ($quote->is_fully_paid) {
            return redirect()->route('quotes.show', $quote)
                ->with('error', 'Ce devis est déjà totalement payé.');
        }

        return view('payments.create', compact('quote'));
    }

    public function store(Request $request, Quote $quote)
    {
        // Vérifier que le devis est accepté ou validé
        if (!in_array($quote->status, ['accepted', 'validated'])) {
            return back()->withErrors(['error' => 'Seuls les devis acceptés ou validés peuvent avoir des paiements.']);
        }

        // Bloquer tout nouveau paiement si le devis est déjà soldé
        if ($quote->is_fully_paid) {
            return redirect()->route('quotes.show', $quote)
                ->with('error', 'Ce devis est déjà totalement payé.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $quote->remaining_amount,
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,check,mobile_money,other',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ], [
            'amount.max' => 'Le montant ne peut pas dépasser le solde restant de ' . number_format($quote->remaining_amount, 2, ',', ' ') . ' GNF.',
        ]);

        $validated['created_by'] = Auth::id();
        $payment = $quote->payments()->create($validated);

        return redirect()->route('quotes.show', $quote)
            ->with('success', 'Paiement enregistré avec succès.');
    }

    public function show(Payment $payment)
    {
        $payment->load('quote.client');
        return view('payments.show', compact('payment'));
    }

    public function edit(Quote $quote, Payment $payment)
    {
        if ($payment->quote_id !== $quote->id) {
            abort(404);
        }

        return view('payments.edit', compact('quote', 'payment'));
    }

    public function update(Request $request, Quote $quote, Payment $payment)
    {
        if ($payment->quote_id !== $quote->id) {
            abort(404);
        }

        // Calculer le montant maximum en utilisant le montant total (qui inclut le montant final si validé)
        $paidWithoutCurrent = $quote->paid_amount - $payment->amount;
        $maxAmount = max(0, $quote->total - $paidWithoutCurrent);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $maxAmount,
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,check,mobile_money,other',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ], [
            'amount.max' => 'Le montant ne peut pas dépasser le solde restant de ' . number_format($maxAmount, 2, ',', ' ') . ' GNF.',
        ]);

        $payment->update($validated);

        return redirect()->route('quotes.show', $quote)
            ->with('success', 'Paiement modifié avec succès.');
    }

    public function destroy(Quote $quote, Payment $payment)
    {
        if ($payment->quote_id !== $quote->id) {
            abort(404);
        }

        $payment->delete();

        return redirect()->route('quotes.show', $quote)
            ->with('success', 'Paiement supprimé avec succès.');
    }

    public function pendingQuotes(Request $request)
    {
        // Récupérer tous les devis acceptés ou validés qui ont encore un solde à payer
        $quotes = Quote::whereIn('status', ['accepted', 'validated'])
            ->with(['client', 'payments', 'lines']);

        // Filtre par client
        if ($request->filled('client_id')) {
            $quotes->where('client_id', $request->client_id);
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $quotes->where('status', $request->status);
        }

        // Filtre par date (de)
        if ($request->filled('date_from')) {
            $quotes->whereDate('date', '>=', $request->date_from);
        }

        // Filtre par date (à)
        if ($request->filled('date_to')) {
            $quotes->whereDate('date', '<=', $request->date_to);
        }

        // Recherche par numéro de devis
        if ($request->filled('search')) {
            $quotes->where('quote_number', 'like', '%' . $request->search . '%');
        }

        $quotes = $quotes->get()
            ->filter(function ($quote) {
                return $quote->remaining_amount > 0;
            })
            ->sortByDesc('date')
            ->values();
        
        // Calculer le montant total restant pour tous les devis affichés
        $totalRemainingAmount = $quotes->sum(function ($quote) {
            return $quote->remaining_amount;
        });
        
        // Calculer le montant non payé uniquement pour les devis validés (exactement comme le dashboard)
        $validatedQuotes = Quote::where('status', 'validated')
            ->with(['lines', 'payments'])
            ->get();
        
        $totalValidatedQuotesAmount = $validatedQuotes->sum(function($quote) {
            return $quote->final_amount ?? $quote->subtotal;
        });
        
        $totalPaidAmountForValidated = $validatedQuotes->sum(function($quote) {
            return $quote->paid_amount;
        });
        
        $totalRemainingAmountForValidated = max(0, $totalValidatedQuotesAmount - $totalPaidAmountForValidated);

        // Pour les filtres
        $clients = \App\Models\Client::orderBy('name')->get();
        
        return view('payments.pending-quotes', compact(
            'quotes', 
            'totalRemainingAmount', 
            'totalRemainingAmountForValidated',
            'clients'
        ));
    }

    public function print(Payment $payment)
    {
        $payment->load(['quote.client']);
        $settings = \App\Models\Setting::getSettings();
        
        if (request()->has('pdf')) {
            try {
                if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payments.print', compact('payment', 'settings'));
                    $pdf->setPaper('a4', 'portrait');
                    $pdf->setOption('enable-local-file-access', true);
                    $pdf->setOption('isHtml5ParserEnabled', true);
                    $pdf->setOption('isRemoteEnabled', true);
                    $pdf->setOption('defaultFont', 'DejaVu Sans');
                    $pdf->setOption('enable_php', true);
                    
                    if (request()->has('download')) {
                        return $pdf->download('recu-paiement-' . $payment->id . '.pdf');
                    }
                    
                    return $pdf->stream('recu-paiement-' . $payment->id . '.pdf');
                }
            } catch (\Exception $e) {
                \Log::error('Erreur DOMPDF: ' . $e->getMessage());
            }
        }
        
        return view('payments.print', compact('payment', 'settings'));
    }
}
