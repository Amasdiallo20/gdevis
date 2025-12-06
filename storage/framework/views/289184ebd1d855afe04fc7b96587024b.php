<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'A2 VitraDevis'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;
            --secondary-color: <?php echo e($settings->secondary_color ?? '#1e40af'); ?>;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(-100%);
            }
            to {
                transform: translateX(0);
            }
        }
        
        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
            }
            to {
                transform: translateX(0);
            }
        }
        
        
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out;
        }
        
        .card-modern {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }
        
        .card-modern:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
        }
        
        .table-row-hover {
            transition: all 0.2s ease;
        }
        
        .table-row-hover:hover {
            background-color: #f9fafb;
            transform: scale(1.01);
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
        }
        
        /* Menu mobile */
        @media (max-width: 640px) {
            .mobile-menu {
                display: none;
            }
            .mobile-menu.active {
                display: block;
                animation: slideIn 0.3s ease-out;
            }
        }
        
        /* Améliorations responsive générales */
        @media (max-width: 640px) {
            .table-responsive {
                font-size: 0.875rem;
            }
            
            .btn-mobile-full {
                width: 100%;
            }
            
            .hide-mobile {
                display: none !important;
            }
        }
        
        @media (max-width: 768px) {
            .container-mobile {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
        
        /* Amélioration des notifications */
        .notification-success {
            animation: fadeIn 0.4s ease-out;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .notification-error {
            animation: fadeIn 0.4s ease-out;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg no-print border-b border-gray-200 sticky top-0 z-50 backdrop-blur-sm bg-white/95">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo et nom -->
                    <div class="flex-shrink-0 flex items-center">
                        <?php if($settings->logo): ?>
                        <img src="<?php echo e(asset('storage/' . $settings->logo)); ?>" alt="Logo" class="h-10 w-auto" 
                             onerror="console.error('Erreur chargement logo: <?php echo e($settings->logo); ?>'); this.style.display='none';">
                        <div class="ml-4 flex flex-col">
                        <?php else: ?>
                        <div class="flex flex-col">
                        <?php endif; ?>
                            <h1 class="text-lg sm:text-xl font-bold" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                                <i class="fas fa-file-invoice-dollar mr-2"></i>
                                <span class="hidden sm:inline">A2 VitraDevis</span>
                                <span class="sm:hidden">A2 VitraDevis</span>
                            </h1>
                            <p class="text-xs text-gray-500 italic hidden sm:block">
                                Votre devis, clair comme le verre.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Menu desktop -->
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-1">
                        <a href="<?php echo e(route('dashboard')); ?>" 
                           class="px-3 py-2 rounded-md text-sm font-medium transition-colors <?php echo e(request()->routeIs('dashboard*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100'); ?>"
                           style="<?php echo e(request()->routeIs('dashboard*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                            <i class="fas fa-chart-line mr-2"></i>Tableau de bord
                        </a>
                        <!-- Menu déroulant Devis -->
                        <?php if (\Illuminate\Support\Facades\Blade::check('hasAnyPermission', ['quotes.view', 'quotes.create', 'payments.view', 'quotes.calculate-materials'])): ?>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center <?php echo e(request()->routeIs('quotes.*') || request()->routeIs('payments.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100'); ?>"
                                    style="<?php echo e(request()->routeIs('quotes.*') || request()->routeIs('payments.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                                <i class="fas fa-file-invoice mr-2"></i>Devis
                                <i class="fas fa-chevron-down text-xs ml-2" :class="{ 'transform rotate-180': open }"></i>
                            </button>
                            
                            <!-- Sous-menu déroulant -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                 style="display: none;">
                                <div class="py-1">
                                    <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'quotes.create')): ?>
                                    <a href="<?php echo e(route('quotes.create')); ?>" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors <?php echo e(request()->routeIs('quotes.create') ? 'font-semibold' : ''); ?>"
                                       style="<?php echo e(request()->routeIs('quotes.create') ? 'color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                                        <i class="fas fa-plus-circle mr-2"></i>Nouveau devis
                                    </a>
                                    <?php endif; ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'quotes.view')): ?>
                                    <a href="<?php echo e(route('quotes.index')); ?>" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors <?php echo e(request()->routeIs('quotes.index') ? 'font-semibold' : ''); ?>"
                                       style="<?php echo e(request()->routeIs('quotes.index') ? 'color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                                        <i class="fas fa-list mr-2"></i>Liste
                                    </a>
                                    <?php endif; ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'payments.view')): ?>
                                    <a href="<?php echo e(route('payments.index')); ?>" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors <?php echo e(request()->routeIs('payments.*') ? 'font-semibold' : ''); ?>"
                                       style="<?php echo e(request()->routeIs('payments.*') ? 'color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                                        <i class="fas fa-money-bill-wave mr-2"></i>Paiement
                                    </a>
                                    <?php endif; ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'quotes.calculate-materials')): ?>
                                    <a href="<?php echo e(route('quotes.calculate-materials')); ?>" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors <?php echo e(request()->routeIs('quotes.calculate-materials') ? 'font-semibold' : ''); ?>"
                                       style="<?php echo e(request()->routeIs('quotes.calculate-materials') ? 'color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                                        <i class="fas fa-calculator mr-2"></i>Calcul Matériaux
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <!-- Menu déroulant Clients -->
                        <?php if (\Illuminate\Support\Facades\Blade::check('hasAnyPermission', ['clients.view', 'clients.create'])): ?>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center <?php echo e(request()->routeIs('clients.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100'); ?>"
                                    style="<?php echo e(request()->routeIs('clients.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                                <i class="fas fa-users mr-2"></i>Clients
                                <i class="fas fa-chevron-down text-xs ml-2" :class="{ 'transform rotate-180': open }"></i>
                            </button>
                            
                            <!-- Sous-menu déroulant -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                 style="display: none;">
                                <div class="py-1">
                                    <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'clients.create')): ?>
                                    <a href="<?php echo e(route('clients.create')); ?>" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors <?php echo e(request()->routeIs('clients.create') ? 'font-semibold' : ''); ?>"
                                       style="<?php echo e(request()->routeIs('clients.create') ? 'color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                                        <i class="fas fa-plus-circle mr-2"></i>Nouveau
                                    </a>
                                    <?php endif; ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'clients.view')): ?>
                                    <a href="<?php echo e(route('clients.index')); ?>" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors <?php echo e(request()->routeIs('clients.index') ? 'font-semibold' : ''); ?>"
                                       style="<?php echo e(request()->routeIs('clients.index') ? 'color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                                        <i class="fas fa-list mr-2"></i>Liste
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <!-- Menu déroulant Produits -->
                        <?php if (\Illuminate\Support\Facades\Blade::check('hasAnyPermission', ['products.view', 'products.create'])): ?>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center <?php echo e(request()->routeIs('products.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100'); ?>"
                                    style="<?php echo e(request()->routeIs('products.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                                <i class="fas fa-box mr-2"></i>Produits
                                <i class="fas fa-chevron-down text-xs ml-2" :class="{ 'transform rotate-180': open }"></i>
                            </button>
                            
                            <!-- Sous-menu déroulant -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                 style="display: none;">
                                <div class="py-1">
                                    <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'products.create')): ?>
                                    <a href="<?php echo e(route('products.create')); ?>" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors <?php echo e(request()->routeIs('products.create') ? 'font-semibold' : ''); ?>"
                                       style="<?php echo e(request()->routeIs('products.create') ? 'color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                                        <i class="fas fa-plus-circle mr-2"></i>Nouveau
                                    </a>
                                    <?php endif; ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'products.view')): ?>
                                    <a href="<?php echo e(route('products.index')); ?>" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors <?php echo e(request()->routeIs('products.index') ? 'font-semibold' : ''); ?>"
                                       style="<?php echo e(request()->routeIs('products.index') ? 'color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                                        <i class="fas fa-list mr-2"></i>Liste
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Menu utilisateur et bouton mobile -->
                <div class="flex items-center space-x-4">
                    <?php if(auth()->guard()->check()): ?>
                    <!-- Menu déroulant utilisateur (desktop) -->
                    <div class="hidden sm:block relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-user-circle"></i>
                            <span><?php echo e(Auth::user()->name); ?></span>
                            <i class="fas fa-chevron-down text-xs" :class="{ 'transform rotate-180': open }"></i>
                        </button>
                        
                        <!-- Sous-menu déroulant -->
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                             style="display: none;">
                            <div class="py-1">
                                <?php if(auth()->guard()->check()): ?>
                                <?php if(Auth::user()->role === 'admin'): ?>
                                <a href="<?php echo e(route('users.index')); ?>" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors <?php echo e(request()->routeIs('users.*') ? 'font-semibold' : ''); ?>"
                                   style="<?php echo e(request()->routeIs('users.*') ? 'color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                                    <i class="fas fa-users-cog mr-2"></i>Utilisateurs
                                </a>
                                <?php endif; ?>
                                <?php endif; ?>
                                <a href="<?php echo e(route('settings.index')); ?>" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors <?php echo e(request()->routeIs('settings.*') ? 'font-semibold' : ''); ?>"
                                   style="<?php echo e(request()->routeIs('settings.*') ? 'color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                                    <i class="fas fa-cog mr-2"></i>Paramètres
                                </a>
                                <a href="<?php echo e(route('profile')); ?>" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors <?php echo e(request()->routeIs('profile') ? 'font-semibold' : ''); ?>"
                                   style="<?php echo e(request()->routeIs('profile') ? 'color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                                    <i class="fas fa-user-circle mr-2"></i>Mon Profil
                                </a>
                                <form action="<?php echo e(route('logout')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" 
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <!-- Bouton menu mobile -->
                    <div class="sm:hidden flex items-center">
                        <button type="button" onclick="toggleMobileMenu()" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-100 focus:outline-none">
                            <i class="fas fa-bars text-xl" id="menu-icon"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Menu mobile -->
        <div class="sm:hidden mobile-menu" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t border-gray-200">
                <a href="<?php echo e(route('dashboard')); ?>" 
                   class="block px-3 py-2 rounded-md text-base font-medium <?php echo e(request()->routeIs('dashboard*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100'); ?>"
                   style="<?php echo e(request()->routeIs('dashboard*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                    <i class="fas fa-chart-line mr-2"></i>Tableau de bord
                </a>
                <!-- Menu Devis mobile -->
                <?php if (\Illuminate\Support\Facades\Blade::check('hasAnyPermission', ['quotes.view', 'quotes.create', 'payments.view', 'quotes.calculate-materials'])): ?>
                <div x-data="{ open: false }" class="border-b border-gray-200 pb-2 mb-2">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-2 rounded-md text-base font-medium <?php echo e(request()->routeIs('quotes.*') || request()->routeIs('payments.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100'); ?>"
                            style="<?php echo e(request()->routeIs('quotes.*') || request()->routeIs('payments.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                        <span><i class="fas fa-file-invoice mr-2"></i>Devis</span>
                        <i class="fas fa-chevron-down text-xs" :class="{ 'transform rotate-180': open }"></i>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0"
                         x-transition:enter-end="transform opacity-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100"
                         x-transition:leave-end="transform opacity-0"
                         class="pl-4 mt-1 space-y-1">
                        <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'quotes.create')): ?>
                        <a href="<?php echo e(route('quotes.create')); ?>" 
                           class="block px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('quotes.create') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100'); ?>"
                           style="<?php echo e(request()->routeIs('quotes.create') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                            <i class="fas fa-plus-circle mr-2"></i>Nouveau devis
                        </a>
                        <?php endif; ?>
                        <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'quotes.view')): ?>
                        <a href="<?php echo e(route('quotes.index')); ?>" 
                           class="block px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('quotes.index') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100'); ?>"
                           style="<?php echo e(request()->routeIs('quotes.index') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                            <i class="fas fa-list mr-2"></i>Liste
                        </a>
                        <?php endif; ?>
                        <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'payments.view')): ?>
                        <a href="<?php echo e(route('payments.index')); ?>" 
                           class="block px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('payments.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100'); ?>"
                           style="<?php echo e(request()->routeIs('payments.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                            <i class="fas fa-money-bill-wave mr-2"></i>Paiement
                        </a>
                        <?php endif; ?>
                        <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'quotes.calculate-materials')): ?>
                        <a href="<?php echo e(route('quotes.calculate-materials')); ?>" 
                           class="block px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('quotes.calculate-materials') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100'); ?>"
                           style="<?php echo e(request()->routeIs('quotes.calculate-materials') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                            <i class="fas fa-calculator mr-2"></i>Calcul Matériaux
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                <!-- Menu Clients mobile -->
                <?php if (\Illuminate\Support\Facades\Blade::check('hasAnyPermission', ['clients.view', 'clients.create'])): ?>
                <div x-data="{ open: false }" class="border-b border-gray-200 pb-2 mb-2">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-2 rounded-md text-base font-medium <?php echo e(request()->routeIs('clients.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100'); ?>"
                            style="<?php echo e(request()->routeIs('clients.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                        <span><i class="fas fa-users mr-2"></i>Clients</span>
                        <i class="fas fa-chevron-down text-xs" :class="{ 'transform rotate-180': open }"></i>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0"
                         x-transition:enter-end="transform opacity-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100"
                         x-transition:leave-end="transform opacity-0"
                         class="pl-4 mt-1 space-y-1">
                        <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'clients.create')): ?>
                        <a href="<?php echo e(route('clients.create')); ?>" 
                           class="block px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('clients.create') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100'); ?>"
                           style="<?php echo e(request()->routeIs('clients.create') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                            <i class="fas fa-plus-circle mr-2"></i>Nouveau
                        </a>
                        <?php endif; ?>
                        <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'clients.view')): ?>
                        <a href="<?php echo e(route('clients.index')); ?>" 
                           class="block px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('clients.index') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100'); ?>"
                           style="<?php echo e(request()->routeIs('clients.index') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                            <i class="fas fa-list mr-2"></i>Liste
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                <!-- Menu Produits mobile -->
                <?php if (\Illuminate\Support\Facades\Blade::check('hasAnyPermission', ['products.view', 'products.create'])): ?>
                <div x-data="{ open: false }" class="border-b border-gray-200 pb-2 mb-2">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-2 rounded-md text-base font-medium <?php echo e(request()->routeIs('products.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100'); ?>"
                            style="<?php echo e(request()->routeIs('products.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                        <span><i class="fas fa-box mr-2"></i>Produits</span>
                        <i class="fas fa-chevron-down text-xs" :class="{ 'transform rotate-180': open }"></i>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0"
                         x-transition:enter-end="transform opacity-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100"
                         x-transition:leave-end="transform opacity-0"
                         class="pl-4 mt-1 space-y-1">
                        <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'products.create')): ?>
                        <a href="<?php echo e(route('products.create')); ?>" 
                           class="block px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('products.create') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100'); ?>"
                           style="<?php echo e(request()->routeIs('products.create') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                            <i class="fas fa-plus-circle mr-2"></i>Nouveau
                        </a>
                        <?php endif; ?>
                        <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'products.view')): ?>
                        <a href="<?php echo e(route('products.index')); ?>" 
                           class="block px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('products.index') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100'); ?>"
                           style="<?php echo e(request()->routeIs('products.index') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                            <i class="fas fa-list mr-2"></i>Liste
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if(auth()->guard()->check()): ?>
                <!-- Menu utilisateur mobile -->
                <div class="border-t border-gray-200 pt-2 mt-2">
                    <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-user-circle mr-2"></i><?php echo e(Auth::user()->name); ?>

                    </div>
                    <?php if(Auth::user()->role === 'admin'): ?>
                    <a href="<?php echo e(route('users.index')); ?>" 
                       class="block px-3 py-2 rounded-md text-base font-medium <?php echo e(request()->routeIs('users.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100'); ?>"
                       style="<?php echo e(request()->routeIs('users.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                        <i class="fas fa-users-cog mr-2"></i>Utilisateurs
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('settings.index')); ?>" 
                       class="block px-3 py-2 rounded-md text-base font-medium <?php echo e(request()->routeIs('settings.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100'); ?>"
                       style="<?php echo e(request()->routeIs('settings.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                        <i class="fas fa-cog mr-2"></i>Paramètres
                    </a>
                    <a href="<?php echo e(route('profile')); ?>" 
                       class="block px-3 py-2 rounded-md text-base font-medium <?php echo e(request()->routeIs('profile') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100'); ?>"
                       style="<?php echo e(request()->routeIs('profile') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : ''); ?>">
                        <i class="fas fa-user-circle mr-2"></i>Mon Profil
                    </a>
                    <form action="<?php echo e(route('logout')); ?>" method="POST" class="block">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                        </button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="py-6 sm:py-8 bg-gradient-to-br from-gray-50 via-white to-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if(session('success')): ?>
                <div class="mb-6 notification-success bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md" role="alert">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-green-800"><?php echo e(session('success')); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="mb-6 notification-error bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md" role="alert">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-red-800 mb-2">Erreurs de validation</h3>
                            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="animate-fade-in">
            <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </main>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('menu-icon');
            menu.classList.toggle('active');
            if (menu.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        }
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\gdevis\resources\views/layouts/app.blade.php ENDPATH**/ ?>