<?php $__env->startSection('title', 'Devis'); ?>

<?php $__env->startSection('content'); ?>
<div class="card-modern overflow-hidden">
    <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
         style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>15 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>15 100%);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 flex items-center">
                    <div class="p-1.5 sm:p-2 rounded-lg mr-2 sm:mr-3" style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                        <i class="fas fa-file-invoice text-white text-sm sm:text-base"></i>
                    </div>
                    Liste des Devis
                </h2>
                <p class="mt-2 text-xs sm:text-sm text-gray-600">Gérez tous vos devis et factures</p>
            </div>
            <a href="<?php echo e(route('quotes.create')); ?>" 
               class="btn-primary inline-flex items-center justify-center px-3 sm:px-5 py-2.5 sm:py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white transition-all duration-300"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.2)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)'"
               title="Nouveau Devis">
                <i class="fas fa-plus sm:mr-2"></i><span class="hidden sm:inline">Nouveau Devis</span>
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <form method="GET" action="<?php echo e(route('quotes.index')); ?>" class="space-y-4">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-5">
                <!-- Recherche -->
                <div>
                    <label for="search" class="block text-xs font-medium text-gray-700 mb-1">
                        <i class="fas fa-search mr-1"></i>Recherche
                    </label>
                    <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" 
                        placeholder="Numéro de devis"
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
                        <option value="draft" <?php echo e(request('status') == 'draft' ? 'selected' : ''); ?>>Brouillon</option>
                        <option value="sent" <?php echo e(request('status') == 'sent' ? 'selected' : ''); ?>>Envoyé</option>
                        <option value="accepted" <?php echo e(request('status') == 'accepted' ? 'selected' : ''); ?>>Accepté</option>
                        <option value="validated" <?php echo e(request('status') == 'validated' ? 'selected' : ''); ?>>Validé</option>
                        <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>Refusé</option>
                        <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Annulé</option>
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

                <!-- Date de -->
                <div>
                    <label for="date_from" class="block text-xs font-medium text-gray-700 mb-1">
                        <i class="fas fa-calendar-alt mr-1"></i>Date de
                    </label>
                    <input type="date" name="date_from" id="date_from" value="<?php echo e(request('date_from')); ?>" 
                        class="block w-full rounded-lg border-2 border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:ring-2 focus:border-transparent transition-all"
                        style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                </div>

                <!-- Date à -->
                <div>
                    <label for="date_to" class="block text-xs font-medium text-gray-700 mb-1">
                        <i class="fas fa-calendar-alt mr-1"></i>Date à
                    </label>
                    <input type="date" name="date_to" id="date_to" value="<?php echo e(request('date_to')); ?>" 
                        class="block w-full rounded-lg border-2 border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:ring-2 focus:border-transparent transition-all"
                        style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                <button type="submit" 
                    class="inline-flex items-center justify-center px-3 sm:px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white transition-all duration-200"
                    style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);"
                    onmouseover="this.style.transform='translateY(-2px)'"
                    onmouseout="this.style.transform='translateY(0)'"
                    title="Filtrer">
                    <i class="fas fa-filter sm:mr-2"></i><span class="hidden sm:inline">Filtrer</span>
                </button>
                <?php if(request()->hasAny(['search', 'status', 'client_id', 'date_from', 'date_to'])): ?>
                    <a href="<?php echo e(route('quotes.index')); ?>" 
                       class="inline-flex items-center justify-center px-3 sm:px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200"
                       title="Réinitialiser">
                        <i class="fas fa-times sm:mr-2"></i><span class="hidden sm:inline">Réinitialiser</span>
                    </a>
                <?php endif; ?>
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
                            <i class="fas fa-user mr-2"></i>Client
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden md:table-cell">
                            <i class="fas fa-calendar mr-2"></i>Date
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden lg:table-cell">
                            <i class="fas fa-info-circle mr-2"></i>Statut
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-money-bill-wave mr-2"></i>Total
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i>Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $quotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="table-row-hover">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-lg flex items-center justify-center mr-3"
                                     style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                                    <i class="fas fa-file-invoice text-white text-xs"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900"><?php echo e($quote->quote_number); ?></div>
                            <div class="text-xs text-gray-500 md:hidden"><?php echo e($quote->date->format('d/m/Y')); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900"><?php echo e($quote->client->name); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 hidden md:table-cell">
                            <i class="fas fa-calendar-alt mr-2 text-gray-400"></i><?php echo e($quote->date->format('d/m/Y')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                            <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-lg shadow-sm
                                <?php if($quote->status == 'accepted'): ?> bg-green-100 text-green-800 border border-green-200
                                <?php elseif($quote->status == 'validated'): ?> bg-emerald-100 text-emerald-800 border border-emerald-200
                                <?php elseif($quote->status == 'rejected'): ?> bg-red-100 text-red-800 border border-red-200
                                <?php elseif($quote->status == 'cancelled'): ?> bg-orange-100 text-orange-800 border border-orange-200
                                <?php elseif($quote->status == 'sent'): ?> bg-blue-100 text-blue-800 border border-blue-200
                                <?php else: ?> bg-gray-100 text-gray-800 border border-gray-200
                                <?php endif; ?>">
                                <?php if($quote->status == 'draft'): ?> Brouillon
                                <?php elseif($quote->status == 'sent'): ?> Envoyé
                                <?php elseif($quote->status == 'accepted'): ?> Accepté
                                <?php elseif($quote->status == 'validated'): ?> Validé
                                <?php elseif($quote->status == 'rejected'): ?> Refusé
                                <?php elseif($quote->status == 'cancelled'): ?> Annulé
                                <?php else: ?> <?php echo e(ucfirst($quote->status)); ?>

                                <?php endif; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                            <span class="text-lg" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"><?php echo e(number_format($quote->total, 0, ',', ' ')); ?></span> <span class="text-xs text-gray-500">GNF</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="<?php echo e(route('quotes.show', $quote)); ?>" 
                                   class="text-blue-600 hover:text-blue-800 p-2.5 rounded-lg hover:bg-blue-50 transition-all duration-200 transform hover:scale-110"
                                   title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if($quote->status !== 'validated'): ?>
                                <a href="<?php echo e(route('quotes.edit', $quote)); ?>" 
                                   class="text-indigo-600 hover:text-indigo-800 p-2.5 rounded-lg hover:bg-indigo-50 transition-all duration-200 transform hover:scale-110"
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php else: ?>
                                <span class="text-gray-400 p-2.5 rounded-lg cursor-not-allowed" 
                                      title="Un devis validé ne peut pas être modifié. Annulez d'abord la validation.">
                                    <i class="fas fa-edit"></i>
                                </span>
                                <?php endif; ?>
                                <form action="<?php echo e(route('quotes.destroy', $quote)); ?>" method="POST" class="inline" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce devis ?');">
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
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="text-gray-400">
                                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4"
                                     style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>20 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>20 100%);">
                                    <i class="fas fa-file-invoice text-4xl" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                                </div>
                                <p class="text-xl font-bold text-gray-900">Aucun devis</p>
                                <p class="text-sm text-gray-500 mt-2">Commencez par créer votre premier devis</p>
                                <a href="<?php echo e(route('quotes.create')); ?>" 
                                   class="mt-6 btn-primary inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300"
                                   onmouseover="this.style.transform='translateY(-2px)'"
                                   onmouseout="this.style.transform='translateY(0)'">
                                    <i class="fas fa-plus mr-2"></i>Créer un devis
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if($quotes->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
        <?php echo e($quotes->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gdevis\resources\views/quotes/index.blade.php ENDPATH**/ ?>