@extends('layouts.app')

@section('title', 'Modifier Matériau')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card-modern overflow-hidden">
        <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
             style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }}15 0%, {{ $settings->secondary_color ?? '#1e40af' }}15 100%);">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                    <i class="fas fa-tools text-white"></i>
                </div>
                Modifier Matériau
            </h2>
            <p class="mt-2 text-sm text-gray-600">Modifiez les informations du matériau</p>
        </div>

        <form action="{{ route('materials.update', $material) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-box mr-1 text-gray-400"></i>Nom du matériau *
                </label>
                <input type="text" name="nom" id="nom" value="{{ old('nom', $material->nom) }}" required
                    class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all @error('nom') border-red-500 bg-red-50 @enderror"
                    style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                    placeholder="Ex: Cadre, Vento, Sikane">
                @error('nom')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label for="prix_unitaire" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tag mr-1 text-gray-400"></i>Prix Unitaire (GNF) *
                </label>
                <input type="number" name="prix_unitaire" id="prix_unitaire" value="{{ old('prix_unitaire', $material->prix_unitaire) }}" 
                    step="0.01" min="0" required
                    class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all @error('prix_unitaire') border-red-500 bg-red-50 @enderror"
                    style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                    placeholder="Ex: 250">
                @error('prix_unitaire')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label for="unite" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-ruler mr-1 text-gray-400"></i>Unité *
                </label>
                <select name="unite" id="unite" required
                    class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all @error('unite') border-red-500 bg-red-50 @enderror"
                    style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                    onchange="handleUniteChange(this)">
                    <option value="">Sélectionner une unité</option>
                    <option value="barre" {{ old('unite', $material->unite) == 'barre' ? 'selected' : '' }}>barre</option>
                    <option value="m" {{ old('unite', $material->unite) == 'm' ? 'selected' : '' }}>m (mètre)</option>
                    <option value="Paire" {{ old('unite', $material->unite) == 'Paire' ? 'selected' : '' }}>Paire</option>
                    <option value="unité" {{ old('unite', $material->unite) == 'unité' ? 'selected' : '' }}>unité</option>
                    <option value="feuille" {{ old('unite', $material->unite) == 'feuille' ? 'selected' : '' }}>feuille</option>
                    <option value="rouleau" {{ old('unite', $material->unite) == 'rouleau' ? 'selected' : '' }}>rouleau</option>
                    <option value="kg" {{ old('unite', $material->unite) == 'kg' ? 'selected' : '' }}>kg (kilogramme)</option>
                    <option value="__custom__">Autre (saisie libre)</option>
                </select>
                <input type="text" name="unite_custom" id="unite_custom" value="{{ old('unite', $material->unite) }}" 
                    class="hidden mt-2 block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all @error('unite') border-red-500 bg-red-50 @enderror"
                    style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                    placeholder="Saisir une unité personnalisée">
                @error('unite')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <script>
                function handleUniteChange(select) {
                    const customInput = document.getElementById('unite_custom');
                    if (select.value === '__custom__') {
                        customInput.classList.remove('hidden');
                        customInput.required = true;
                        select.required = false;
                        customInput.value = '';
                        customInput.focus();
                    } else {
                        customInput.classList.add('hidden');
                        customInput.required = false;
                        select.required = true;
                    }
                }

                // Vérifier si l'unité actuelle n'est pas dans la liste
                document.addEventListener('DOMContentLoaded', function() {
                    const select = document.getElementById('unite');
                    const currentUnite = '{{ old('unite', $material->unite) }}';
                    const options = Array.from(select.options).map(opt => opt.value);
                    
                    if (currentUnite && !options.includes(currentUnite)) {
                        // L'unité actuelle n'est pas dans la liste, afficher le champ personnalisé
                        select.value = '__custom__';
                        handleUniteChange(select);
                        document.getElementById('unite_custom').value = currentUnite;
                    }
                });

                // Gérer la soumission du formulaire
                document.querySelector('form').addEventListener('submit', function(e) {
                    const select = document.getElementById('unite');
                    const customInput = document.getElementById('unite_custom');
                    
                    if (select.value === '__custom__') {
                        // Utiliser la valeur du champ personnalisé
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'unite';
                        hiddenInput.value = customInput.value;
                        this.appendChild(hiddenInput);
                        select.disabled = true;
                    }
                });
            </script>

            <div class="mt-6 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 border-t pt-6">
                <a href="{{ route('materials.index') }}" 
                   class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-2"></i>Annuler
                </a>
                <button type="submit" 
                        class="inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white transition-all hover:shadow-md"
                        style="background-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                        onmouseover="this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-save mr-2"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection



