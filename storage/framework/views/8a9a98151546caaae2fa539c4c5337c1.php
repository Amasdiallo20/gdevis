<?php $__env->startSection('title', 'Produits'); ?>

<?php $__env->startSection('content'); ?>
<div class="card-modern overflow-hidden">
    <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
         style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>15 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>15 100%);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                    <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                        <i class="fas fa-box text-white"></i>
                    </div>
                    Liste des Produits
                </h2>
                <p class="mt-2 text-sm text-gray-600">Gérez votre catalogue de produits</p>
            </div>
            <a href="<?php echo e(route('products.create')); ?>" 
               class="btn-primary inline-flex items-center justify-center px-5 py-3 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300"
               onmouseover="this.style.transform='translateY(-2px)'"
               onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-plus mr-2"></i>Nouveau Produit
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <form method="GET" action="<?php echo e(route('products.index')); ?>" class="space-y-4">
            <div class="flex flex-col sm:flex-row gap-6">
                <!-- Recherche -->
                <div class="flex-1">
                    <label for="search" class="block text-xs font-medium text-gray-700 mb-1">
                        <i class="fas fa-search mr-1"></i>Recherche
                    </label>
                    <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" 
                        placeholder="Nom du produit"
                        class="block w-full rounded-lg border-2 border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:ring-2 focus:border-transparent transition-all"
                        style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white transition-all duration-200"
                        style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);"
                        onmouseover="this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                        <i class="fas fa-filter mr-2"></i>Filtrer
                    </button>
                    <?php if(request()->has('search')): ?>
                        <a href="<?php echo e(route('products.index')); ?>" 
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
                        <i class="fas fa-box mr-2"></i>Nom du Produit
                    </th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-cog mr-2"></i>Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="table-row-hover">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12 rounded-lg flex items-center justify-center text-white shadow-md"
                                 style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-bold text-gray-900"><?php echo e($product->name); ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="<?php echo e(route('products.show', $product)); ?>" 
                               class="text-blue-600 hover:text-blue-800 p-2.5 rounded-lg hover:bg-blue-50 transition-all duration-200 transform hover:scale-110"
                               title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?php echo e(route('products.edit', $product)); ?>" 
                               class="text-indigo-600 hover:text-indigo-800 p-2.5 rounded-lg hover:bg-indigo-50 transition-all duration-200 transform hover:scale-110"
                               title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('products.destroy', $product)); ?>" method="POST" class="inline" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
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
                    <td colspan="2" class="px-6 py-16 text-center">
                        <div class="text-gray-400">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4"
                                 style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>20 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>20 100%);">
                                <i class="fas fa-box text-4xl" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                            </div>
                            <p class="text-xl font-bold text-gray-900">Aucun produit</p>
                            <p class="text-sm text-gray-500 mt-2">Commencez par ajouter votre premier produit</p>
                            <a href="<?php echo e(route('products.create')); ?>" 
                               class="mt-6 btn-primary inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300"
                               onmouseover="this.style.transform='translateY(-2px)'"
                               onmouseout="this.style.transform='translateY(0)'">
                                <i class="fas fa-plus mr-2"></i>Ajouter un produit
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($products->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
        <?php echo e($products->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gdevis\resources\views/products/index.blade.php ENDPATH**/ ?>