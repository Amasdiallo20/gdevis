<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="@yield('description', 'A2 VitraDevis - Votre devis, clair comme le verre. Gestion de devis pour vitrerie et aluminium.')">
    <meta name="keywords" content="devis, vitrerie, aluminium, gestion, facturation">
    <meta name="author" content="A2 VitraDevis">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="{{ $settings->primary_color ?? '#3b82f6' }}">
    
    <!-- Favicon - Utilise le logo des paramètres si disponible, sinon favicon par défaut -->
    @php
        $faviconUrl = null;
        if ($settings->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($settings->logo)) {
            $faviconUrl = asset('storage/' . $settings->logo);
        } elseif (file_exists(public_path('favicon.ico'))) {
            $faviconUrl = asset('favicon.ico');
        } elseif (file_exists(public_path('favicon.png'))) {
            $faviconUrl = asset('favicon.png');
        }
    @endphp
    @if($faviconUrl)
        <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
        <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
        <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
    @else
        <!-- Favicon par défaut si aucun logo n'est configuré -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    @endif
    
    <!-- Preconnect pour améliorer les performances -->
    <link rel="preconnect" href="https://cdn.tailwindcss.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.tailwindcss.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    
    <title>@yield('title', 'A2 VitraDevis')</title>
    
    <!-- Tailwind CSS - doit être chargé immédiatement pour générer les styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js avec defer -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --primary-color: {{ $settings->primary_color ?? '#3b82f6' }};
            --secondary-color: {{ $settings->secondary_color ?? '#1e40af' }};
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
        
        /* Menu mobile optimisé */
        @media (max-width: 640px) {
            .mobile-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                border-top: 1px solid #e5e7eb;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                max-height: calc(100vh - 64px);
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
                z-index: 50;
            }
            .mobile-menu.active {
                display: block;
                animation: slideDown 0.3s ease-out;
            }
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Sidebar mobile optimisée */
        .sidebar-mobile {
            position: fixed;
            top: 0;
            left: 0;
            width: 85%;
            max-width: 320px;
            height: 100vh;
            background: white;
            border-right: 1px solid #e5e7eb;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 50;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
            -webkit-overflow-scrolling: touch;
        }
        
        .sidebar-mobile.active {
            transform: translateX(0);
        }
        
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(2px);
            z-index: 45;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        /* Amélioration des boutons tactiles mobile */
        @media (max-width: 640px) {
            .mobile-touch-target {
                min-height: 44px;
                min-width: 44px;
                padding: 12px 16px;
                touch-action: manipulation;
            }
            
            .mobile-menu a,
            .mobile-menu button {
                min-height: 48px;
                display: flex;
                align-items: center;
                padding: 14px 16px;
                font-size: 16px;
                -webkit-tap-highlight-color: rgba(0, 0, 0, 0.1);
            }
            
            .sidebar-mobile .sidebar-item a {
                min-height: 48px;
                padding: 14px 16px;
                font-size: 15px;
                -webkit-tap-highlight-color: rgba(0, 0, 0, 0.1);
            }
        }
        
        /* Améliorations responsive générales */
        @media (max-width: 640px) {
            /* Réduction très agressive des tailles de texte sur mobile */
            h1, .text-4xl, .text-5xl, .text-6xl {
                font-size: 1.125rem !important; /* 18px */
                line-height: 1.3 !important;
            }
            
            h2, .text-3xl {
                font-size: 1rem !important; /* 16px */
                line-height: 1.3 !important;
            }
            
            h3, .text-2xl {
                font-size: 0.9375rem !important; /* 15px */
                line-height: 1.4 !important;
            }
            
            h4, .text-xl {
                font-size: 0.875rem !important; /* 14px */
                line-height: 1.4 !important;
            }
            
            .text-lg {
                font-size: 0.8125rem !important; /* 13px */
            }
            
            .text-base {
                font-size: 0.75rem !important; /* 12px */
            }
            
            .text-sm {
                font-size: 0.6875rem !important; /* 11px */
            }
            
            .text-xs {
                font-size: 0.625rem !important; /* 10px */
            }
            
            /* Réduction des nombres dans les cartes */
            .text-3xl.font-bold {
                font-size: 1.125rem !important; /* 18px */
            }
            
            .text-2xl.font-bold {
                font-size: 1rem !important; /* 16px */
            }
            
            .text-xl.font-bold {
                font-size: 0.9375rem !important; /* 15px */
            }
            
            /* Réduction très agressive des tailles de boutons sur mobile */
            button:not(.mobile-touch-target):not([class*="text-xs"]), 
            .btn-primary, 
            a[class*="btn"]:not([class*="text-xs"]),
            a[class*="px-6"][class*="py-3"]:not([class*="text-xs"]),
            a[class*="px-5"][class*="py-3"]:not([class*="text-xs"]),
            a[class*="px-4"][class*="py-3"]:not([class*="text-xs"]),
            button[class*="px-6"][class*="py-3"]:not([class*="text-xs"]),
            button[class*="px-5"][class*="py-3"]:not([class*="text-xs"]),
            button[class*="px-4"][class*="py-3"]:not([class*="text-xs"]) {
                padding: 0.25rem 0.625rem !important; /* 4px 10px */
                font-size: 0.6875rem !important; /* 11px */
                min-height: 28px !important;
            }
            
            /* Réduction des boutons avec classes spécifiques */
            a[class*="inline-flex"][class*="items-center"][class*="justify-center"]:not(.mobile-touch-target),
            button[class*="inline-flex"][class*="items-center"][class*="justify-center"]:not(.mobile-touch-target) {
                padding: 0.25rem 0.625rem !important;
                font-size: 0.6875rem !important;
                min-height: 28px !important;
            }
            
            /* Réduction spécifique pour les boutons dans les cartes */
            .card-modern button,
            .card-modern a[class*="btn"],
            .card-modern .btn-primary {
                padding: 0.25rem 0.5rem !important;
                font-size: 0.625rem !important; /* 10px */
                min-height: 26px !important;
            }
            
            /* Réduction agressive des espacements des cartes et conteneurs */
            .p-5, .p-6 {
                padding: 0.75rem !important; /* 12px */
            }
            
            .p-4 {
                padding: 0.625rem !important; /* 10px */
            }
            
            .px-6 {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
            
            .py-6 {
                padding-top: 0.75rem !important;
                padding-bottom: 0.75rem !important;
            }
            
            .px-4 {
                padding-left: 0.625rem !important;
                padding-right: 0.625rem !important;
            }
            
            .px-5 {
                padding-left: 0.625rem !important;
                padding-right: 0.625rem !important;
            }
            
            .py-3 {
                padding-top: 0.375rem !important;
                padding-bottom: 0.375rem !important;
            }
            
            .py-2 {
                padding-top: 0.25rem !important;
                padding-bottom: 0.25rem !important;
            }
            
            .mb-4, .mb-6, .mb-8 {
                margin-bottom: 0.5rem !important;
            }
            
            .mt-4, .mt-6, .mt-8 {
                margin-top: 0.5rem !important;
            }
            
            .gap-2 {
                gap: 0.375rem !important;
            }
            
            .gap-3 {
                gap: 0.5rem !important;
            }
            
            .gap-4 {
                gap: 0.625rem !important;
            }
            
            .gap-6 {
                gap: 0.75rem !important;
            }
            
            /* Réduction agressive des icônes sur mobile */
            .fas, .fa, i[class*="fa-"] {
                font-size: 0.75rem !important; /* 12px */
            }
            
            i.text-xl, .text-xl i {
                font-size: 0.875rem !important; /* 14px */
            }
            
            i.text-2xl, .text-2xl i {
                font-size: 1rem !important; /* 16px */
            }
            
            i.text-3xl, .text-3xl i {
                font-size: 1.125rem !important; /* 18px */
            }
            
            i.text-4xl, .text-4xl i {
                font-size: 1.25rem !important; /* 20px */
            }
            
            i.text-5xl, .text-5xl i {
                font-size: 1.5rem !important; /* 24px */
            }
            
            /* Réduction des icônes dans les boutons */
            button i, a i, .btn-primary i {
                font-size: 0.6875rem !important; /* 11px */
            }
            
            .table-responsive {
                font-size: 0.875rem;
            }
            
            .btn-mobile-full {
                width: 100%;
            }
            
            .hide-mobile {
                display: none !important;
            }
            
            /* Amélioration des tableaux sur mobile */
            .table-mobile-card {
                display: block;
            }
            
            .table-mobile-card thead {
                display: none;
            }
            
            .table-mobile-card tbody,
            .table-mobile-card tr,
            .table-mobile-card td {
                display: block;
                width: 100%;
            }
            
            .table-mobile-card tr {
                margin-bottom: 1rem;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                padding: 1rem;
                background: white;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            }
            
            .table-mobile-card td {
                text-align: left !important;
                padding: 0.5rem 0;
                border: none;
            }
            
            .table-mobile-card td:before {
                content: attr(data-label);
                font-weight: 700;
                display: inline-block;
                width: 40%;
                color: #6b7280;
                margin-right: 0.5rem;
            }
        }
        
        @media (max-width: 768px) {
            .container-mobile {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            /* Amélioration du scroll horizontal pour les tableaux */
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
                scrollbar-width: thin;
            }
            
            .overflow-x-auto::-webkit-scrollbar {
                height: 8px;
            }
            
            .overflow-x-auto::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 4px;
            }
            
            .overflow-x-auto::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 4px;
            }
            
            .overflow-x-auto::-webkit-scrollbar-thumb:hover {
                background: #555;
            }
        }
        
        /* Optimisation des performances */
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        img {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }
        
        /* Réduction des animations sur mobile pour améliorer les performances */
        @media (max-width: 768px) and (prefers-reduced-motion: no-preference) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
        
        /* Support pour prefers-reduced-motion */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
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
        
        /* Sidebar latérale */
        .sidebar {
            width: 260px;
            min-height: calc(100vh - 64px);
            background: white;
            border-right: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        
        .sidebar-item {
            transition: all 0.2s ease;
        }
        
        .sidebar-item:hover {
            background-color: #f9fafb;
        }
        
        .sidebar-item.active {
            background: linear-gradient(135deg, var(--primary-color)15 0%, var(--secondary-color)15 100%);
            border-left: 3px solid var(--primary-color);
        }
        
        .sidebar-item.active a {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        @media (max-width: 1024px) {
            .sidebar {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg no-print border-b border-gray-200 sticky top-0 z-50 backdrop-blur-sm bg-white/95">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                    <!-- Logo et nom -->
                    <div class="flex-shrink-0 flex items-center">
                        @if($settings->logo)
                        <img src="{{ asset('storage/' . $settings->logo) }}" 
                             alt="Logo A2 VitraDevis" 
                             class="h-10 w-auto" 
                             loading="eager"
                             width="auto"
                             height="40"
                             onerror="console.error('Erreur chargement logo: {{ $settings->logo }}'); this.style.display='none';">
                        <div class="ml-4 flex flex-col">
                        @else
                        <div class="flex flex-col">
                        @endif
                        @php
                            $homeRoute = Auth::check() ? route('dashboard') : route('home');
                            $isMobileMenuEnabled = Auth::check();
                        @endphp
                        <a href="{{ $homeRoute }}" 
                           class="text-lg sm:text-xl font-bold hover:opacity-80 transition-opacity cursor-pointer" 
                           style="color: {{ $settings->primary_color ?? '#3b82f6' }}; text-decoration: none;">
                            A2 VitraDevis
                        </a>
                            <p class="text-xs text-gray-500 italic hidden sm:block">
                                Votre devis, clair comme le verre.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Menu desktop -->
                <div class="hidden sm:flex sm:items-center sm:flex-1 sm:justify-around sm:mx-8">
                        @auth
                        <a href="{{ route('dashboard') }}" 
                           class="px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center {{ request()->routeIs('dashboard*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100' }}"
                           style="{{ request()->routeIs('dashboard*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : '' }}">
                            <i class="fas fa-home mr-2"></i>Accueil
                        </a>
                        <!-- Menu Devis -->
                        @hasAnyPermission(['quotes.view', 'quotes.create', 'payments.view', 'quotes.calculate-materials'])
                        <a href="{{ route('quotes.index') }}" 
                                    class="px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center {{ request()->routeIs('quotes.*') || request()->routeIs('payments.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100' }}"
                                    style="{{ request()->routeIs('quotes.*') || request()->routeIs('payments.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : '' }}">
                                <i class="fas fa-file-invoice mr-2"></i>Devis
                        </a>
                        @endhasAnyPermission
                        <!-- Menu Clients -->
                        @hasAnyPermission(['clients.view', 'clients.create'])
                        <a href="{{ route('clients.index') }}" 
                                    class="px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center {{ request()->routeIs('clients.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100' }}"
                                    style="{{ request()->routeIs('clients.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : '' }}">
                                <i class="fas fa-users mr-2"></i>Clients
                        </a>
                        @endhasAnyPermission
                        <!-- Menu Produits -->
                        @hasAnyPermission(['products.view', 'products.create'])
                        <a href="{{ route('products.index') }}" 
                                    class="px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center {{ request()->routeIs('products.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100' }}"
                                    style="{{ request()->routeIs('products.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : '' }}">
                                <i class="fas fa-box mr-2"></i>Produits
                        </a>
                        @endhasAnyPermission
                        <!-- Menu Matériaux -->
                        @hasAnyPermission(['materials.view', 'materials.create'])
                        <a href="{{ route('materials.index') }}" 
                                    class="px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center {{ request()->routeIs('materials.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100' }}"
                                    style="{{ request()->routeIs('materials.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : '' }}">
                                <i class="fas fa-tools mr-2"></i>Matériaux
                        </a>
                        @endhasAnyPermission
                        @endauth
                        <!-- Menu Catalogue (toujours visible) -->
                        <a href="{{ route('modeles.index') }}" 
                                    class="px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center {{ request()->routeIs('modeles.*') || request()->routeIs('catalogue.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100' }}"
                                    style="{{ request()->routeIs('modeles.*') || request()->routeIs('catalogue.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : '' }}">
                                <i class="fas fa-images mr-2"></i>Catalogue
                        </a>
                </div>
                
                <!-- Menu utilisateur et bouton mobile -->
                <div class="flex items-center space-x-4">
                    @guest
                    <!-- Bouton Connexion pour les visiteurs -->
                    <a href="{{ route('login') }}" 
                       class="hidden sm:inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-white shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200"
                       style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                        <i class="fas fa-sign-in-alt mr-2"></i>Connexion
                    </a>
                    @endguest
                    @auth
                    <!-- Menu déroulant utilisateur (desktop) -->
                    <div class="hidden sm:block relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ Auth::user()->name }}</span>
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
                                @auth
                                @if(Auth::user()->role === 'admin')
                                <a href="{{ route('users.index') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('users.*') ? 'font-semibold' : '' }}"
                                   style="{{ request()->routeIs('users.*') ? 'color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : '' }}">
                                    <i class="fas fa-users-cog mr-2"></i>Utilisateurs
                                </a>
                                @endif
                                @endauth
                                <a href="{{ route('settings.index') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('settings.*') ? 'font-semibold' : '' }}"
                                   style="{{ request()->routeIs('settings.*') ? 'color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : '' }}">
                                    <i class="fas fa-cog mr-2"></i>Paramètres
                                </a>
                                <a href="{{ route('profile') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('profile') ? 'font-semibold' : '' }}"
                                   style="{{ request()->routeIs('profile') ? 'color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : '' }}">
                                    <i class="fas fa-user-circle mr-2"></i>Mon Profil
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endauth
                    <!-- Boutons menu mobile optimisés -->
                    <div class="sm:hidden flex items-center space-x-2">
                        <!-- Bouton sidebar mobile -->
                        <button type="button" onclick="toggleSidebarMobile()" 
                                class="mobile-touch-target inline-flex items-center justify-center rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 active:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all"
                                style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                                aria-label="Ouvrir le menu de navigation">
                            <i class="fas fa-bars text-xl" id="sidebar-icon"></i>
                        </button>
                        <!-- Bouton menu utilisateur mobile -->
                        <button type="button" onclick="toggleMobileMenu()" 
                                class="mobile-touch-target inline-flex items-center justify-center rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 active:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all"
                                style="focus:ring-color: {{ $settings->primary_color ?? '#3b82f6' }};"
                                aria-label="Ouvrir le menu utilisateur">
                            <i class="fas fa-user-circle text-xl" id="menu-icon"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Menu mobile optimisé -->
        <div class="sm:hidden mobile-menu" id="mobile-menu">
            <div class="px-0 pt-1 pb-2 space-y-0 bg-white">
                @auth
                <a href="{{ route('dashboard') }}" 
                   onclick="closeMobileMenu()"
                   class="block px-4 py-3 rounded-none text-base font-medium transition-colors {{ request()->routeIs('dashboard*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 active:bg-gray-100' }}"
                   style="{{ request()->routeIs('dashboard*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : '' }}">
                    <i class="fas fa-home mr-3 w-5"></i>Accueil
                </a>
                @hasAnyPermission(['quotes.view', 'quotes.create', 'payments.view', 'quotes.calculate-materials'])
                <a href="{{ route('quotes.index') }}" 
                   onclick="closeMobileMenu()"
                   class="block px-4 py-3 rounded-none text-base font-medium transition-colors {{ request()->routeIs('quotes.*') || request()->routeIs('payments.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 active:bg-gray-100' }}"
                   style="{{ request()->routeIs('quotes.*') || request()->routeIs('payments.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : '' }}">
                    <i class="fas fa-file-invoice mr-3 w-5"></i>Devis
                </a>
                @endhasAnyPermission
                @hasAnyPermission(['clients.view', 'clients.create'])
                <a href="{{ route('clients.index') }}" 
                   onclick="closeMobileMenu()"
                   class="block px-4 py-3 rounded-none text-base font-medium transition-colors {{ request()->routeIs('clients.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 active:bg-gray-100' }}"
                   style="{{ request()->routeIs('clients.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : '' }}">
                    <i class="fas fa-users mr-3 w-5"></i>Clients
                </a>
                @endhasAnyPermission
                @hasAnyPermission(['products.view', 'products.create'])
                <a href="{{ route('products.index') }}" 
                   onclick="closeMobileMenu()"
                   class="block px-4 py-3 rounded-none text-base font-medium transition-colors {{ request()->routeIs('products.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 active:bg-gray-100' }}"
                   style="{{ request()->routeIs('products.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : '' }}">
                    <i class="fas fa-box mr-3 w-5"></i>Produits
                </a>
                @endhasAnyPermission
                @hasAnyPermission(['materials.view', 'materials.create'])
                <a href="{{ route('materials.index') }}" 
                   onclick="closeMobileMenu()"
                   class="block px-4 py-3 rounded-none text-base font-medium transition-colors {{ request()->routeIs('materials.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 active:bg-gray-100' }}"
                   style="{{ request()->routeIs('materials.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : '' }}">
                    <i class="fas fa-tools mr-3 w-5"></i>Matériaux
                </a>
                @endhasAnyPermission
                @endauth
                <a href="{{ route('modeles.index') }}" 
                   onclick="closeMobileMenu()"
                   class="block px-4 py-3 rounded-none text-base font-medium transition-colors {{ request()->routeIs('modeles.*') || request()->routeIs('catalogue.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 active:bg-gray-100' }}"
                   style="{{ request()->routeIs('modeles.*') || request()->routeIs('catalogue.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : '' }}">
                    <i class="fas fa-images mr-3 w-5"></i>Catalogue
                </a>
                @auth
                <!-- Menu utilisateur mobile optimisé -->
                <div class="border-t-2 border-gray-200 pt-3 mt-2">
                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center">
                        <i class="fas fa-user-circle mr-2"></i>{{ Auth::user()->name }}
                    </div>
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('users.index') }}" 
                       onclick="closeMobileMenu()"
                       class="block px-4 py-3 rounded-none text-base font-medium transition-colors {{ request()->routeIs('users.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 active:bg-gray-100' }}"
                       style="{{ request()->routeIs('users.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : '' }}">
                        <i class="fas fa-users-cog mr-3 w-5"></i>Utilisateurs
                    </a>
                    @endif
                    <a href="{{ route('settings.index') }}" 
                       onclick="closeMobileMenu()"
                       class="block px-4 py-3 rounded-none text-base font-medium transition-colors {{ request()->routeIs('settings.*') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 active:bg-gray-100' }}"
                       style="{{ request()->routeIs('settings.*') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : '' }}">
                        <i class="fas fa-cog mr-3 w-5"></i>Paramètres
                    </a>
                    <a href="{{ route('profile') }}" 
                       onclick="closeMobileMenu()"
                       class="block px-4 py-3 rounded-none text-base font-medium transition-colors {{ request()->routeIs('profile') ? 'text-white' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 active:bg-gray-100' }}"
                       style="{{ request()->routeIs('profile') ? 'background-color: ' . ($settings->primary_color ?? '#3b82f6') . ';' : '' }}">
                        <i class="fas fa-user-circle mr-3 w-5"></i>Mon Profil
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="block">
                        @csrf
                        <button type="submit" onclick="closeMobileMenu()" class="w-full text-left px-4 py-3 rounded-none text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 active:bg-gray-100 transition-colors">
                            <i class="fas fa-sign-out-alt mr-3 w-5"></i>Déconnexion
                        </button>
                    </form>
                </div>
                @endauth
                @guest
                <div class="border-t-2 border-gray-200 pt-3 mt-2">
                    <a href="{{ route('login') }}" 
                       onclick="closeMobileMenu()"
                       class="block px-4 py-3 rounded-none text-base font-medium text-white transition-colors hover:opacity-90 active:opacity-80"
                       style="background: linear-gradient(135deg, {{ $settings->primary_color ?? '#3b82f6' }} 0%, {{ $settings->secondary_color ?? '#1e40af' }} 100%);">
                        <i class="fas fa-sign-in-alt mr-3 w-5"></i>Connexion
                    </a>
                </div>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Overlay pour sidebar mobile -->
    <div class="sidebar-overlay lg:hidden" id="sidebar-overlay" onclick="toggleSidebarMobile()"></div>

    <div class="flex no-print">
        <!-- Sidebar latérale Desktop -->
        <aside class="sidebar hidden lg:block fixed left-0 top-16 z-40 overflow-y-auto">
            <div class="p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 flex items-center">
                    <i class="fas fa-list mr-2"></i>
                    Navigation
                </h3>
                
                <nav class="space-y-1">
                    @php
                        $currentRoute = request()->route()->getName();
                    @endphp
                    
                    <!-- Sous-menus pour Devis -->
                    @if(request()->routeIs('quotes.*') || request()->routeIs('payments.*'))
                        @hasPermission('quotes.view')
                        <div class="sidebar-item {{ request()->routeIs('quotes.index') ? 'active' : '' }}">
                            <a href="{{ route('quotes.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700">
                                <i class="fas fa-list mr-3 w-5"></i>
                                Liste
                            </a>
                        </div>
                        @endhasPermission
                        
                        @hasPermission('quotes.create')
                        <div class="sidebar-item {{ request()->routeIs('quotes.create') ? 'active' : '' }}">
                            <a href="{{ route('quotes.create') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700">
                                <i class="fas fa-plus-circle mr-3 w-5"></i>
                                Nouveau devis
                            </a>
                        </div>
                        @endhasPermission
                        
                        @hasPermission('payments.view')
                        <div class="sidebar-item {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                            <a href="{{ route('payments.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700">
                                <i class="fas fa-money-bill-wave mr-3 w-5"></i>
                                Paiements
                            </a>
                        </div>
                        @endhasPermission
                        
                        @hasPermission('quotes.calculate-materials')
                        <div class="sidebar-item {{ request()->routeIs('quotes.calculate-materials') || request()->routeIs('quotes.print-materials') ? 'active' : '' }}">
                            <a href="{{ route('quotes.calculate-materials') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700">
                                <i class="fas fa-calculator mr-3 w-5"></i>
                                Calcul Matériaux
                            </a>
                        </div>
                        @endhasPermission
                    @endif
                    
                    <!-- Sous-menus pour Clients -->
                    @if(request()->routeIs('clients.*'))
                        @hasAnyPermission(['clients.view', 'clients.create'])
                            @hasPermission('clients.view')
                            <div class="sidebar-item {{ request()->routeIs('clients.index') || request()->routeIs('clients.show') || request()->routeIs('clients.edit') ? 'active' : '' }}">
                                <a href="{{ route('clients.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700">
                                    <i class="fas fa-list mr-3 w-5"></i>
                                    Liste
                                </a>
                            </div>
                            @endhasPermission
                            
                            @hasPermission('clients.create')
                            <div class="sidebar-item {{ request()->routeIs('clients.create') ? 'active' : '' }}">
                                <a href="{{ route('clients.create') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700">
                                    <i class="fas fa-user-plus mr-3 w-5"></i>
                                    Nouveau client
                                </a>
                            </div>
                            @endhasPermission
                        @endhasAnyPermission
                    @endif
                    
                    <!-- Sous-menus pour Produits -->
                    @if(request()->routeIs('products.*'))
                        @hasAnyPermission(['products.view', 'products.create'])
                            @hasPermission('products.view')
                            <div class="sidebar-item {{ request()->routeIs('products.index') || request()->routeIs('products.show') || request()->routeIs('products.edit') ? 'active' : '' }}">
                                <a href="{{ route('products.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700">
                                    <i class="fas fa-list mr-3 w-5"></i>
                                    Liste
                                </a>
                            </div>
                            @endhasPermission
                            
                            @hasPermission('products.create')
                            <div class="sidebar-item {{ request()->routeIs('products.create') ? 'active' : '' }}">
                                <a href="{{ route('products.create') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700">
                                    <i class="fas fa-plus-circle mr-3 w-5"></i>
                                    Nouveau produit
                                </a>
                            </div>
                            @endhasPermission
                        @endhasAnyPermission
                    @endif
                    
                    <!-- Sous-menus pour Matériaux -->
                    @if(request()->routeIs('materials.*'))
                        @hasAnyPermission(['materials.view', 'materials.create'])
                            @hasPermission('materials.view')
                            <div class="sidebar-item {{ request()->routeIs('materials.index') || request()->routeIs('materials.show') || request()->routeIs('materials.edit') ? 'active' : '' }}">
                                <a href="{{ route('materials.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700" {{ isset($onclick) ? 'onclick="toggleSidebarMobile()"' : '' }}>
                                    <i class="fas fa-list mr-3 w-5"></i>
                                    Liste
                                </a>
                            </div>
                            @endhasPermission
                            
                            @hasPermission('materials.create')
                            <div class="sidebar-item {{ request()->routeIs('materials.create') ? 'active' : '' }}">
                                <a href="{{ route('materials.create') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700" {{ isset($onclick) ? 'onclick="toggleSidebarMobile()"' : '' }}>
                                    <i class="fas fa-plus-circle mr-3 w-5"></i>
                                    Nouveau matériau
                                </a>
                            </div>
                            @endhasPermission
                        @endhasAnyPermission
                    @endif
                    
                    <!-- Sous-menus pour Catalogue -->
                    @if(request()->routeIs('modeles.*') || request()->routeIs('catalogue.*'))
                        <div class="sidebar-item {{ request()->routeIs('modeles.index') || request()->routeIs('catalogue.*') ? 'active' : '' }}">
                            <a href="{{ route('modeles.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700">
                                <i class="fas fa-th-large mr-3 w-5"></i>
                                Tous les modèles
                            </a>
                        </div>
                        @auth
                        @if(Auth::user()->hasPermission('modeles.create'))
                        <div class="sidebar-item {{ request()->routeIs('modeles.create') ? 'active' : '' }}">
                            <a href="{{ route('modeles.create') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700">
                                <i class="fas fa-plus-circle mr-3 w-5"></i>
                                Nouveau modèle
                            </a>
                        </div>
                        @endif
                        @endauth
                    @endif
                    
                    <!-- Sous-menus pour Dashboard -->
                    @if(request()->routeIs('dashboard*'))
                        <div class="sidebar-item active">
                            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700">
                                <i class="fas fa-chart-bar mr-3 w-5"></i>
                                Statistiques
                            </a>
                        </div>
                    @endif
                    
                    <!-- Sous-menus pour Utilisateurs (Admin) -->
                    @if(request()->routeIs('users.*') && Auth::user()->role === 'admin')
                        <div class="sidebar-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700">
                                <i class="fas fa-list mr-3 w-5"></i>
                                Liste
                            </a>
                        </div>
                        <div class="sidebar-item {{ request()->routeIs('users.create') ? 'active' : '' }}">
                            <a href="{{ route('users.create') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700">
                                <i class="fas fa-user-plus mr-3 w-5"></i>
                                Nouveau utilisateur
                            </a>
                        </div>
                    @endif
                    
                    <!-- Sous-menus pour Paramètres -->
                    @if(request()->routeIs('settings.*'))
                        <div class="sidebar-item active">
                            <a href="{{ route('settings.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700">
                                <i class="fas fa-cog mr-3 w-5"></i>
                                Configuration
                            </a>
                        </div>
                    @endif
                </nav>
            </div>
        </aside>
        
        <!-- Sidebar latérale Mobile optimisée -->
        <aside class="sidebar-mobile lg:hidden" id="sidebar-mobile">
            <div class="p-4 pb-6">
                <div class="flex items-center justify-between mb-6 pt-2">
                    <h3 class="text-base font-bold text-gray-800 flex items-center">
                        <i class="fas fa-list mr-2" style="color: {{ $settings->primary_color ?? '#3b82f6' }};"></i>
                        Navigation
                    </h3>
                    <button onclick="toggleSidebarMobile()" 
                            class="mobile-touch-target p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 active:bg-gray-200 focus:outline-none transition-colors"
                            aria-label="Fermer le menu">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                
                <nav class="space-y-1">
                    <!-- Sous-menus pour Devis -->
                    @if(request()->routeIs('quotes.*') || request()->routeIs('payments.*'))
                        @hasPermission('quotes.view')
                        <div class="sidebar-item {{ request()->routeIs('quotes.index') ? 'active' : '' }}">
                            <a href="{{ route('quotes.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700" onclick="toggleSidebarMobile()">
                                <i class="fas fa-list mr-3 w-5"></i>
                                Liste
                            </a>
                        </div>
                        @endhasPermission
                        
                        @hasPermission('quotes.create')
                        <div class="sidebar-item {{ request()->routeIs('quotes.create') ? 'active' : '' }}">
                            <a href="{{ route('quotes.create') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700" onclick="toggleSidebarMobile()">
                                <i class="fas fa-plus-circle mr-3 w-5"></i>
                                Nouveau devis
                            </a>
                        </div>
                        @endhasPermission
                        
                        @hasPermission('payments.view')
                        <div class="sidebar-item {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                            <a href="{{ route('payments.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700" onclick="toggleSidebarMobile()">
                                <i class="fas fa-money-bill-wave mr-3 w-5"></i>
                                Paiements
                            </a>
                        </div>
                        @endhasPermission
                        
                        @hasPermission('quotes.calculate-materials')
                        <div class="sidebar-item {{ request()->routeIs('quotes.calculate-materials') || request()->routeIs('quotes.print-materials') ? 'active' : '' }}">
                            <a href="{{ route('quotes.calculate-materials') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700" onclick="toggleSidebarMobile()">
                                <i class="fas fa-calculator mr-3 w-5"></i>
                                Calcul Matériaux
                            </a>
                        </div>
                        @endhasPermission
                    @endif
                    
                    <!-- Sous-menus pour Clients -->
                    @if(request()->routeIs('clients.*'))
                        @hasAnyPermission(['clients.view', 'clients.create'])
                            @hasPermission('clients.view')
                            <div class="sidebar-item {{ request()->routeIs('clients.index') || request()->routeIs('clients.show') || request()->routeIs('clients.edit') ? 'active' : '' }}">
                                <a href="{{ route('clients.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700" onclick="toggleSidebarMobile()">
                                    <i class="fas fa-list mr-3 w-5"></i>
                                    Liste
                                </a>
                            </div>
                            @endhasPermission
                            
                            @hasPermission('clients.create')
                            <div class="sidebar-item {{ request()->routeIs('clients.create') ? 'active' : '' }}">
                                <a href="{{ route('clients.create') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700" onclick="toggleSidebarMobile()">
                                    <i class="fas fa-user-plus mr-3 w-5"></i>
                                    Nouveau client
                                </a>
                            </div>
                            @endhasPermission
                        @endhasAnyPermission
                    @endif
                    
                    <!-- Sous-menus pour Produits -->
                    @if(request()->routeIs('products.*'))
                        @hasAnyPermission(['products.view', 'products.create'])
                            @hasPermission('products.view')
                            <div class="sidebar-item {{ request()->routeIs('products.index') || request()->routeIs('products.show') || request()->routeIs('products.edit') ? 'active' : '' }}">
                                <a href="{{ route('products.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700" onclick="toggleSidebarMobile()">
                                    <i class="fas fa-list mr-3 w-5"></i>
                                    Liste
                                </a>
                            </div>
                            @endhasPermission
                            
                            @hasPermission('products.create')
                            <div class="sidebar-item {{ request()->routeIs('products.create') ? 'active' : '' }}">
                                <a href="{{ route('products.create') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700" onclick="toggleSidebarMobile()">
                                    <i class="fas fa-plus-circle mr-3 w-5"></i>
                                    Nouveau produit
                                </a>
                            </div>
                            @endhasPermission
                        @endhasAnyPermission
                    @endif
                    
                    <!-- Sous-menus pour Matériaux -->
                    @if(request()->routeIs('materials.*'))
                        @hasAnyPermission(['materials.view', 'materials.create'])
                            @hasPermission('materials.view')
                            <div class="sidebar-item {{ request()->routeIs('materials.index') || request()->routeIs('materials.show') || request()->routeIs('materials.edit') ? 'active' : '' }}">
                                <a href="{{ route('materials.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700" onclick="toggleSidebarMobile()">
                                    <i class="fas fa-list mr-3 w-5"></i>
                                    Liste
                                </a>
                            </div>
                            @endhasPermission
                            
                            @hasPermission('materials.create')
                            <div class="sidebar-item {{ request()->routeIs('materials.create') ? 'active' : '' }}">
                                <a href="{{ route('materials.create') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700" onclick="toggleSidebarMobile()">
                                    <i class="fas fa-plus-circle mr-3 w-5"></i>
                                    Nouveau matériau
                                </a>
                            </div>
                            @endhasPermission
                        @endhasAnyPermission
                    @endif
                    
                    <!-- Sous-menus pour Catalogue -->
                    @if(request()->routeIs('modeles.*') || request()->routeIs('catalogue.*'))
                        <div class="sidebar-item {{ request()->routeIs('modeles.index') || request()->routeIs('catalogue.*') ? 'active' : '' }}">
                            <a href="{{ route('modeles.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700" onclick="toggleSidebarMobile()">
                                <i class="fas fa-th-large mr-3 w-5"></i>
                                Tous les modèles
                            </a>
                        </div>
                        @auth
                        @if(Auth::user()->hasPermission('modeles.create'))
                        <div class="sidebar-item {{ request()->routeIs('modeles.create') ? 'active' : '' }}">
                            <a href="{{ route('modeles.create') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700" onclick="toggleSidebarMobile()">
                                <i class="fas fa-plus-circle mr-3 w-5"></i>
                                Nouveau modèle
                            </a>
                        </div>
                        @endif
                        @endauth
                    @endif
                    
                    <!-- Sous-menus pour Dashboard -->
                    @if(request()->routeIs('dashboard*'))
                        <div class="sidebar-item active">
                            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700" onclick="toggleSidebarMobile()">
                                <i class="fas fa-chart-bar mr-3 w-5"></i>
                                Statistiques
                            </a>
                        </div>
                    @endif
                </nav>
            </div>
        </aside>

        <!-- Contenu principal -->
        <main class="flex-1 lg:ml-[260px] py-6 sm:py-8 bg-gradient-to-br from-gray-50 via-white to-gray-50 min-h-screen w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 notification-success bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md" role="alert">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 notification-error bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md" role="alert">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-red-800 mb-2">Erreurs de validation</h3>
                            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="animate-fade-in">
            @yield('content')
            </div>
        </div>
    </main>
    </div>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const sidebar = document.getElementById('sidebar-mobile');
            const overlay = document.getElementById('sidebar-overlay');
            
            // Fermer la sidebar si elle est ouverte
            if (sidebar && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
            
            // Toggle le menu mobile
            if (menu) {
                menu.classList.toggle('active');
            }
        }
        
        function closeMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            if (menu) {
                menu.classList.remove('active');
            }
        }
        
        function toggleSidebarMobile() {
            const sidebar = document.getElementById('sidebar-mobile');
            const overlay = document.getElementById('sidebar-overlay');
            const menu = document.getElementById('mobile-menu');
            
            // Fermer le menu mobile si il est ouvert
            if (menu && menu.classList.contains('active')) {
                menu.classList.remove('active');
            }
            
            // Toggle la sidebar
            if (sidebar && overlay) {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
                
                // Empêcher le scroll du body quand la sidebar est ouverte
                if (sidebar.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            }
        }
        
        // Fermer les menus quand on clique sur l'overlay
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('sidebar-overlay');
            if (overlay) {
                overlay.addEventListener('click', function() {
                    toggleSidebarMobile();
                });
            }
            
            // Fermer la sidebar mobile quand on clique sur un lien
            const sidebarLinks = document.querySelectorAll('#sidebar-mobile a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    setTimeout(() => {
                        toggleSidebarMobile();
                    }, 150);
                });
            });
            
            // Fermer les menus au scroll (optionnel, pour améliorer l'UX)
            let scrollTimeout;
            window.addEventListener('scroll', function() {
                if (window.innerWidth <= 640) {
                    clearTimeout(scrollTimeout);
                    scrollTimeout = setTimeout(function() {
                        const menu = document.getElementById('mobile-menu');
                        if (menu && menu.classList.contains('active')) {
                            menu.classList.remove('active');
                        }
                    }, 100);
                }
            }, { passive: true });
            
            // Fermer les menus avec la touche Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeMobileMenu();
                    const sidebar = document.getElementById('sidebar-mobile');
                    if (sidebar && sidebar.classList.contains('active')) {
                        toggleSidebarMobile();
                    }
                }
            });
        });
    </script>
</body>
</html>
