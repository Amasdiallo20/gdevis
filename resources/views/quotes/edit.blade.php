@extends('layouts.app')

@section('title', 'Modifier Devis')

@section('content')
<div class="space-y-6">
    <!-- Informations du devis -->
    <div class="card-modern overflow-hidden">
        <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
             style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }}15 0%, {{ $settings->secondary_color ?? '#1e40af' }}15 100%);">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center mb-2">
                <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                    <i class="fas fa-edit text-white"></i>
                </div>
                Modifier le Devis {{ $quote->quote_number }}
            </h2>
            <p class="text-sm text-gray-600">Modifiez les informations du devis</p>
        </div>
        <div class="px-6 py-6">

            <form action="{{ route('quotes.update', $quote) }}" method="POST" class="mb-6" id="updateQuoteForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="{{ $quote->status }}">

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-1 text-gray-400"></i>Client *
                        </label>
                        <select name="client_id" id="client_id" required
                            class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all @error('client_id') border-red-500 bg-red-50 @enderror"
                            style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }}; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"%23374151\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"6 9 12 15 18 9\"></polyline></svg>'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1em 1em; padding-right: 2.5rem;">
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ $quote->client_id == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-1 text-gray-400"></i>Date *
                        </label>
                        <input type="date" name="date" id="date" value="{{ old('date', $quote->date->format('Y-m-d')) }}" required
                            class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all @error('date') border-red-500 bg-red-50 @enderror"
                            style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="valid_until" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-check mr-1 text-gray-400"></i>Valide jusqu'au
                        </label>
                        <input type="date" name="valid_until" id="valid_until" value="{{ old('valid_until', $quote->valid_until ? $quote->valid_until->format('Y-m-d') : '') }}"
                            class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all @error('valid_until') border-red-500 bg-red-50 @enderror"
                            style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                            placeholder="jj/mm/aaaa">
                        @error('valid_until')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-info-circle mr-1 text-gray-400"></i>Statut du devis
                        </label>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $statuses = [
                                    'draft' => ['label' => 'Brouillon', 'short' => 'Brouillon', 'icon' => 'fa-file-alt', 'color' => 'gray'],
                                    'sent' => ['label' => 'Envoyé', 'short' => 'Envoyé', 'icon' => 'fa-paper-plane', 'color' => 'blue'],
                                    'accepted' => ['label' => 'Accepté', 'short' => 'Accepté', 'icon' => 'fa-check-circle', 'color' => 'green'],
                                    'rejected' => ['label' => 'Refusé', 'short' => 'Refusé', 'icon' => 'fa-times-circle', 'color' => 'red'],
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
                            @foreach($statuses as $statusValue => $statusInfo)
                                @if($quote->status == $statusValue && !$isValidated)
                                    <span class="inline-flex items-center px-2 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold 
                                        @if($statusValue == 'draft') bg-gray-200 text-gray-800 border-2 border-gray-400
                                        @elseif($statusValue == 'sent') bg-blue-200 text-blue-800 border-2 border-blue-400
                                        @elseif($statusValue == 'accepted') bg-green-200 text-green-800 border-2 border-green-400
                                        @elseif($statusValue == 'rejected') bg-red-200 text-red-800 border-2 border-red-400
                                        @endif">
                                        <i class="fas {{ $statusInfo['icon'] }} mr-1 sm:mr-2"></i>
                                        <span class="hidden sm:inline">{{ $statusInfo['label'] }}</span>
                                        <span class="sm:hidden">{{ $statusInfo['short'] }}</span>
                                        <span class="ml-1 sm:ml-2 text-xs opacity-75 hidden sm:inline">(Actuel)</span>
                                    </span>
                                @elseif(!$isValidated)
                                    <form action="{{ route('quotes.update-status', $quote) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="{{ $statusValue }}">
                                        <button type="submit" 
                                            class="inline-flex items-center px-2 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md
                                            @if($statusValue == 'draft') bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300
                                            @elseif($statusValue == 'sent') bg-blue-100 text-blue-700 hover:bg-blue-200 border border-blue-300
                                            @elseif($statusValue == 'accepted') bg-green-100 text-green-700 hover:bg-green-200 border border-green-300
                                            @elseif($statusValue == 'rejected') bg-red-100 text-red-700 hover:bg-red-200 border border-red-300
                                            @endif">
                                            <i class="fas {{ $statusInfo['icon'] }} mr-1 sm:mr-2"></i>
                                            <span class="hidden sm:inline">{{ $statusInfo['label'] }}</span>
                                            <span class="sm:hidden">{{ $statusInfo['short'] }}</span>
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
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-sticky-note mr-1 text-gray-400"></i>Notes
                        </label>
                        <textarea name="notes" id="notes" rows="4"
                            class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all resize-y @error('notes') border-red-500 bg-red-50 @enderror"
                            style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                            placeholder="Notes additionnelles...">{{ old('notes', $quote->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('quotes.show', $quote) }}" 
                       class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 rounded-lg shadow-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-all duration-300"
                       onmouseover="this.style.transform='translateY(-2px)'"
                       onmouseout="this.style.transform='translateY(0)'">
                        <i class="fas fa-times mr-2"></i>Annuler
                    </a>
                    <button type="button" 
                            id="updateQuoteBtn"
                            onclick="submitUpdateForm()"
                            class="btn-primary inline-flex items-center justify-center px-6 py-2.5 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.2)'"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)'">
                        <i class="fas fa-save mr-2"></i>Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lignes du devis -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Lignes du Devis</h3>
                @if($quote->lines->count() > 0)
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 flex-1 w-full sm:w-auto">
                        <label for="global_product_select" class="text-sm font-medium text-gray-700 whitespace-nowrap">Modifier Prix M² pour:</label>
                        <select id="global_product_select" 
                            class="block w-full sm:w-auto rounded-lg border-2 border-gray-400 bg-white px-4 py-2 text-gray-900 text-sm shadow-sm focus:ring-2 focus:border-transparent transition-all"
                            style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }}; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"%23374151\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"6 9 12 15 18 9\"></polyline></svg>'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1em 1em; padding-right: 2.5rem; min-width: 200px;">
                            <option value="">Toutes les lignes</option>
                            @php
                                $uniqueProductIds = $quote->lines->where('line_type', 'product')->whereNotNull('product_id')->pluck('product_id')->unique();
                            @endphp
                            @foreach($uniqueProductIds as $productId)
                                @php
                                    $product = $products->find($productId);
                                @endphp
                                @if($product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <label for="global_price_per_m2" class="text-sm font-medium text-gray-700 whitespace-nowrap">Prix M²:</label>
                        <input type="number" step="0.01" min="0" id="global_price_per_m2" placeholder="Prix M²" 
                            class="block w-full sm:w-32 rounded-lg border-2 border-gray-400 bg-white px-3 py-2 text-gray-900 text-sm shadow-sm focus:ring-2 focus:border-transparent transition-all"
                            style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                        <button type="button" onclick="updateAllPrices()" 
                            class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-200"
                            style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.2)'"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)'">
                            <i class="fas fa-sync-alt mr-2"></i>Appliquer
                        </button>
                    </div>
                </div>
                @endif
            </div>

            <!-- Formulaire d'ajout/modification de ligne -->
            <div id="addLineForm" class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <form action="{{ route('quotes.lines.store', $quote) }}" method="POST" id="addLineFormElement">
                    @csrf
                    <input type="hidden" name="line_id" id="line_id" value="">
                    <div class="mb-4 flex justify-between items-center">
                        <h4 class="text-sm font-semibold text-gray-700" id="formTitle">
                            <i class="fas fa-plus-circle mr-2"></i>Ajouter une nouvelle ligne
                        </h4>
                        <button type="button" onclick="toggleForm()" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-chevron-up" id="toggleIcon"></i>
                        </button>
                    </div>
                    <div id="formFields">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-12 mb-4">
                        <div class="sm:col-span-12">
                            <label for="line_type" class="block text-sm font-medium text-gray-700">Type de ligne *</label>
                            <select name="line_type" id="line_type" onchange="toggleLineTypeFields()" required
                                class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all"
                                style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                                <option value="product">Produit</option>
                                <option value="transport">Frais de transport</option>
                                <option value="labor">Main d'œuvre</option>
                                <option value="material">Matériel</option>
                            </select>
                        </div>
                        <div class="sm:col-span-12" id="product_field">
                            <label for="product_id" class="block text-sm font-medium text-gray-700">Produit *</label>
                            <select name="product_id" id="product_id" onchange="fillProductData()"
                                class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all"
                                style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                                <option value="">Sélectionner un produit</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-12" id="description_field" style="display: none;">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                            <input type="text" name="description" id="description" placeholder="Ex: Frais de transport, Main d'œuvre, etc."
                                class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all"
                                style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-6">
                        <div id="dimensions_fields">
                            <div>
                                <label for="width" class="block text-sm font-medium text-gray-700">Largeur (cm)</label>
                                <input type="number" step="0.01" min="0" name="width" id="width" placeholder="0.00"
                                oninput="calculateSurfaceAndAmount()"
                                class="block w-full rounded-lg border-2 border-gray-400 bg-white px-3 py-2.5 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all"
                                style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                            </div>
                        </div>
                        <div id="dimensions_fields_height">
                            <div>
                                <label for="height" class="block text-sm font-medium text-gray-700">Hauteur (cm)</label>
                                <input type="number" step="0.01" min="0" name="height" id="height" placeholder="0.00"
                                    oninput="calculateSurfaceAndAmount()"
                                    class="block w-full rounded-lg border-2 border-gray-400 bg-white px-3 py-2.5 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all"
                                    style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                            </div>
                        </div>
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantité *</label>
                            <input type="number" step="0.01" min="0.01" name="quantity" id="quantity" value="1" required
                                oninput="calculateSurfaceAndAmount()"
                                class="block w-full rounded-lg border-2 border-gray-400 bg-white px-3 py-2.5 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all"
                                style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                        </div>
                        <div id="nombre_fenetres_field" style="display: none;">
                            <label for="nombre_total_fenetres" class="block text-sm font-medium text-gray-700">Nombre total fenêtres</label>
                            <input type="number" step="1" min="1" name="nombre_total_fenetres" id="nombre_total_fenetres" placeholder="1"
                                class="block w-full rounded-lg border-2 border-gray-400 bg-white px-3 py-2.5 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all"
                                style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                        </div>
                        <div id="price_per_m2_field">
                            <label for="price_per_m2" class="block text-sm font-medium text-gray-700">Prix M² (GNF) *</label>
                            <input type="number" step="0.01" min="0" name="price_per_m2" id="price_per_m2" placeholder="0.00"
                                oninput="calculateSurfaceAndAmount()"
                                onkeypress="if(event.key === 'Enter') { event.preventDefault(); submitLineForm(event); }"
                                class="block w-full rounded-lg border-2 border-gray-400 bg-white px-3 py-2.5 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all"
                                style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                        </div>
                        <div id="unit_price_field" style="display: none;">
                            <label for="unit_price" class="block text-sm font-medium text-gray-700">Prix unitaire (GNF)</label>
                            <input type="number" step="0.01" min="0" name="unit_price" id="unit_price" placeholder="0.00"
                                oninput="calculateAmountForOtherTypes()"
                                onkeypress="if(event.key === 'Enter') { event.preventDefault(); submitLineForm(event); }"
                                class="block w-full rounded-lg border-2 border-gray-400 bg-white px-3 py-2.5 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all"
                                style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                        </div>
                        <div id="unit_field" style="display: none;">
                            <label for="unit" class="block text-sm font-medium text-gray-700">Unité</label>
                            <input type="text" name="unit" id="unit" placeholder="Ex: frais, heure, unité"
                                class="block w-full rounded-lg border-2 border-gray-400 bg-white px-3 py-2.5 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all"
                                style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                        </div>
                        <div id="surface_field">
                            <label for="surface" class="block text-sm font-medium text-gray-700">
                                Surface (m²)
                                <span class="text-xs text-gray-500 font-normal">(calculée)</span>
                            </label>
                            <input type="number" step="0.01" min="0" name="surface" id="surface" readonly
                                class="block w-full rounded-lg border-2 border-gray-400 bg-white px-3 py-2.5 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all"
                                style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                        </div>
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">
                                Montant (GNF)
                                <span class="text-xs text-gray-500 font-normal">(calculé)</span>
                            </label>
                            <input type="number" step="0.01" min="0" name="amount" id="amount" readonly
                                class="block w-full rounded-lg border-2 border-gray-300 bg-gray-100 px-3 py-2.5 text-gray-700 text-base shadow-sm cursor-not-allowed"
                                placeholder="0.00">
                        </div>
                    </div>
                    
                    <div class="mt-4 flex justify-end space-x-3 border-t pt-4">
                        <button type="button" onclick="cancelEdit()" id="cancelBtn" class="hidden bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition-colors">
                            <i class="fas fa-times mr-2"></i>Annuler
                        </button>
                        <button type="button" onclick="resetForm()" id="resetBtn"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition-colors">
                            <i class="fas fa-redo mr-2"></i>Réinitialiser
                        </button>
                        <button type="button" onclick="submitLineForm(event)" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors shadow-md" id="submitBtn">
                            <i class="fas fa-check mr-2"></i>Ajouter la ligne
                        </button>
                    </div>
                    </div>
                </form>
            </div>

            <!-- Tableau des lignes -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Largeur</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Hauteur</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Quantité</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Prix M²</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Surface</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Montant</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            // Grouper les lignes par description (produit)
                            $groupedLines = $quote->lines->groupBy('description');
                            $currentProduct = null;
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
                                <td class="px-3 sm:px-6 py-4 text-sm text-gray-900">{{ $line->description }}</td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right hidden sm:table-cell">{{ $line->width ? number_format($line->width, 2, ',', ' ') : '-' }}</td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right hidden sm:table-cell">{{ $line->height ? number_format($line->height, 2, ',', ' ') : '-' }}</td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ number_format($line->quantity, 2, ',', ' ') }} {{ $line->unit }}</td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right hidden md:table-cell">{{ $line->price_per_m2 ? number_format($line->price_per_m2, 2, ',', ' ') . ' GNF' : '-' }}</td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right hidden md:table-cell">{{ $line->surface ? number_format($line->surface, 2, ',', ' ') . ' m²' : '-' }}</td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">{{ $line->amount ? number_format($line->amount, 2, ',', ' ') . ' GNF' : number_format($line->subtotal, 2, ',', ' ') . ' GNF' }}</td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-1 sm:space-x-2">
                                        <button type="button" onclick="duplicateLine({{ $line->id }})" class="text-green-600 hover:text-green-900" title="Dupliquer">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <button type="button" onclick="editLine({{ $line->id }})" class="text-blue-600 hover:text-blue-900" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('quotes.lines.destroy', [$quote, $line]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette ligne ?')" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
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
                                <td></td>
                            </tr>
                            @endif
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Aucune ligne ajoutée</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50">
                        @php
                            $totalQuantity = $quote->lines->where('line_type', 'product')->sum('quantity');
                            $totalSurface = $quote->lines->sum('surface');
                        @endphp
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-right text-sm font-medium text-gray-900">MONTANT TOTAL</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600 text-right text-lg">{{ number_format($quote->total, 2, ',', ' ') }} GNF</td>
                            <td></td>
                        </tr>
                        <tr class="bg-blue-50">
                            <td colspan="6" class="px-6 py-3 text-right text-sm font-semibold text-blue-900">
                                QUANTITE TOTAL
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-blue-900 text-right">
                                {{ number_format($totalQuantity, 2, ',', ' ') }} unité
                            </td>
                            <td></td>
                        </tr>
                        <tr class="bg-blue-50">
                            <td colspan="6" class="px-6 py-3 text-right text-sm font-semibold text-blue-900">
                                SURFACE TOTAL
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-blue-900 text-right">
                                {{ number_format($totalSurface, 2, ',', ' ') }} m²
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Section Montant Négocié et Validation -->
            @if($quote->status === 'accepted' || $quote->status === 'validated')
            <div id="validation" class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Montant initial calculé
                        </label>
                        <div class="text-xl sm:text-2xl font-bold text-gray-900">
                            {{ number_format($quote->subtotal, 0, ',', ' ') }} GNF
                        </div>
                    </div>
                    <div>
                        @if($quote->status === 'validated' && $quote->final_amount)
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Montant final validé
                            </label>
                            <div class="text-xl sm:text-2xl font-bold text-green-600">
                                {{ number_format($quote->final_amount, 0, ',', ' ') }} GNF
                            </div>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Devis validé
                                </span>
                            </div>
                        @elseif($quote->status === 'accepted')
                            <label for="final_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Montant final s'accorder (GNF)
                            </label>
                            <form action="{{ route('quotes.validate', $quote) }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                                @csrf
                                <input 
                                    type="number" 
                                    id="final_amount" 
                                    name="final_amount" 
                                    step="0.01"
                                    min="0"
                                    value="{{ old('final_amount', $quote->final_amount) }}"
                                    placeholder="{{ number_format($quote->subtotal, 0, ',', ' ') }}"
                                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-base"
                                    required
                                >
                                <button 
                                    type="submit" 
                                    class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 sm:px-6 rounded transition-all duration-200 shadow-md hover:shadow-lg"
                                >
                                    <i class="fas fa-check mr-2"></i><span class="hidden sm:inline">Valider le devis</span><span class="sm:hidden">Valider</span>
                                </button>
                            </form>
                            @error('final_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('quotes.print', $quote) }}" target="_blank" class="w-full sm:w-auto bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded text-center transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-print mr-2"></i>Imprimer
                </a>
                <a href="{{ route('quotes.index') }}" class="w-full sm:w-auto bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-center transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modale de confirmation pour la modification des prix -->
<div id="confirmPriceModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="closeConfirmModal()"></div>
        
        <!-- Centrer la modale -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Contenu de la modale -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-blue-600 text-xl"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Confirmation
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="confirmMessage">
                                Êtes-vous sûr de vouloir modifier le prix M² pour toutes les lignes ?
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="confirmOkBtn" onclick="confirmPriceUpdate()"
                    class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white sm:ml-3 sm:w-auto sm:text-sm transition-all duration-200"
                    style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.2)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)'">
                    OK
                </button>
                <button type="button" onclick="closeConfirmModal()" 
                    class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-200">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Fonction pour soumettre le formulaire de mise à jour
