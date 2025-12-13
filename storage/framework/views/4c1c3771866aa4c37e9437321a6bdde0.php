<?php $__env->startSection('title', 'Catalogue de Modèles - A2 VitraDevis'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<div class="relative overflow-hidden bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-8 sm:py-12 lg:py-16">
    <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 rounded-xl sm:rounded-2xl mb-4 sm:mb-6 shadow-lg transform hover:scale-105 transition-transform duration-300 touch-manipulation"
                 style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                <i class="fas fa-images text-white text-2xl sm:text-3xl"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl xl:text-5xl font-extrabold text-gray-900 mb-2 sm:mb-3 lg:mb-4 px-2">
                Catalogue de <span style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">Modèles</span>
            </h1>
            <p class="text-sm sm:text-base lg:text-xl text-gray-600 max-w-2xl mx-auto mb-4 sm:mb-6 lg:mb-8 px-2">
                Découvrez notre collection complète de modèles aluminium : fenêtres, portes, garde-corps et bien plus encore
            </p>
            <?php if(auth()->guard()->check()): ?>
            <?php if(Auth::user()->hasPermission('modeles.create')): ?>
            <a href="<?php echo e(route('modeles.create')); ?>" 
               class="inline-flex items-center justify-center px-4 py-2.5 sm:px-6 sm:py-3 rounded-lg text-sm sm:text-base font-medium text-white shadow-sm hover:shadow-md transition-all duration-300 touch-manipulation"
               style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                <i class="fas fa-plus mr-2"></i>
                <span>Ajouter un Modèle</span>
            </a>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Filtres et Recherche -->
<div class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-10">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-3 sm:py-6">
        <form method="GET" action="<?php echo e(route('modeles.index')); ?>" class="space-y-3 sm:space-y-4" id="filterForm">
            <!-- Version Desktop -->
            <div class="hidden sm:block">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Recherche -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-search mr-2" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>Rechercher un modèle
                        </label>
                        <div class="relative">
                            <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" 
                                placeholder="Tapez le nom du modèle..."
                                class="block w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 pl-12 text-sm shadow-sm focus:ring-2 focus:border-transparent transition-all focus:shadow-md"
                                style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>; width: 18px; text-align: center;"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Filtre par catégorie -->
                    <div>
                        <label for="categorie" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-filter mr-2" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>Catégorie
                        </label>
                        <select name="categorie" id="categorie" 
                            class="block w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm shadow-sm focus:ring-2 focus:border-transparent transition-all focus:shadow-md"
                            style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"%23374151\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"6 9 12 15 18 9\"></polyline></svg>'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1em 1em; padding-right: 2.5rem;">
                            <option value="">Toutes les catégories</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(request('categorie') == $key ? 'selected' : ''); ?>>
                                <?php echo e($label); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Boutons -->
                    <div class="flex items-end gap-2">
                        <button type="submit" 
                            class="flex-1 inline-flex items-center justify-center px-6 py-3 rounded-lg text-sm font-medium text-white shadow-sm hover:shadow-md transition-all duration-200"
                            style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                            <i class="fas fa-filter mr-2"></i>Filtrer
                        </button>
                        <?php if(request()->hasAny(['search', 'categorie'])): ?>
                        <a href="<?php echo e(route('modeles.index')); ?>" 
                           class="inline-flex items-center justify-center px-4 py-3 rounded-xl border-2 border-gray-300 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md"
                           title="Réinitialiser">
                            <i class="fas fa-times"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Version Mobile - Recherche toujours visible -->
            <div class="sm:hidden space-y-2">
                <!-- Barre de recherche principale - toujours visible -->
                <div class="relative">
                    <input type="text" name="search" id="search_mobile" value="<?php echo e(request('search')); ?>" 
                        placeholder="Rechercher un modèle..."
                        class="block w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3.5 pl-14 pr-12 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all touch-manipulation"
                        style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>; -webkit-appearance: none;"
                        autocomplete="off">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                        <i class="fas fa-search text-lg" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>; width: 20px; text-align: center;"></i>
                    </div>
                    <?php if(request('search')): ?>
                    <button type="button" onclick="document.getElementById('search_mobile').value=''; document.getElementById('search').value='';" 
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-gray-700 z-10 touch-manipulation"
                            title="Effacer">
                        <i class="fas fa-times text-base"></i>
                    </button>
                    <?php endif; ?>
                </div>
                
                <!-- Filtres supplémentaires - pliable -->
                <div class="space-y-2" id="mobileFilters">
                    <div class="flex items-center gap-2">
                        <select name="categorie" id="categorie_mobile" 
                            class="flex-1 rounded-xl border-2 border-gray-300 bg-white px-4 py-3.5 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all touch-manipulation"
                            style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"%23374151\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"6 9 12 15 18 9\"></polyline></svg>'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1em 1em; padding-right: 2.5rem;">
                            <option value="">Toutes les catégories</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(request('categorie') == $key ? 'selected' : ''); ?>>
                                <?php echo e($label); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php if(request()->hasAny(['search', 'categorie'])): ?>
                        <a href="<?php echo e(route('modeles.index')); ?>" 
                           class="inline-flex items-center justify-center px-4 py-3.5 rounded-xl border-2 border-gray-300 text-base font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 shadow-sm touch-manipulation"
                           title="Réinitialiser">
                            <i class="fas fa-times"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                    <button type="submit" 
                        class="w-full inline-flex items-center justify-center px-6 py-3.5 rounded-lg text-base font-medium text-white shadow-sm hover:shadow-md transition-all duration-200 touch-manipulation"
                        style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                        <i class="fas fa-filter sm:mr-2"></i>
                        <span class="hidden sm:inline">Appliquer les filtres</span>
                        <span class="sm:hidden">Filtrer</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Grille de modèles -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <?php if($modeles->count() > 0): ?>
    <!-- Compteur de résultats -->
    <div class="mb-6 sm:mb-8 flex items-center justify-between">
        <div>
            <p class="text-sm sm:text-base text-gray-600">
                <span class="font-semibold text-gray-900"><?php echo e($modeles->total()); ?></span> 
                modèle<?php echo e($modeles->total() > 1 ? 's' : ''); ?> trouvé<?php echo e($modeles->total() > 1 ? 's' : ''); ?>

            </p>
        </div>
    </div>

    <!-- Grille -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
        <?php $__currentLoopData = $modeles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modele): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="group bg-white rounded-xl sm:rounded-2xl shadow-md sm:shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 transform hover:-translate-y-1 sm:hover:-translate-y-2">
            <!-- Image -->
            <a href="<?php echo e(route('modeles.show', $modele)); ?><?php echo e(request()->has('quote_id') ? '?quote_id=' . request('quote_id') : ''); ?>" 
               class="relative block h-48 sm:h-56 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden group/image">
                <?php if($modele->image): ?>
                <img src="<?php echo e($modele->thumbnail_url ?? $modele->image_url); ?>" 
                     srcset="<?php echo e($modele->thumbnail_url ?? $modele->image_url); ?> 300w,
                             <?php echo e($modele->medium_image_url ?? $modele->image_url); ?> 800w,
                             <?php echo e($modele->large_image_url ?? $modele->image_url); ?> 1200w"
                     sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, (max-width: 1280px) 33vw, 25vw"
                     loading="lazy" 
                     alt="<?php echo e($modele->nom); ?>"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 cursor-pointer touch-manipulation"
                     oncontextmenu="event.preventDefault(); openImageZoom(this, '<?php echo e($modele->nom); ?>'); return false;"
                     data-zoom-src="<?php echo e($modele->large_image_url ?? $modele->image_url); ?>">
                <?php else: ?>
                <div class="w-full h-full flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-4xl sm:text-5xl"></i>
                </div>
                <?php endif; ?>
                <!-- Overlay gradient -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover/image:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                
                <!-- Badge catégorie -->
                <div class="absolute top-2 sm:top-4 left-2 sm:left-4 z-20 pointer-events-none">
                    <span class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg text-xs font-bold text-white shadow-lg backdrop-blur-sm bg-white/20 border border-white/30">
                        <i class="fas fa-tag mr-1 sm:mr-1.5 text-xs"></i>
                        <span class="hidden xs:inline"><?php echo e($categories[$modele->categorie] ?? $modele->categorie); ?></span>
                        <span class="xs:hidden"><?php echo e(substr($categories[$modele->categorie] ?? $modele->categorie, 0, 3)); ?></span>
                    </span>
                </div>
                
                <?php if($modele->statut === 'inactif'): ?>
                <div class="absolute top-2 sm:top-4 right-2 sm:right-4 bg-red-500 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg text-xs font-bold shadow-lg z-20 pointer-events-none">
                    Inactif
                </div>
                <?php endif; ?>
                
                <!-- Indicateur visuel au survol -->
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover/image:opacity-100 transition-opacity duration-300 bg-black/30 z-10 pointer-events-none">
                    <div class="bg-white/95 rounded-full px-4 py-2.5 shadow-xl">
                        <span class="text-sm font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-eye mr-2"></i>Voir les détails
                        </span>
                    </div>
                </div>
            </a>
            
            <!-- Contenu -->
            <div class="p-4 sm:p-5">
                <a href="<?php echo e(route('modeles.show', $modele)); ?><?php echo e(request()->has('quote_id') ? '?quote_id=' . request('quote_id') : ''); ?>">
                    <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900 mb-3 sm:mb-4 line-clamp-2 group-hover:text-blue-600 transition-colors cursor-pointer hover:underline">
                        <?php echo e($modele->nom); ?>

                    </h3>
                </a>
                
                <div class="flex flex-col sm:flex-row gap-2">
                    <a href="<?php echo e(route('modeles.show', $modele)); ?><?php echo e(request()->has('quote_id') ? '?quote_id=' . request('quote_id') : ''); ?>"
                       class="flex-1 inline-flex items-center justify-center px-4 py-2.5 sm:px-4 sm:py-3 rounded-lg text-sm font-medium text-white shadow-sm hover:shadow-md transition-all duration-200 touch-manipulation"
                       style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                        <i class="fas fa-eye mr-2"></i>
                        <span>Voir les détails</span>
                    </a>
                    <?php if(request()->has('quote_id')): ?>
                    <a href="<?php echo e(route('modeles.add-to-quote', $modele)); ?>?quote_id=<?php echo e(request('quote_id')); ?>" 
                       class="inline-flex items-center justify-center px-4 py-2.5 sm:px-4 sm:py-3 rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 shadow-sm hover:shadow-md transition-all duration-200 touch-manipulation">
                        <i class="fas fa-link mr-2 sm:mr-0"></i>
                        <span class="sm:hidden">Associer</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Pagination -->
    <div class="mt-8 sm:mt-12 flex justify-center">
        <div class="bg-white rounded-xl shadow-md p-2 sm:p-4 overflow-x-auto w-full sm:w-auto">
            <div class="flex justify-center min-w-max">
                <?php echo e($modeles->links()); ?>

            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- État vide -->
    <div class="text-center py-20">
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full mb-6 shadow-lg"
             style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>20 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>20 100%);">
            <i class="fas fa-images text-5xl" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-3">Aucun modèle trouvé</h3>
        <p class="text-gray-600 max-w-md mx-auto mb-6">
            <?php if(request()->hasAny(['search', 'categorie'])): ?>
            Aucun modèle ne correspond à vos critères de recherche. Essayez de modifier vos filtres.
            <?php else: ?>
            Le catalogue est vide pour le moment. Revenez bientôt pour découvrir nos modèles.
            <?php endif; ?>
        </p>
        <?php if(request()->hasAny(['search', 'categorie'])): ?>
        <a href="<?php echo e(route('modeles.index')); ?>" 
           class="inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold text-white shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200"
           style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
            <i class="fas fa-redo mr-2"></i>Réinitialiser les filtres
        </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<style>
