@extends('layouts.app')

@section('title', 'Devis avec Solde Restant')

@section('content')
<div class="card-modern overflow-hidden">
    <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
         style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }}15 0%, {{ $settings->secondary_color ?? '#1e40af' }}15 100%);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                    <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    Devis avec Solde Restant
                </h2>
                <p class="mt-2 text-sm text-gray-600">Liste des devis acceptés ou validés ayant encore un montant à payer</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="bg-gradient-to-r from-orange-50 to-red-50 border-2 border-orange-200 rounded-lg p-3 shadow-md">
                    <p class="text-xs font-medium text-gray-600 mb-1">
                        <i class="fas fa-wallet mr-2 text-orange-500"></i>
                        Total restant
                    </p>
                    <p class="text-xl font-bold" style="color: {{ $settings->primary_color ?? '#3b82f6' }};">
                        {{ number_format($totalRemainingAmount, 2, ',', ' ') }} <span class="text-sm text-gray-500">GNF</span>
                    </p>
                </div>
                <a href="{{ route('payments.index') }}" 
                   class="btn-primary inline-flex items-center justify-center px-5 py-3 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="p-6">
        @if($quotes->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-hashtag mr-2"></i>Numéro Devis
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>Client
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden md:table-cell">
                            <i class="fas fa-calendar mr-2"></i>Date
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-2"></i>Statut
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-money-bill-wave mr-2"></i>Montant Total
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-check-circle mr-2"></i>Montant Payé
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-exclamation-circle mr-2"></i>Solde Restant
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i>Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($quotes as $quote)
                    <tr class="table-row-hover">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-lg flex items-center justify-center mr-3"
                                     style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                                    <i class="fas fa-file-invoice text-white text-xs"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $quote->quote_number }}</div>
                                    <div class="text-xs text-gray-500 md:hidden">{{ $quote->date->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">{{ $quote->client->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 hidden md:table-cell">
                            <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>{{ $quote->date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-lg shadow-sm
                                @if($quote->status == 'accepted') bg-green-100 text-green-800 border border-green-200
                                @elseif($quote->status == 'validated') bg-emerald-100 text-emerald-800 border border-emerald-200
                                @else bg-gray-100 text-gray-800 border border-gray-200
                                @endif">
                                @if($quote->status == 'accepted') Accepté
                                @elseif($quote->status == 'validated') Validé
                                @else {{ ucfirst($quote->status) }}
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                            <span class="text-base" style="color: {{ $settings->primary_color ?? '#3b82f6' }};">
                                {{ number_format($quote->total, 2, ',', ' ') }}
                            </span>
                            <span class="text-xs text-gray-500"> GNF</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-700 text-right">
                            <span class="text-base">{{ number_format($quote->paid_amount, 2, ',', ' ') }}</span>
                            <span class="text-xs text-gray-500"> GNF</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right">
                            <span class="text-lg text-orange-600">{{ number_format($quote->remaining_amount, 2, ',', ' ') }}</span>
                            <span class="text-xs text-gray-500"> GNF</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('quotes.show', $quote) }}" 
                                   class="text-blue-600 hover:text-blue-800 p-2.5 rounded-lg hover:bg-blue-50 transition-all duration-200 transform hover:scale-110"
                                   title="Voir le devis">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(in_array($quote->status, ['accepted', 'validated']))
                                <a href="{{ route('payments.create', $quote) }}" 
                                   class="text-green-600 hover:text-green-800 p-2.5 rounded-lg hover:bg-green-50 transition-all duration-200 transform hover:scale-110"
                                   title="Ajouter un paiement">
                                    <i class="fas fa-money-bill-wave"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4"
                 style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }}20 0%, {{ $settings->secondary_color ?? '#1e40af' }}20 100%);">
                <i class="fas fa-check-circle text-4xl text-green-500"></i>
            </div>
            <p class="text-xl font-bold text-gray-900">Aucun devis avec solde restant</p>
            <p class="text-sm text-gray-500 mt-2">Tous les devis acceptés ou validés sont entièrement payés.</p>
        </div>
        @endif
    </div>
</div>
@endsection

