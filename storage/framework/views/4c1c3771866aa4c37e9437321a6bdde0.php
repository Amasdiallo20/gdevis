<?php $__env->startSection('title', 'Catalogue de Modèles - A2 VitraDevis'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<div class="relative overflow-hidden bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-12 sm:py-16">
    <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl mb-6 shadow-lg transform hover:scale-105 transition-transform duration-300"
                 style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                <i class="fas fa-images text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 mb-4">
                Catalogue de <span style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">Modèles</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-8">
                Découvrez notre collection complète de modèles aluminium : fenêtres, portes, garde-corps et bien plus encore
            </p>
            <?php if(auth()->guard()->check()): ?>
            <?php if(Auth::user()->hasPermission('modeles.create')): ?>
            <a href="<?php echo e(route('modeles.create')); ?>" 
               class="inline-flex items-center justify-center px-6 py-3 rounded-xl text-base font-semibold text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300"
               style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                <i class="fas fa-plus mr-2"></i>Ajouter un Modèle
            </a>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Filtres et Recherche -->
<div class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <form method="GET" action="<?php echo e(route('modeles.index')); ?>" class="space-y-4" id="filterForm">
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
                            <i class="fas fa-search text-gray-400"></i>
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
                        style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
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
                        class="flex-1 inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold text-white shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200"
                        style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                        <i class="fas fa-filter mr-2"></i>Filtrer
                    </button>
                    <?php if(request()->hasAny(['search', 'categorie'])): ?>
                    <a href="<?php echo e(route('modeles.index')); ?>" 
                       class="inline-flex items-center justify-center px-4 py-3 rounded-xl border-2 border-gray-300 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-times"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Grille de modèles -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <?php if($modeles->count() > 0): ?>
    <!-- Compteur de résultats -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-600">
                <span class="font-semibold text-gray-900"><?php echo e($modeles->total()); ?></span> 
                modèle<?php echo e($modeles->total() > 1 ? 's' : ''); ?> trouvé<?php echo e($modeles->total() > 1 ? 's' : ''); ?>

            </p>
        </div>
    </div>

    <!-- Grille -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8">
        <?php $__currentLoopData = $modeles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modele): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 cursor-pointer transform hover:-translate-y-2"
             onclick="window.location.href='<?php echo e(route('modeles.show', $modele)); ?><?php echo e(request()->has('quote_id') ? '?quote_id=' . request('quote_id') : ''); ?>'">
            <!-- Image -->
            <div class="relative h-56 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                    <?php if($modele->image): ?>
                    <img src="<?php echo e($modele->thumbnail_url ?? $modele->image_url); ?>" 
                         srcset="<?php echo e($modele->thumbnail_url ?? $modele->image_url); ?> 300w,
                                 <?php echo e($modele->medium_image_url ?? $modele->image_url); ?> 800w,
                                 <?php echo e($modele->large_image_url ?? $modele->image_url); ?> 1200w"
                         sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, (max-width: 1280px) 33vw, 25vw"
                         loading="lazy" 
                         alt="<?php echo e($modele->nom); ?>"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 cursor-zoom-in"
                         onclick="event.stopPropagation(); openImageZoom(this, '<?php echo e($modele->nom); ?>')"
                         data-zoom-src="<?php echo e($modele->large_image_url ?? $modele->image_url); ?>">
                <?php else: ?>
                <div class="w-full h-full flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-5xl"></i>
                </div>
                <?php endif; ?>
                <!-- Overlay gradient -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                
                <!-- Badge catégorie -->
                <div class="absolute top-4 left-4">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold text-white shadow-lg backdrop-blur-sm bg-white/20 border border-white/30">
                        <i class="fas fa-tag mr-1.5"></i>
                        <?php echo e($categories[$modele->categorie] ?? $modele->categorie); ?>

                    </span>
                </div>
                
                <?php if($modele->statut === 'inactif'): ?>
                <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-lg">
                    Inactif
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Contenu -->
            <div class="p-5">
                <h3 class="text-xl font-bold text-gray-900 mb-4 line-clamp-2 group-hover:text-blue-600 transition-colors">
                    <?php echo e($modele->nom); ?>

                </h3>
                
                <div class="flex gap-2">
                    <button class="flex-1 inline-flex items-center justify-center px-4 py-3 rounded-xl text-sm font-semibold text-white shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200"
                            style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);"
                            onclick="event.stopPropagation(); window.location.href='<?php echo e(route('modeles.show', $modele)); ?><?php echo e(request()->has('quote_id') ? '?quote_id=' . request('quote_id') : ''); ?>'">
                        <i class="fas fa-eye mr-2"></i>Voir les détails
                    </button>
                    <?php if(request()->has('quote_id')): ?>
                    <a href="<?php echo e(route('modeles.add-to-quote', $modele)); ?>?quote_id=<?php echo e(request('quote_id')); ?>" 
                       class="inline-flex items-center justify-center px-4 py-3 rounded-xl text-sm font-semibold text-white bg-green-600 hover:bg-green-700 shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200"
                       onclick="event.stopPropagation()">
                        <i class="fas fa-link"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Pagination -->
    <div class="mt-12 flex justify-center">
        <div class="bg-white rounded-xl shadow-md p-4">
            <?php echo e($modeles->links()); ?>

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
</style>