.bg-grid-pattern {
    background-image: 
        linear-gradient(to right, rgba(0,0,0,0.05) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(0,0,0,0.05) 1px, transparent 1px);
    background-size: 20px 20px;
}

/* Améliorations pour la zone de recherche mobile */
@media (max-width: 640px) {
    #filterForm input[type="text"],
    #filterForm select {
        font-size: 16px !important; /* Évite le zoom automatique sur iOS */
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
    
    #filterForm input[type="text"]:focus {
        outline: none;
    }
    
    /* Amélioration de l'espacement pour les boutons tactiles */
    #filterForm button,
    #filterForm a[class*="inline-flex"] {
        min-height: 48px;
        touch-action: manipulation;
    }
}
</style>

<script>
// Sauvegarder les filtres dans localStorage pour mode hors-ligne
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    
    // Synchroniser les champs mobile et desktop
    const searchDesktop = document.getElementById('search');
    const searchMobile = document.getElementById('search_mobile');
    const categorieDesktop = document.getElementById('categorie');
    const categorieMobile = document.getElementById('categorie_mobile');
    
    if (searchDesktop && searchMobile) {
        searchMobile.addEventListener('input', function() {
            searchDesktop.value = this.value;
        });
        searchDesktop.addEventListener('input', function() {
            searchMobile.value = this.value;
        });
    }
    
    if (categorieDesktop && categorieMobile) {
        categorieMobile.addEventListener('change', function() {
            categorieDesktop.value = this.value;
        });
        categorieDesktop.addEventListener('change', function() {
            categorieMobile.value = this.value;
        });
    }
    
    // Recherche en temps réel sur mobile (optionnel - soumission automatique après 500ms)
    let searchTimeout;
    if (searchMobile) {
        searchMobile.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            // Optionnel : soumission automatique après 1 seconde d'inactivité
            // searchTimeout = setTimeout(() => {
            //     filterForm.submit();
            // }, 1000);
        });
    }
    
    if (filterForm) {
        // Charger les filtres sauvegardés
        const savedFilters = localStorage.getItem('modele_filters');
        if (savedFilters && !window.location.search) {
            try {
                const filters = JSON.parse(savedFilters);
                const searchField = document.getElementById('search') || document.getElementById('search_mobile');
                const categorieField = document.getElementById('categorie') || document.getElementById('categorie_mobile');
                if (filters.search && searchField) searchField.value = filters.search;
                if (filters.categorie && categorieField) categorieField.value = filters.categorie;
            } catch (e) {
                console.error('Erreur lors du chargement des filtres:', e);
            }
        }

        // Sauvegarder les filtres lors de la soumission
        filterForm.addEventListener('submit', function() {
            const searchField = document.getElementById('search') || document.getElementById('search_mobile');
            const categorieField = document.getElementById('categorie') || document.getElementById('categorie_mobile');
            const filters = {
                search: searchField ? searchField.value : '',
                categorie: categorieField ? categorieField.value : '',
            };
            localStorage.setItem('modele_filters', JSON.stringify(filters));
        });
    }
    
    // Animation d'apparition des cartes
    const cards = document.querySelectorAll('.group');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 50);
    });
});

