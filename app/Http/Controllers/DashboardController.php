<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Client;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Utiliser des requêtes optimisées avec cache pour les statistiques
        $cacheKey = 'dashboard_stats_' . date('Y-m-d-H');
        
        $stats = \Cache::remember($cacheKey, 300, function () {
            // Statistiques générales - une seule requête avec selectRaw
            $counts = DB::table('quotes')
                ->selectRaw('COUNT(*) as total_quotes')
                ->first();
            
            $totalQuotes = $counts->total_quotes ?? 0;
            $totalClients = Client::count();
            $totalProducts = Product::count();
            $totalPayments = Payment::count();
            
            // Montants - Utiliser des agrégations SQL au lieu de charger tous les modèles
            $totalQuotesAmount = DB::table('quotes')
                ->leftJoin('quote_lines', 'quotes.id', '=', 'quote_lines.quote_id')
                ->selectRaw('
                    SUM(CASE 
                        WHEN quotes.status = "validated" AND quotes.final_amount IS NOT NULL 
                        THEN quotes.final_amount 
                        ELSE (quote_lines.quantity * quote_lines.unit_price) 
                    END) as total
                ')
                ->value('total') ?? 0;
            
            // Montant total des devis validés - requête optimisée
            $totalValidatedQuotesAmount = DB::table('quotes')
                ->where('status', 'validated')
                ->selectRaw('COALESCE(SUM(final_amount), 0) as total')
                ->value('total') ?? 0;
            
            // Si pas de final_amount, calculer depuis les lignes
            if ($totalValidatedQuotesAmount == 0) {
                $totalValidatedQuotesAmount = DB::table('quotes')
                    ->join('quote_lines', 'quotes.id', '=', 'quote_lines.quote_id')
                    ->where('quotes.status', 'validated')
                    ->selectRaw('SUM(quote_lines.quantity * quote_lines.unit_price) as total')
                    ->value('total') ?? 0;
            }
            
            // Montant payé uniquement pour les devis validés - requête optimisée
            $totalPaidAmount = DB::table('payments')
                ->join('quotes', 'payments.quote_id', '=', 'quotes.id')
                ->where('quotes.status', 'validated')
                ->selectRaw('COALESCE(SUM(payments.amount), 0) as total')
                ->value('total') ?? 0;
            
            // Montant non payé
            $totalRemainingAmount = max(0, $totalValidatedQuotesAmount - $totalPaidAmount);
            
            // Statistiques par statut - une seule requête avec groupBy
            $quotesByStatusRaw = DB::table('quotes')
                ->select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
            
            $quotesByStatus = [
                'draft' => $quotesByStatusRaw['draft'] ?? 0,
                'sent' => $quotesByStatusRaw['sent'] ?? 0,
                'accepted' => $quotesByStatusRaw['accepted'] ?? 0,
                'validated' => $quotesByStatusRaw['validated'] ?? 0,
                'rejected' => $quotesByStatusRaw['rejected'] ?? 0,
            ];
            
            // Statistiques du mois en cours
            $currentMonth = Carbon::now()->startOfMonth();
            $quotesThisMonth = Quote::where('created_at', '>=', $currentMonth)->count();
            $paymentsThisMonth = Payment::where('payment_date', '>=', $currentMonth->format('Y-m-d'))->sum('amount');
            
            return compact(
                'totalQuotes',
                'totalClients',
                'totalProducts',
                'totalPayments',
                'totalQuotesAmount',
                'totalValidatedQuotesAmount',
                'totalPaidAmount',
                'totalRemainingAmount',
                'quotesByStatus',
                'quotesThisMonth',
                'paymentsThisMonth'
            );
        });
        
        // Données qui changent fréquemment - pas de cache
        $recentQuotes = Quote::with('client')
            ->latest()
            ->take(5)
            ->get();
        
        $recentPayments = Payment::with(['quote.client'])
            ->latest('payment_date')
            ->take(5)
            ->get();
        
        $topClients = Client::withCount('quotes')
            ->orderBy('quotes_count', 'desc')
            ->take(5)
            ->get();
        
        return view('dashboard.index', array_merge($stats, compact(
            'recentQuotes',
            'recentPayments',
            'topClients'
        )));
    }
}

