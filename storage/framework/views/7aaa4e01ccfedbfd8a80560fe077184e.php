

<?php $__env->startSection('title', 'Ajouter un Paiement'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-slate-50">
        <h2 class="text-2xl font-bold text-gray-900">
            <i class="fas fa-money-bill-wave mr-2 text-gray-600"></i>Ajouter un Paiement
        </h2>
        <p class="mt-1 text-sm text-gray-600">Devis <?php echo e($quote->quote_number); ?> - Client: <?php echo e($quote->client->name); ?></p>
    </div>

    <div class="p-6">
        <!-- Résumé du devis -->
        <div class="mb-6 bg-blue-50 p-4 rounded-lg">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <p class="text-sm font-medium text-gray-600">Montant total</p>
                    <p class="text-xl font-bold text-blue-900"><?php echo e(number_format($quote->total, 2, ',', ' ')); ?> GNF</p>
                    <?php if($quote->status === 'validated' && $quote->final_amount): ?>
                        <p class="text-xs text-gray-500 mt-1">(Montant final s'accorder)</p>
                    <?php endif; ?>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Déjà payé</p>
                    <p class="text-xl font-bold text-green-900"><?php echo e(number_format($quote->paid_amount, 2, ',', ' ')); ?> GNF</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Solde restant</p>
                    <p class="text-xl font-bold text-orange-900"><?php echo e(number_format($quote->remaining_amount, 2, ',', ' ')); ?> GNF</p>
                </div>
            </div>
        </div>

        <form action="<?php echo e(route('payments.store', $quote)); ?>" method="POST" class="space-y-6">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">
                        Montant (GNF) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="amount" 
                           id="amount" 
                           step="0.01" 
                           min="0.01" 
                           max="<?php echo e($quote->remaining_amount); ?>"
                           required
                           value="<?php echo e(old('amount')); ?>"
                           class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"
                           placeholder="0.00">
                    <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php else: ?>
                        <p class="mt-1 text-xs text-gray-500">Maximum: <?php echo e(number_format($quote->remaining_amount, 2, ',', ' ')); ?> GNF</p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Date de paiement <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="payment_date" 
                           id="payment_date" 
                           required
                           value="<?php echo e(old('payment_date', date('Y-m-d'))); ?>"
                           class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all <?php $__errorArgs = ['payment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                    <?php $__errorArgs = ['payment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">
                        Méthode de paiement <span class="text-red-500">*</span>
                    </label>
                    <select name="payment_method" 
                            id="payment_method" 
                            required
                            class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                        <option value="cash" <?php echo e(old('payment_method') == 'cash' ? 'selected' : ''); ?>>Espèces</option>
                        <option value="bank_transfer" <?php echo e(old('payment_method') == 'bank_transfer' ? 'selected' : ''); ?>>Virement bancaire</option>
                        <option value="check" <?php echo e(old('payment_method') == 'check' ? 'selected' : ''); ?>>Chèque</option>
                        <option value="mobile_money" <?php echo e(old('payment_method') == 'mobile_money' ? 'selected' : ''); ?>>Mobile Money</option>
                        <option value="other" <?php echo e(old('payment_method') == 'other' ? 'selected' : ''); ?>>Autre</option>
                    </select>
                    <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">
                        Référence
                    </label>
                    <input type="text" 
                           name="reference" 
                           id="reference" 
                           value="<?php echo e(old('reference')); ?>"
                           class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all <?php $__errorArgs = ['reference'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"
                           placeholder="Ex: CHQ-12345, VIR-67890">
                    <?php $__errorArgs = ['reference'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="sm:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                        Notes
                    </label>
                    <textarea name="notes" 
                              id="notes" 
                              rows="3"
                              class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                              style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"
                              placeholder="Notes supplémentaires sur ce paiement..."><?php echo e(old('notes')); ?></textarea>
                    <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="<?php echo e(route('quotes.show', $quote)); ?>" 
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-times mr-2"></i>Annuler
                </a>
                <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                        style="background-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"
                        onmouseover="this.style.opacity='0.9'"
                        onmouseout="this.style.opacity='1'">
                    <i class="fas fa-save mr-2"></i>Enregistrer le paiement
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gdevis\resources\views/payments/create.blade.php ENDPATH**/ ?>