function submitUpdateForm() {
    const updateForm = document.getElementById('updateQuoteForm');
    const updateBtn = document.getElementById('updateQuoteBtn');
    
    if (!updateForm) {
        console.error('Formulaire de mise à jour introuvable');
        return;
    }
    
    // Vérifier que tous les champs requis sont remplis
    const clientId = document.getElementById('client_id');
    const date = document.getElementById('date');
    
    if (!clientId || !clientId.value) {
        alert('Veuillez sélectionner un client.');
        if (clientId) clientId.focus();
        return;
    }
    
    if (!date || !date.value) {
        alert('Veuillez sélectionner une date.');
        if (date) date.focus();
        return;
    }
    
    // Désactiver le bouton pour éviter les doubles soumissions
    if (updateBtn) {
        updateBtn.disabled = true;
        updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mise à jour...';
    }
    
    // Soumettre le formulaire
    updateForm.submit();
}

function toggleLineTypeFields() {
    const lineType = document.getElementById('line_type').value;
    const productField = document.getElementById('product_field');
    const descriptionField = document.getElementById('description_field');
    const dimensionsFields = document.getElementById('dimensions_fields');
    const dimensionsFieldsHeight = document.getElementById('dimensions_fields_height');
    const pricePerM2Field = document.getElementById('price_per_m2_field');
    const unitPriceField = document.getElementById('unit_price_field');
    const unitField = document.getElementById('unit_field');
    const surfaceField = document.getElementById('surface_field');
    
    if (lineType === 'product') {
        // Afficher les champs pour les produits
        productField.style.display = 'block';
        productField.querySelector('#product_id').required = true;
        descriptionField.style.display = 'none';
        descriptionField.querySelector('#description').required = false;
        dimensionsFields.style.display = 'block';
        dimensionsFieldsHeight.style.display = 'block';
        pricePerM2Field.style.display = 'block';
        pricePerM2Field.querySelector('#price_per_m2').required = true;
        unitPriceField.style.display = 'none';
        unitPriceField.querySelector('#unit_price').required = false;
        unitField.style.display = 'none';
        // Définir l'unité à "unité" pour les produits (masquée mais définie)
        document.getElementById('unit').value = 'unité';
        surfaceField.style.display = 'block';
        
    } else {
        // Afficher les champs pour les autres types
        productField.style.display = 'none';
        productField.querySelector('#product_id').required = false;
        productField.querySelector('#product_id').value = '';
        descriptionField.style.display = 'block';
        descriptionField.querySelector('#description').required = true;
        dimensionsFields.style.display = 'none';
        dimensionsFieldsHeight.style.display = 'none';
        pricePerM2Field.style.display = 'none';
        pricePerM2Field.querySelector('#price_per_m2').required = false;
        pricePerM2Field.querySelector('#price_per_m2').value = '';
        unitPriceField.style.display = 'block';
        unitPriceField.querySelector('#unit_price').required = true;
        unitField.style.display = 'block';
        surfaceField.style.display = 'none';
        
        // Définir l'unité par défaut selon le type
        const unitInput = document.getElementById('unit');
        switch(lineType) {
            case 'transport':
                unitInput.value = 'frais';
                break;
            case 'labor':
                unitInput.value = 'heure';
                break;
            case 'material':
                unitInput.value = 'unité';
                break;
        }
        
        // Réinitialiser les champs de dimensions
        document.getElementById('width').value = '';
        document.getElementById('height').value = '';
        document.getElementById('surface').value = '';
        
        // Focus sur le champ Prix unitaire après un délai pour s'assurer que le champ est visible
        // Utiliser plusieurs tentatives pour s'assurer que le focus fonctionne
        const focusUnitPrice = () => {
            const unitPriceField = document.getElementById('unit_price');
            const unitPriceFieldContainer = document.getElementById('unit_price_field');
            if (unitPriceField && unitPriceFieldContainer) {
                // Vérifier que le champ est visible
                const computedStyle = window.getComputedStyle(unitPriceFieldContainer);
                const isVisible = computedStyle.display !== 'none' && 
                                 computedStyle.visibility !== 'hidden' &&
                                 unitPriceFieldContainer.offsetParent !== null;
                if (isVisible && !unitPriceField.disabled) {
                    try {
                        unitPriceField.focus();
                        // Vérifier que le focus a bien été appliqué
                        if (document.activeElement === unitPriceField) {
                            unitPriceField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    } catch (e) {
                        console.log('Erreur lors du focus:', e);
                    }
                }
            }
        };
        
        // Essayer plusieurs fois avec des délais différents
        if (shouldFocusOnUnitPrice) {
            requestAnimationFrame(() => {
                setTimeout(focusUnitPrice, 100);
                setTimeout(focusUnitPrice, 250);
                setTimeout(focusUnitPrice, 400);
            });
        }
    }
    
    calculateAmountForOtherTypes();
}

function calculateAmountForOtherTypes() {
    const lineType = document.getElementById('line_type').value;
    const quantity = parseFloat(document.getElementById('quantity').value) || 0;
    const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
    const amountInput = document.getElementById('amount');
    
    if (lineType !== 'product' && quantity > 0 && unitPrice > 0) {
        const amount = quantity * unitPrice;
        amountInput.value = amount.toFixed(2);
        amountInput.classList.add('bg-green-50');
    } else if (lineType === 'product') {
        calculateSurfaceAndAmount();
    } else {
        amountInput.value = '';
        amountInput.classList.remove('bg-green-50');
    }
}

function fillProductData() {
    // Recalculer après avoir sélectionné un produit
    calculateSurfaceAndAmount();
}



function editLine(lineId) {
    // Récupérer les données de la ligne via AJAX
    fetch('{{ route("quotes.lines.edit", [$quote, ":lineId"]) }}'.replace(':lineId', lineId), {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.line) {
            const line = data.line;
            
            // Remplir le formulaire avec les données de la ligne
            document.getElementById('line_id').value = line.id;
            document.getElementById('line_type').value = line.line_type || 'product';
            toggleLineTypeFields(); // Afficher/masquer les champs selon le type
            document.getElementById('product_id').value = line.product_id || '';
            document.getElementById('description').value = line.description || '';
            document.getElementById('width').value = line.width || '';
            document.getElementById('height').value = line.height || '';
            document.getElementById('quantity').value = line.quantity || 1;
            document.getElementById('price_per_m2').value = line.price_per_m2 || '';
            document.getElementById('unit_price').value = line.unit_price || '';
            document.getElementById('unit').value = line.unit || '';
            document.getElementById('surface').value = line.surface || '';
            document.getElementById('amount').value = line.amount || '';
            
            // Changer le titre et le bouton
            document.getElementById('formTitle').innerHTML = '<i class="fas fa-edit mr-2"></i>Modifier la ligne';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-check mr-2"></i>Modifier la ligne';
            document.getElementById('resetBtn').classList.add('hidden');
            document.getElementById('cancelBtn').classList.remove('hidden');
            
            // Afficher le formulaire
            document.getElementById('formFields').style.display = 'block';
            document.getElementById('addLineForm').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            
            // Changer l'action du formulaire
            const form = document.getElementById('addLineFormElement');
            form.action = '{{ route("quotes.lines.update", [$quote, ":lineId"]) }}'.replace(':lineId', line.id);
            form.method = 'POST';
            
            // Ajouter le champ _method pour PUT
            let methodInput = document.getElementById('_method');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.id = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);
            } else {
                methodInput.value = 'PUT';
            }
            
            calculateSurfaceAndAmount();
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors du chargement de la ligne.');
    });
}

