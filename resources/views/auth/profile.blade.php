@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-slate-50">
        <h2 class="text-2xl font-bold text-gray-900">
            <i class="fas fa-user-circle mr-2 text-gray-600"></i>Mon Profil
        </h2>
        <p class="mt-1 text-sm text-gray-600">Gérez vos informations personnelles et votre mot de passe</p>
    </div>

    <div class="p-6">
        <!-- Informations utilisateur -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                <i class="fas fa-info-circle mr-2" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>Informations du compte
            </h3>
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                    <div class="mt-1 px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-gray-900">
                        {{ Auth::user()->name }}
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
                    <div class="mt-1 px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-gray-900">
                        {{ Auth::user()->email }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire de changement de mot de passe -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                <i class="fas fa-lock mr-2" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>Changer le mot de passe
            </h3>
            
            <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-1 max-w-2xl">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                            Mot de passe actuel <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               name="current_password" 
                               id="current_password" 
                               required
                               class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all @error('current_password') border-red-500 @enderror"
                               style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                               placeholder="Entrez votre mot de passe actuel">
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Nouveau mot de passe <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               required
                               minlength="8"
                               class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all @error('password') border-red-500 @enderror"
                               style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                               placeholder="Minimum 8 caractères">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @else
                            <p class="mt-1 text-xs text-gray-500">Le mot de passe doit contenir au moins 8 caractères</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirmer le nouveau mot de passe <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               required
                               minlength="8"
                               class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all"
                               style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                               placeholder="Répétez le nouveau mot de passe">
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-4">
                    <a href="{{ route('quotes.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-times mr-2"></i>Annuler
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                            style="background-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                            onmouseover="this.style.opacity='0.9'"
                            onmouseout="this.style.opacity='1'">
                        <i class="fas fa-save mr-2"></i>Enregistrer le nouveau mot de passe
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


















