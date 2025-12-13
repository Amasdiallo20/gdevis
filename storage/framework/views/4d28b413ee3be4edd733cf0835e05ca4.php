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
                <p class="mt-2 text-sm text-gray-600">Gérez les prix unitaires des matériaux utilisés pour les fenêtres et portes</p>
            </div>
            <?php if(auth()->guard()->check()): ?>
            <?php if(Auth::user()->hasPermission('materials.create')): ?>
            <a href="<?php echo e(route('materials.create')); ?>" 
               class="btn-primary inline-flex items-center justify-center px-3 sm:px-5 py-2.5 sm:py-3 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300"
               onmouseover="this.style.transform='translateY(-2px)'"
               onmouseout="this.style.transform='translateY(0)'"
               title="Nouveau Matériau">
                <i class="fas fa-plus sm:mr-2"></i><span class="hidden sm:inline">Nouveau Matériau</span>
            </a>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="p-6">
        <?php if(session('success')): ?>
        <div class="mb-4 bg-green-50 border-2 border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                <p class="text-sm font-semibold text-green-800"><?php echo e(session('success')); ?></p>
            </div>
        </div>
        <?php endif; ?>

        <?php if($materiaux->count() > 0): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-box mr-1"></i>Nom
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-tag mr-1"></i>Prix Unitaire (GNF)
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-ruler mr-1"></i>Unité
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-calendar mr-1"></i>Dernière Mise à Jour
                        </th>
                        <?php if(auth()->guard()->check()): ?>
                        <?php if(Auth::user()->hasPermission('materials.update') || Auth::user()->hasPermission('materials.delete')): ?>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-cog mr-1"></i>Actions
                        </th>
                        <?php endif; ?>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $materiaux; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $materiau): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">
                                <?php echo e($materiau->nom); ?>

                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-indigo-100 text-indigo-800">
                                <?php echo e(number_format($materiau->prix_unitaire, 0, ',', ' ')); ?> GNF
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm text-gray-600">
                                <?php echo e($materiau->unite); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm text-gray-600">
                                <?php if($materiau->date_mise_a_jour): ?>
                                    <?php echo e($materiau->date_mise_a_jour->format('d/m/Y H:i')); ?>

                                <?php else: ?>
                                    <?php echo e($materiau->updated_at->format('d/m/Y H:i')); ?>

                                <?php endif; ?>
                            </span>
                        </td>
                        <?php if(auth()->guard()->check()): ?>
                        <?php if(Auth::user()->hasPermission('materials.update') || Auth::user()->hasPermission('materials.delete')): ?>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <?php if(Auth::user()->hasPermission('materials.update')): ?>
                                <a href="<?php echo e(route('materials.edit', $materiau)); ?>" 
                                   class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md text-xs font-medium text-white transition-all duration-200 bg-blue-600 hover:bg-blue-700"
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php endif; ?>
                                <?php if(Auth::user()->hasPermission('materials.delete')): ?>
                                <form action="<?php echo e(route('materials.destroy', $materiau)); ?>" method="POST" class="inline" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce matériau ?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md text-xs font-medium text-white transition-all duration-200 bg-red-600 hover:bg-red-700"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                        <?php endif; ?>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-12">
            <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
            <p class="text-lg font-semibold text-gray-900 mb-2">Aucun matériau enregistré</p>
            <p class="text-sm text-gray-600 mb-4">Commencez par créer votre premier matériau</p>
            <?php if(auth()->guard()->check()): ?>
            <?php if(Auth::user()->hasPermission('materials.create')): ?>
            <a href="<?php echo e(route('materials.create')); ?>" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white transition-all duration-200"
               style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                <i class="fas fa-plus mr-2"></i>Créer un Matériau
            </a>
            <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gdevis\resources\views/materials/index.blade.php ENDPATH**/ ?>