function duplicateLine(lineId) {
    if (!confirm('Voulez-vous dupliquer cette ligne ?')) {
        return;
    }
    
    // Récupérer les données de la ligne via AJAX
    fetch('{{ route("quotes.lines.edit", [$quote, ":lineId"]) }}'.replace(':lineId', lineId), {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.line) {
            const line = data.line;
            
            // Créer un formulaire pour dupliquer la ligne
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("quotes.lines.duplicate", [$quote, ":lineId"]) }}'.replace(':lineId', lineId);
            
            // Ajouter le token CSRF
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('input[name="_token"]').value;
            form.appendChild(csrfInput);
            
            // Soumettre le formulaire
            document.body.appendChild(form);
            form.submit();
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de la duplication de la ligne.');
    });
}

function cancelEdit() {
    // Réinitialiser le formulaire
    document.getElementById('line_id').value = '';
    document.getElementById('addLineFormElement').action = '{{ route("quotes.lines.store", $quote) }}';
    document.getElementById('addLineFormElement').method = 'POST';
    
    const methodInput = document.getElementById('_method');
    if (methodInput) {
        methodInput.remove();
    }
    
    resetForm();
    
    // Remettre le titre et les boutons
    document.getElementById('formTitle').innerHTML = '<i class="fas fa-plus-circle mr-2"></i>Ajouter une nouvelle ligne';
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-check mr-2"></i>Ajouter la ligne';
    document.getElementById('resetBtn').classList.remove('hidden');
    document.getElementById('cancelBtn').classList.add('hidden');
}

