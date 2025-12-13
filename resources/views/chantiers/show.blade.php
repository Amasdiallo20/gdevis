@extends('layouts.app')

@section('title', 'Détails Chantier')

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
                            <i class="fas fa-hard-hat text-white"></i>
                        </div>
                        Chantier {{ $chantier->chantier_number }}
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Devis: <a href="{{ route('quotes.show', $chantier->quote) }}" class="text-blue-600 hover:underline">{{ $chantier->quote->quote_number }}</a> | 
                        Client: {{ $chantier->quote->client->name }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ url('chantiers') }}" 
                       class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 rounded-lg shadow-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>

        <!-- Informations générales -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Statut -->
                <div class="bg-white border-2 rounded-lg p-4">
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Statut</label>
                    <form method="POST" action="{{ route('chantiers.update-status', $chantier) }}" class="mb-2">
                        @csrf
                        <select name="status" onchange="this.form.submit()" 
                                class="block w-full rounded-lg border-2 border-gray-300 bg-white px-3 py-2 text-sm">
                            <option value="planifié" {{ $chantier->status == 'planifié' ? 'selected' : '' }}>Planifié</option>
                            <option value="en_cours" {{ $chantier->status == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="suspendu" {{ $chantier->status == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                            <option value="terminé" {{ $chantier->status == 'terminé' ? 'selected' : '' }}>Terminé</option>
                            <option value="facturé" {{ $chantier->status == 'facturé' ? 'selected' : '' }}>Facturé</option>
                        </select>
                    </form>
                </div>

                <!-- Avancement -->
                <div class="bg-white border-2 rounded-lg p-4">
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Avancement</label>
                    <form method="POST" action="{{ route('chantiers.update-progress', $chantier) }}" class="mb-2">
                        @csrf
                        <div class="flex items-center gap-2">
                            <input type="range" name="progress" min="0" max="100" value="{{ $chantier->progress }}" 
                                   onchange="this.nextElementSibling.value = this.value; this.form.submit()"
                                   class="flex-1">
                            <input type="number" value="{{ $chantier->progress }}" readonly 
                                   class="w-16 text-center font-bold text-lg" style="color: {{ $settings->primary_color ?? '#3b82f6' }};">
                            <span class="text-sm text-gray-600">%</span>
                        </div>
                    </form>
                </div>

                <!-- Dates -->
                <div class="bg-white border-2 rounded-lg p-4">
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Dates</label>
                    <form method="POST" action="{{ route('chantiers.update-dates', $chantier) }}">
                        @csrf
                        <div class="space-y-2">
                            <input type="date" name="date_debut" value="{{ $chantier->date_debut?->format('Y-m-d') }}" 
                                   placeholder="Début" class="w-full text-xs border rounded px-2 py-1">
                            <input type="date" name="date_fin_prevue" value="{{ $chantier->date_fin_prevue?->format('Y-m-d') }}" 
                                   placeholder="Fin prévue" class="w-full text-xs border rounded px-2 py-1">
                            <input type="date" name="date_fin_reelle" value="{{ $chantier->date_fin_reelle?->format('Y-m-d') }}" 
                                   placeholder="Fin réelle" class="w-full text-xs border rounded px-2 py-1">
                            <button type="submit" class="w-full text-xs bg-blue-500 text-white py-1 rounded">Mettre à jour</button>
                        </div>
                    </form>
                </div>

                <!-- Notes -->
                <div class="bg-white border-2 rounded-lg p-4">
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Notes</label>
                    <form method="POST" action="{{ route('chantiers.update-notes', $chantier) }}">
                        @csrf
                        <textarea name="notes" rows="4" placeholder="Notes..." 
                                  class="w-full text-xs border rounded px-2 py-1 mb-2">{{ $chantier->notes }}</textarea>
                        <button type="submit" class="w-full text-xs bg-blue-500 text-white py-1 rounded">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tâches -->
    <div class="card-modern">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">
                    <i class="fas fa-tasks mr-2" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
                    Tâches
                </h3>
                <button onclick="openModalTache()" 
                        class="btn-primary text-sm px-4 py-2">
                    <i class="fas fa-plus mr-2"></i>Ajouter une tâche
                </button>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($chantier->taches as $tache)
                <div class="bg-white border-2 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h4 class="font-bold text-gray-900 text-lg">{{ $tache->nom }}</h4>
                                <span class="px-2 py-1 text-xs rounded font-semibold {{ $tache->status == 'termine' ? 'bg-green-100 text-green-800' : ($tache->status == 'en_cours' ? 'bg-blue-100 text-blue-800' : ($tache->status == 'bloque' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ $tache->status_label }}
                                </span>
                                <span class="px-2 py-1 text-xs rounded bg-indigo-100 text-indigo-800">
                                    <i class="fas fa-tag mr-1"></i>{{ $tache->type_label }}
                                </span>
                            </div>
                            @if($tache->description)
                            <p class="text-sm text-gray-600 mt-1 mb-2">{{ $tache->description }}</p>
                            @endif
                            <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                <span><i class="fas fa-users mr-1"></i>{{ $tache->techniciens->count() }} technicien(s)</span>
                                @if($tache->date_debut_prevue)
                                <span><i class="fas fa-calendar mr-1"></i>Début: {{ $tache->date_debut_prevue->format('d/m/Y') }}</span>
                                @endif
                                @if($tache->date_fin_prevue)
                                <span><i class="fas fa-calendar-check mr-1"></i>Fin: {{ $tache->date_fin_prevue->format('d/m/Y') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="openModalEditTache({{ $tache->id }})" 
                                    class="text-blue-600 hover:text-blue-800 p-2 rounded hover:bg-blue-50" 
                                    title="Modifier"
                                    data-tache-nom="{{ $tache->nom }}"
                                    data-tache-description="{{ $tache->description ?? '' }}"
                                    data-tache-type="{{ $tache->type }}"
                                    data-tache-status="{{ $tache->status }}"
                                    data-tache-progress="{{ $tache->progress }}"
                                    data-tache-ordre="{{ $tache->ordre }}"
                                    data-tache-date-debut="{{ $tache->date_debut_prevue?->format('Y-m-d') ?? '' }}"
                                    data-tache-date-fin="{{ $tache->date_fin_prevue?->format('Y-m-d') ?? '' }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('chantiers.taches.destroy', [$chantier, $tache]) }}" 
                                  class="inline" 
                                  onsubmit="return confirm('Supprimer cette tâche?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 p-2 rounded hover:bg-red-50" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Progression de la tâche -->
                    <div class="mb-3">
                        <div class="flex items-center gap-3 mb-2">
                            <label class="text-xs font-semibold text-gray-700">Progression:</label>
                            <form method="POST" action="{{ route('chantiers.taches.update', [$chantier, $tache]) }}" class="flex items-center gap-2 flex-1">
                                @csrf
                                <input type="hidden" name="nom" value="{{ $tache->nom }}">
                                <input type="hidden" name="description" value="{{ $tache->description }}">
                                <input type="hidden" name="type" value="{{ $tache->type }}">
                                <input type="hidden" name="status" value="{{ $tache->status }}">
                                <input type="range" name="progress" min="0" max="100" value="{{ $tache->progress }}" 
                                       onchange="this.nextElementSibling.value = this.value; this.form.submit()"
                                       class="flex-1">
                                <input type="number" value="{{ $tache->progress }}" readonly 
                                       class="w-16 text-center font-bold text-sm" style="color: {{ $settings->primary_color ?? '#3b82f6' }};">
                                <span class="text-xs text-gray-600">%</span>
                            </form>
                        </div>
                        <div class="flex-1 bg-gray-200 rounded-full h-3">
                            <div class="h-3 rounded-full transition-all" 
                                 style="width: {{ $tache->progress }}%; background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);"></div>
                        </div>
                    </div>

                    <!-- Statut de la tâche -->
                    <div class="mb-3">
                        <form method="POST" action="{{ route('chantiers.taches.update', [$chantier, $tache]) }}" class="flex items-center gap-2">
                            @csrf
                            <input type="hidden" name="nom" value="{{ $tache->nom }}">
                            <input type="hidden" name="description" value="{{ $tache->description }}">
                            <input type="hidden" name="type" value="{{ $tache->type }}">
                            <input type="hidden" name="progress" value="{{ $tache->progress }}">
                            <label class="text-xs font-semibold text-gray-700">Statut:</label>
                            <select name="status" onchange="this.form.submit()" 
                                    class="flex-1 text-xs border rounded px-2 py-1">
                                <option value="a_faire" {{ $tache->status == 'a_faire' ? 'selected' : '' }}>À faire</option>
                                <option value="en_cours" {{ $tache->status == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                <option value="termine" {{ $tache->status == 'termine' ? 'selected' : '' }}>Terminé</option>
                                <option value="bloque" {{ $tache->status == 'bloque' ? 'selected' : '' }}>Bloqué</option>
                            </select>
                        </form>
                    </div>

                    <!-- Assignation des techniciens -->
                    <div class="border-t pt-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-2">
                            <i class="fas fa-users mr-1"></i>Techniciens assignés:
                        </label>
                        <form method="POST" action="{{ route('chantiers.taches.assign-techniciens', [$chantier, $tache]) }}" class="flex items-center gap-2">
                            @csrf
                            <select name="techniciens[]" multiple 
                                    class="flex-1 text-sm border-2 rounded-lg px-3 py-2 min-h-[80px]"
                                    style="min-width: 200px;">
                                @foreach($techniciens as $tech)
                                    <option value="{{ $tech->id }}" {{ $tache->techniciens->contains($tech->id) ? 'selected' : '' }}>
                                        {{ $tech->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn-primary text-sm px-4 py-2 whitespace-nowrap">
                                <i class="fas fa-save mr-2"></i>Enregistrer
                            </button>
                        </form>
                        @if($tache->techniciens->count() > 0)
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($tache->techniciens as $tech)
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                <i class="fas fa-user mr-1"></i>{{ $tech->name }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Matériaux -->
    <div class="card-modern">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">
                    <i class="fas fa-boxes mr-2" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
                    Matériaux (Prévu vs Utilisé)
                </h3>
                <button onclick="openModalMateriau()" 
                        class="btn-primary text-sm px-4 py-2">
                    <i class="fas fa-plus mr-2"></i>Ajouter un matériau
                </button>
            </div>
        </div>
        <div class="p-6 overflow-x-auto">
            @if($chantier->materiaux->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700">Matériau</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700">Unité</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700">Prévu</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700">Utilisé</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700">Différence</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700">%</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($chantier->materiaux as $materiau)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $materiau->nom_materiau }}</td>
                        <td class="px-4 py-3 text-sm text-center text-gray-600">{{ $materiau->unite }}</td>
                        <td class="px-4 py-3 text-sm text-center font-bold text-gray-900">{{ number_format($materiau->quantite_prevue, 3, ',', ' ') }}</td>
                        <td class="px-4 py-3 text-sm text-center font-bold text-gray-900">{{ number_format($materiau->quantite_utilisee, 3, ',', ' ') }}</td>
                        <td class="px-4 py-3 text-sm text-center font-bold {{ $materiau->difference >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($materiau->difference, 3, ',', ' ') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-center font-bold">{{ number_format($materiau->difference_percent, 1, ',', ' ') }}%</td>
                        <td class="px-4 py-3 text-center">
                            <button onclick="openModalEditMateriau({{ $materiau->id }}, '{{ $materiau->nom_materiau }}', '{{ $materiau->unite }}', {{ $materiau->quantite_prevue }}, {{ $materiau->quantite_utilisee }}, '{{ $materiau->notes ?? '' }}')" 
                                    class="text-blue-600 hover:text-blue-800 mr-2 p-2 rounded hover:bg-blue-50" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('chantiers.materiaux.destroy', [$chantier, $materiau]) }}" 
                                  class="inline" 
                                  onsubmit="return confirm('Supprimer ce matériau?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 p-2 rounded hover:bg-red-50" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-box-open text-4xl mb-3"></i>
                <p>Aucun matériau ajouté pour ce chantier</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Photos -->
    <div class="card-modern">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">
                    <i class="fas fa-camera mr-2" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
                    Photos du Chantier
                </h3>
                <form method="POST" action="{{ route('chantiers.photos.store', $chantier) }}" enctype="multipart/form-data" class="flex items-center gap-2">
                    @csrf
                    <input type="file" name="photo" accept="image/*" required class="text-sm border rounded px-2 py-1">
                    <input type="text" name="commentaire" placeholder="Commentaire..." class="text-sm border rounded px-2 py-1">
                    <button type="submit" class="btn-primary text-sm px-4 py-2">
                        <i class="fas fa-upload mr-2"></i>Uploader
                    </button>
                </form>
            </div>
        </div>
        <div class="p-6">
            @if($chantier->photos->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($chantier->photos as $photo)
                <div class="bg-white border-2 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                    <img src="{{ Storage::url($photo->chemin_fichier) }}" alt="Photo chantier" class="w-full h-48 object-cover">
                    <div class="p-3">
                        <p class="text-xs text-gray-600 mb-2">{{ $photo->commentaire }}</p>
                        <p class="text-xs text-gray-400 mb-2">Par {{ $photo->uploader->name ?? 'N/A' }}</p>
                        <div class="flex gap-2">
                            <button onclick="openModalEditPhoto({{ $photo->id }}, '{{ $photo->commentaire ?? '' }}')" 
                                    class="text-blue-600 hover:text-blue-800 text-xs p-1 rounded hover:bg-blue-50">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('chantiers.photos.destroy', [$chantier, $photo]) }}" 
                                  class="inline" 
                                  onsubmit="return confirm('Supprimer cette photo?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs p-1 rounded hover:bg-red-50">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-camera text-4xl mb-3"></i>
                <p>Aucune photo ajoutée pour ce chantier</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Ajouter/Modifier Tâche -->
<div id="modal-tache" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900" id="modal-tache-title">Ajouter une tâche</h3>
            <button onclick="closeModalTache()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="form-tache" method="POST" action="" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nom de la tâche *</label>
                    <input type="text" name="nom" id="tache-nom" required
                           class="w-full border-2 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="tache-description" rows="3"
                              class="w-full border-2 rounded-lg px-3 py-2 text-sm"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Type *</label>
                        <select name="type" id="tache-type" required
                                class="w-full border-2 rounded-lg px-3 py-2 text-sm">
                            <option value="coupe">Coupe</option>
                            <option value="assemblage">Assemblage</option>
                            <option value="decoupe_vitres">Découpe vitres</option>
                            <option value="pose">Pose</option>
                            <option value="finitions">Finitions</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ordre</label>
                        <input type="number" name="ordre" id="tache-ordre" min="0"
                               class="w-full border-2 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Date début prévue</label>
                        <input type="date" name="date_debut_prevue" id="tache-date-debut"
                               class="w-full border-2 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Date fin prévue</label>
                        <input type="date" name="date_fin_prevue" id="tache-date-fin"
                               class="w-full border-2 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeModalTache()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Annuler
                </button>
                <button type="submit" class="btn-primary px-4 py-2 text-sm">
                    <i class="fas fa-save mr-2"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Ajouter/Modifier Matériau -->
<div id="modal-materiau" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900" id="modal-materiau-title">Ajouter un matériau</h3>
            <button onclick="closeModalMateriau()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="form-materiau" method="POST" action="" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nom du matériau *</label>
                    <input type="text" name="nom_materiau" id="materiau-nom" required
                           class="w-full border-2 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Unité *</label>
                    <input type="text" name="unite" id="materiau-unite" required
                           placeholder="m, m², feuille, paire, unité, etc."
                           class="w-full border-2 rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Quantité prévue *</label>
                        <input type="number" name="quantite_prevue" id="materiau-prevue" step="0.001" min="0" required
                               class="w-full border-2 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Quantité utilisée</label>
                        <input type="number" name="quantite_utilisee" id="materiau-utilisee" step="0.001" min="0"
                               class="w-full border-2 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="materiau-notes" rows="3"
                              class="w-full border-2 rounded-lg px-3 py-2 text-sm"></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeModalMateriau()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Annuler
                </button>
                <button type="submit" class="btn-primary px-4 py-2 text-sm">
                    <i class="fas fa-save mr-2"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Modifier Photo -->
<div id="modal-photo" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900">Modifier le commentaire</h3>
            <button onclick="closeModalPhoto()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="form-photo" method="POST" action="" class="p-6">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Commentaire</label>
                <textarea name="commentaire" id="photo-commentaire" rows="4"
                          class="w-full border-2 rounded-lg px-3 py-2 text-sm"></textarea>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeModalPhoto()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Annuler
                </button>
                <button type="submit" class="btn-primary px-4 py-2 text-sm">
                    <i class="fas fa-save mr-2"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Gestion des modals
function openModalTache() {
    document.getElementById('modal-tache').classList.remove('hidden');
    document.getElementById('form-tache').action = '{{ route("chantiers.taches.store", $chantier) }}';
    document.getElementById('modal-tache-title').textContent = 'Ajouter une tâche';
    document.getElementById('form-tache').reset();
    document.getElementById('form-tache').querySelector('input[name="_method"]')?.remove();
}

function openModalEditTache(tacheId) {
    // Récupérer les données depuis le bouton qui a été cliqué
    const button = event.target.closest('button[onclick*="openModalEditTache"]');
    const tacheData = {
        nom: button.getAttribute('data-tache-nom') || '',
        description: button.getAttribute('data-tache-description') || '',
        type: button.getAttribute('data-tache-type') || 'autre',
        status: button.getAttribute('data-tache-status') || 'a_faire',
        progress: button.getAttribute('data-tache-progress') || 0,
        ordre: button.getAttribute('data-tache-ordre') || '',
        date_debut_prevue: button.getAttribute('data-tache-date-debut') || '',
        date_fin_prevue: button.getAttribute('data-tache-date-fin') || ''
    };
    
    document.getElementById('modal-tache').classList.remove('hidden');
    const form = document.getElementById('form-tache');
    form.action = '{{ route("chantiers.taches.update", [$chantier, ":id"]) }}'.replace(':id', tacheId);
    
    // Ajouter la méthode POST si elle n'existe pas déjà
    let methodInput = form.querySelector('input[name="_method"]');
    if (!methodInput) {
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'POST';
        form.appendChild(methodInput);
    }
    
    // Ajouter les champs status et progress pour la mise à jour
    let statusInput = form.querySelector('input[name="status"]');
    if (!statusInput) {
        statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        form.appendChild(statusInput);
    }
    statusInput.value = tacheData.status;
    
    let progressInput = form.querySelector('input[name="progress"]');
    if (!progressInput) {
        progressInput = document.createElement('input');
        progressInput.type = 'hidden';
        progressInput.name = 'progress';
        form.appendChild(progressInput);
    }
    progressInput.value = tacheData.progress;
    
    document.getElementById('modal-tache-title').textContent = 'Modifier la tâche';
    document.getElementById('tache-nom').value = tacheData.nom;
    document.getElementById('tache-description').value = tacheData.description;
    document.getElementById('tache-type').value = tacheData.type;
    document.getElementById('tache-ordre').value = tacheData.ordre;
    document.getElementById('tache-date-debut').value = tacheData.date_debut_prevue;
    document.getElementById('tache-date-fin').value = tacheData.date_fin_prevue;
}

function closeModalTache() {
    document.getElementById('modal-tache').classList.add('hidden');
}

function openModalMateriau() {
    const modal = document.getElementById('modal-materiau');
    const form = document.getElementById('form-materiau');
    modal.classList.remove('hidden');
    form.action = '{{ route("chantiers.materiaux.store", $chantier) }}';
    document.getElementById('modal-materiau-title').textContent = 'Ajouter un matériau';
    form.reset();
    const methodInput = form.querySelector('input[name="_method"]');
    if (methodInput) {
        methodInput.remove();
    }
}

function openModalEditMateriau(id, nom, unite, prevue, utilisee, notes) {
    const modal = document.getElementById('modal-materiau');
    const form = document.getElementById('form-materiau');
    modal.classList.remove('hidden');
    form.action = '{{ route("chantiers.materiaux.update", [$chantier, ":id"]) }}'.replace(':id', id);
    
    if (!form.querySelector('input[name="_method"]')) {
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'POST';
        form.appendChild(methodInput);
    }
    
    document.getElementById('modal-materiau-title').textContent = 'Modifier le matériau';
    document.getElementById('materiau-nom').value = nom || '';
    document.getElementById('materiau-unite').value = unite || '';
    document.getElementById('materiau-prevue').value = prevue || 0;
    document.getElementById('materiau-utilisee').value = utilisee || 0;
    document.getElementById('materiau-notes').value = notes || '';
}

function closeModalMateriau() {
    document.getElementById('modal-materiau').classList.add('hidden');
}

function openModalEditPhoto(id, commentaire) {
    const modal = document.getElementById('modal-photo');
    const form = document.getElementById('form-photo');
    modal.classList.remove('hidden');
    form.action = '{{ route("chantiers.photos.update", [$chantier, ":id"]) }}'.replace(':id', id);
    
    if (!form.querySelector('input[name="_method"]')) {
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'POST';
        form.appendChild(methodInput);
    }
    
    document.getElementById('photo-commentaire').value = commentaire || '';
}

function closeModalPhoto() {
    document.getElementById('modal-photo').classList.add('hidden');
}

// Fermer les modals en cliquant à l'extérieur
document.addEventListener('click', function(e) {
    if (e.target.id === 'modal-tache' || e.target.id === 'modal-materiau' || e.target.id === 'modal-photo') {
        e.target.classList.add('hidden');
    }
});
</script>
@endsection
