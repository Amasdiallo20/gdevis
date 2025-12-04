@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="card-modern overflow-hidden">
        <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
             style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }}15 0%, {{ $settings->secondary_color ?? '#1e40af' }}15 100%);">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                    <i class="fas fa-chart-line text-white"></i>
                </div>
                Tableau de bord
            </h2>
            <p class="mt-2 text-sm text-gray-600">Vue d'ensemble de votre activité</p>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Carte Devis -->
        <div class="card-modern p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Devis</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalQuotes, 0, ',', ' ') }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-calendar-alt mr-1"></i>{{ $quotesThisMonth }} ce mois
                    </p>
                </div>
                <div class="p-4 rounded-xl" style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                    <i class="fas fa-file-invoice text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Carte Clients -->
        <div class="card-modern p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Clients</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalClients, 0, ',', ' ') }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-users mr-1"></i>Actifs
                    </p>
                </div>
                <div class="p-4 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Carte Produits -->
        <div class="card-modern p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Produits</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalProducts, 0, ',', ' ') }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-box mr-1"></i>En catalogue
                    </p>
                </div>
                <div class="p-4 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600">
                    <i class="fas fa-box text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Carte Paiements -->
        <div class="card-modern p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Paiements</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalPayments, 0, ',', ' ') }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-money-bill-wave mr-1"></i>{{ number_format($paymentsThisMonth, 0, ',', ' ') }} GNF ce mois
                    </p>
                </div>
                <div class="p-4 rounded-xl bg-gradient-to-br from-yellow-500 to-orange-600">
                    <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes financières -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <!-- Montant total des devis -->
        <div class="card-modern p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-900">Montant Total Devis</h3>
                <i class="fas fa-file-invoice-dollar text-xl" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
            </div>
            <p class="text-xl sm:text-2xl font-bold whitespace-nowrap" style="color: {{ $settings->primary_color ?? '#3b82f6' }};">
                {{ number_format($totalQuotesAmount, 0, ',', ' ') }} <span class="text-sm">GNF</span>
            </p>
        </div>

        <!-- Montant payé -->
        <div class="card-modern p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-900">Montant Payé</h3>
                <i class="fas fa-check-circle text-xl text-green-500"></i>
            </div>
            <p class="text-xl sm:text-2xl font-bold text-green-600 whitespace-nowrap">
                {{ number_format($totalPaidAmount, 0, ',', ' ') }} <span class="text-sm">GNF</span>
            </p>
        </div>

        <!-- Montant en attente -->
        <div class="card-modern p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-900">Montant en Attente</h3>
                <i class="fas fa-clock text-xl text-orange-500"></i>
            </div>
            <p class="text-xl sm:text-2xl font-bold text-orange-600 whitespace-nowrap">
                {{ number_format($totalPendingAmount, 0, ',', ' ') }} <span class="text-sm">GNF</span>
            </p>
        </div>

        <!-- Montant restant non payé -->
        <a href="{{ route('payments.pending-quotes') }}" class="block">
            <div class="card-modern p-6 bg-gradient-to-r from-orange-50 to-red-50 border-2 border-orange-200 hover:shadow-lg transition-all duration-200 transform hover:scale-105 cursor-pointer">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-900">
                        <i class="fas fa-exclamation-circle mr-2 text-orange-500"></i>
                        Montant Restant Non Payé
                    </h3>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center" 
                         style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }}20 0%, {{ $settings->secondary_color ?? '#1e40af' }}20 100%);">
                        <i class="fas fa-wallet text-lg" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
                    </div>
                </div>
                <p class="text-xl sm:text-2xl font-bold mb-2 whitespace-nowrap" style="color: {{ $settings->primary_color ?? '#3b82f6' }};">
                    {{ number_format($totalRemainingAmount ?? 0, 0, ',', ' ') }} <span class="text-sm">GNF</span>
                </p>
                <p class="text-xs text-gray-500">
                    <i class="fas fa-mouse-pointer mr-1"></i>Cliquez pour voir les devis
                </p>
            </div>
        </a>
    </div>

    <!-- Statistiques par statut et listes -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Statistiques par statut -->
        <div class="card-modern overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <i class="fas fa-chart-pie mr-2" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
                    Devis par Statut
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-gray-400 mr-3"></span>
                            <span class="text-sm font-medium text-gray-700">Brouillon</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ $quotesByStatus['draft'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-blue-400 mr-3"></span>
                            <span class="text-sm font-medium text-gray-700">Envoyé</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ $quotesByStatus['sent'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-green-400 mr-3"></span>
                            <span class="text-sm font-medium text-gray-700">Accepté</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ $quotesByStatus['accepted'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-emerald-400 mr-3"></span>
                            <span class="text-sm font-medium text-gray-700">Validé</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ $quotesByStatus['validated'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-red-400 mr-3"></span>
                            <span class="text-sm font-medium text-gray-700">Refusé</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ $quotesByStatus['rejected'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Clients -->
        <div class="card-modern overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <i class="fas fa-trophy mr-2" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
                    Top Clients
                </h3>
            </div>
            <div class="p-6">
                @if($topClients->count() > 0)
                <div class="space-y-4">
                    @foreach($topClients as $index => $client)
                    <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center text-white font-bold mr-3"
                                 style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $client->name }}</p>
                                <p class="text-xs text-gray-500">{{ $client->quotes_count }} devis</p>
                            </div>
                        </div>
                        <a href="{{ route('clients.show', $client) }}" 
                           class="text-blue-600 hover:text-blue-800 transition-colors">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-gray-500 text-center py-4">Aucun client pour le moment</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Devis récents et Paiements récents -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Devis récents -->
        <div class="card-modern overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <i class="fas fa-file-invoice mr-2" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
                        Devis Récents
                    </h3>
                    <a href="{{ route('quotes.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                @if($recentQuotes->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentQuotes as $quote)
                        <tr class="table-row-hover">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-lg flex items-center justify-center mr-3"
                                         style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                                        <i class="fas fa-file-invoice text-white text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ $quote->quote_number }}</p>
                                        <p class="text-xs text-gray-500">{{ $quote->client->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('quotes.show', $quote) }}" 
                                   class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-all">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="p-6 text-center">
                    <p class="text-sm text-gray-500">Aucun devis récent</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Paiements récents -->
        <div class="card-modern overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <i class="fas fa-money-bill-wave mr-2" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
                        Paiements Récents
                    </h3>
                    <a href="{{ route('payments.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                @if($recentPayments->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentPayments as $payment)
                        <tr class="table-row-hover">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-lg flex items-center justify-center mr-3 bg-green-100">
                                        <i class="fas fa-money-bill-wave text-green-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ $payment->quote->quote_number }}</p>
                                        <p class="text-xs text-gray-500">{{ $payment->quote->client->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <p class="text-sm font-bold text-green-600">{{ number_format($payment->amount, 0, ',', ' ') }} GNF</p>
                                <p class="text-xs text-gray-500">{{ $payment->payment_date->format('d/m/Y') }}</p>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="p-6 text-center">
                    <p class="text-sm text-gray-500">Aucun paiement récent</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


