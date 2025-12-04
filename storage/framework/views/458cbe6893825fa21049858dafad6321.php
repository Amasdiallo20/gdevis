<?php $__env->startSection('title', 'Devis'); ?>

<?php $__env->startSection('content'); ?>
<div class="card-modern overflow-hidden">
    <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
         style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>15 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>15 100%);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                    <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                        <i class="fas fa-file-invoice text-white"></i>
                    </div>
                    Liste des Devis
                </h2>
                <p class="mt-2 text-sm text-gray-600">Gérez tous vos devis et factures</p>
            </div>
            <a href="<?php echo e(route('quotes.create')); ?>" 
               class="btn-primary inline-flex items-center justify-center px-5 py-3 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.2)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)'">
                <i class="fas fa-plus mr-2"></i>Nouveau Devis
            </a>
        </div>
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
                                <?php elseif($quote->status == 'sent'): ?> bg-blue-100 text-blue-800 border border-blue-200
                                <?php else: ?> bg-gray-100 text-gray-800 border border-gray-200
                                <?php endif; ?>">
                                <?php if($quote->status == 'draft'): ?> Brouillon
                                <?php elseif($quote->status == 'sent'): ?> Envoyé
                                <?php elseif($quote->status == 'accepted'): ?> Accepté
                                <?php elseif($quote->status == 'validated'): ?> Validé
                                <?php elseif($quote->status == 'rejected'): ?> Refusé
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
                                <a href="<?php echo e(route('quotes.edit', $quote)); ?>" 
                                   class="text-indigo-600 hover:text-indigo-800 p-2.5 rounded-lg hover:bg-indigo-50 transition-all duration-200 transform hover:scale-110"
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?php echo e(route('quotes.print', $quote)); ?>" 
                                   target="_blank"
                                   class="text-teal-600 hover:text-teal-800 p-2.5 rounded-lg hover:bg-teal-50 transition-all duration-200 transform hover:scale-110"
                                   title="Imprimer">
                                    <i class="fas fa-print"></i>
                                </a>
                                <?php if($quote->status === 'accepted'): ?>
                                    <a href="<?php echo e(route('quotes.show-validation', $quote)); ?>" 
                                       class="text-emerald-600 hover:text-emerald-800 p-2.5 rounded-lg hover:bg-emerald-50 transition-all duration-200 transform hover:scale-110"
                                       title="Valider le devis">
                                        <i class="fas fa-check-circle"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if(in_array($quote->status, ['accepted', 'validated'])): ?>
                                    <?php if($quote->is_fully_paid): ?>
                                    <span class="text-gray-400 p-2.5 rounded-lg cursor-not-allowed" 
                                          title="Devis entièrement payé">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </span>
                                    <?php else: ?>
                                    <a href="<?php echo e(route('payments.create', $quote)); ?>" 
                                       class="text-green-600 hover:text-green-800 p-2.5 rounded-lg hover:bg-green-50 transition-all duration-200 transform hover:scale-110"
                                       title="Paiements">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </a>
                                    <?php endif; ?>
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