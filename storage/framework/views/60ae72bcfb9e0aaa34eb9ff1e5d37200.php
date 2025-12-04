<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion Devis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php
        $settings = \App\Models\Setting::getSettings();
        $primaryColor = $settings->primary_color ?? '#3b82f6';
        $secondaryColor = $settings->secondary_color ?? '#1e40af';
    ?>
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
            background: linear-gradient(135deg, <?php echo e($primaryColor); ?> 0%, <?php echo e($secondaryColor); ?> 100%);
        }
        .card-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .focus-ring {
            --tw-ring-color: <?php echo e($primaryColor); ?>;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 gradient-bg">
    <div class="max-w-md w-full animate-fade-in">
        <!-- Card principale -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden card-shadow">
            <!-- Header avec gradient -->
            <div class="gradient-bg px-8 py-10 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-4 shadow-lg">
                    <i class="fas fa-file-invoice-dollar text-4xl" style="color: <?php echo e($primaryColor); ?>;"></i>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">
                    Connexion
                </h2>
                <p class="text-blue-100 text-sm">
                    Accédez à votre espace de gestion
                </p>
            </div>

            <!-- Formulaire -->
            <div class="px-8 py-8">
                <form class="space-y-6" action="<?php echo e(route('login')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <?php if($errors->any()): ?>
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                                </div>
                                <div class="ml-3">
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
                                   style="focus:ring-color: <?php echo e($primaryColor); ?>; focus:border-color: <?php echo e($primaryColor); ?>;"
                                   placeholder="votre@email.com" 
                                   value="<?php echo e(old('email')); ?>">
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
                            <input id="password" name="password" type="password" autocomplete="current-password" required 
                                   class="block w-full pl-10 pr-3 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 transition-all text-base placeholder-gray-400"
                                   style="focus:ring-color: <?php echo e($primaryColor); ?>; focus:border-color: <?php echo e($primaryColor); ?>;"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Se souvenir de moi -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" 
                                   class="h-4 w-4 border-gray-300 rounded cursor-pointer"
                                   style="accent-color: <?php echo e($primaryColor); ?>;"
                                   onchange="this.style.accentColor='<?php echo e($primaryColor); ?>'">
                            <label for="remember" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                                Se souvenir de moi
                            </label>
                        </div>
                    </div>

                    <!-- Bouton de connexion -->
                    <div>
                        <button type="submit" 
                                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-base font-semibold text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 transform hover:scale-[1.02]"
                                style="background: linear-gradient(135deg, <?php echo e($primaryColor); ?> 0%, <?php echo e($secondaryColor); ?> 100%); focus:ring-color: <?php echo e($primaryColor); ?>;">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Se connecter
                        </button>
                    </div>
                </form>

                <!-- Lien vers inscription -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Vous n'avez pas de compte ?
                        <a href="<?php echo e(route('register')); ?>" class="font-semibold transition-colors"
                           style="color: <?php echo e($primaryColor); ?>;"
                           onmouseover="this.style.color='<?php echo e($secondaryColor); ?>'"
                           onmouseout="this.style.color='<?php echo e($primaryColor); ?>'">
                            Créer un compte
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center">
            <p class="text-white text-sm opacity-90">
                <i class="fas fa-shield-alt mr-1"></i>
                Connexion sécurisée
            </p>
        </div>
    </div>
</body>
</html>




<?php /**PATH C:\xampp\htdocs\gdevis\resources\views/auth/login.blade.php ENDPATH**/ ?>