<script>
// Sauvegarder les filtres dans localStorage pour mode hors-ligne
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        // Charger les filtres sauvegardés
        const savedFilters = localStorage.getItem('modele_filters');
        if (savedFilters && !window.location.search) {
            try {
                const filters = JSON.parse(savedFilters);
                if (filters.search) document.getElementById('search').value = filters.search;
                if (filters.categorie) document.getElementById('categorie').value = filters.categorie;
            } catch (e) {
                console.error('Erreur lors du chargement des filtres:', e);
            }
        }

        // Sauvegarder les filtres lors de la soumission
        filterForm.addEventListener('submit', function() {
            const filters = {
                search: document.getElementById('search').value,
                categorie: document.getElementById('categorie').value,
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
}

// Fermer avec la touche Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageZoom();
    }
});

// Ajouter les event listeners aux boutons de zoom (délégation d'événements)
document.addEventListener('click', function(e) {
    if (e.target.closest('#zoomInBtn')) {
        e.preventDefault();
        e.stopPropagation();
        zoomIn();
    } else if (e.target.closest('#zoomOutBtn')) {
        e.preventDefault();
        e.stopPropagation();
        zoomOut();
    } else if (e.target.closest('#resetZoomBtn')) {
        e.preventDefault();
        e.stopPropagation();
        resetZoom();
    }
});
</script>

<!-- Lightbox Modal pour zoom image (catalogue) -->
<div id="imageZoomModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/90 backdrop-blur-sm" onclick="closeImageZoom()">
    <div class="relative w-full h-full flex items-center justify-center p-4" onclick="event.stopPropagation()">
        <!-- Bouton fermer -->
        <button onclick="closeImageZoom()" 
                class="absolute top-4 right-4 z-50 text-white hover:text-gray-300 transition-colors bg-black/50 rounded-full p-3 hover:bg-black/70">
            <i class="fas fa-times text-xl"></i>
        </button>
        <!-- Titre du modèle -->
        <div id="zoomModelTitle" class="absolute top-4 left-1/2 transform -translate-x-1/2 z-50 text-white text-lg font-semibold bg-black/50 rounded-lg px-4 py-2 backdrop-blur-sm"></div>
        <!-- Bouton zoom in -->
        <button id="zoomInBtn" 
                class="absolute top-20 left-4 z-50 text-white hover:text-gray-300 transition-colors bg-black/50 rounded-full p-3 hover:bg-black/70"
                title="Zoomer">
            <i class="fas fa-search-plus text-xl"></i>
        </button>
        <!-- Bouton zoom out -->
        <button id="zoomOutBtn" 
                class="absolute top-36 left-4 z-50 text-white hover:text-gray-300 transition-colors bg-black/50 rounded-full p-3 hover:bg-black/70"
                title="Dézoomer">
            <i class="fas fa-search-minus text-xl"></i>
        </button>
        <!-- Bouton reset zoom -->
        <button id="resetZoomBtn" 
                class="absolute top-52 left-4 z-50 text-white hover:text-gray-300 transition-colors bg-black/50 rounded-full p-3 hover:bg-black/70"
                title="Réinitialiser">
            <i class="fas fa-expand text-xl"></i>
        </button>
        <!-- Image zoomée -->
        <img id="zoomedImage" 
             src="" 
             alt="Image zoomée"
             class="max-w-full max-h-full object-contain transition-transform duration-300"
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