// Variables globales pour la modale de confirmation
let pendingPriceUpdate = null;

function updateAllPrices() {
    const pricePerM2 = document.getElementById('global_price_per_m2').value;
    const productId = document.getElementById('global_product_select').value;
    
    if (!pricePerM2 || parseFloat(pricePerM2) <= 0) {
        alert('Veuillez saisir un prix M² valide.');
        return;
    }
    
    const productName = productId 
        ? document.getElementById('global_product_select').options[document.getElementById('global_product_select').selectedIndex].text
        : 'toutes les lignes';
    
    // Préparer les données pour la soumission
    pendingPriceUpdate = {
        pricePerM2: pricePerM2,
        productId: productId
    };
    
    // Mettre à jour le message de confirmation
    const confirmMessage = document.getElementById('confirmMessage');
    if (confirmMessage) {
        confirmMessage.textContent = `Êtes-vous sûr de vouloir modifier le prix M² pour ${productName} ?`;
    }
    
    // Afficher la modale
    showConfirmModal();
}

function showConfirmModal() {
    const modal = document.getElementById('confirmPriceModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Empêcher le scroll de la page
    }
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmPriceModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = ''; // Restaurer le scroll
    }
    pendingPriceUpdate = null;
}

function confirmPriceUpdate() {
    if (!pendingPriceUpdate) {
        closeConfirmModal();
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("quotes.update-all-prices", $quote) }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('input[name="_token"]').value;
    form.appendChild(csrfToken);
    
    const priceInput = document.createElement('input');
    priceInput.type = 'hidden';
    priceInput.name = 'price_per_m2';
    priceInput.value = pendingPriceUpdate.pricePerM2;
    form.appendChild(priceInput);
    
    if (pendingPriceUpdate.productId) {
        const productInput = document.createElement('input');
        productInput.type = 'hidden';
        productInput.name = 'product_id';
        productInput.value = pendingPriceUpdate.productId;
        form.appendChild(productInput);
    }
    
    document.body.appendChild(form);
    form.submit();
    
    closeConfirmModal();
}