// Fonctions pour le zoom d'image
let currentZoom = 1;
let imageElement = null;

function openImageZoom(imgElement, modelName = '') {
    const modal = document.getElementById('imageZoomModal');
    const zoomedImg = document.getElementById('zoomedImage');
    const titleElement = document.getElementById('zoomModelTitle');
    const zoomSrc = imgElement.getAttribute('data-zoom-src') || imgElement.src;
    
    zoomedImg.src = zoomSrc;
    if (titleElement && modelName) {
        titleElement.textContent = modelName;
    }
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    currentZoom = 1;
    zoomedImg.style.transform = 'scale(1)';
    imageElement = zoomedImg;
    
    // Empêcher le scroll du body
    document.body.style.overflow = 'hidden';
    
    // Gestion du drag
    setupImageDrag(zoomedImg);
}

function closeImageZoom() {
    const modal = document.getElementById('imageZoomModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    currentZoom = 1;
    document.body.style.overflow = '';
}

function zoomIn() {
    if (!imageElement) {
        imageElement = document.getElementById('zoomedImage');
    }
    if (currentZoom < 5) {
        currentZoom += 0.5;
        updateZoom();
    }
}

function zoomOut() {
    if (currentZoom > 0.5) {
        currentZoom -= 0.5;
        updateZoom();
    }
}

function resetZoom() {
    currentZoom = 1;
    updateZoom();
    if (imageElement) {
        imageElement.style.transform = 'translate(0, 0) scale(1)';
    }
}

function updateZoom() {
    if (!imageElement) {
        imageElement = document.getElementById('zoomedImage');
    }
    
    if (imageElement) {
        // Préserver le translate existant si présent
        const currentTransform = imageElement.style.transform.match(/translate\(([^,]+),\s*([^)]+)\)/);
        if (currentTransform) {
            const currentX = currentTransform[1];
            const currentY = currentTransform[2];
            imageElement.style.transform = `translate(${currentX}, ${currentY}) scale(${currentZoom})`;
        } else {
            imageElement.style.transform = `scale(${currentZoom})`;
        }
        // Mettre à jour le curseur
        imageElement.style.cursor = currentZoom > 1 ? 'grab' : 'default';
    }
}

