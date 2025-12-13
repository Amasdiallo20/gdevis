<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Accès Refusé</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    @php
        $settings = \App\Models\Setting::getSettings();
        $primaryColor = $settings->primary_color ?? '#667eea';
        $secondaryColor = $settings->secondary_color ?? '#764ba2';
    @endphp
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }
        
        .animate-pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <!-- Carte principale -->
        <div class="glass-effect rounded-3xl shadow-2xl p-8 md:p-12 text-center">
            <!-- Icône animée -->
            <div class="mb-8 animate-float">
                <div class="inline-flex items-center justify-center w-32 h-32 rounded-full bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg border-4 border-white border-opacity-30">
                    <i class="fas fa-shield-alt text-6xl text-white"></i>
                </div>
            </div>
            
            <!-- Code d'erreur -->
            <h1 class="text-8xl md:text-9xl font-bold text-white mb-4 animate-pulse-slow">
                403
            </h1>
            
            <!-- Titre -->
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Accès Refusé
            </h2>
            
            <!-- Message -->
            <p class="text-lg md:text-xl text-white text-opacity-90 mb-8 leading-relaxed">
                Désolé, vous n'avez pas les permissions nécessaires pour accéder à cette page.
            </p>
            
            <!-- Message supplémentaire -->
            <div class="bg-white bg-opacity-10 rounded-xl p-6 mb-8 backdrop-filter backdrop-blur-lg">
                <p class="text-white text-opacity-80 text-sm md:text-base">
                    <i class="fas fa-info-circle mr-2"></i>
                    Si vous pensez que c'est une erreur, veuillez contacter votre administrateur pour obtenir les permissions appropriées.
                </p>
            </div>
            
            <!-- Boutons d'action -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('chantiers.mes-taches') }}" 
                   class="inline-flex items-center justify-center px-8 py-3 bg-white text-purple-600 rounded-xl font-semibold hover:bg-opacity-90 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-tasks mr-2"></i>
                    Mes Tâches
                </a>
                
                <a href="{{ route('modeles.index') }}" 
                   class="inline-flex items-center justify-center px-8 py-3 bg-white bg-opacity-20 text-white rounded-xl font-semibold hover:bg-opacity-30 transition-all duration-300 backdrop-filter backdrop-blur-lg border border-white border-opacity-30">
                    <i class="fas fa-th-large mr-2"></i>
                    Catalogue
                </a>
                
                @if(Auth::check() && Auth::user()->role === 'admin')
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center justify-center px-8 py-3 bg-white bg-opacity-20 text-white rounded-xl font-semibold hover:bg-opacity-30 transition-all duration-300 backdrop-filter backdrop-blur-lg border border-white border-opacity-30">
                    <i class="fas fa-home mr-2"></i>
                    Tableau de bord
                </a>
                @endif
            </div>
        </div>
        
        <!-- Éléments décoratifs -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-white bg-opacity-10 rounded-full blur-xl animate-pulse-slow"></div>
        <div class="absolute bottom-10 right-10 w-32 h-32 bg-white bg-opacity-10 rounded-full blur-xl animate-pulse-slow" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 right-20 w-16 h-16 bg-white bg-opacity-10 rounded-full blur-xl animate-pulse-slow" style="animation-delay: 2s;"></div>
    </div>
    
    <script>
        // Animation supplémentaire au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.glass-effect');
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>

