

<?php $__env->startSection('title', 'Matériaux'); ?>

<?php $__env->startSection('content'); ?>
<div class="card-modern overflow-hidden">
    <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
         style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>15 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>15 100%);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                    <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                        <i class="fas fa-tools text-white"></i>
                    </div>
                    Liste des Matériaux
                </h2>
                <p class="mt-2 text-sm text-gray-600">Gérez votre catalogue de matériaux</p>
            </div>
            <a href="<?php echo e(route('materials.create')); ?>" 
               class="btn-primary inline-flex items-center justify-center px-5 py-3 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300"
               onmouseover="this.style.transform='translateY(-2px)'"
               onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-plus mr-2"></i>Nouveau Matériau
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <form method="GET" action="<?php echo e(route('materials.index')); ?>" class="space-y-4">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Recherche -->
                <div>
                    <label for="search" class="block text-xs font-medium text-gray-700 mb-1">
                        <i class="fas fa-search mr-1"></i>Recherche
                    </label>
                    <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" 
                        placeholder="Nom ou type"
                        class="block w-full rounded-lg border-2 border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:ring-2 focus:border-transparent transition-all"
                        style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-xs font-medium text-gray-700 mb-1">
                        <i class="fas fa-tag mr-1"></i>Type
                    </label>
                    <select name="type" id="type" 
                        class="block w-full rounded-lg border-2 border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:ring-2 focus:border-transparent transition-all"
                        style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"%23374151\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"6 9 12 15 18 9\"></polyline></svg>'); background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 1em 1em; padding-right: 2rem;">
                        <option value="">Tous les types</option>
                        <option value="CADRE" <?php echo e(request('type') == 'CADRE' ? 'selected' : ''); ?>>CADRE</option>
                        <option value="VENTO" <?php echo e(request('type') == 'VENTO' ? 'selected' : ''); ?>>VENTO</option>
                        <option value="SIKANE" <?php echo e(request('type') == 'SIKANE' ? 'selected' : ''); ?>>SIKANE</option>
                        <option value="MOUSTIQUAIRE" <?php echo e(request('type') == 'MOUSTIQUAIRE' ? 'selected' : ''); ?>>MOUSTIQUAIRE</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white transition-all duration-200"
                        style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);"
                        onmouseover="this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                        <i class="fas fa-filter mr-2"></i>Filtrer
                    </button>
                    <?php if(request()->hasAny(['search', 'type'])): ?>
                        <a href="<?php echo e(route('materials.index')); ?>" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                            <i class="fas fa-times mr-2"></i>Réinitialiser
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-tools mr-2"></i>Nom
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden md:table-cell">
                        <i class="fas fa-tag mr-2"></i>Type
                    </th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-money-bill-wave mr-2"></i>Prix Unitaire
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden lg:table-cell">
                        <i class="fas fa-ruler mr-2"></i>Unité
                    </th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-cog mr-2"></i>Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="table-row-hover">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12 rounded-lg flex items-center justify-center text-white shadow-md"
                                 style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                                <i class="fas fa-tools"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-bold text-gray-900"><?php echo e($material->name); ?></div>
                                <div class="text-xs text-gray-500 md:hidden"><?php echo e($material->type ?? '-'); ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 hidden md:table-cell">
                        <?php if($material->type): ?>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                <?php if($material->type == 'CADRE'): ?> bg-blue-100 text-blue-800
                                <?php elseif($material->type == 'VENTO'): ?> bg-green-100 text-green-800
                                <?php elseif($material->type == 'SIKANE'): ?> bg-purple-100 text-purple-800
                                <?php elseif($material->type == 'MOUSTIQUAIRE'): ?> bg-orange-100 text-orange-800
                                <?php else: ?> bg-gray-100 text-gray-800
                                <?php endif; ?>">
                                <?php echo e($material->type); ?>

                            </span>
                        <?php else: ?>
                            <span class="text-gray-400">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                        <span class="text-base" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                            <?php echo e(number_format($material->unit_price, 0, ',', ' ')); ?>

                        </span>
                        <span class="text-xs text-gray-500"> GNF</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 hidden lg:table-cell">
                        <?php echo e($material->unit ?? 'unité'); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="<?php echo e(route('materials.show', $material)); ?>" 
                               class="text-blue-600 hover:text-blue-800 p-2.5 rounded-lg hover:bg-blue-50 transition-all duration-200 transform hover:scale-110"
                               title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?php echo e(route('materials.edit', $material)); ?>" 
                               class="text-indigo-600 hover:text-indigo-800 p-2.5 rounded-lg hover:bg-indigo-50 transition-all duration-200 transform hover:scale-110"
                               title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('materials.destroy', $material)); ?>" method="POST" class="inline" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce matériau ?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 p-2.5 rounded-lg hover:bg-red-50 transition-all duration-200 transform hover:scale-110"
                                        title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="text-gray-400">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4"
                                 style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>20 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>20 100%);">
                                <i class="fas fa-tools text-4xl" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                            </div>
                            <p class="text-xl font-bold text-gray-900">Aucun matériau</p>
                            <p class="text-sm text-gray-500 mt-2">Commencez par ajouter votre premier matériau</p>
                            <a href="<?php echo e(route('materials.create')); ?>" 
                               class="mt-6 btn-primary inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300"
                               onmouseover="this.style.transform='translateY(-2px)'"
                               onmouseout="this.style.transform='translateY(0)'">
                                <i class="fas fa-plus mr-2"></i>Ajouter un matériau
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($materials->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
        <?php echo e($materials->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gdevis\resources\views/materials/index.blade.php ENDPATH**/ ?>