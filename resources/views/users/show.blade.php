@extends('layouts.app')

@section('title', 'Détails Utilisateur')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card-modern overflow-hidden">
        <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
             style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }}15 0%, {{ $settings->secondary_color ?? '#1e40af' }}15 100%);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                        <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        Détails Utilisateur
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">Informations de l'utilisateur</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('users.edit', $user) }}" 
                       class="btn-primary inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300"
                       onmouseover="this.style.transform='translateY(-2px)'"
                       onmouseout="this.style.transform='translateY(0)'">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                    <a href="{{ route('users.index') }}" 
                       class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 rounded-lg shadow-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-all duration-300"
                       onmouseover="this.style.transform='translateY(-2px)'"
                       onmouseout="this.style.transform='translateY(0)'">
                        <i class="fas fa-arrow-left mr-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-5 rounded-xl border border-blue-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user-circle mr-2" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
                        Informations Personnelles
                    </h3>
                    <dl class="space-y-3">
                        <div class="flex items-center">
                            <dt class="text-sm font-semibold text-gray-600 w-24">Nom:</dt>
                            <dd class="text-sm font-bold text-gray-900">{{ $user->name }}</dd>
                        </div>
                        <div class="flex items-center">
                            <dt class="text-sm font-semibold text-gray-600 w-24">Email:</dt>
                            <dd class="text-sm text-gray-900"><i class="fas fa-envelope mr-2 text-gray-400"></i>{{ $user->email }}</dd>
                        </div>
                    </dl>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-5 rounded-xl border border-purple-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
                        Informations Système
                    </h3>
                    <dl class="space-y-3">
                        <div class="flex items-center">
                            <dt class="text-sm font-semibold text-gray-600 w-32">Rôle:</dt>
                            <dd class="text-sm">
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
                            </dd>
                        </div>
                        <div class="flex items-center">
                            <dt class="text-sm font-semibold text-gray-600 w-32">Validité:</dt>
                            <dd class="text-sm">
                                @if($user->valid_until)
                                    @if($user->isExpired())
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg shadow-sm bg-red-100 text-red-800 border border-red-200">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Expiré le {{ $user->valid_until->format('d/m/Y') }}
                                        </span>
                                    @elseif($user->valid_until->diffInDays(now()) <= 7)
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg shadow-sm bg-yellow-100 text-yellow-800 border border-yellow-200">
                                            <i class="fas fa-clock mr-1"></i>Expire le {{ $user->valid_until->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg shadow-sm bg-green-100 text-green-800 border border-green-200">
                                            <i class="fas fa-check-circle mr-1"></i>Valide jusqu'au {{ $user->valid_until->format('d/m/Y') }}
                                        </span>
                                    @endif
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg shadow-sm bg-blue-100 text-blue-800 border border-blue-200">
                                        <i class="fas fa-infinity mr-1"></i>Valide indéfiniment
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div class="flex items-center">
                            <dt class="text-sm font-semibold text-gray-600 w-32">Créé le:</dt>
                            <dd class="text-sm text-gray-900"><i class="fas fa-calendar-alt mr-2 text-gray-400"></i>{{ $user->created_at->format('d/m/Y à H:i') }}</dd>
                        </div>
                        <div class="flex items-center">
                            <dt class="text-sm font-semibold text-gray-600 w-32">Modifié le:</dt>
                            <dd class="text-sm text-gray-900"><i class="fas fa-calendar-check mr-2 text-gray-400"></i>{{ $user->updated_at->format('d/m/Y à H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Section Permissions -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-key mr-2" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
                    Permissions
                </h3>
                @if($user->role === 'admin')
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            Les administrateurs ont automatiquement toutes les permissions.
                        </p>
                    </div>
                @elseif($user->permissions->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($user->permissions->groupBy('category') as $category => $categoryPermissions)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-2 capitalize text-sm">{{ $category }}</h4>
                                <ul class="space-y-1">
                                    @foreach($categoryPermissions as $permission)
                                        <li class="text-sm text-gray-700 flex items-center">
                                            <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                            {{ $permission->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            Aucune permission spécifique attribuée à cet utilisateur.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

