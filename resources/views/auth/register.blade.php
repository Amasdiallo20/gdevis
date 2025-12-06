<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - A2 VitraDevis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @php
        $settings = \App\Models\Setting::getSettings();
        $primaryColor = $settings->primary_color ?? '#3b82f6';
        $secondaryColor = $settings->secondary_color ?? '#1e40af';
    @endphp
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        .gradient-bg {
            background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%);
        }
        .card-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 gradient-bg">
    <div class="max-w-md w-full animate-fade-in">
        <!-- Card principale -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden card-shadow">
            <!-- Header avec gradient -->
            <div class="gradient-bg px-8 py-10 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-4 shadow-lg overflow-hidden">
                    @if($settings->logo)
                        <img src="{{ asset('storage/' . $settings->logo) }}" 
                             alt="Logo" 
                             class="h-full w-full object-contain p-2"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="hidden items-center justify-center w-full h-full">
                            <i class="fas fa-user-plus text-4xl" style="color: {{ $primaryColor }};"></i>
                        </div>
                    @else
                        <i class="fas fa-user-plus text-4xl" style="color: {{ $primaryColor }};"></i>
                    @endif
                </div>
                <h1 class="text-2xl font-bold text-white mb-1">
                    A2 VitraDevis
                </h1>
                <p class="text-blue-100 text-xs mb-3 italic">
                    Votre devis, clair comme le verre.
                </p>
                <h2 class="text-xl font-semibold text-white mb-2">
                    Créer un compte
                </h2>
                <p class="text-blue-100 text-sm">
                    Rejoignez-nous dès aujourd'hui
                </p>
            </div>

            <!-- Formulaire -->
            <div class="px-8 py-8">
                <form class="space-y-5" action="{{ route('register') }}" method="POST">
                    @csrf
                    
                    @if($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                                </div>
                                <div class="ml-3">
                                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Champ Nom -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-gray-400"></i>Nom complet
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input id="name" name="name" type="text" autocomplete="name" required 
                                   class="block w-full pl-10 pr-3 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 transition-all text-base placeholder-gray-400"
                                   style="focus:ring-color: {{ $primaryColor }}; focus:border-color: {{ $primaryColor }};"
                                   placeholder="Votre nom complet" 
                                   value="{{ old('name') }}">
                        </div>
                    </div>

                    <!-- Champ Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-gray-400"></i>Adresse email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                   class="block w-full pl-10 pr-3 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 transition-all text-base placeholder-gray-400"
                                   style="focus:ring-color: {{ $primaryColor }}; focus:border-color: {{ $primaryColor }};"
                                   placeholder="email@exemple.com" 
                                   value="{{ old('email') }}">
                        </div>
                    </div>

                    <!-- Champ Mot de passe -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-gray-400"></i>Mot de passe
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="new-password" required 
                                   class="block w-full pl-10 pr-3 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 transition-all text-base placeholder-gray-400"
                                   style="focus:ring-color: {{ $primaryColor }}; focus:border-color: {{ $primaryColor }};"
                                   placeholder="Minimum 8 caractères">
                        </div>
                    </div>

                    <!-- Champ Confirmation mot de passe -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-gray-400"></i>Confirmer le mot de passe
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                                   class="block w-full pl-10 pr-3 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 transition-all text-base placeholder-gray-400"
                                   style="focus:ring-color: {{ $primaryColor }}; focus:border-color: {{ $primaryColor }};"
                                   placeholder="Répétez le mot de passe">
                        </div>
                    </div>

                    <!-- Bouton d'inscription -->
                    <div>
                        <button type="submit" 
                                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-base font-semibold text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 transform hover:scale-[1.02]"
                                style="background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%); focus:ring-color: {{ $primaryColor }};">
                            <i class="fas fa-user-plus mr-2"></i>
                            Créer le compte
                        </button>
                    </div>
                </form>

                <!-- Lien vers connexion -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Vous avez déjà un compte ?
                        <a href="{{ route('login') }}" class="font-semibold transition-colors"
                           style="color: {{ $primaryColor }};"
                           onmouseover="this.style.color='{{ $secondaryColor }}'"
                           onmouseout="this.style.color='{{ $primaryColor }}'">
                            Se connecter
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center">
            <p class="text-white text-sm opacity-90">
                <i class="fas fa-shield-alt mr-1"></i>
                Inscription sécurisée
            </p>
        </div>
    </div>
</body>
</html>




