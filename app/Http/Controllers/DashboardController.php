<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Client;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $totalQuotes = Quote::count();
        $totalClients = Client::count();
        $totalProducts = Product::count();
        $totalPayments = Payment::count();
        
        // Montants - Calculer le total des devis
        $totalQuotesAmount = Quote::with('lines')->get()->sum(function($quote) {
            return $quote->status === 'validated' && $quote->final_amount 
                ? $quote->final_amount 
                : $quote->subtotal;
        });
        $totalPaidAmount = Payment::sum('amount');
        $totalPendingAmount = max(0, $totalQuotesAmount - $totalPaidAmount);
        
        // Calculer le montant restant non payé (devis acceptés ou validés avec solde restant)
        $quotesWithRemaining = Quote::whereIn('status', ['accepted', 'validated'])
            ->with(['payments', 'lines'])
            ->get();
        
        $totalRemainingAmount = $quotesWithRemaining->sum(function ($quote) {
            return $quote->remaining_amount;
        });
        
        // Statistiques par statut de devis
        $quotesByStatus = [
            'draft' => Quote::where('status', 'draft')->count(),
            'sent' => Quote::where('status', 'sent')->count(),
            'accepted' => Quote::where('status', 'accepted')->count(),
            'validated' => Quote::where('status', 'validated')->count(),
            'rejected' => Quote::where('status', 'rejected')->count(),
        ];
        
        // Devis récents (5 derniers)
        $recentQuotes = Quote::with('client')
            ->latest()
            ->take(5)
            ->get();
        
        // Paiements récents (5 derniers)
        $recentPayments = Payment::with(['quote.client'])
            ->latest('payment_date')
            ->take(5)
            ->get();
        
        // Statistiques du mois en cours
        $currentMonth = Carbon::now()->startOfMonth();
        $quotesThisMonth = Quote::where('created_at', '>=', $currentMonth)->count();
        $paymentsThisMonth = Payment::where('payment_date', '>=', $currentMonth->format('Y-m-d'))->sum('amount');
        
        // Top clients (par nombre de devis)
        $topClients = Client::withCount('quotes')
            ->orderBy('quotes_count', 'desc')
            ->take(5)
            ->get();
        
        return view('dashboard.index', compact(
            'totalQuotes',
            'totalClients',
            'totalProducts',
            'totalPayments',
            'totalQuotesAmount',
            'totalPaidAmount',
            'totalPendingAmount',
            'totalRemainingAmount',
            'quotesByStatus',
            'recentQuotes',
            'recentPayments',
            'quotesThisMonth',
            'paymentsThisMonth',
            'topClients'
        ));
    }
}

