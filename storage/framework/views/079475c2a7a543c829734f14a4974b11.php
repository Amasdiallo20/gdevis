

<?php $__env->startSection('title', 'Valider le Devis'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-check-circle mr-2 text-emerald-600"></i>Valider le Devis
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">Devis <?php echo e($quote->quote_number); ?> - <?php echo e($quote->client->name); ?></p>
                </div>
                <a href="<?php echo e(route('quotes.show', $quote)); ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Informations du devis -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>Informations du Devis
            </h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-6">
                <div>
                    <p class="text-sm font-medium text-gray-500">Date</p>
                    <p class="text-sm text-gray-900"><?php echo e($quote->date->format('d/m/Y')); ?></p>
                </div>
                <?php if($quote->valid_until): ?>
                <div>
                    <p class="text-sm font-medium text-gray-500">Valide jusqu'au</p>
                    <p class="text-sm text-gray-900"><?php echo e($quote->valid_until->format('d/m/Y')); ?></p>
                </div>
                <?php endif; ?>
                <div>
                    <p class="text-sm font-medium text-gray-500">Statut</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Accepté
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Validation -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">
                <i class="fas fa-handshake mr-2 text-yellow-600"></i>Montant Final à S'accorder
            </h3>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Montant initial calculé
                        </label>
                        <div class="text-3xl font-bold text-gray-900">
                            <?php echo e(number_format($quote->subtotal, 0, ',', ' ')); ?> GNF
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Montant calculé automatiquement à partir des lignes du devis</p>
                    </div>
                    <div>
                        <form action="<?php echo e(route('quotes.validate', $quote)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <label for="final_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Montant final s'accorder (GNF) <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                id="final_amount" 
                                name="final_amount" 
                                step="0.01"
                                min="0"
                                value="<?php echo e(old('final_amount', $quote->final_amount ?: $quote->subtotal)); ?>"
                                placeholder="<?php echo e(number_format($quote->subtotal, 0, ',', ' ')); ?>"
                                class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-lg font-semibold shadow-sm focus:ring-2 focus:border-emerald-500 focus:ring-emerald-500 transition-all <?php $__errorArgs = ['final_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required
                                autofocus
                            >
                            <?php $__errorArgs = ['final_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="mt-2 text-xs text-gray-500">Saisissez le montant final négocié avec le client</p>

                            <div class="mt-6 flex gap-3">
                                <button 
                                    type="submit" 
                                    class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg"
                                >
                                    <i class="fas fa-check-circle mr-2"></i>Valider le Devis
                                </button>
                                <a href="<?php echo e(route('quotes.show', $quote)); ?>" 
                                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-times mr-2"></i>Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Résumé des lignes -->
            <?php if($quote->lines->count() > 0): ?>
            <div class="mt-6">
                <h4 class="text-md font-medium text-gray-900 mb-3">
                    <i class="fas fa-list mr-2 text-gray-400"></i>Détail du devis
                </h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Prix unitaire</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $quote->lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($line->description); ?></td>
                                <td class="px-4 py-3 text-sm text-gray-600 text-right"><?php echo e(number_format($line->quantity, 2, ',', ' ')); ?></td>
                                <td class="px-4 py-3 text-sm text-gray-600 text-right">
                                    <?php if($line->unit_price): ?>
                                        <?php echo e(number_format($line->unit_price, 0, ',', ' ')); ?> GNF
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">
                                    <?php echo e(number_format($line->subtotal, 0, ',', ' ')); ?> GNF
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right text-sm font-medium text-gray-900">TOTAL</td>
                                <td class="px-4 py-3 text-right text-lg font-bold text-blue-600">
                                    <?php echo e(number_format($quote->subtotal, 0, ',', ' ')); ?> GNF
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gdevis\resources\views/quotes/validate.blade.php ENDPATH**/ ?>