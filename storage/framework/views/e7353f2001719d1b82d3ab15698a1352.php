<?php $__env->startSection('title', 'Devis avec Solde Restant'); ?>

<?php $__env->startSection('content'); ?>
<div class="card-modern overflow-hidden">
    <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
         style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>15 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>15 100%);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                    <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    Devis avec Solde Restant
                </h2>
                <p class="mt-2 text-sm text-gray-600">Liste des devis acceptés ou validés ayant encore un montant à payer</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="bg-gradient-to-r from-orange-50 to-red-50 border-2 border-orange-200 rounded-lg p-3 shadow-md">
                    <p class="text-xs font-medium text-gray-600 mb-1">
                        <i class="fas fa-wallet mr-2 text-orange-500"></i>
                        Total restant
                    </p>
                    <p class="text-xl font-bold" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                        <?php echo e(number_format($totalRemainingAmount, 2, ',', ' ')); ?> <span class="text-sm text-gray-500">GNF</span>
                    </p>
                </div>
                <a href="<?php echo e(route('payments.index')); ?>" 
                   class="btn-primary inline-flex items-center justify-center px-5 py-3 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <form method="GET" action="<?php echo e(route('payments.pending-quotes')); ?>" class="space-y-4">
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
                        <option value="accepted" <?php echo e(request('status') == 'accepted' ? 'selected' : ''); ?>>Accepté</option>
                        <option value="validated" <?php echo e(request('status') == 'validated' ? 'selected' : ''); ?>>Validé</option>
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

            <div class="flex flex-wrap gap-2 mt-4">
                <button type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white transition-all duration-200"
                    style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);"
                    onmouseover="this.style.transform='translateY(-2px)'"
                    onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-filter mr-2"></i>Filtrer
                </button>
                <?php if(request()->hasAny(['search', 'status', 'client_id', 'date_from', 'date_to'])): ?>
                    <a href="<?php echo e(route('payments.pending-quotes')); ?>" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                        <i class="fas fa-times mr-2"></i>Réinitialiser
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="p-6">
        <?php if($quotes->count() > 0): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-hashtag mr-2"></i>Numéro Devis
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>Client
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider hidden md:table-cell">
                            <i class="fas fa-calendar mr-2"></i>Date
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-2"></i>Statut
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-money-bill-wave mr-2"></i>Montant Total
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-check-circle mr-2"></i>Montant Payé
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-exclamation-circle mr-2"></i>Solde Restant
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i>Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $quotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-row-hover cursor-pointer" 
                        onclick="window.location.href='<?php echo e(route('payments.create', $quote)); ?>'"
                        style="transition: all 0.2s;"
                        onmouseover="this.style.backgroundColor='#f0f9ff'; this.style.transform='scale(1.01)';"
                        onmouseout="this.style.backgroundColor='white'; this.style.transform='scale(1)';">
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
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-lg shadow-sm
                                <?php if($quote->status == 'accepted'): ?> bg-green-100 text-green-800 border border-green-200
                                <?php elseif($quote->status == 'validated'): ?> bg-emerald-100 text-emerald-800 border border-emerald-200
                                <?php else: ?> bg-gray-100 text-gray-800 border border-gray-200
                                <?php endif; ?>">
                                <?php if($quote->status == 'accepted'): ?> Accepté
                                <?php elseif($quote->status == 'validated'): ?> Validé
                                <?php else: ?> <?php echo e(ucfirst($quote->status)); ?>

                                <?php endif; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                            <span class="text-base" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                                <?php echo e(number_format($quote->total, 2, ',', ' ')); ?>

                            </span>
                            <span class="text-xs text-gray-500"> GNF</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-700 text-right">
                            <span class="text-base"><?php echo e(number_format($quote->paid_amount, 2, ',', ' ')); ?></span>
                            <span class="text-xs text-gray-500"> GNF</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right">
                            <span class="text-lg text-orange-600"><?php echo e(number_format($quote->remaining_amount, 2, ',', ' ')); ?></span>
                            <span class="text-xs text-gray-500"> GNF</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" onclick="event.stopPropagation();">
                            <div class="flex justify-end space-x-2">
                                <a href="<?php echo e(route('quotes.show', $quote)); ?>" 
                                   class="text-blue-600 hover:text-blue-800 p-2.5 rounded-lg hover:bg-blue-50 transition-all duration-200 transform hover:scale-110"
                                   title="Voir le devis"
                                   onclick="event.stopPropagation();">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if(in_array($quote->status, ['accepted', 'validated'])): ?>
                                <a href="<?php echo e(route('payments.create', $quote)); ?>" 
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-white transition-all duration-200 shadow-sm hover:shadow-md"
                                   style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);"
                                   title="Payer ce devis"
                                   onclick="event.stopPropagation();">
                                    <i class="fas fa-money-bill-wave mr-1"></i>
                                    <span class="hidden sm:inline">Payer</span>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4"
                 style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>20 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>20 100%);">
                <i class="fas fa-check-circle text-4xl text-green-500"></i>
            </div>
            <p class="text-xl font-bold text-gray-900">Aucun devis avec solde restant</p>
            <p class="text-sm text-gray-500 mt-2">Tous les devis acceptés ou validés sont entièrement payés.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gdevis\resources\views/payments/pending-quotes.blade.php ENDPATH**/ ?>