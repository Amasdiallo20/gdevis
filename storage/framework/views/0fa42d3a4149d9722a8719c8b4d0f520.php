<?php $__env->startSection('title', 'Paiements'); ?>

<?php $__env->startSection('content'); ?>
<div class="card-modern overflow-hidden">
    <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
         style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>15 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>15 100%);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                    <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                        <i class="fas fa-money-bill-wave text-white"></i>
                    </div>
                    Gestion des Paiements
                </h2>
                <p class="mt-2 text-sm text-gray-600">Liste de tous les paiements enregistrés</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-4">
                <a href="<?php echo e(route('payments.pending-quotes')); ?>" class="block">
                    <div class="bg-gradient-to-r from-orange-50 to-red-50 border-2 border-orange-200 rounded-lg p-4 shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer transform hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">
                                    <i class="fas fa-exclamation-circle mr-2 text-orange-500"></i>
                                    Montant restant non payé
                                </p>
                                <p class="text-xl sm:text-2xl font-bold" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                                    <?php echo e(number_format($totalRemainingAmount ?? 0, 2, ',', ' ')); ?> <span class="text-sm text-gray-500">GNF</span>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-mouse-pointer mr-1"></i>Cliquez pour voir les devis
                                </p>
                            </div>
                            <div class="ml-4">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center" 
                                     style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>20 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>20 100%);">
                                    <i class="fas fa-wallet text-xl" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="p-6">
        <?php if($payments->count() > 0): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-calendar mr-2"></i>Date
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-file-invoice mr-2"></i>Devis
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>Client
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-money-bill-wave mr-2"></i>Montant
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-credit-card mr-2"></i>Méthode
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-hashtag mr-2"></i>Référence
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i>Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-row-hover">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <i class="fas fa-calendar-alt mr-2 text-gray-400"></i><?php echo e($payment->payment_date->format('d/m/Y')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <a href="<?php echo e(route('quotes.show', $payment->quote)); ?>" class="text-blue-600 hover:text-blue-800 font-bold transition-colors">
                                <?php echo e($payment->quote->quote_number); ?>

                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo e($payment->quote->client->name); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                            <span class="text-lg" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"><?php echo e(number_format($payment->amount, 2, ',', ' ')); ?></span> <span class="text-xs text-gray-500">GNF</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo e($payment->payment_method_label); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo e($payment->reference ?? '-'); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="<?php echo e(route('payments.print', $payment)); ?>" target="_blank" class="text-green-600 hover:text-green-800 p-2.5 rounded-lg hover:bg-green-50 transition-all duration-200 transform hover:scale-110" title="Imprimer le reçu">
                                    <i class="fas fa-receipt"></i>
                                </a>
                                <a href="<?php echo e(route('payments.edit', [$payment->quote, $payment])); ?>" class="text-blue-600 hover:text-blue-800 p-2.5 rounded-lg hover:bg-blue-50 transition-all duration-200 transform hover:scale-110" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6 px-6 py-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <?php echo e($payments->links()); ?>

        </div>
        <?php else: ?>
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4"
                 style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>20 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>20 100%);">
                <i class="fas fa-money-bill-wave text-4xl" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
            </div>
            <p class="text-xl font-bold text-gray-900">Aucun paiement enregistré</p>
            <p class="text-sm text-gray-500 mt-2">Les paiements apparaîtront ici une fois qu'un devis accepté aura reçu un paiement.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gdevis\resources\views/payments/index.blade.php ENDPATH**/ ?>