function calculateSurfaceAndAmount() {
    const width = parseFloat(document.getElementById('width').value) || 0;
    const height = parseFloat(document.getElementById('height').value) || 0;
    const pricePerM2 = parseFloat(document.getElementById('price_per_m2').value) || 0;
    const quantity = parseFloat(document.getElementById('quantity').value) || 0;
    const surfaceInput = document.getElementById('surface');
    const amountInput = document.getElementById('amount');
    
    // Calculer la surface = (Largeur x Hauteur) / 10000 x Quantité
    let surface = 0;
    if (width > 0 && height > 0 && quantity > 0) {
        surface = ((width * height) / 10000) * quantity; // Conversion cm² vers m² puis multiplication par quantité
        surfaceInput.value = surface.toFixed(3);
        surfaceInput.classList.add('bg-green-50');
    } else {
        surfaceInput.value = '';
        surfaceInput.classList.remove('bg-green-50');
    }
    
    // Calculer le montant = Surface x Prix M²
    if (surface > 0 && pricePerM2 > 0) {
        const amount = surface * pricePerM2;
        amountInput.value = amount.toFixed(2);
        amountInput.classList.add('bg-green-50');
    } else {
        amountInput.value = '';
        amountInput.classList.remove('bg-green-50');
    }
}

function toggleForm() {
    const formFields = document.getElementById('formFields');
    const toggleIcon = document.getElementById('toggleIcon');
    if (formFields.style.display === 'none') {
        formFields.style.display = 'block';
        toggleIcon.classList.remove('fa-chevron-down');
        toggleIcon.classList.add('fa-chevron-up');
    } else {
        formFields.style.display = 'none';
        toggleIcon.classList.remove('fa-chevron-up');
        toggleIcon.classList.add('fa-chevron-down');
    }
}

