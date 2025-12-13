@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Mes Tâches')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="card-modern overflow-hidden">
        <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
             style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }}15 0%, {{ $settings->secondary_color ?? '#1e40af' }}15 100%);">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                        <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                            <i class="fas fa-tasks text-white"></i>
                        </div>
                        Mes Tâches Assignées
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Gérez vos tâches et montrez l'évolution de votre travail
                    </p>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="p-6">
            <form method="GET" action="{{ route('chantiers.mes-taches') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Statut</label>
                    <select name="status" onchange="this.form.submit()" 
                            class="w-full border-2 rounded-lg px-3 py-2 text-sm">
                        <option value="">Tous les statuts</option>
                        <option value="a_faire" {{ request('status') == 'a_faire' ? 'selected' : '' }}>À faire</option>
                        <option value="en_cours" {{ request('status') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="termine" {{ request('status') == 'termine' ? 'selected' : '' }}>Terminé</option>
                        <option value="bloque" {{ request('status') == 'bloque' ? 'selected' : '' }}>Bloqué</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Chantier</label>
                    <select name="chantier_id" onchange="this.form.submit()" 
                            class="w-full border-2 rounded-lg px-3 py-2 text-sm">
                        <option value="">Tous les chantiers</option>
                        @foreach($chantiers as $chantier)
                            <option value="{{ $chantier->id }}" {{ request('chantier_id') == $chantier->id ? 'selected' : '' }}>
                                {{ $chantier->chantier_number }} - {{ $chantier->quote->client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des tâches -->
    <div class="space-y-4">
        @forelse($taches as $tache)
        <div class="card-modern">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Informations principales -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $tache->nom }}</h3>
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-3 py-1 text-xs rounded-full font-semibold {{ $tache->status == 'termine' ? 'bg-green-100 text-green-800' : ($tache->status == 'en_cours' ? 'bg-blue-100 text-blue-800' : ($tache->status == 'bloque' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ $tache->status_label }}
                                    </span>
                                    <span class="px-3 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800">
                                        <i class="fas fa-tag mr-1"></i>{{ $tache->type_label }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3">
                                    <i class="fas fa-hard-hat mr-1"></i>
                                    <a href="{{ route('chantiers.show', $tache->chantier) }}" class="text-blue-600 hover:underline">
                                        Chantier {{ $tache->chantier->chantier_number }}
                                    </a>
                                    - {{ $tache->chantier->quote->client->name }}
                                </p>
                                @if($tache->description)
                                <p class="text-sm text-gray-700 mb-3">{{ $tache->description }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Progression -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-semibold text-gray-700">Progression</label>
                                <span class="text-sm font-bold" style="color: {{ $settings->primary_color ?? '#3b82f6' }};">{{ $tache->progress }}%</span>
                            </div>
                            <form method="POST" action="{{ route('taches.update-progress', $tache) }}" class="mb-2">
                                @csrf
                                <div class="flex items-center gap-3">
                                    <input type="range" name="progress" min="0" max="100" value="{{ $tache->progress }}" 
                                           onchange="this.nextElementSibling.value = this.value"
                                           class="flex-1">
                                    <input type="number" name="progress_display" value="{{ $tache->progress }}" readonly 
                                           class="w-20 text-center font-bold text-sm border rounded px-2 py-1" 
                                           style="color: {{ $settings->primary_color ?? '#3b82f6' }};">
                                    <span class="text-sm text-gray-600">%</span>
                                    <button type="submit" class="btn-primary text-sm px-4 py-2 whitespace-nowrap">
                                        <i class="fas fa-save mr-1"></i>Enregistrer
                                    </button>
                                </div>
                            </form>
                            <div class="flex-1 bg-gray-200 rounded-full h-3 mt-2">
                                <div class="h-3 rounded-full transition-all" 
                                     style="width: {{ $tache->progress }}%; background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);"></div>
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="mb-4">
                            <form method="POST" action="{{ route('taches.update-progress', $tache) }}" class="flex items-center gap-3">
                                @csrf
                                <input type="hidden" name="progress" value="{{ $tache->progress }}">
                                <label class="text-sm font-semibold text-gray-700 whitespace-nowrap">Statut:</label>
                                <select name="status" onchange="this.form.submit()" 
                                        class="flex-1 border-2 rounded-lg px-3 py-2 text-sm">
                                    <option value="a_faire" {{ $tache->status == 'a_faire' ? 'selected' : '' }}>À faire</option>
                                    <option value="en_cours" {{ $tache->status == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="termine" {{ $tache->status == 'termine' ? 'selected' : '' }}>Terminé</option>
                                    <option value="bloque" {{ $tache->status == 'bloque' ? 'selected' : '' }}>Bloqué</option>
                                </select>
                            </form>
                        </div>

                        <!-- Upload de photos -->
                        <div class="border-t pt-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-camera mr-2"></i>Photos de l'évolution
                            </h4>
                            <form method="POST" action="{{ route('taches.photos.store', $tache) }}" 
                                  enctype="multipart/form-data" 
                                  class="flex items-center gap-2 mb-4">
                                @csrf
                                <input type="file" name="photo" accept="image/*" required 
                                       class="flex-1 text-sm border-2 rounded-lg px-3 py-2">
                                <input type="text" name="commentaire" placeholder="Commentaire..." 
                                       class="flex-1 text-sm border-2 rounded-lg px-3 py-2">
                                <button type="submit" class="btn-primary text-sm px-4 py-2 whitespace-nowrap">
                                    <i class="fas fa-upload mr-2"></i>Uploader
                                </button>
                            </form>

                            <!-- Galerie de photos -->
                            @if($tache->photos->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                @foreach($tache->photos as $photo)
                                <div class="bg-white border-2 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                    <img src="{{ Storage::url($photo->chemin_fichier) }}" 
                                         alt="Photo tâche" 
                                         class="w-full h-32 object-cover cursor-pointer"
                                         onclick="openImageModal('{{ Storage::url($photo->chemin_fichier) }}', '{{ $photo->commentaire ?? '' }}')">
                                    <div class="p-2">
                                        <form method="POST" action="{{ route('taches.photos.comment', [$tache, $photo]) }}" class="mb-2">
                                            @csrf
                                            <input type="text" name="commentaire" value="{{ $photo->commentaire ?? '' }}" 
                                                   placeholder="Ajouter un commentaire..." 
                                                   class="w-full text-xs border rounded px-2 py-1 mb-1"
                                                   onchange="this.form.submit()">
                                        </form>
                                        <p class="text-xs text-gray-400 mb-2">
                                            {{ $photo->created_at->format('d/m/Y H:i') }}
                                        </p>
                                        <div class="flex gap-2">
                                            <form method="POST" action="{{ route('taches.photos.destroy', [$tache, $photo]) }}" 
                                                  class="inline" 
                                                  onsubmit="return confirm('Supprimer cette photo?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs">
                                                    <i class="fas fa-trash mr-1"></i>Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-sm text-gray-500 text-center py-4">
                                <i class="fas fa-image mr-2"></i>Aucune photo uploadée pour cette tâche
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card-modern">
            <div class="p-12 text-center">
                <i class="fas fa-tasks text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-700 mb-2">Aucune tâche assignée</h3>
                <p class="text-gray-500">Vous n'avez actuellement aucune tâche assignée.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($taches->hasPages())
    <div class="mt-6">
        {{ $taches->links() }}
    </div>
    @endif
</div>

<!-- Modal pour voir l'image en grand -->
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-auto">
        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900">Photo de l'évolution</h3>
            <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-4">
            <img id="modalImage" src="" alt="Photo" class="w-full h-auto rounded-lg mb-3">
            <p id="modalComment" class="text-sm text-gray-600"></p>
        </div>
    </div>
</div>

<script>
function openImageModal(imageSrc, comment) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('modalComment').textContent = comment || '';
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('imageModal')?.addEventListener('click', function(e) {
    if (e.target.id === 'imageModal') {
        closeImageModal();
    }
});
</script>
@endsection

