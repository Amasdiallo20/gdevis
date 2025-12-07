@extends('layouts.app')

@section('title', 'Plan de Coupe Optimisé')

@section('content')
<div class="card-modern overflow-hidden">
    <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
         style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }}15 0%, {{ $settings->secondary_color ?? '#1e40af' }}15 100%);">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                    <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                        <i class="fas fa-cut text-white"></i>
                    </div>
                    Plan de Coupe Optimisé
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Devis {{ $cutPlan->quote->quote_number }} - {{ $cutPlan->quote->client->name }}
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('quotes.show', $cutPlan->quote) }}" 
                   class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300 bg-gray-600 hover:bg-gray-700"
                   onmouseover="this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-arrow-left mr-2"></i>Retour au Devis
                </a>
                <a href="{{ route('cut-plans.pdf', $cutPlan) }}?download=1" 
                   class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300"
                   style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);"
                   onmouseover="this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-file-pdf mr-2"></i>Générer PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Résumé du plan -->
    <div class="p-6 bg-gray-50 border-b border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Barres Utilisées</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $cutPlan->total_bars_used }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-blue-100">
                        <i class="fas fa-ruler-combined text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Longueur par Barre</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">580 <span class="text-sm text-gray-500">cm</span></p>
                    </div>
                    <div class="p-3 rounded-lg bg-green-100">
                        <i class="fas fa-ruler text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Chutes</p>
                        <p class="text-2xl font-bold text-orange-600 mt-1">{{ number_format($cutPlan->total_waste, 2, ',', ' ') }} <span class="text-sm text-gray-500">cm</span></p>
                    </div>
                    <div class="p-3 rounded-lg bg-orange-100">
                        <i class="fas fa-exclamation-triangle text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau détaillé -->
    <div class="p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">
            <i class="fas fa-list-ul mr-2" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
            Détail des Barres
        </h3>
        
        @if($cutPlan->details->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-lg shadow-sm">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-hashtag mr-2"></i>N° Barre
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-cut mr-2"></i>Coupes (cm)
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-ruler mr-2"></i>Longueur Utilisée (cm)
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-exclamation-circle mr-2"></i>Chute (cm)
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($cutPlan->details as $detail)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-lg flex items-center justify-center mr-3"
                                     style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                                    <span class="text-white font-bold text-sm">{{ $detail->bar_number }}</span>
                                </div>
                                <span class="text-sm font-semibold text-gray-900">Barre #{{ $detail->bar_number }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                @foreach($detail->sections as $section)
                                <span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                    {{ number_format($section, 2, ',', ' ') }} cm
                                </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="text-sm font-semibold text-gray-900">
                                {{ number_format($detail->used_length, 2, ',', ' ') }} cm
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="text-sm font-bold {{ $detail->waste > 0 ? 'text-orange-600' : 'text-green-600' }}">
                                {{ number_format($detail->waste, 2, ',', ' ') }} cm
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td class="px-6 py-4 font-bold text-gray-900" colspan="2">
                            <i class="fas fa-calculator mr-2"></i>Totaux
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-900">
                            {{ number_format($cutPlan->details->sum('used_length'), 2, ',', ' ') }} cm
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-orange-600">
                            {{ number_format($cutPlan->total_waste, 2, ',', ' ') }} cm
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="text-center py-12 bg-gray-50 rounded-lg border border-gray-200">
            <i class="fas fa-info-circle text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-600">Aucun détail disponible pour ce plan de coupe.</p>
        </div>
        @endif
    </div>

    <!-- Informations supplémentaires -->
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 text-sm text-gray-600">
            <div>
                <i class="fas fa-info-circle mr-2"></i>
                Plan généré le {{ $cutPlan->created_at->format('d/m/Y à H:i') }}
            </div>
            <div>
                <i class="fas fa-percentage mr-2"></i>
                Taux d'utilisation: {{ number_format((($cutPlan->details->sum('used_length') / ($cutPlan->total_bars_used * 580)) * 100), 2, ',', ' ') }}%
            </div>
        </div>
    </div>
</div>
@endsection