function resetForm() {
    // Réinitialiser le type de ligne à 'product'
    document.getElementById('line_type').value = 'product';
    toggleLineTypeFields();
    
    // Réinitialiser tous les champs
    document.getElementById('line_id').value = '';
    document.getElementById('product_id').value = '';
    document.getElementById('description').value = '';
    document.getElementById('width').value = '';
    document.getElementById('height').value = '';
    document.getElementById('quantity').value = 1;
    document.getElementById('price_per_m2').value = '';
    document.getElementById('unit_price').value = '';
    document.getElementById('unit').value = '';
    document.getElementById('surface').value = '';
    document.getElementById('amount').value = '';
    
    // Réinitialiser le titre et le bouton
    document.getElementById('formTitle').innerHTML = '<i class="fas fa-plus-circle mr-2"></i>Ajouter une nouvelle ligne';
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-plus mr-2"></i>Ajouter la ligne';
    document.getElementById('resetBtn').classList.remove('hidden');
    document.getElementById('cancelBtn').classList.add('hidden');
    
    // Réinitialiser l'action du formulaire
    const form = document.getElementById('addLineFormElement');
    form.action = '{{ route("quotes.lines.store", $quote) }}';
    
    calculateSurfaceAndAmount();
    
    // Focus sur le champ approprié selon le type de ligne
    setTimeout(() => {
        const lineType = document.getElementById('line_type').value;
        if (lineType === 'product') {
            const widthField = document.getElementById('width');
            if (widthField) {
                widthField.focus();
            }
        } else {
            const unitPriceField = document.getElementById('unit_price');
            const unitPriceFieldContainer = document.getElementById('unit_price_field');
            if (unitPriceField && unitPriceFieldContainer && unitPriceFieldContainer.style.display !== 'none') {
                unitPriceField.focus();
            }
        }
    }, 200);
}