function setupImageDrag(img) {
    let isDown = false;
    let startX, startY;
    let touchStartDistance = 0;
    let lastTouchX = 0;
    let lastTouchY = 0;
    
    // Support souris
    img.addEventListener('mousedown', (e) => {
        if (currentZoom > 1) {
            isDown = true;
            img.style.cursor = 'grabbing';
            startX = e.pageX - img.offsetLeft;
            startY = e.pageY - img.offsetTop;
        }
    });
    
    img.addEventListener('mouseleave', () => {
        isDown = false;
        img.style.cursor = currentZoom > 1 ? 'grab' : 'default';
    });
    
    img.addEventListener('mouseup', () => {
        isDown = false;
        img.style.cursor = currentZoom > 1 ? 'grab' : 'default';
    });
    
    img.addEventListener('mousemove', (e) => {
        if (!isDown || currentZoom <= 1) return;
        e.preventDefault();
        const x = e.pageX - img.offsetLeft;
        const y = e.pageY - img.offsetTop;
        const walkX = (x - startX) * 2;
        const walkY = (y - startY) * 2;
        
        const currentTransform = img.style.transform.match(/translate\(([^,]+),\s*([^)]+)\)/);
        const currentX = currentTransform ? parseFloat(currentTransform[1]) : 0;
        const currentY = currentTransform ? parseFloat(currentTransform[2]) : 0;
        
        img.style.transform = `translate(${currentX + walkX}px, ${currentY + walkY}px) scale(${currentZoom})`;
        startX = x;
        startY = y;
    });
    
    // Zoom avec la molette de la souris
    img.addEventListener('wheel', (e) => {
        e.preventDefault();
        if (e.deltaY < 0) {
            zoomIn();
        } else {
            zoomOut();
        }
    });
    
    // Support tactile (mobile)
    img.addEventListener('touchstart', (e) => {
        if (e.touches.length === 1) {
            // Un seul doigt : déplacement
            if (currentZoom > 1) {
                isDown = true;
                const touch = e.touches[0];
                lastTouchX = touch.clientX;
                lastTouchY = touch.clientY;
            }
        } else if (e.touches.length === 2) {
            // Deux doigts : pincement pour zoom
            e.preventDefault();
            const touch1 = e.touches[0];
            const touch2 = e.touches[1];
            touchStartDistance = Math.hypot(
                touch2.clientX - touch1.clientX,
                touch2.clientY - touch1.clientY
            );
        }
    }, { passive: false });
    
    img.addEventListener('touchmove', (e) => {
        if (e.touches.length === 1 && isDown && currentZoom > 1) {
            // Un seul doigt : déplacement
            e.preventDefault();
            const touch = e.touches[0];
            const deltaX = touch.clientX - lastTouchX;
            const deltaY = touch.clientY - lastTouchY;
            
            const currentTransform = img.style.transform.match(/translate\(([^,]+),\s*([^)]+)\)/);
            const currentX = currentTransform ? parseFloat(currentTransform[1]) : 0;
            const currentY = currentTransform ? parseFloat(currentTransform[2]) : 0;
            
            img.style.transform = `translate(${currentX + deltaX}px, ${currentY + deltaY}px) scale(${currentZoom})`;
            lastTouchX = touch.clientX;
            lastTouchY = touch.clientY;
        } else if (e.touches.length === 2) {
            // Deux doigts : zoom par pincement
            e.preventDefault();
            const touch1 = e.touches[0];
            const touch2 = e.touches[1];
            const currentDistance = Math.hypot(
                touch2.clientX - touch1.clientX,
                touch2.clientY - touch1.clientY
            );
            
            if (touchStartDistance > 0) {
                const scale = currentDistance / touchStartDistance;
                const newZoom = Math.max(0.5, Math.min(5, currentZoom * scale));
                if (Math.abs(newZoom - currentZoom) > 0.1) {
                    currentZoom = newZoom;
                    updateZoom();
                    touchStartDistance = currentDistance;
                }
            }
        }
    }, { passive: false });
    
    img.addEventListener('touchend', () => {
        isDown = false;
        touchStartDistance = 0;
    });
}

