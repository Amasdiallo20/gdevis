@extends('layouts.app')

@section('title', 'Modifier Utilisateur')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card-modern overflow-hidden">
        <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
             style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }}15 0%, {{ $settings->secondary_color ?? '#1e40af' }}15 100%);">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                    <i class="fas fa-user-edit text-white"></i>
                </div>
                Modifier Utilisateur
            </h2>
            <p class="mt-2 text-sm text-gray-600">Modifiez les informations de l'utilisateur</p>
        </div>

        <form action="{{ route('users.update', $user) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-1 text-gray-400"></i>Nom complet *
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all @error('name') border-red-500 bg-red-50 @enderror"
                        style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                        placeholder="Ex: Jean Dupont">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-1 text-gray-400"></i>Email *
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all @error('email') border-red-500 bg-red-50 @enderror"
                        style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                        placeholder="exemple@email.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-tag mr-1 text-gray-400"></i>Rôle *
                    </label>
                    <select name="role" id="role" required
                        class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all @error('role') border-red-500 bg-red-50 @enderror"
                        style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }}; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"%23374151\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"6 9 12 15 18 9\"></polyline></svg>'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1em 1em; padding-right: 2.5rem;">
                        <option value="">-- Sélectionner un rôle --</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Utilisateur</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="valid_until" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt mr-1 text-gray-400"></i>Date de validité
                        <span class="text-xs text-gray-500">(laisser vide pour valide indéfiniment)</span>
                    </label>
                    <input type="date" name="valid_until" id="valid_until" 
                        value="{{ old('valid_until', $user->valid_until ? $user->valid_until->format('Y-m-d') : '') }}"
                        class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all @error('valid_until') border-red-500 bg-red-50 @enderror"
                        style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                    @error('valid_until')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Le compte sera désactivé après cette date</p>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-1 text-gray-400"></i>Nouveau mot de passe
                        <span class="text-xs text-gray-500">(laisser vide pour ne pas changer)</span>
                    </label>
                    <input type="password" name="password" id="password"
                        class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all @error('password') border-red-500 bg-red-50 @enderror"
                        style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                        placeholder="Minimum 8 caractères">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-1 text-gray-400"></i>Confirmer le nouveau mot de passe
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all"
                        style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                        placeholder="Répétez le mot de passe">
                </div>
            </div>

            <!-- Section Permissions -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-key mr-2" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
                    Permissions
                </h3>
                <p class="text-sm text-gray-600 mb-4">Sélectionnez les permissions à attribuer à cet utilisateur. Les administrateurs ont automatiquement toutes les permissions.</p>
                
                @if(empty($permissions) || $permissions->isEmpty())
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Aucune permission trouvée. Veuillez exécuter le seeder des permissions : <code class="bg-yellow-100 px-2 py-1 rounded">php artisan db:seed --class=PermissionSeeder</code>
                    </p>
                </div>
                @else
                <div class="space-y-6">
                    @foreach($permissions as $category => $categoryPermissions)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3 capitalize">{{ $category }}</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($categoryPermissions as $permission)
                                    <label class="flex items-start p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                        <input type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $permission->id }}"
                                               {{ in_array($permission->id, $userPermissions) ? 'checked' : '' }}
                                               class="mt-1 mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                               style="accent-color: {{ $settings->primary_color ?? '#3b82f6' }};">
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-900">{{ $permission->name }}</div>
                                            @if($permission->description)
                                                <div class="text-xs text-gray-500 mt-1">{{ $permission->description }}</div>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-end">
                <a href="{{ route('users.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg shadow-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-all duration-300">
                    <i class="fas fa-times mr-2"></i>Annuler
                </a>
                <button type="submit" 
                        class="btn-primary inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300"
                        onmouseover="this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-save mr-2"></i>Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