function submitLineForm(event) {
    event.preventDefault();
    
    const form = document.getElementById('addLineFormElement');
    const formData = new FormData(form);
    
    // Sauvegarder le produit sélectionné avant la soumission
    const productId = document.getElementById('product_id').value;
    const lineId = document.getElementById('line_id').value;
    const isEdit = lineId !== '';
    
    // Récupérer le token CSRF
    const csrfToken = document.querySelector('input[name="_token"]').value;
    
    // Si c'est une modification, utiliser PUT
    const method = isEdit ? 'POST' : 'POST';
    
    fetch(form.action, {
        method: method,
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        if (response.ok) {
            return response.json().catch(() => ({}));
        } else {
            return response.json().then(data => {
                throw new Error(data.message || 'Une erreur est survenue');
            }).catch(() => {
                return response.text().then(text => {
                    throw new Error(text || 'Une erreur est survenue');
                });
            });
        }
    })
    .then(data => {
        if (data.success !== false) {
            // Sauvegarder le produit dans sessionStorage avant le rechargement
            if (productId && !isEdit) {
                sessionStorage.setItem('selectedProductId', productId);
            }
            
            // Sauvegarder l'info pour le focus après rechargement
            sessionStorage.setItem('focusOnWidth', 'true');
            
            // Recharger la page pour afficher les modifications
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        const errorMessage = error.message || 'Une erreur est survenue lors de ' + (isEdit ? 'la modification' : 'l\'ajout') + ' de la ligne.';
        alert(errorMessage);
    });
    
    return false;
}

// Initialiser les champs selon le type de ligne au chargement
document.addEventListener('DOMContentLoaded', function() {
    // Pré-remplir avec les données du modèle si disponibles
    @if(isset($modeleData) && $modeleData)
    const modeleData = @json($modeleData);
    if (modeleData) {
        // Pré-remplir la description
        const descriptionField = document.getElementById('description');
        if (descriptionField) {
            descriptionField.value = modeleData.nom || '';
            descriptionField.style.display = 'block';
        }
        
        // Pré-remplir le prix unitaire si disponible
        if (modeleData.prix_indicatif) {
            const unitPriceField = document.getElementById('unit_price');
            if (unitPriceField) {
                unitPriceField.value = modeleData.prix_indicatif;
            }
        }
        
        // Afficher un message
        if (typeof alert !== 'undefined') {
            setTimeout(() => {
                alert('Modèle "' + modeleData.nom + '" ajouté. Veuillez compléter les informations.');
            }, 500);
        }
    }
    @endif
    
    // Initialiser les champs selon le type sélectionné
    const lineTypeSelect = document.getElementById('line_type');
    if (lineTypeSelect) {
        toggleLineTypeFields();
        
        // Surcharger la fonction toggleLineTypeFields pour ajouter le focus
        // Note: toggleLineTypeFields() est aussi appelé via onchange dans le HTML
        const originalToggle = window.toggleLineTypeFields;
        if (originalToggle) {
            window.toggleLineTypeFields = function() {
                const lineType = document.getElementById('line_type').value;
                originalToggle();
                
                // Forcer le focus après que toggleLineTypeFields a terminé
                if (lineType !== 'product') {
                    // Utiliser plusieurs tentatives avec des délais croissants
                    const attemptFocus = (attempt = 0) => {
                        if (attempt >= 6) return; // Maximum 6 tentatives
                        setTimeout(() => {
                            const unitPriceField = document.getElementById('unit_price');
                            const unitPriceFieldContainer = document.getElementById('unit_price_field');
                            if (unitPriceField && unitPriceFieldContainer) {
                                const computedStyle = window.getComputedStyle(unitPriceFieldContainer);
                                const isVisible = computedStyle.display !== 'none' && 
                                                 computedStyle.visibility !== 'hidden' &&
                                                 unitPriceFieldContainer.offsetParent !== null;
                                if (isVisible && !unitPriceField.disabled) {
                                    unitPriceField.focus();
                                    // Vérifier si le focus a réussi
                                    if (document.activeElement === unitPriceField) {
                                        return; // Focus réussi, arrêter les tentatives
                                    }
                                }
                            }
                            // Réessayer si le focus n'a pas fonctionné
                            if (attempt < 5) {
                                attemptFocus(attempt + 1);
                            }
                        }, 150 * (attempt + 1)); // Délais croissants: 150ms, 300ms, 450ms, etc.
                    };
                    // Commencer après le prochain frame de rendu
                    requestAnimationFrame(() => {
                        requestAnimationFrame(() => attemptFocus());
                    });
                }
            };
        }
    }
    
    // Calculer automatiquement la surface au chargement si les valeurs existent
    calculateSurfaceAndAmount();
    
    // Gestionnaire pour fermer la modale avec la touche Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('confirmPriceModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeConfirmModal();
            }
        }
    });
    
    // Le formulaire reste ouvert par défaut
    const formFields = document.getElementById('formFields');
    if (formFields) {
        formFields.style.display = 'block';
    }
    
    // Restaurer le produit sélectionné depuis sessionStorage
    const savedProductId = sessionStorage.getItem('selectedProductId');
    if (savedProductId) {
        const productSelect = document.getElementById('product_id');
        if (productSelect) {
            productSelect.value = savedProductId;
            // Ne pas supprimer immédiatement pour permettre plusieurs ajouts avec le même produit
        }
    }
    
    // Focus sur le champ approprié après ajout de ligne
    const focusOnWidth = sessionStorage.getItem('focusOnWidth');
    if (focusOnWidth === 'true') {
        sessionStorage.removeItem('focusOnWidth');
        // Attendre que le formulaire soit visible et le champ soit disponible
        setTimeout(() => {
            const lineType = document.getElementById('line_type').value;
            const formFields = document.getElementById('formFields');
            if (formFields && formFields.style.display === 'none') {
                formFields.style.display = 'block';
            }
            
            // Focus selon le type de ligne
            if (lineType === 'product') {
                const widthField = document.getElementById('width');
                if (widthField) {
                    widthField.focus();
                    widthField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                const unitPriceField = document.getElementById('unit_price');
                const unitPriceFieldContainer = document.getElementById('unit_price_field');
                if (unitPriceField && unitPriceFieldContainer && unitPriceFieldContainer.style.display !== 'none') {
                    unitPriceField.focus();
                    unitPriceField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        }, 500);
    } else {
        // Améliorer l'expérience utilisateur : focus automatique sur le champ approprié au chargement
        setTimeout(() => {
            const lineType = document.getElementById('line_type').value;
            if (lineType === 'product') {
                const widthField = document.getElementById('width');
                if (widthField && !widthField.value) {
                    widthField.focus();
                }
            } else {
                const unitPriceField = document.getElementById('unit_price');
                const unitPriceFieldContainer = document.getElementById('unit_price_field');
                if (unitPriceField && unitPriceFieldContainer && unitPriceFieldContainer.style.display !== 'none' && !unitPriceField.value) {
                    unitPriceField.focus();
                }
            }
        }, 200);
    }
});
</script>
@endsection

