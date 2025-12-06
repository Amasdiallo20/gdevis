@extends('layouts.app')

@section('title', 'Utilisateurs')

@section('content')
<div class="card-modern overflow-hidden">
    <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
         style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }}15 0%, {{ $settings->secondary_color ?? '#1e40af' }}15 100%);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                    <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                        <i class="fas fa-users-cog text-white"></i>
                    </div>
                    Gestion des Utilisateurs
                </h2>
                <p class="mt-2 text-sm text-gray-600">Gérez les utilisateurs et leurs rôles</p>
            </div>
            <a href="{{ route('users.create') }}" 
               class="btn-primary inline-flex items-center justify-center px-5 py-3 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300"
               onmouseover="this.style.transform='translateY(-2px)'"
               onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-plus mr-2"></i>Nouvel Utilisateur
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <form method="GET" action="{{ route('users.index') }}" class="space-y-4">
            <div class="flex flex-col sm:flex-row gap-6">
                <!-- Recherche -->
                <div class="flex-1">
                    <label for="search" class="block text-xs font-medium text-gray-700 mb-1">
                        <i class="fas fa-search mr-1"></i>Recherche
                    </label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                        placeholder="Nom ou email"
                        class="block w-full rounded-lg border-2 border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:ring-2 focus:border-transparent transition-all"
                        style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                </div>

                <!-- Filtre par rôle -->
                <div class="sm:w-48">
                    <label for="role" class="block text-xs font-medium text-gray-700 mb-1">
                        <i class="fas fa-user-tag mr-1"></i>Rôle
                    </label>
                    <select name="role" id="role" 
                        class="block w-full rounded-lg border-2 border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:ring-2 focus:border-transparent transition-all"
                        style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }}; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"%23374151\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"6 9 12 15 18 9\"></polyline></svg>'); background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 1em 1em; padding-right: 2rem;">
                        <option value="">Tous les rôles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Utilisateur</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white transition-all duration-200"
                        style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);"
                        onmouseover="this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                        <i class="fas fa-filter mr-2"></i>Filtrer
                    </button>
                    @if(request()->hasAny(['search', 'role']))
                        <a href="{{ route('users.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                            <i class="fas fa-times mr-2"></i>Réinitialiser
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-user mr-2"></i>Nom
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-user-tag mr-2"></i>Rôle
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden md:table-cell">
                        <i class="fas fa-calendar mr-2"></i>Créé le
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden lg:table-cell">
                        <i class="fas fa-calendar-check mr-2"></i>Validité
                    </th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-cog mr-2"></i>Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="table-row-hover">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md"
                                 style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                @if($user->id === Auth::id())
                                    <div class="text-xs text-blue-600 font-semibold">(Vous)</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <i class="fas fa-envelope mr-2 text-gray-400"></i>{{ $user->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg shadow-sm
                            @if($user->role === 'admin') bg-purple-100 text-purple-800 border border-purple-200
                            @else bg-gray-100 text-gray-800 border border-gray-200
                            @endif">
                            @if($user->role === 'admin')
                                <i class="fas fa-shield-alt mr-1"></i>Administrateur
                            @else
                                <i class="fas fa-user mr-1"></i>Utilisateur
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 hidden md:table-cell">
                        <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>{{ $user->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm hidden lg:table-cell">
                        @if($user->valid_until)
                            @if($user->isExpired())
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg bg-red-100 text-red-800 border border-red-200">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Expiré
                                </span>
                            @elseif($user->valid_until->diffInDays(now()) <= 7)
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    <i class="fas fa-clock mr-1"></i>{{ $user->valid_until->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg bg-green-100 text-green-800 border border-green-200">
                                    <i class="fas fa-check-circle mr-1"></i>{{ $user->valid_until->format('d/m/Y') }}
                                </span>
                            @endif
                        @else
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg bg-blue-100 text-blue-800 border border-blue-200">
                                <i class="fas fa-infinity mr-1"></i>Indéfini
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('users.show', $user) }}" 
                               class="text-blue-600 hover:text-blue-800 p-2.5 rounded-lg hover:bg-blue-50 transition-all duration-200 transform hover:scale-110"
                               title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('users.edit', $user) }}" 
                               class="text-indigo-600 hover:text-indigo-800 p-2.5 rounded-lg hover:bg-indigo-50 transition-all duration-200 transform hover:scale-110"
                               title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($user->id !== Auth::id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 p-2.5 rounded-lg hover:bg-red-50 transition-all duration-200 transform hover:scale-110"
                                        title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @else
                            <span class="text-gray-400 p-2.5 rounded-lg cursor-not-allowed" 
                                  title="Vous ne pouvez pas supprimer votre propre compte">
                                <i class="fas fa-trash"></i>
                            </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="text-gray-400">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4"
                                 style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }}20 0%, {{ $settings->secondary_color ?? '#1e40af' }}20 100%);">
                                <i class="fas fa-users-cog text-4xl" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
                            </div>
                            <p class="text-xl font-bold text-gray-900">Aucun utilisateur</p>
                            <p class="text-sm text-gray-500 mt-2">Commencez par créer votre premier utilisateur</p>
                            <a href="{{ route('users.create') }}" 
                               class="mt-6 btn-primary inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300"
                               onmouseover="this.style.transform='translateY(-2px)'"
                               onmouseout="this.style.transform='translateY(0)'">
                                <i class="fas fa-plus mr-2"></i>Ajouter un utilisateur
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection

