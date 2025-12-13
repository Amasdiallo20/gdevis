@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Activités des Techniciens')

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
                            <i class="fas fa-history text-white"></i>
                        </div>
                        Activités des Techniciens
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Suivez toutes les actions effectuées par les techniciens sur leurs tâches
                    </p>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="p-6">
            <form method="GET" action="{{ route('chantiers.activities') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Type d'activité</label>
                    <select name="type" onchange="this.form.submit()" 
                            class="w-full border-2 rounded-lg px-3 py-2 text-sm">
                        <option value="">Tous les types</option>
                        <option value="progress_updated" {{ request('type') == 'progress_updated' ? 'selected' : '' }}>Progression mise à jour</option>
                        <option value="status_changed" {{ request('type') == 'status_changed' ? 'selected' : '' }}>Statut modifié</option>
                        <option value="photo_uploaded" {{ request('type') == 'photo_uploaded' ? 'selected' : '' }}>Photo uploadée</option>
                        <option value="photo_comment_added" {{ request('type') == 'photo_comment_added' ? 'selected' : '' }}>Commentaire ajouté</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Technicien</label>
                    <select name="user_id" onchange="this.form.submit()" 
                            class="w-full border-2 rounded-lg px-3 py-2 text-sm">
                        <option value="">Tous les techniciens</option>
                        @foreach($techniciens as $tech)
                            <option value="{{ $tech->id }}" {{ request('user_id') == $tech->id ? 'selected' : '' }}>
                                {{ $tech->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
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
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Date début</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           onchange="this.form.submit()"
                           class="w-full border-2 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Date fin</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           onchange="this.form.submit()"
                           class="w-full border-2 rounded-lg px-3 py-2 text-sm">
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des activités -->
    <div class="space-y-4">
        @forelse($activities as $activity)
        <div class="card-modern">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <!-- Icône -->
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center"
                             style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }}15 0%, {{ $settings->secondary_color ?? '#1e40af' }}15 100%);">
                            <i class="fas {{ $activity->icon }} text-xl" 
                               style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
                        </div>
                    </div>

                    <!-- Contenu -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">
                                    <i class="fas {{ $activity->icon }} mr-2" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
                                    {{ $activity->type_label }}
                                </h3>
                                <p class="text-sm text-gray-700">{{ $activity->description }}</p>
                            </div>
                            <span class="text-xs text-gray-500 whitespace-nowrap ml-4">
                                {{ $activity->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>

                        <!-- Informations supplémentaires -->
                        <div class="mt-3 flex flex-wrap gap-3 text-xs">
                            <span class="px-2 py-1 rounded bg-blue-100 text-blue-800">
                                <i class="fas fa-user mr-1"></i>{{ $activity->user->name }}
                            </span>
                            @if($activity->tache)
                            <span class="px-2 py-1 rounded bg-indigo-100 text-indigo-800">
                                <i class="fas fa-tasks mr-1"></i>{{ $activity->tache->nom }}
                            </span>
                            @endif
                            @if($activity->chantier)
                            <span class="px-2 py-1 rounded bg-purple-100 text-purple-800">
                                <i class="fas fa-hard-hat mr-1"></i>
                                <a href="{{ route('chantiers.show', $activity->chantier) }}" class="hover:underline">
                                    {{ $activity->chantier->chantier_number }}
                                </a>
                            </span>
                            @endif
                        </div>

                        <!-- Données supplémentaires pour certains types -->
                        @if($activity->type == 'progress_updated' && $activity->data)
                        <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-4">
                                <div>
                                    <span class="text-xs text-gray-600">Ancienne progression:</span>
                                    <span class="ml-2 font-bold text-gray-900">{{ $activity->data['old_progress'] ?? 'N/A' }}%</span>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400"></i>
                                <div>
                                    <span class="text-xs text-gray-600">Nouvelle progression:</span>
                                    <span class="ml-2 font-bold" style="color: {{ $settings->primary_color ?? '#3b82f6' }};">{{ $activity->data['new_progress'] ?? 'N/A' }}%</span>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($activity->type == 'status_changed' && $activity->data)
                        <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-4">
                                <div>
                                    <span class="text-xs text-gray-600">Ancien statut:</span>
                                    <span class="ml-2 px-2 py-1 rounded bg-gray-200 text-gray-800 text-xs font-semibold">
                                        {{ $activity->data['old_status_label'] ?? 'N/A' }}
                                    </span>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400"></i>
                                <div>
                                    <span class="text-xs text-gray-600">Nouveau statut:</span>
                                    <span class="ml-2 px-2 py-1 rounded bg-blue-200 text-blue-800 text-xs font-semibold">
                                        {{ $activity->data['new_status_label'] ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($activity->type == 'photo_uploaded')
                        <div class="mt-3 space-y-2">
                            @php
                                // Récupérer la photo depuis l'ID stocké dans les données de l'activité
                                $photo = null;
                                if ($activity->data && isset($activity->data['photo_id'])) {
                                    $photo = \App\Models\PhotoTache::find($activity->data['photo_id']);
                                }
                            @endphp
                            
                            @if($photo && Storage::disk('public')->exists($photo->chemin_fichier))
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-600 mb-2 font-semibold">Photo uploadée:</p>
                                <div class="relative inline-block">
                                    <img src="{{ Storage::url($photo->chemin_fichier) }}" 
                                         alt="Photo tâche" 
                                         class="max-w-full h-auto max-h-64 rounded-lg cursor-pointer hover:opacity-90 transition-opacity shadow-md"
                                         onclick="openImageModal('{{ Storage::url($photo->chemin_fichier) }}', '{{ addslashes($photo->commentaire ?? '') }}')">
                                    <div class="absolute top-2 right-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs">
                                        <i class="fas fa-expand"></i> Cliquer pour agrandir
                                    </div>
                                </div>
                                @if($photo->commentaire)
                                <div class="mt-3 p-2 bg-white rounded border border-gray-200">
                                    <p class="text-xs text-gray-600 mb-1 font-semibold">Commentaire:</p>
                                    <p class="text-sm text-gray-800 italic">"{{ $photo->commentaire }}"</p>
                                </div>
                                @elseif($activity->data && isset($activity->data['commentaire']) && $activity->data['commentaire'])
                                <div class="mt-3 p-2 bg-white rounded border border-gray-200">
                                    <p class="text-xs text-gray-600 mb-1 font-semibold">Commentaire:</p>
                                    <p class="text-sm text-gray-800 italic">"{{ $activity->data['commentaire'] }}"</p>
                                </div>
                                @endif
                            </div>
                            @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <p class="text-xs text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Photo non disponible (fichier supprimé ou introuvable)
                                </p>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card-modern">
            <div class="p-12 text-center">
                <i class="fas fa-history text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-700 mb-2">Aucune activité</h3>
                <p class="text-gray-500">Aucune activité enregistrée pour le moment.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($activities->hasPages())
    <div class="mt-6">
        {{ $activities->links() }}
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

