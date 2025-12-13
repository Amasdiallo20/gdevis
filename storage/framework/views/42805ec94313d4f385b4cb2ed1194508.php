

<?php $__env->startSection('title', 'Chantiers'); ?>

<?php
    $settings = \App\Models\Setting::getSettings();
?>

<?php $__env->startSection('content'); ?>
<div class="card-modern overflow-hidden">
    <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
         style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>15 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>15 100%);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 flex items-center">
                    <div class="p-1.5 sm:p-2 rounded-lg mr-2 sm:mr-3" style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                        <i class="fas fa-hard-hat text-white text-sm sm:text-base"></i>
                    </div>
                    Liste des Chantiers
                </h2>
                <p class="mt-2 text-xs sm:text-sm text-gray-600">Gérez tous vos chantiers et leur suivi</p>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <form method="GET" action="<?php echo e(route('chantiers.index')); ?>" class="space-y-4">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Recherche -->
                <div>
                    <label for="search" class="block text-xs font-medium text-gray-700 mb-1">
                        <i class="fas fa-search mr-1"></i>Recherche
                    </label>
                    <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" 
                        placeholder="Numéro chantier ou devis"
                        class="block w-full rounded-lg border-2 border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:ring-2 focus:border-transparent transition-all"
                        style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                </div>

                <!-- Statut -->
                <div>
                    <label for="status" class="block text-xs font-medium text-gray-700 mb-1">
                        <i class="fas fa-info-circle mr-1"></i>Statut
                    </label>
                    <select name="status" id="status" 
                        class="block w-full rounded-lg border-2 border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:ring-2 focus:border-transparent transition-all"
                        style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"%23374151\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"6 9 12 15 18 9\"></polyline></svg>'); background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 1em 1em; padding-right: 2rem;">
                        <option value="">Tous les statuts</option>
                        <option value="planifié" <?php echo e(request('status') == 'planifié' ? 'selected' : ''); ?>>Planifié</option>
                        <option value="en_cours" <?php echo e(request('status') == 'en_cours' ? 'selected' : ''); ?>>En cours</option>
                        <option value="suspendu" <?php echo e(request('status') == 'suspendu' ? 'selected' : ''); ?>>Suspendu</option>
                        <option value="terminé" <?php echo e(request('status') == 'terminé' ? 'selected' : ''); ?>>Terminé</option>
                        <option value="facturé" <?php echo e(request('status') == 'facturé' ? 'selected' : ''); ?>>Facturé</option>
                    </select>
                </div>

                <!-- Client -->
                <div>
                    <label for="client_id" class="block text-xs font-medium text-gray-700 mb-1">
                        <i class="fas fa-user mr-1"></i>Client
                    </label>
                    <select name="client_id" id="client_id" 
                        class="block w-full rounded-lg border-2 border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:ring-2 focus:border-transparent transition-all"
                        style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"%23374151\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"6 9 12 15 18 9\"></polyline></svg>'); background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 1em 1em; padding-right: 2rem;">
                        <option value="">Tous les clients</option>
                        <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($client->id); ?>" <?php echo e(request('client_id') == $client->id ? 'selected' : ''); ?>>
                                <?php echo e($client->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="flex items-end gap-2">
                    <button type="submit" 
                        class="flex-1 inline-flex items-center justify-center px-3 sm:px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white transition-all duration-200"
                        style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);"
                        onmouseover="this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.transform='translateY(0)'"
                        title="Filtrer">
                        <i class="fas fa-filter sm:mr-2"></i><span class="hidden sm:inline">Filtrer</span>
                    </button>
                    <?php if(request()->hasAny(['search', 'status', 'client_id'])): ?>
                        <a href="<?php echo e(route('chantiers.index')); ?>" 
                           class="inline-flex items-center justify-center px-3 sm:px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200"
                           title="Réinitialiser">
                            <i class="fas fa-times"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto -mx-4 sm:mx-0">
        <div class="inline-block min-w-full align-middle">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-hashtag mr-2"></i>Numéro
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-file-invoice mr-2"></i>Devis
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>Client
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-2"></i>Statut
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-chart-line mr-2"></i>Avancement
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i>Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $chantiers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chantier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="table-row-hover">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-lg flex items-center justify-center mr-3"
                                     style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                                    <i class="fas fa-hard-hat text-white text-xs"></i>
                                </div>
                                <div class="text-sm font-bold text-gray-900"><?php echo e($chantier->chantier_number); ?></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900"><?php echo e($chantier->quote->quote_number); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900"><?php echo e($chantier->quote->client->name); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-lg shadow-sm
                                <?php if($chantier->status == 'en_cours'): ?> bg-green-100 text-green-800 border border-green-200
                                <?php elseif($chantier->status == 'terminé'): ?> bg-gray-100 text-gray-800 border border-gray-200
                                <?php elseif($chantier->status == 'facturé'): ?> bg-purple-100 text-purple-800 border border-purple-200
                                <?php elseif($chantier->status == 'suspendu'): ?> bg-yellow-100 text-yellow-800 border border-yellow-200
                                <?php else: ?> bg-blue-100 text-blue-800 border border-blue-200
                                <?php endif; ?>">
                                <?php echo e($chantier->status_label); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center">
                                <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2 max-w-[100px]">
                                    <div class="h-2.5 rounded-full" 
                                         style="width: <?php echo e($chantier->progress); ?>%; background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);"></div>
                                </div>
                                <span class="text-sm font-bold text-gray-900"><?php echo e($chantier->progress); ?>%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="<?php echo e(route('chantiers.show', $chantier)); ?>" 
                               class="text-blue-600 hover:text-blue-800 p-2.5 rounded-lg hover:bg-blue-50 transition-all duration-200 transform hover:scale-110"
                               title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <i class="fas fa-hard-hat text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-500 text-lg font-semibold">Aucun chantier trouvé</p>
                            <p class="text-gray-400 text-sm mt-2">Les chantiers sont créés automatiquement lors de la validation d'un devis.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <?php echo e($chantiers->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gdevis\resources\views/chantiers/index.blade.php ENDPATH**/ ?>