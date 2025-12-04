@extends('layouts.app')

@section('title', 'Modifier un Paiement')

@section('content')
<div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-slate-50">
        <h2 class="text-2xl font-bold text-gray-900">
            <i class="fas fa-edit mr-2 text-gray-600"></i>Modifier un Paiement
        </h2>
        <p class="mt-1 text-sm text-gray-600">Devis {{ $quote->quote_number }} - Client: {{ $quote->client->name }}</p>
    </div>

    <div class="p-6">
        <!-- Résumé du devis -->
        <div class="mb-6 bg-blue-50 p-4 rounded-lg">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <p class="text-sm font-medium text-gray-600">Montant total</p>
                    <p class="text-xl font-bold text-blue-900">{{ number_format($quote->total, 2, ',', ' ') }} GNF</p>
                    @if($quote->status === 'validated' && $quote->final_amount)
                        <p class="text-xs text-gray-500 mt-1">(Montant final s'accorder)</p>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Déjà payé</p>
                    <p class="text-xl font-bold text-green-900">{{ number_format($quote->paid_amount - $payment->amount, 2, ',', ' ') }} GNF</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Solde restant</p>
                    @php
                        $paidWithoutCurrent = $quote->paid_amount - $payment->amount;
                        $remaining = max(0, $quote->total - $paidWithoutCurrent);
                    @endphp
                    <p class="text-xl font-bold text-orange-900">{{ number_format($remaining, 2, ',', ' ') }} GNF</p>
                </div>
            </div>
        </div>

        <form action="{{ route('payments.update', [$quote, $payment]) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">
                        Montant (GNF) <span class="text-red-500">*</span>
                    </label>
                    @php
                        $paidWithoutCurrent = $quote->paid_amount - $payment->amount;
                        $maxAmount = max(0, $quote->total - $paidWithoutCurrent);
                    @endphp
                    <input type="number" 
                           name="amount" 
                           id="amount" 
                           step="0.01" 
                           min="0.01" 
                           max="{{ $maxAmount }}"
                           required
                           value="{{ old('amount', $payment->amount) }}"
                           class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all @error('amount') border-red-500 @enderror"
                           style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                           placeholder="0.00">
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @else
                        <p class="mt-1 text-xs text-gray-500">Maximum: {{ number_format($maxAmount, 2, ',', ' ') }} GNF</p>
                    @enderror
                </div>

                <div>
                    <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Date de paiement <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="payment_date" 
                           id="payment_date" 
                           required
                           value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}"
                           class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all @error('payment_date') border-red-500 @enderror"
                           style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                    @error('payment_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">
                        Méthode de paiement <span class="text-red-500">*</span>
                    </label>
                    <select name="payment_method" 
                            id="payment_method" 
                            required
                            class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all @error('payment_method') border-red-500 @enderror"
                            style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                        <option value="cash" {{ old('payment_method', $payment->payment_method) == 'cash' ? 'selected' : '' }}>Espèces</option>
                        <option value="bank_transfer" {{ old('payment_method', $payment->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Virement bancaire</option>
                        <option value="check" {{ old('payment_method', $payment->payment_method) == 'check' ? 'selected' : '' }}>Chèque</option>
                        <option value="mobile_money" {{ old('payment_method', $payment->payment_method) == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                        <option value="other" {{ old('payment_method', $payment->payment_method) == 'other' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">
                        Référence
                    </label>
                    <input type="text" 
                           name="reference" 
                           id="reference" 
                           value="{{ old('reference', $payment->reference) }}"
                           class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all @error('reference') border-red-500 @enderror"
                           style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                           placeholder="Ex: CHQ-12345, VIR-67890">
                    @error('reference')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                        Notes
                    </label>
                    <textarea name="notes" 
                              id="notes" 
                              rows="3"
                              class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all @error('notes') border-red-500 @enderror"
                              style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                              placeholder="Notes supplémentaires sur ce paiement...">{{ old('notes', $payment->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="{{ route('quotes.show', $quote) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-times mr-2"></i>Annuler
                </a>
                <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                        style="background-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                        onmouseover="this.style.opacity='0.9'"
                        onmouseout="this.style.opacity='1'">
                    <i class="fas fa-save mr-2"></i>Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection



