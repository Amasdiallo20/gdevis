

<?php $__env->startSection('title', $modele->nom . ' - A2 VitraDevis'); ?>

<?php
    $categories = \App\Models\Modele::getCategories();
?>

<?php $__env->startSection('content'); ?>
<div class="animate-fade-in">
    <!-- Header avec breadcrumb -->
    <div class="mb-6">
        <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-4">
            <a href="<?php echo e(route('home')); ?>" class="hover:text-blue-600 transition-colors">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="<?php echo e(route('modeles.index')); ?>" class="hover:text-blue-600 transition-colors">Catalogue</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium"><?php echo e($modele->nom); ?></span>
        </nav>
        
        <!-- Header avec titre et actions -->
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-3 rounded-xl shadow-lg"
                         style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                        <i class="fas fa-images text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 uppercase tracking-tight">
                            <?php echo e($modele->nom); ?>

                        </h1>
                        <div class="mt-1.5">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold text-white shadow-md"
                                  style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                                <i class="fas fa-tag mr-1.5"></i>
                                <?php echo e($categories[$modele->categorie] ?? $modele->categorie); ?>

                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-2">
                <a href="<?php echo e(route('modeles.index')); ?>" 
                   class="inline-flex items-center justify-center px-3 py-2 rounded-lg border-2 border-gray-300 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class="fas fa-arrow-left mr-1.5"></i><span class="hidden sm:inline">Retour au Catalogue</span><span class="sm:hidden">Retour</span>
                </a>
                <?php if(auth()->guard()->check()): ?>
                <?php if(Auth::user()->hasPermission('modeles.update')): ?>
                <a href="<?php echo e(route('modeles.edit', $modele)); ?>" 
                   class="inline-flex items-center justify-center px-3 py-2 rounded-lg text-xs font-semibold text-white transition-all duration-200 shadow-md hover:shadow-lg"
                   style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                    <i class="fas fa-edit mr-1.5"></i>Modifier
                </a>
                <?php endif; ?>
                <?php if(Auth::user()->hasPermission('modeles.delete')): ?>
                <form action="<?php echo e(route('modeles.destroy', $modele)); ?>" method="POST" class="inline" 
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce modèle ? Cette action est irréversible.');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-3 py-2 rounded-lg text-xs font-semibold text-white bg-red-600 hover:bg-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-trash mr-1.5"></i>Supprimer
                    </button>
                </form>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Image principale avec zoom -->
            <div class="relative bg-gradient-to-br from-gray-50 to-gray-100 overflow-hidden group">
                <?php if($modele->image): ?>
                <div class="relative h-full min-h-[350px] md:min-h-[400px] cursor-zoom-in" id="imageContainer">
                    <img src="<?php echo e($modele->large_image_url ?? $modele->image_url); ?>" 
                         srcset="<?php echo e($modele->thumbnail_url ?? $modele->image_url); ?> 300w,
                                 <?php echo e($modele->medium_image_url ?? $modele->image_url); ?> 800w,
                                 <?php echo e($modele->large_image_url ?? $modele->image_url); ?> 1200w"
                         sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 50vw"
                         alt="<?php echo e($modele->nom); ?>"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                         loading="eager"
                         id="mainImage"
                         data-zoom-src="<?php echo e($modele->image_url); ?>"
                         onclick="openImageZoom(this)">
                    <!-- Overlay gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <!-- Badge sur l'image -->
                    <div class="absolute top-3 left-3 z-10">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold text-white shadow-2xl backdrop-blur-sm bg-white/20 border border-white/30">
                            <i class="fas fa-image mr-1.5"></i>Vue du produit
                        </span>
                    </div>
                    <!-- Indicateur de zoom -->
                    <div class="absolute bottom-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold text-white shadow-2xl backdrop-blur-sm bg-black/40 border border-white/30">
                            <i class="fas fa-search-plus mr-1.5"></i>Cliquez pour zoomer
                        </span>
                    </div>
                </div>
                <?php else: ?>
                <div class="w-full h-[400px] md:h-[450px] lg:h-[500px] flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                    <div class="text-center">
                        <i class="fas fa-image text-gray-400 text-4xl md:text-6xl mb-4"></i>
                        <p class="text-gray-500 font-medium text-sm md:text-base">Aucune image disponible</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Informations -->
            <div class="p-5 md:p-6 bg-gradient-to-br from-white to-gray-50">
                <div class="space-y-4">
                    <!-- Catégorie -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                            <i class="fas fa-folder mr-1.5" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>Catégorie
                        </label>
                        <div class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold shadow-md"
                             style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>15 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>15 100%); border: 2px solid <?php echo e($settings->primary_color ?? '#3b82f6'); ?>30;">
                            <i class="fas fa-folder mr-1.5" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                            <span style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                                <?php echo e($categories[$modele->categorie] ?? $modele->categorie); ?>

                            </span>
                        </div>
                    </div>

                    <!-- Description - Carte -->
                    <?php if($modele->description): ?>
                    <div class="bg-white rounded-xl shadow-md border-2 p-4"
                         style="border-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>20;">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center">
                            <i class="fas fa-info-circle mr-1.5" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>Description
                        </label>
                        <p class="text-gray-700 leading-relaxed text-sm"><?php echo e($modele->description); ?></p>
                    </div>
                    <?php endif; ?>

                    <!-- Prix indicatif - Carte -->
                    <?php if($modele->prix_indicatif): ?>
                    <div class="bg-white rounded-xl shadow-md border-2 p-4"
                         style="border-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>20;">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center">
                            <i class="fas fa-tag mr-1.5" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>Prix Indicatif
                        </label>
                        <div class="bg-gradient-to-br p-4 rounded-lg shadow-sm border"
                             style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>10 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>10 100%); border-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>30;">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-600 mb-1">Prix indicatif</p>
                                    <p class="text-2xl font-extrabold" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                                        <?php echo e(number_format($modele->prix_indicatif, 0, ',', ' ')); ?>

                                        <span class="text-base text-gray-600 font-normal">GNF</span>
                                    </p>
                                </div>
                                <div class="p-2.5 rounded-lg bg-white shadow-md ml-3">
                                    <i class="fas fa-tag text-xl" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-xs text-gray-600 flex items-start">
                                    <i class="fas fa-info-circle mr-1.5 mt-0.5 flex-shrink-0"></i>
                                    <span>Prix indicatif, peut varier selon les dimensions et options sélectionnées</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Bouton Ajouter au devis -->
                    <?php if(auth()->guard()->check()): ?>
                    <div class="pt-4 border-t border-gray-200">
                        <?php if(request()->has('quote_id')): ?>
                            <a href="<?php echo e(route('modeles.add-to-quote', $modele)); ?>?quote_id=<?php echo e(request('quote_id')); ?>" 
                               class="w-full inline-flex items-center justify-center px-5 py-3 rounded-xl text-sm font-extrabold text-white transition-all duration-300 shadow-xl hover:shadow-2xl"
                               style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                                <i class="fas fa-link mr-2 text-base"></i>
                                Associer à ce Devis
                            </a>
                            <p class="text-xs text-gray-500 mt-2 text-center leading-relaxed">
                                <i class="fas fa-check-circle mr-1"></i>
                                Ce modèle sera associé au devis #<?php echo e(request('quote_id')); ?> et son image sera visible sur la page du devis
                            </p>
                        <?php else: ?>
                            <a href="<?php echo e(route('modeles.add-to-quote', $modele)); ?>" 
                               class="w-full inline-flex items-center justify-center px-5 py-3 rounded-xl text-sm font-extrabold text-white transition-all duration-300 shadow-xl hover:shadow-2xl"
                               style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                                <i class="fas fa-link mr-2 text-base"></i>
                                Associer au Devis
                            </a>
                            <p class="text-xs text-gray-500 mt-2 text-center leading-relaxed">
                                <i class="fas fa-info-circle mr-1"></i>
                                L'image de ce modèle sera associée au devis lors de sa création
                            </p>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <!-- Informations de contact pour les visiteurs non connectés -->
                    <?php if($settings->address || $settings->phone): ?>
                    <div class="pt-4 border-t border-gray-200">
                        <div class="bg-white rounded-xl shadow-md border-2 p-4"
                             style="border-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>20;">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center">
                                <i class="fas fa-address-card mr-1.5" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>Contact
                            </label>
                            <div class="space-y-3">
                                <?php if($settings->address): ?>
                                <div class="flex items-start">
                                    <i class="fas fa-map-marker-alt mr-2 mt-0.5 flex-shrink-0" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                                    <p class="text-sm text-gray-700 leading-relaxed"><?php echo e($settings->address); ?></p>
                                </div>
                                <?php endif; ?>
                                <?php if($settings->phone): ?>
                                <div class="flex items-center">
                                    <i class="fas fa-phone mr-2 flex-shrink-0" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                                    <a href="tel:<?php echo e($settings->phone); ?>" class="text-sm text-gray-700 hover:underline">
                                        <?php echo e($settings->phone); ?>

                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modèles similaires -->
    <?php if($relatedModeles->count() > 0): ?>
    <div class="mt-12">
        <div class="flex items-center gap-3 mb-6">
            <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
            <h3 class="text-2xl font-extrabold text-gray-900 flex items-center gap-2">
                <i class="fas fa-th-large" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                Modèles Similaires
            </h3>
            <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php $__currentLoopData = $relatedModeles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('modeles.show', $related)); ?>" 
               class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-200 cursor-pointer transform hover:-translate-y-2 block">
                <!-- Image -->
                <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                    <?php if($related->image): ?>
                    <img src="<?php echo e($related->thumbnail_url ?? $related->image_url); ?>" 
                         alt="<?php echo e($related->nom); ?>"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                         loading="lazy"
                         loading="lazy">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fas fa-image text-gray-400 text-4xl"></i>
                    </div>
                    <?php endif; ?>
                    <!-- Overlay gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <!-- Badge catégorie -->
                    <div class="absolute top-3 left-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold text-white shadow-lg backdrop-blur-sm bg-white/20 border border-white/30">
                            <?php echo e($categories[$related->categorie] ?? $related->categorie); ?>

                        </span>
                    </div>
                </div>
                
                <!-- Contenu -->
                <div class="p-4">
                    <h4 class="text-base font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                        <?php echo e($related->nom); ?>

                    </h4>
                    <?php if($related->prix_indicatif): ?>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Prix indicatif</span>
                        <span class="text-lg font-extrabold" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                            <?php echo e(number_format($related->prix_indicatif, 0, ',', ' ')); ?> GNF
                        </span>
                    </div>
                    <?php endif; ?>
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <span class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-eye mr-1"></i>Voir les détails
                        </span>
                    </div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}
