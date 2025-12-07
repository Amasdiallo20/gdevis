@extends('layouts.app')

@section('title', 'Nouveau Matériau')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card-modern overflow-hidden">
        <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
             style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }}15 0%, {{ $settings->secondary_color ?? '#1e40af' }}15 100%);">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                    <i class="fas fa-tools text-white"></i>
                </div>
                Nouveau Matériau
            </h2>
            <p class="mt-2 text-sm text-gray-600">Ajoutez un nouveau matériau avec son prix unitaire</p>
        </div>

        <form action="{{ route('materials.store') }}" method="POST" class="p-6">
            @csrf

            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-box mr-1 text-gray-400"></i>Nom du matériau *
                </label>
                <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
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
                <input type="number" name="prix_unitaire" id="prix_unitaire" value="{{ old('prix_unitaire') }}" 
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
                <input type="text" name="unite" id="unite" value="{{ old('unite', 'barre') }}" required
                    class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all @error('unite') border-red-500 bg-red-50 @enderror"
                    style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                    placeholder="Ex: barre, mètre, kg">
                @error('unite')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

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