// Fermer avec la touche Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageZoom();
    }
});

// Ajouter les event listeners aux boutons de zoom (délégation d'événements)
document.addEventListener('click', function(e) {
    if (e.target.closest('#zoomInBtn') || e.target.closest('#zoomInBtnMobile')) {
        e.preventDefault();
        e.stopPropagation();
        zoomIn();
    } else if (e.target.closest('#zoomOutBtn') || e.target.closest('#zoomOutBtnMobile')) {
        e.preventDefault();
        e.stopPropagation();
        zoomOut();
    } else if (e.target.closest('#resetZoomBtn') || e.target.closest('#resetZoomBtnMobile')) {
        e.preventDefault();
        e.stopPropagation();
        resetZoom();
    }
});
</script>

<!-- Lightbox Modal pour zoom image (catalogue) -->
<div id="imageZoomModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/90 backdrop-blur-sm" onclick="closeImageZoom()">
    <div class="relative w-full h-full flex items-center justify-center p-4 sm:p-8" onclick="event.stopPropagation()">
        <!-- Bouton fermer -->
        <button onclick="closeImageZoom()" 
                class="absolute top-2 right-2 sm:top-4 sm:right-4 z-50 text-white hover:text-gray-300 transition-colors bg-black/70 rounded-full p-2.5 sm:p-3 hover:bg-black/80 touch-manipulation"
                aria-label="Fermer">
            <i class="fas fa-times text-lg sm:text-xl"></i>
        </button>
        <!-- Titre du modèle -->
        <div id="zoomModelTitle" class="absolute top-2 sm:top-4 left-1/2 transform -translate-x-1/2 z-50 text-white text-sm sm:text-lg font-semibold bg-black/70 rounded-lg px-3 sm:px-4 py-1.5 sm:py-2 backdrop-blur-sm max-w-[90%] text-center truncate"></div>
        
        <!-- Contrôles de zoom - Desktop (vertical) -->
        <div class="hidden sm:flex flex-col absolute top-20 left-4 z-50 gap-2">
            <button id="zoomInBtn" 
                    class="text-white hover:text-gray-300 transition-colors bg-black/70 rounded-full p-3 hover:bg-black/80 touch-manipulation"
                    title="Zoomer"
                    aria-label="Zoomer">
                <i class="fas fa-search-plus text-xl"></i>
            </button>
            <button id="zoomOutBtn" 
                    class="text-white hover:text-gray-300 transition-colors bg-black/70 rounded-full p-3 hover:bg-black/80 touch-manipulation"
                    title="Dézoomer"
                    aria-label="Dézoomer">
                <i class="fas fa-search-minus text-xl"></i>
            </button>
            <button id="resetZoomBtn" 
                    class="text-white hover:text-gray-300 transition-colors bg-black/70 rounded-full p-3 hover:bg-black/80 touch-manipulation"
                    title="Réinitialiser"
                    aria-label="Réinitialiser le zoom">
                <i class="fas fa-expand text-xl"></i>
            </button>
        </div>
        
        <!-- Contrôles de zoom - Mobile (horizontal en bas) -->
        <div class="sm:hidden flex absolute bottom-4 left-1/2 transform -translate-x-1/2 z-50 gap-2 bg-black/70 rounded-full px-2 py-2 backdrop-blur-sm">
            <button id="zoomInBtnMobile" 
                    class="text-white transition-colors rounded-full p-2.5 hover:bg-black/80 touch-manipulation"
                    title="Zoomer"
                    aria-label="Zoomer">
                <i class="fas fa-search-plus text-lg"></i>
            </button>
            <button id="zoomOutBtnMobile" 
                    class="text-white transition-colors rounded-full p-2.5 hover:bg-black/80 touch-manipulation"
                    title="Dézoomer"
                    aria-label="Dézoomer">
                <i class="fas fa-search-minus text-lg"></i>
            </button>
            <button id="resetZoomBtnMobile" 
                    class="text-white transition-colors rounded-full p-2.5 hover:bg-black/80 touch-manipulation"
                    title="Réinitialiser"
                    aria-label="Réinitialiser le zoom">
                <i class="fas fa-expand text-lg"></i>
            </button>
        </div>
        
        <!-- Image zoomée -->
        <img id="zoomedImage" 
             src="" 
             alt="Image zoomée"
             class="max-w-full max-h-[calc(100vh-8rem)] sm:max-h-full object-contain transition-transform duration-300 touch-manipulation"
             style="transform: scale(1);">
    </div>
</div>

<style>
#imageZoomModal {
    animation: fadeIn 0.3s ease-out;
}

#zoomedImage {
    cursor: move;
    user-select: none;
    -webkit-user-select: none;
    -webkit-touch-callout: none;
    -webkit-tap-highlight-color: transparent;
}

.touch-manipulation {
    touch-action: manipulation;
    -webkit-tap-highlight-color: transparent;
}

/* Amélioration des boutons sur mobile */
@media (max-width: 640px) {
    #imageZoomModal button {
        min-width: 44px;
        min-height: 44px;
    }
    
    .group {
        -webkit-tap-highlight-color: transparent;
    }
}

/* Amélioration de la grille sur très petits écrans */
@media (max-width: 375px) {
    .grid {
        gap: 0.75rem;
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gdevis\resources\views/modeles/index.blade.php ENDPATH**/ ?>