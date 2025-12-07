@extends('layouts.app')

@section('title', 'Nouveau Modèle')

@section('content')
<div class="card-modern overflow-hidden">
    <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
         style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }}15 0%, {{ $settings->secondary_color ?? '#1e40af' }}15 100%);">
        <div class="flex items-center">
            <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                <i class="fas fa-plus text-white"></i>
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Nouveau Modèle</h2>
        </div>
    </div>

    <div class="p-6">
        <form action="{{ route('modeles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom -->
                <div class="md:col-span-2">
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                        Nom du modèle <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                        class="block w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm focus:ring-2 focus:border-transparent transition-all"
                        style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                        placeholder="Ex: Fenêtre coulissante 2 vantaux">
                    @error('nom')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Catégorie -->
                <div>
                    <label for="categorie" class="block text-sm font-medium text-gray-700 mb-2">
                        Catégorie <span class="text-red-500">*</span>
                    </label>
                    <select name="categorie" id="categorie" required
                        class="block w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm focus:ring-2 focus:border-transparent transition-all"
                        style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ old('categorie') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                    @error('categorie')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prix indicatif -->
                <div>
                    <label for="prix_indicatif" class="block text-sm font-medium text-gray-700 mb-2">
                        Prix indicatif (GNF)
                    </label>
                    <input type="number" name="prix_indicatif" id="prix_indicatif" value="{{ old('prix_indicatif') }}" step="0.01" min="0"
                        class="block w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm focus:ring-2 focus:border-transparent transition-all"
                        style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                        placeholder="0.00">
                    @error('prix_indicatif')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="4"
                        class="block w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm focus:ring-2 focus:border-transparent transition-all"
                        style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                        placeholder="Description détaillée du modèle...">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image -->
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Image du modèle
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-4xl"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="image" class="relative cursor-pointer rounded-md font-medium focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2"
                                       style="focus-within:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                                    <span>Cliquez pour télécharger</span>
                                    <input type="file" name="image" id="image" accept="image/*" class="sr-only">
                                </label>
                                <p class="pl-1">ou glissez-déposez</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 5MB</p>
                        </div>
                    </div>
                    <div id="imagePreview" class="mt-4 hidden">
                        <img id="previewImg" src="" alt="Aperçu" class="max-w-xs rounded-lg shadow-md">
                    </div>
                    @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Statut -->
                <div class="md:col-span-2">
                    <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">
                        Statut <span class="text-red-500">*</span>
                    </label>
                    <select name="statut" id="statut" required
                        class="block w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm focus:ring-2 focus:border-transparent transition-all"
                        style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                        <option value="actif" {{ old('statut', 'actif') == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ old('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>
                    @error('statut')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('modeles.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                    <i class="fas fa-times mr-2"></i>Annuler
                </a>
                <button type="submit" 
                        class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white transition-all duration-200"
                        style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                    <i class="fas fa-save mr-2"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endsection

