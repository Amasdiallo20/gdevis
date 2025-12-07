@extends('layouts.app')

@section('title', 'Détails Devis')

@section('content')
<div class="card-modern overflow-hidden">
    <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
         style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }}15 0%, {{ $settings->secondary_color ?? '#1e40af' }}15 100%);">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                    <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                        <i class="fas fa-file-invoice text-white"></i>
                    </div>
                    Devis {{ $quote->quote_number }}
                </h2>
                <p class="mt-2 text-sm text-gray-600">Détails et informations du devis</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if($quote->status !== 'validated')
                <a href="{{ route('quotes.edit', $quote) }}" 
                   class="btn-primary inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300"
                   onmouseover="this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-edit mr-2"></i><span class="hidden sm:inline">Modifier</span><span class="sm:hidden">Modif.</span>
                </a>
                @else
                <span class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-gray-400 bg-gray-200 cursor-not-allowed"
                      title="Un devis validé ne peut pas être modifié. Annulez d'abord la validation.">
                    <i class="fas fa-edit mr-2"></i><span class="hidden sm:inline">Modifier</span><span class="sm:hidden">Modif.</span>
                </span>
                @endif
                @if($quote->status === 'accepted')
                <a href="{{ route('quotes.show-validation', $quote) }}" 
                   class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300 bg-emerald-600 hover:bg-emerald-700"
                   onmouseover="this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-check-circle mr-2"></i><span class="hidden sm:inline">Valider</span><span class="sm:hidden">Val.</span>
                </a>
                @endif
                @if($quote->status === 'validated')
                <a href="{{ route('payments.create', $quote) }}" 
                   class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300 bg-green-600 hover:bg-green-700"
                   onmouseover="this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-money-bill-wave mr-2"></i><span class="hidden sm:inline">Payer</span><span class="sm:hidden">Pay.</span>
                </a>
                @endif
                <a href="{{ route('quotes.print', $quote) }}" target="_blank" 
                   class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300 bg-teal-600 hover:bg-teal-700"
                   onmouseover="this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-print mr-2"></i><span class="hidden sm:inline">Imprimer</span><span class="sm:hidden">Print</span>
                </a>
                @hasPermission('quotes.calculate-materials')
                <a href="{{ route('quotes.calculate-materials', ['quote_id' => $quote->id]) }}" 
                   class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300 bg-orange-600 hover:bg-orange-700"
                   onmouseover="this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-calculator mr-2"></i><span class="hidden sm:inline">Calcul Matériaux</span><span class="sm:hidden">Matériaux</span>
                </a>
                @endhasPermission
                <button type="button" id="optimizeCutsBtn" 
                   class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300 bg-purple-600 hover:bg-purple-700"
                   onmouseover="this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-cut mr-2"></i><span class="hidden sm:inline">Optimiser Coupes</span><span class="sm:hidden">Coupes</span>
                </button>
                <a href="{{ route('quotes.index') }}" 
                   class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 rounded-lg shadow-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-all duration-300"
                   onmouseover="this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-arrow-left mr-2"></i><span class="hidden sm:inline">Retour</span><span class="sm:hidden">Ret.</span>
                </a>
            </div>
            </div>
        </div>
    
    <div class="px-6 py-6">

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-5 rounded-xl border border-blue-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user-circle mr-2" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
                    Informations Client
                </h3>
                <dl class="space-y-3">
                    <div class="flex items-center">
                        <dt class="text-sm font-semibold text-gray-600 w-24">Nom:</dt>
                        <dd class="text-sm font-bold text-gray-900">{{ $quote->client->name }}</dd>
                    </div>
                    @if($quote->client->email)
                    <div class="flex items-center">
                        <dt class="text-sm font-semibold text-gray-600 w-24">Email:</dt>
                        <dd class="text-sm text-gray-900"><i class="fas fa-envelope mr-2 text-gray-400"></i>{{ $quote->client->email }}</dd>
                    </div>
                    @endif
                    @if($quote->client->phone)
                    <div class="flex items-center">
                        <dt class="text-sm font-semibold text-gray-600 w-24">Téléphone:</dt>
                        <dd class="text-sm text-gray-900"><i class="fas fa-phone mr-2 text-gray-400"></i>{{ $quote->client->phone }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-5 rounded-xl border border-purple-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
                    Informations Devis
                </h3>
                <dl class="space-y-3">
                    <div class="flex items-center">
                        <dt class="text-sm font-semibold text-gray-600 w-32">Date:</dt>
                        <dd class="text-sm font-bold text-gray-900"><i class="fas fa-calendar-alt mr-2 text-gray-400"></i>{{ $quote->date->format('d/m/Y') }}</dd>
                    </div>
                    @if($quote->valid_until)
                    <div class="flex items-center">
                        <dt class="text-sm font-semibold text-gray-600 w-32">Valide jusqu'au:</dt>
                        <dd class="text-sm text-gray-900"><i class="fas fa-calendar-check mr-2 text-gray-400"></i>{{ $quote->valid_until->format('d/m/Y') }}</dd>
                    </div>
                    @endif
                    @if($quote->creator)
                    <div class="flex items-center">
                        <dt class="text-sm font-semibold text-gray-600 w-32">Créé par:</dt>
                        <dd class="text-sm text-gray-900">
                            <i class="fas fa-user mr-2 text-gray-400"></i>
                            <span class="font-medium">{{ $quote->creator->name }}</span>
                            <span class="text-xs text-gray-500 ml-2">({{ $quote->created_at->format('d/m/Y à H:i') }})</span>
                        </dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-2">Statut</dt>
                        <dd class="text-sm">
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $statuses = [
                                        'draft' => ['label' => 'Brouillon', 'icon' => 'fa-file-alt', 'color' => 'gray'],
                                        'sent' => ['label' => 'Envoyé', 'icon' => 'fa-paper-plane', 'color' => 'blue'],
                                        'accepted' => ['label' => 'Accepté', 'icon' => 'fa-check-circle', 'color' => 'green'],
                                        'rejected' => ['label' => 'Refusé', 'icon' => 'fa-times-circle', 'color' => 'red'],
                                    ];
                                    $isValidated = $quote->status === 'validated';
                                    $isCancelled = $quote->status === 'cancelled';
                                @endphp
                                @if($isValidated)
                                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-emerald-200 text-emerald-800 border-2 border-emerald-400">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Validé
                                        <span class="ml-2 text-xs opacity-75">(Actuel)</span>
                                    </span>
                                @endif
                                @if($isCancelled)
                                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-orange-200 text-orange-800 border-2 border-orange-400">
                                        <i class="fas fa-ban mr-2"></i>
                                        Annulé
                                        <span class="ml-2 text-xs opacity-75">(Actuel)</span>
                                    </span>
                                @endif
                                
                                <!-- Bouton Valider (si devis accepté ou annulé) -->
                                @if(in_array($quote->status, ['accepted', 'cancelled']))
                                    <a href="{{ route('quotes.show-validation', $quote) }}" 
                                       class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md bg-emerald-100 text-emerald-700 hover:bg-emerald-200 border border-emerald-300">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Valider
                                    </a>
                                @endif
                                
                                <!-- Bouton Annuler (si devis validé et sans paiements) -->
                                @if($isValidated)
                                    @if($quote->payments->count() > 0)
                                        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-gray-100 text-gray-400 border border-gray-300 cursor-not-allowed"
                                              title="Un devis avec des paiements ne peut pas être annulé. Supprimez d'abord les paiements.">
                                            <i class="fas fa-ban mr-2"></i>
                                            Annuler
                                        </span>
                                    @else
                                        <form action="{{ route('quotes.cancel', $quote) }}" method="POST" class="inline" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce devis validé ?');">
                                            @csrf
                                            <button type="submit" 
                                                    class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md bg-orange-100 text-orange-700 hover:bg-orange-200 border border-orange-300">
                                                <i class="fas fa-ban mr-2"></i>
                                                Annuler
                                            </button>
                                        </form>
                                    @endif
                                @endif
                                
                                @foreach($statuses as $statusValue => $statusInfo)
                                    @if($quote->status == $statusValue && !$isValidated && !$isCancelled)
                                        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold 
                                            @if($statusValue == 'draft') bg-gray-200 text-gray-800 border-2 border-gray-400
                                            @elseif($statusValue == 'sent') bg-blue-200 text-blue-800 border-2 border-blue-400
                                            @elseif($statusValue == 'accepted') bg-green-200 text-green-800 border-2 border-green-400
                                            @elseif($statusValue == 'rejected') bg-red-200 text-red-800 border-2 border-red-400
                                            @endif">
                                            <i class="fas {{ $statusInfo['icon'] }} mr-2"></i>
                                            {{ $statusInfo['label'] }}
                                            <span class="ml-2 text-xs opacity-75">(Actuel)</span>
                                        </span>
                                    @elseif(!$isValidated && !$isCancelled)
                                        <form action="{{ route('quotes.update-status', $quote) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="{{ $statusValue }}">
                                            <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md
                                                @if($statusValue == 'draft') bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300
                                                @elseif($statusValue == 'sent') bg-blue-100 text-blue-700 hover:bg-blue-200 border border-blue-300
                                                @elseif($statusValue == 'accepted') bg-green-100 text-green-700 hover:bg-green-200 border border-green-300
                                                @elseif($statusValue == 'rejected') bg-red-100 text-red-700 hover:bg-red-200 border border-red-300
                                                @endif">
                                                <i class="fas {{ $statusInfo['icon'] }} mr-2"></i>
                                                {{ $statusInfo['label'] }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-gray-100 text-gray-400 border border-gray-300 cursor-not-allowed opacity-60">
                                            <i class="fas {{ $statusInfo['icon'] }} mr-2"></i>
                                            {{ $statusInfo['label'] }}
                            </span>
                                    @endif
                                @endforeach
                            </div>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Version mobile (cartes) -->
        <div class="block md:hidden space-y-4">
            @php
                // Recharger les lignes pour s'assurer qu'elles sont à jour
                $quote->load('lines');
                $groupedLines = $quote->lines->groupBy('description');
            @endphp
            @forelse($groupedLines as $productName => $lines)
                @php
                    $productTotalAmount = $lines->sum(function($line) {
                        return $line->amount ?: $line->subtotal;
                    });
                    $productTotalQuantity = $lines->sum('quantity');
                    $productTotalSurface = $lines->sum('surface');
                @endphp
                @foreach($lines as $line)
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="font-semibold text-gray-900 mb-3">{{ $line->description }}</div>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <span class="text-gray-500">Largeur:</span>
                            <span class="text-gray-900 ml-1">{{ $line->width ? number_format($line->width, 2, ',', ' ') : '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Hauteur:</span>
                            <span class="text-gray-900 ml-1">{{ $line->height ? number_format($line->height, 2, ',', ' ') : '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Quantité:</span>
                            <span class="text-gray-900 ml-1">{{ number_format($line->quantity, 2, ',', ' ') }} {{ $line->unit }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Prix M²:</span>
                            <span class="text-gray-900 ml-1">{{ $line->price_per_m2 ? number_format($line->price_per_m2, 2, ',', ' ') . ' GNF' : '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Surface:</span>
                            <span class="text-gray-900 ml-1">{{ $line->surface ? number_format($line->surface, 2, ',', ' ') . ' m²' : '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Montant:</span>
                            <span class="text-gray-900 font-semibold ml-1">{{ $line->amount ? number_format($line->amount, 2, ',', ' ') . ' GNF' : number_format($line->subtotal, 2, ',', ' ') . ' GNF' }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            @empty
                <p class="text-center text-gray-500 py-8">Aucune ligne</p>
            @endforelse
        </div>

        <!-- Version desktop (tableau) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Largeur</th>
                        <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Hauteur</th>
                        <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Quantité</th>
                        <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Prix M²</th>
                        <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Surface</th>
                        <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Montant</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        // Grouper les lignes par description (produit)
                        $groupedLines = $quote->lines->groupBy('description');
                    @endphp
                        @forelse($groupedLines as $productName => $lines)
                        @php
                            $productTotalAmount = $lines->sum(function($line) {
                                return $line->amount ?: $line->subtotal;
                            });
                            $productTotalQuantity = $lines->sum('quantity');
                            $productTotalSurface = $lines->sum('surface');
                        @endphp
                        @foreach($lines as $index => $line)
                        <tr class="{{ $index === 0 ? 'border-t-2 border-blue-300' : '' }}">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $line->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $line->width ? number_format($line->width, 2, ',', ' ') : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $line->height ? number_format($line->height, 2, ',', ' ') : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ number_format($line->quantity, 2, ',', ' ') }} {{ $line->unit }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $line->price_per_m2 ? number_format($line->price_per_m2, 2, ',', ' ') . ' GNF' : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $line->surface ? number_format($line->surface, 2, ',', ' ') . ' m²' : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">{{ $line->amount ? number_format($line->amount, 2, ',', ' ') . ' GNF' : number_format($line->subtotal, 2, ',', ' ') . ' GNF' }}</td>
                        </tr>
                        @endforeach
                        @if($lines->count() > 0)
                        <tr class="bg-blue-50 border-t-2 border-blue-300">
                            <td colspan="3" class="px-6 py-3 text-right text-sm font-semibold text-blue-900">
                                Total {{ $productName }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-blue-900 text-right">
                                {{ number_format($productTotalQuantity, 2, ',', ' ') }} {{ $lines->first()->unit }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-blue-900 text-right">
                                -
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-blue-900 text-right">
                                {{ number_format($productTotalSurface, 2, ',', ' ') }} m²
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-blue-900 text-right">
                                {{ number_format($productTotalAmount, 2, ',', ' ') }} GNF
                            </td>
                        </tr>
                        @endif
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Aucune ligne</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Total</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600 text-right text-lg">{{ number_format($quote->total, 2, ',', ' ') }} GNF</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Image du modèle associé -->
        @if($quote->modele && $quote->modele->image)
        <div class="mt-8">
            <div class="flex items-center mb-4">
                <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                    <i class="fas fa-image text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Modèle associé</h3>
            </div>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border-2 border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="relative">
                    <!-- Image principale avec overlay -->
                    <div class="relative h-64 sm:h-80 overflow-hidden">
                        <img src="{{ $quote->modele->large_image_url ?? $quote->modele->image_url }}" 
                             alt="{{ $quote->modele->nom }}"
                             class="w-full h-full object-cover"
                             loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                        <!-- Badge catégorie sur l'image -->
                        <div class="absolute top-4 left-4">
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold text-white shadow-lg backdrop-blur-sm bg-white/20 border border-white/30">
                                <i class="fas fa-folder mr-2"></i>
                                {{ \App\Models\Modele::getCategories()[$quote->modele->categorie] ?? $quote->modele->categorie }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Contenu en dessous de l'image -->
                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            <div class="flex-1">
                                <h4 class="text-2xl font-bold text-gray-900 mb-3 uppercase tracking-tight">
                                    {{ $quote->modele->nom }}
                                </h4>
                                @if($quote->modele->description)
                                <p class="text-gray-600 mb-4 leading-relaxed">{{ $quote->modele->description }}</p>
                                @endif
                                
                                @if($quote->modele->prix_indicatif)
                                <div class="inline-flex items-center px-5 py-3 rounded-xl text-base font-bold shadow-md"
                                     style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }}15 0%, {{ $settings->secondary_color ?? '#1e40af' }}15 100%); border: 2px solid {{ $settings->primary_color ?? '#3b82f6' }}40;">
                                    <i class="fas fa-tag mr-2" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
                                    <span style="color: {{ $settings->primary_color ?? '#3b82f6' }};">
                                        {{ number_format($quote->modele->prix_indicatif, 0, ',', ' ') }} GNF
                                    </span>
                                    <span class="ml-2 text-xs font-normal text-gray-500">(Prix indicatif)</span>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Bouton pour changer le modèle -->
                            @if($quote->status !== 'validated')
                            <div class="flex-shrink-0">
                                <a href="{{ route('modeles.index') }}?quote_id={{ $quote->id }}" 
                                   class="inline-flex items-center justify-center px-5 py-3 rounded-xl text-sm font-semibold text-white transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105"
                                   style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                                    <i class="fas fa-exchange-alt mr-2"></i>Changer
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($quote->notes)
        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Notes</h3>
            <p class="text-sm text-gray-700">{{ $quote->notes }}</p>
        </div>
        @endif

        <!-- Bouton pour associer un modèle depuis le catalogue -->
        @if($quote->status !== 'validated')
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">
                        <i class="fas fa-images mr-2" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
                        {{ $quote->modele ? 'Changer le modèle associé' : 'Associer un modèle' }}
                    </h3>
                    <p class="text-xs text-gray-600">Sélectionnez un modèle dans le catalogue pour l'associer à ce devis</p>
                </div>
                <a href="{{ route('modeles.index') }}?quote_id={{ $quote->id }}" 
                   class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-semibold text-white transition-all duration-200 shadow-sm hover:shadow-md"
                   style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                    <i class="fas fa-images mr-2"></i>Voir le Catalogue
                </a>
            </div>
        </div>
        @endif

        <!-- Section Paiements (uniquement pour les devis acceptés ou validés) -->
        @if(in_array($quote->status, ['accepted', 'validated']))
        <div id="payments" class="mt-8 border-t border-gray-200 pt-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-money-bill-wave mr-2"></i>Gestion des Paiements
                </h3>
                @if($quote->is_fully_paid)
                <button disabled class="bg-gray-400 text-white font-bold py-2 px-4 rounded transition-all duration-200 shadow-md cursor-not-allowed opacity-60">
                    <i class="fas fa-plus mr-2"></i>Ajouter un paiement
                </button>
                @else
                <a href="{{ route('payments.create', $quote) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i>Ajouter un paiement
                </a>
                @endif
            </div>

            <!-- Résumé des paiements -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm font-medium text-gray-600">Montant total</p>
                    <p class="text-2xl font-bold text-blue-900">{{ number_format($quote->total, 2, ',', ' ') }} GNF</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-sm font-medium text-gray-600">Montant payé</p>
                    <p class="text-2xl font-bold text-green-900">{{ number_format($quote->paid_amount, 2, ',', ' ') }} GNF</p>
                </div>
                <div class="bg-{{ $quote->is_fully_paid ? 'green' : 'orange' }}-50 p-4 rounded-lg">
                    <p class="text-sm font-medium text-gray-600">Solde restant</p>
                    <p class="text-2xl font-bold text-{{ $quote->is_fully_paid ? 'green' : 'orange' }}-900">{{ number_format($quote->remaining_amount, 2, ',', ' ') }} GNF</p>
                </div>
            </div>

            <!-- Liste des paiements -->
            @if($quote->payments->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Méthode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Référence</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Créé par</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($quote->payments as $payment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment->payment_date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">{{ number_format($payment->amount, 2, ',', ' ') }} GNF</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->payment_method_label }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->reference ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($payment->creator)
                                    <i class="fas fa-user mr-1 text-gray-400"></i>
                                    <span class="font-medium">{{ $payment->creator->name }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $payment->notes ? \Illuminate\Support\Str::limit($payment->notes, 50) : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('payments.print', $payment) }}" target="_blank" class="text-green-600 hover:text-green-900" title="Imprimer le reçu">
                                        <i class="fas fa-receipt"></i>
                                    </a>
                                    <a href="{{ route('payments.edit', [$quote, $payment]) }}" class="text-blue-600 hover:text-blue-900" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('payments.destroy', [$quote, $payment]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ?')" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8 bg-gray-50 rounded-lg">
                <i class="fas fa-money-bill-wave text-gray-400 text-4xl mb-2"></i>
                <p class="text-gray-500">Aucun paiement enregistré pour ce devis.</p>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const optimizeBtn = document.getElementById('optimizeCutsBtn');
    if (optimizeBtn) {
        optimizeBtn.addEventListener('click', function() {
            // Désactiver le bouton pendant le traitement
            const originalText = optimizeBtn.innerHTML;
            optimizeBtn.disabled = true;
            optimizeBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Traitement...';
            
            // Appel AJAX
            fetch('{{ route("quotes.cut-optimize", $quote) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Afficher un message de succès
                    alert('Plan de coupe généré avec succès !');
                    // Rediriger vers la page du plan
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        window.location.reload();
                    }
                } else {
                    // Afficher l'erreur
                    alert(data.message || 'Une erreur est survenue lors de la génération du plan de coupe.');
                    optimizeBtn.disabled = false;
                    optimizeBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de la génération du plan de coupe.');
                optimizeBtn.disabled = false;
                optimizeBtn.innerHTML = originalText;
            });
        });
    }
});
</script>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const optimizeBtn = document.getElementById('optimizeCutsBtn');
    if (optimizeBtn) {
        optimizeBtn.addEventListener('click', function() {
            // Désactiver le bouton pendant le traitement
            const originalText = optimizeBtn.innerHTML;
            optimizeBtn.disabled = true;
            optimizeBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Traitement...';
            
            // Appel AJAX
            fetch('{{ route("quotes.cut-optimize", $quote) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Afficher un message de succès
                    alert('Plan de coupe généré avec succès !');
                    // Rediriger vers la page du plan
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        window.location.reload();
                    }
                } else {
                    // Afficher l'erreur
                    alert(data.message || 'Une erreur est survenue lors de la génération du plan de coupe.');
                    optimizeBtn.disabled = false;
                    optimizeBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de la génération du plan de coupe.');
                optimizeBtn.disabled = false;
                optimizeBtn.innerHTML = originalText;
            });
        });
    }
});
</script>
@endpush
@endsection

