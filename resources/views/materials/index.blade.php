@extends('layouts.app')

@section('title', 'Matériaux')

@section('content')
<div class="card-modern overflow-hidden">
    <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
         style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }}15 0%, {{ $settings->secondary_color ?? '#1e40af' }}15 100%);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                    <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                        <i class="fas fa-tools text-white"></i>
                    </div>
                    Liste des Matériaux
                </h2>
                <p class="mt-2 text-sm text-gray-600">Gérez les prix unitaires des matériaux utilisés pour les fenêtres et portes</p>
            </div>
            @auth
            @if(Auth::user()->hasPermission('materials.create'))
            <a href="{{ route('materials.create') }}" 
               class="btn-primary inline-flex items-center justify-center px-5 py-3 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300"
               onmouseover="this.style.transform='translateY(-2px)'"
               onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-plus mr-2"></i>Nouveau Matériau
            </a>
            @endif
            @endauth
        </div>
    </div>

    <div class="p-6">
        @if(session('success'))
        <div class="mb-4 bg-green-50 border-2 border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if($materiaux->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-box mr-1"></i>Nom
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-tag mr-1"></i>Prix Unitaire (GNF)
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-ruler mr-1"></i>Unité
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-calendar mr-1"></i>Dernière Mise à Jour
                        </th>
                        @auth
                        @if(Auth::user()->hasPermission('materials.update') || Auth::user()->hasPermission('materials.delete'))
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-cog mr-1"></i>Actions
                        </th>
                        @endif
                        @endauth
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($materiaux as $materiau)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">
                                {{ $materiau->nom }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-indigo-100 text-indigo-800">
                                {{ number_format($materiau->prix_unitaire, 0, ',', ' ') }} GNF
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm text-gray-600">
                                {{ $materiau->unite }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm text-gray-600">
                                @if($materiau->date_mise_a_jour)
                                    {{ $materiau->date_mise_a_jour->format('d/m/Y H:i') }}
                                @else
                                    {{ $materiau->updated_at->format('d/m/Y H:i') }}
                                @endif
                            </span>
                        </td>
                        @auth
                        @if(Auth::user()->hasPermission('materials.update') || Auth::user()->hasPermission('materials.delete'))
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if(Auth::user()->hasPermission('materials.update'))
                                <a href="{{ route('materials.edit', $materiau) }}" 
                                   class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md text-xs font-medium text-white transition-all duration-200 bg-blue-600 hover:bg-blue-700"
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                @if(Auth::user()->hasPermission('materials.delete'))
                                <form action="{{ route('materials.destroy', $materiau) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce matériau ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md text-xs font-medium text-white transition-all duration-200 bg-red-600 hover:bg-red-700"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                        @endif
                        @endauth
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
            <p class="text-lg font-semibold text-gray-900 mb-2">Aucun matériau enregistré</p>
            <p class="text-sm text-gray-600 mb-4">Commencez par créer votre premier matériau</p>
            @auth
            @if(Auth::user()->hasPermission('materials.create'))
            <a href="{{ route('materials.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white transition-all duration-200"
               style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                <i class="fas fa-plus mr-2"></i>Créer un Matériau
            </a>
            @endif
            @endauth
        </div>
        @endif
    </div>
</div>
@endsection

