<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Quote;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('quote.client')
            ->latest('payment_date')
            ->paginate(15);
        
        // Calculer le montant total restant non payé
        // Récupérer tous les devis acceptés ou validés qui ont des paiements ou un solde restant
        $quotesWithPayments = Quote::whereIn('status', ['accepted', 'validated'])
            ->with(['payments', 'lines'])
            ->get();
        
        $totalRemainingAmount = $quotesWithPayments->sum(function ($quote) {
            return $quote->remaining_amount;
        });
        
        return view('payments.index', compact('payments', 'totalRemainingAmount'));
    }

    public function create(Quote $quote)
    {
        // Vérifier que le devis est accepté ou validé
        if (!in_array($quote->status, ['accepted', 'validated'])) {
            return redirect()->route('quotes.show', $quote)
                ->with('error', 'Seuls les devis acceptés ou validés peuvent avoir des paiements.');
        }

        return view('payments.create', compact('quote'));
    }

    public function store(Request $request, Quote $quote)
    {
        // Vérifier que le devis est accepté ou validé
        if (!in_array($quote->status, ['accepted', 'validated'])) {
            return back()->withErrors(['error' => 'Seuls les devis acceptés ou validés peuvent avoir des paiements.']);
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

    public function pendingQuotes()
    {
        // Récupérer tous les devis acceptés ou validés qui ont encore un solde à payer
        $quotes = Quote::whereIn('status', ['accepted', 'validated'])
            ->with(['client', 'payments', 'lines'])
            ->get()
            ->filter(function ($quote) {
                return $quote->remaining_amount > 0;
            })
            ->sortByDesc('date')
            ->values();
        
        // Calculer le montant total restant
        $totalRemainingAmount = $quotes->sum(function ($quote) {
            return $quote->remaining_amount;
        });
        
        return view('payments.pending-quotes', compact('quotes', 'totalRemainingAmount'));
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