</style>

<script>
// Sauvegarder le modèle dans localStorage pour mode hors-ligne
document.addEventListener('DOMContentLoaded', function() {
    const modeleData = {
        id: <?php echo e($modele->id); ?>,
        nom: <?php echo json_encode($modele->nom, 15, 512) ?>,
        categorie: <?php echo json_encode($modele->categorie, 15, 512) ?>,
        description: <?php echo json_encode($modele->description, 15, 512) ?>,
        prix_indicatif: <?php echo e($modele->prix_indicatif ?? 0); ?>,
        image_url: <?php echo json_encode($modele->image_url, 15, 512) ?>,
    };
    
    // Sauvegarder dans localStorage
    let savedModeles = JSON.parse(localStorage.getItem('modeles_cache') || '[]');
    const existingIndex = savedModeles.findIndex(m => m.id === modeleData.id);
    
    if (existingIndex >= 0) {
        savedModeles[existingIndex] = modeleData;
    } else {
        savedModeles.push(modeleData);
    }
    
    // Limiter à 50 modèles en cache
    if (savedModeles.length > 50) {
        savedModeles = savedModeles.slice(-50);
    }
    
    localStorage.setItem('modeles_cache', JSON.stringify(savedModeles));
    
    // Animation d'apparition des cartes similaires
    const cards = document.querySelectorAll('.group');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Fonctions pour le zoom d'image
let currentZoom = 1;
let imageElement = null;

function openImageZoom(imgElement) {
    const modal = document.getElementById('imageZoomModal');
    const zoomedImg = document.getElementById('zoomedImage');
    const zoomSrc = imgElement.getAttribute('data-zoom-src') || imgElement.src;
    
    zoomedImg.src = zoomSrc;
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
    if (!imageElement) {
        console.error('zoomIn: imageElement not found');
        return;
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

let dragState = { isDown: false, startX: 0, startY: 0 };

function setupImageDrag(img) {
    // Réinitialiser l'état de drag
    dragState.isDown = false;
    
    // Supprimer les anciens listeners en clonant l'élément
    const newImg = img.cloneNode(true);
    img.parentNode.replaceChild(newImg, img);
    const freshImg = document.getElementById('zoomedImage');
    
    // Mettre à jour la référence
    imageElement = freshImg;
    
    freshImg.addEventListener('mousedown', (e) => {
        if (currentZoom > 1) {
            dragState.isDown = true;
            freshImg.style.cursor = 'grabbing';
            dragState.startX = e.pageX - freshImg.offsetLeft;
            dragState.startY = e.pageY - freshImg.offsetTop;
        }
    });
    
    freshImg.addEventListener('mouseleave', () => {
        dragState.isDown = false;
        freshImg.style.cursor = currentZoom > 1 ? 'grab' : 'default';
    });
    
    freshImg.addEventListener('mouseup', () => {
        dragState.isDown = false;
        freshImg.style.cursor = currentZoom > 1 ? 'grab' : 'default';
    });
    
    freshImg.addEventListener('mousemove', (e) => {
        if (!dragState.isDown || currentZoom <= 1) return;
        e.preventDefault();
        const x = e.pageX - freshImg.offsetLeft;
        const y = e.pageY - freshImg.offsetTop;
        const walkX = (x - dragState.startX) * 2;
        const walkY = (y - dragState.startY) * 2;
        
        const currentTransform = freshImg.style.transform.match(/translate\(([^,]+),\s*([^)]+)\)/);
        const currentX = currentTransform ? parseFloat(currentTransform[1]) : 0;
        const currentY = currentTransform ? parseFloat(currentTransform[2]) : 0;
        
        freshImg.style.transform = `translate(${currentX + walkX}px, ${currentY + walkY}px) scale(${currentZoom})`;
        dragState.startX = x;
        dragState.startY = y;
    });
    
    // Zoom avec la molette de la souris
    freshImg.addEventListener('wheel', (e) => {
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
    const zoomInBtn = e.target.closest('#zoomInBtn');
    const zoomOutBtn = e.target.closest('#zoomOutBtn');
    const resetZoomBtn = e.target.closest('#resetZoomBtn');
    
    if (zoomInBtn) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Zoom In button clicked');
        zoomIn();
        return false;
    } else if (zoomOutBtn) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Zoom Out button clicked');
        zoomOut();
        return false;
    } else if (resetZoomBtn) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Reset Zoom button clicked');
        resetZoom();
        return false;
    }
}, true);
</script>

<!-- Lightbox Modal pour zoom image -->
<div id="imageZoomModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/90 backdrop-blur-sm" onclick="closeImageZoom()">
    <div class="relative w-full h-full flex items-center justify-center p-4" onclick="event.stopPropagation()">
        <!-- Bouton fermer -->
        <button onclick="closeImageZoom()" 
                class="absolute top-4 right-4 z-50 text-white hover:text-gray-300 transition-colors bg-black/50 rounded-full p-3 hover:bg-black/70">
            <i class="fas fa-times text-xl"></i>
        </button>
        <!-- Bouton zoom in -->
        <button id="zoomInBtn" 
                class="absolute top-4 left-4 z-50 text-white hover:text-gray-300 transition-colors bg-black/50 rounded-full p-3 hover:bg-black/70"
                title="Zoomer">
            <i class="fas fa-search-plus text-xl"></i>
        </button>
        <!-- Bouton zoom out -->
        <button id="zoomOutBtn" 
                class="absolute top-20 left-4 z-50 text-white hover:text-gray-300 transition-colors bg-black/50 rounded-full p-3 hover:bg-black/70"
                title="Dézoomer">
            <i class="fas fa-search-minus text-xl"></i>
        </button>
        <!-- Bouton reset zoom -->
        <button id="resetZoomBtn" 
                class="absolute top-36 left-4 z-50 text-white hover:text-gray-300 transition-colors bg-black/50 rounded-full p-3 hover:bg-black/70"
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gdevis\resources\views/modeles/show.blade.php ENDPATH**/ ?>