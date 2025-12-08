<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - A2 VitraDevis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php
        $settings = \App\Models\Setting::getSettings();
        $primaryColor = $settings->primary_color ?? '#3b82f6';
        $secondaryColor = $settings->secondary_color ?? '#1e40af';
    ?>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        
        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, <?php echo e($primaryColor); ?> 0%, <?php echo e($secondaryColor); ?> 100%);
            position: relative;
            overflow: hidden;
        }
        
        .gradient-bg::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }
        
        .card-shadow {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.1);
        }
        
        .input-focus:focus {
            border-color: <?php echo e($primaryColor); ?>;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, <?php echo e($primaryColor); ?> 0%, <?php echo e($secondaryColor); ?> 100%);
            background-size: 200% 200%;
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            background-position: right center;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4);
        }
        
        .btn-gradient:active {
            transform: translateY(0);
        }
        
        .pattern-dots {
            background-image: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8 gradient-bg pattern-dots">
    <div class="max-w-md w-full animate-fade-in-up">
        <!-- Card principale -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden card-shadow backdrop-blur-sm bg-white/95">
            <!-- Header avec gradient -->
            <div class="gradient-bg px-6 py-6 text-center relative overflow-hidden">
                <!-- Effet de brillance animé -->
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-transparent via-white to-transparent transform -skew-x-12 animate-shimmer" style="background-size: 200% 100%;"></div>
                </div>
                
                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-xl mb-3 shadow-2xl overflow-hidden transform hover:scale-105 transition-transform duration-300 animate-float">
                        <?php if($settings->logo): ?>
                            <img src="<?php echo e(asset('storage/' . $settings->logo)); ?>" 
                                 alt="Logo" 
                                 class="h-full w-full object-contain p-2"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="hidden items-center justify-center w-full h-full">
                                <span class="text-3xl font-bold" style="color: <?php echo e($primaryColor); ?>;">A2</span>
                            </div>
                        <?php else: ?>
                            <span class="text-3xl font-bold" style="color: <?php echo e($primaryColor); ?>;">A2</span>
                        <?php endif; ?>
                    </div>
                    <h1 class="text-2xl font-extrabold text-white mb-1 tracking-tight">
                        A2 VitraDevis
                    </h1>
                    <p class="text-blue-100 text-xs mb-2 italic font-light">
                        Votre devis, clair comme le verre.
                    </p>
                    <div class="inline-block px-3 py-1.5 bg-white/20 backdrop-blur-sm rounded-full border border-white/30">
                        <h2 class="text-sm font-semibold text-white">
                            <i class="fas fa-sign-in-alt mr-1.5"></i>Connexion
                        </h2>
                    </div>
                    <p class="text-blue-100 text-xs mt-2 opacity-90">
                        Accédez à votre espace de gestion
                    </p>
                </div>
            </div>

            <!-- Formulaire -->
            <div class="px-6 py-6 bg-gradient-to-b from-white to-gray-50">
                <form class="space-y-6" action="<?php echo e(route('login')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <?php if($errors->any()): ?>
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm animate-fade-in-up">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h3 class="text-sm font-semibold text-red-800 mb-1">Erreur de connexion</h3>
                                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Champ Email -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold text-gray-700">
                            <i class="fas fa-envelope mr-1.5" style="color: <?php echo e($primaryColor); ?>;"></i>Adresse email
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400 group-focus-within:text-blue-500 transition-colors text-sm"></i>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                   class="input-focus block w-full pl-10 pr-3 py-2.5 border-2 border-gray-200 rounded-lg bg-white text-sm placeholder-gray-400 transition-all duration-200 focus:outline-none"
                                   style="focus:border-color: <?php echo e($primaryColor); ?>;"
                                   placeholder="votre@email.com" 
                                   value="<?php echo e(old('email')); ?>">
                        </div>
                    </div>

                    <!-- Champ Mot de passe -->
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-bold text-gray-700">
                            <i class="fas fa-lock mr-1.5" style="color: <?php echo e($primaryColor); ?>;"></i>Mot de passe
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400 group-focus-within:text-blue-500 transition-colors text-sm"></i>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password" required 
                                   class="input-focus block w-full pl-10 pr-3 py-2.5 border-2 border-gray-200 rounded-lg bg-white text-sm placeholder-gray-400 transition-all duration-200 focus:outline-none"
                                   style="focus:border-color: <?php echo e($primaryColor); ?>;"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Se souvenir de moi -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center group cursor-pointer">
                            <input id="remember" name="remember" type="checkbox" 
                                   class="h-4 w-4 border-2 border-gray-300 rounded cursor-pointer transition-all duration-200"
                                   style="accent-color: <?php echo e($primaryColor); ?>;"
                                   onchange="this.style.accentColor='<?php echo e($primaryColor); ?>'">
                            <label for="remember" class="ml-2 block text-xs font-medium text-gray-700 cursor-pointer group-hover:text-gray-900 transition-colors">
                                Se souvenir de moi
                            </label>
                        </div>
                        <a href="#" class="text-xs font-medium transition-colors hover:underline"
                           style="color: <?php echo e($primaryColor); ?>;"
                           onmouseover="this.style.color='<?php echo e($secondaryColor); ?>'"
                           onmouseout="this.style.color='<?php echo e($primaryColor); ?>'">
                            Mot de passe oublié ?
                        </a>
                    </div>

                    <!-- Bouton de connexion -->
                    <div class="pt-1">
                        <button type="submit" 
                                class="btn-gradient w-full flex justify-center items-center py-2.5 px-4 rounded-lg text-sm font-bold text-white shadow-lg focus:outline-none focus:ring-4 focus:ring-offset-2 transition-all duration-300"
                                style="focus:ring-color: <?php echo e($primaryColor); ?>40;">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Se connecter
                        </button>
                    </div>
                </form>

                <?php if(!isset($hasUsers) || !$hasUsers): ?>
                <!-- Divider - Affiché uniquement lors du premier lancement -->
                <div class="relative my-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="px-3 bg-gradient-to-b from-white to-gray-50 text-gray-500 font-medium">Premier lancement</span>
                    </div>
                </div>

                <!-- Lien vers inscription - Affiché uniquement lors du premier lancement -->
                <div class="text-center">
                    <a href="<?php echo e(route('register')); ?>" 
                       class="inline-flex items-center justify-center w-full py-2 px-4 border-2 rounded-lg text-sm font-semibold transition-all duration-200 transform hover:scale-105 hover:shadow-md"
                       style="border-color: <?php echo e($primaryColor); ?>; color: <?php echo e($primaryColor); ?>;"
                       onmouseover="this.style.backgroundColor='<?php echo e($primaryColor); ?>15';"
                       onmouseout="this.style.backgroundColor='transparent';">
                        <i class="fas fa-user-plus mr-1.5"></i>
                        Créer le compte administrateur
                    </a>
                    <p class="text-xs text-gray-500 mt-1.5">
                        <i class="fas fa-info-circle mr-1"></i>
                        Créez le premier compte administrateur de l'application
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                <i class="fas fa-shield-alt mr-2 text-white text-sm"></i>
                <p class="text-white text-sm font-medium opacity-90">
                    Connexion sécurisée SSL
                </p>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\gdevis\resources\views/auth/login.blade.php ENDPATH**/ ?>