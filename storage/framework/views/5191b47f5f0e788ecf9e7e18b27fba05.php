<?php $__env->startSection('title', 'Détails Devis'); ?>

<?php $__env->startSection('content'); ?>
<div class="card-modern overflow-hidden">
    <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
         style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>15 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>15 100%);">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                    <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                        <i class="fas fa-file-invoice text-white"></i>
                    </div>
                    Devis <?php echo e($quote->quote_number); ?>

                </h2>
                <p class="mt-2 text-sm text-gray-600">Détails et informations du devis</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <?php if($quote->status !== 'validated'): ?>
                <a href="<?php echo e(route('quotes.edit', $quote)); ?>" 
                   class="btn-primary inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300"
                   onmouseover="this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-edit mr-2"></i><span class="hidden sm:inline">Modifier</span><span class="sm:hidden">Modif.</span>
                </a>
                <?php else: ?>
                <span class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-gray-400 bg-gray-200 cursor-not-allowed"
                      title="Un devis validé ne peut pas être modifié. Annulez d'abord la validation.">
                    <i class="fas fa-edit mr-2"></i><span class="hidden sm:inline">Modifier</span><span class="sm:hidden">Modif.</span>
                </span>
                <?php endif; ?>
                <?php if($quote->status === 'accepted'): ?>
                <a href="<?php echo e(route('quotes.show-validation', $quote)); ?>" 
                   class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300 bg-emerald-600 hover:bg-emerald-700"
                   onmouseover="this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-check-circle mr-2"></i><span class="hidden sm:inline">Valider</span><span class="sm:hidden">Val.</span>
                </a>
                <?php endif; ?>
                <?php if($quote->status === 'validated'): ?>
                <a href="<?php echo e(route('payments.create', $quote)); ?>" 
                   class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300 bg-green-600 hover:bg-green-700"
                   onmouseover="this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-money-bill-wave mr-2"></i><span class="hidden sm:inline">Payer</span><span class="sm:hidden">Pay.</span>
                </a>
                <?php endif; ?>
                <a href="<?php echo e(route('quotes.print', $quote)); ?>" target="_blank" 
                   class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300 bg-teal-600 hover:bg-teal-700"
                   onmouseover="this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-print mr-2"></i><span class="hidden sm:inline">Imprimer</span><span class="sm:hidden">Print</span>
                </a>
                <a href="<?php echo e(route('quotes.index')); ?>" 
                   class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 rounded-lg shadow-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-all duration-300"
                   onmouseover="this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-arrow-left mr-2"></i><span class="hidden sm:inline">Retour</span><span class="sm:hidden">Ret.</span>
                </a>
            </div>
            </div>
        </div>
    
    <div class="px-6 py-6">

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-5 rounded-xl border border-blue-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user-circle mr-2" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                    Informations Client
                </h3>
                <dl class="space-y-3">
                    <div class="flex items-center">
                        <dt class="text-sm font-semibold text-gray-600 w-24">Nom:</dt>
                        <dd class="text-sm font-bold text-gray-900"><?php echo e($quote->client->name); ?></dd>
                    </div>
                    <?php if($quote->client->email): ?>
                    <div class="flex items-center">
                        <dt class="text-sm font-semibold text-gray-600 w-24">Email:</dt>
                        <dd class="text-sm text-gray-900"><i class="fas fa-envelope mr-2 text-gray-400"></i><?php echo e($quote->client->email); ?></dd>
                    </div>
                    <?php endif; ?>
                    <?php if($quote->client->phone): ?>
                    <div class="flex items-center">
                        <dt class="text-sm font-semibold text-gray-600 w-24">Téléphone:</dt>
                        <dd class="text-sm text-gray-900"><i class="fas fa-phone mr-2 text-gray-400"></i><?php echo e($quote->client->phone); ?></dd>
                    </div>
                    <?php endif; ?>
                </dl>
            </div>
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-5 rounded-xl border border-purple-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                    Informations Devis
                </h3>
                <dl class="space-y-3">
                    <div class="flex items-center">
                        <dt class="text-sm font-semibold text-gray-600 w-32">Date:</dt>
                        <dd class="text-sm font-bold text-gray-900"><i class="fas fa-calendar-alt mr-2 text-gray-400"></i><?php echo e($quote->date->format('d/m/Y')); ?></dd>
                    </div>
                    <?php if($quote->valid_until): ?>
                    <div class="flex items-center">
                        <dt class="text-sm font-semibold text-gray-600 w-32">Valide jusqu'au:</dt>
                        <dd class="text-sm text-gray-900"><i class="fas fa-calendar-check mr-2 text-gray-400"></i><?php echo e($quote->valid_until->format('d/m/Y')); ?></dd>
                    </div>
                    <?php endif; ?>
                    <?php if($quote->creator): ?>
                    <div class="flex items-center">
                        <dt class="text-sm font-semibold text-gray-600 w-32">Créé par:</dt>
                        <dd class="text-sm text-gray-900">
                            <i class="fas fa-user mr-2 text-gray-400"></i>
                            <span class="font-medium"><?php echo e($quote->creator->name); ?></span>
                            <span class="text-xs text-gray-500 ml-2">(<?php echo e($quote->created_at->format('d/m/Y à H:i')); ?>)</span>
                        </dd>
                    </div>
                    <?php endif; ?>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-2">Statut</dt>
                        <dd class="text-sm">
                            <div class="flex flex-wrap gap-2">
                                <?php
                                    $statuses = [
                                        'draft' => ['label' => 'Brouillon', 'icon' => 'fa-file-alt', 'color' => 'gray'],
                                        'sent' => ['label' => 'Envoyé', 'icon' => 'fa-paper-plane', 'color' => 'blue'],
                                        'accepted' => ['label' => 'Accepté', 'icon' => 'fa-check-circle', 'color' => 'green'],
                                        'rejected' => ['label' => 'Refusé', 'icon' => 'fa-times-circle', 'color' => 'red'],
                                    ];
                                    $isValidated = $quote->status === 'validated';
                                    $isCancelled = $quote->status === 'cancelled';
                                ?>
                                <?php if($isValidated): ?>
                                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-emerald-200 text-emerald-800 border-2 border-emerald-400">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Validé
                                        <span class="ml-2 text-xs opacity-75">(Actuel)</span>
                                    </span>
                                <?php endif; ?>
                                <?php if($isCancelled): ?>
                                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-orange-200 text-orange-800 border-2 border-orange-400">
                                        <i class="fas fa-ban mr-2"></i>
                                        Annulé
                                        <span class="ml-2 text-xs opacity-75">(Actuel)</span>
                                    </span>
                                <?php endif; ?>
                                
                                <!-- Bouton Valider (si devis accepté ou annulé) -->
                                <?php if(in_array($quote->status, ['accepted', 'cancelled'])): ?>
                                    <a href="<?php echo e(route('quotes.show-validation', $quote)); ?>" 
                                       class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md bg-emerald-100 text-emerald-700 hover:bg-emerald-200 border border-emerald-300">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Valider
                                    </a>
                                <?php endif; ?>
                                
                                <!-- Bouton Annuler (si devis validé et sans paiements) -->
                                <?php if($isValidated): ?>
                                    <?php if($quote->payments->count() > 0): ?>
                                        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-gray-100 text-gray-400 border border-gray-300 cursor-not-allowed"
                                              title="Un devis avec des paiements ne peut pas être annulé. Supprimez d'abord les paiements.">
                                            <i class="fas fa-ban mr-2"></i>
                                            Annuler
                                        </span>
                                    <?php else: ?>
                                        <form action="<?php echo e(route('quotes.cancel', $quote)); ?>" method="POST" class="inline" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce devis validé ?');">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" 
                                                    class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md bg-orange-100 text-orange-700 hover:bg-orange-200 border border-orange-300">
                                                <i class="fas fa-ban mr-2"></i>
                                                Annuler
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusValue => $statusInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($quote->status == $statusValue && !$isValidated && !$isCancelled): ?>
                                        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold 
                                            <?php if($statusValue == 'draft'): ?> bg-gray-200 text-gray-800 border-2 border-gray-400
                                            <?php elseif($statusValue == 'sent'): ?> bg-blue-200 text-blue-800 border-2 border-blue-400
                                            <?php elseif($statusValue == 'accepted'): ?> bg-green-200 text-green-800 border-2 border-green-400
                                            <?php elseif($statusValue == 'rejected'): ?> bg-red-200 text-red-800 border-2 border-red-400
                                            <?php endif; ?>">
                                            <i class="fas <?php echo e($statusInfo['icon']); ?> mr-2"></i>
                                            <?php echo e($statusInfo['label']); ?>

                                            <span class="ml-2 text-xs opacity-75">(Actuel)</span>
                                        </span>
                                    <?php elseif(!$isValidated && !$isCancelled): ?>
                                        <form action="<?php echo e(route('quotes.update-status', $quote)); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="status" value="<?php echo e($statusValue); ?>">
                                            <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md
                                                <?php if($statusValue == 'draft'): ?> bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300
                                                <?php elseif($statusValue == 'sent'): ?> bg-blue-100 text-blue-700 hover:bg-blue-200 border border-blue-300
                                                <?php elseif($statusValue == 'accepted'): ?> bg-green-100 text-green-700 hover:bg-green-200 border border-green-300
                                                <?php elseif($statusValue == 'rejected'): ?> bg-red-100 text-red-700 hover:bg-red-200 border border-red-300
                                                <?php endif; ?>">
                                                <i class="fas <?php echo e($statusInfo['icon']); ?> mr-2"></i>
                                                <?php echo e($statusInfo['label']); ?>

                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-gray-100 text-gray-400 border border-gray-300 cursor-not-allowed opacity-60">
                                            <i class="fas <?php echo e($statusInfo['icon']); ?> mr-2"></i>
                                            <?php echo e($statusInfo['label']); ?>

                            </span>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Version mobile (cartes) -->
        <div class="block md:hidden space-y-4">
            <?php
                $groupedLines = $quote->lines->groupBy('description');
            ?>
            <?php $__empty_1 = true; $__currentLoopData = $groupedLines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productName => $lines): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $productTotalAmount = $lines->sum(function($line) {
                        return $line->amount ?: $line->subtotal;
                    });
                    $productTotalQuantity = $lines->sum('quantity');
                    $productTotalSurface = $lines->sum('surface');
                ?>
                <?php $__currentLoopData = $lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="font-semibold text-gray-900 mb-3"><?php echo e($line->description); ?></div>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <span class="text-gray-500">Largeur:</span>
                            <span class="text-gray-900 ml-1"><?php echo e($line->width ? number_format($line->width, 2, ',', ' ') : '-'); ?></span>
                        </div>
                        <div>
                            <span class="text-gray-500">Hauteur:</span>
                            <span class="text-gray-900 ml-1"><?php echo e($line->height ? number_format($line->height, 2, ',', ' ') : '-'); ?></span>
                        </div>
                        <div>
                            <span class="text-gray-500">Quantité:</span>
                            <span class="text-gray-900 ml-1"><?php echo e(number_format($line->quantity, 2, ',', ' ')); ?> <?php echo e($line->unit); ?></span>
                        </div>
                        <div>
                            <span class="text-gray-500">Prix M²:</span>
                            <span class="text-gray-900 ml-1"><?php echo e($line->price_per_m2 ? number_format($line->price_per_m2, 2, ',', ' ') . ' GNF' : '-'); ?></span>
                        </div>
                        <div>
                            <span class="text-gray-500">Surface:</span>
                            <span class="text-gray-900 ml-1"><?php echo e($line->surface ? number_format($line->surface, 2, ',', ' ') . ' m²' : '-'); ?></span>
                        </div>
                        <div>
                            <span class="text-gray-500">Montant:</span>
                            <span class="text-gray-900 font-semibold ml-1"><?php echo e($line->amount ? number_format($line->amount, 2, ',', ' ') . ' GNF' : number_format($line->subtotal, 2, ',', ' ') . ' GNF'); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-center text-gray-500 py-8">Aucune ligne</p>
            <?php endif; ?>
        </div>

        <!-- Version desktop (tableau) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Largeur</th>
                        <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Hauteur</th>
                        <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Quantité</th>
                        <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Prix M²</th>
                        <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Surface</th>
                        <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Montant</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                        // Grouper les lignes par description (produit)
                        $groupedLines = $quote->lines->groupBy('description');
                    ?>
                        <?php $__empty_1 = true; $__currentLoopData = $groupedLines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productName => $lines): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $productTotalAmount = $lines->sum(function($line) {
                                return $line->amount ?: $line->subtotal;
                            });
                            $productTotalQuantity = $lines->sum('quantity');
                            $productTotalSurface = $lines->sum('surface');
                        ?>
                        <?php $__currentLoopData = $lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="<?php echo e($index === 0 ? 'border-t-2 border-blue-300' : ''); ?>">
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($line->description); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right"><?php echo e($line->width ? number_format($line->width, 2, ',', ' ') : '-'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right"><?php echo e($line->height ? number_format($line->height, 2, ',', ' ') : '-'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right"><?php echo e(number_format($line->quantity, 2, ',', ' ')); ?> <?php echo e($line->unit); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right"><?php echo e($line->price_per_m2 ? number_format($line->price_per_m2, 2, ',', ' ') . ' GNF' : '-'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right"><?php echo e($line->surface ? number_format($line->surface, 2, ',', ' ') . ' m²' : '-'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right"><?php echo e($line->amount ? number_format($line->amount, 2, ',', ' ') . ' GNF' : number_format($line->subtotal, 2, ',', ' ') . ' GNF'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if($lines->count() > 0): ?>
                        <tr class="bg-blue-50 border-t-2 border-blue-300">
                            <td colspan="3" class="px-6 py-3 text-right text-sm font-semibold text-blue-900">
                                Total <?php echo e($productName); ?>

                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-blue-900 text-right">
                                <?php echo e(number_format($productTotalQuantity, 2, ',', ' ')); ?> <?php echo e($lines->first()->unit); ?>

                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-blue-900 text-right">
                                -
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-blue-900 text-right">
                                <?php echo e(number_format($productTotalSurface, 2, ',', ' ')); ?> m²
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-blue-900 text-right">
                                <?php echo e(number_format($productTotalAmount, 2, ',', ' ')); ?> GNF
                            </td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Aucune ligne</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Total</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600 text-right text-lg"><?php echo e(number_format($quote->total, 2, ',', ' ')); ?> GNF</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <?php if($quote->notes): ?>
        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Notes</h3>
            <p class="text-sm text-gray-700"><?php echo e($quote->notes); ?></p>
        </div>
        <?php endif; ?>

        <!-- Section Paiements (uniquement pour les devis acceptés ou validés) -->
        <?php if(in_array($quote->status, ['accepted', 'validated'])): ?>
        <div id="payments" class="mt-8 border-t border-gray-200 pt-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-money-bill-wave mr-2"></i>Gestion des Paiements
                </h3>
                <?php if($quote->is_fully_paid): ?>
                <button disabled class="bg-gray-400 text-white font-bold py-2 px-4 rounded transition-all duration-200 shadow-md cursor-not-allowed opacity-60">
                    <i class="fas fa-plus mr-2"></i>Ajouter un paiement
                </button>
                <?php else: ?>
                <a href="<?php echo e(route('payments.create', $quote)); ?>" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i>Ajouter un paiement
                </a>
                <?php endif; ?>
            </div>

            <!-- Résumé des paiements -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm font-medium text-gray-600">Montant total</p>
                    <p class="text-2xl font-bold text-blue-900"><?php echo e(number_format($quote->total, 2, ',', ' ')); ?> GNF</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-sm font-medium text-gray-600">Montant payé</p>
                    <p class="text-2xl font-bold text-green-900"><?php echo e(number_format($quote->paid_amount, 2, ',', ' ')); ?> GNF</p>
                </div>
                <div class="bg-<?php echo e($quote->is_fully_paid ? 'green' : 'orange'); ?>-50 p-4 rounded-lg">
                    <p class="text-sm font-medium text-gray-600">Solde restant</p>
                    <p class="text-2xl font-bold text-<?php echo e($quote->is_fully_paid ? 'green' : 'orange'); ?>-900"><?php echo e(number_format($quote->remaining_amount, 2, ',', ' ')); ?> GNF</p>
                </div>
            </div>

            <!-- Liste des paiements -->
            <?php if($quote->payments->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Méthode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Référence</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Créé par</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $quote->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($payment->payment_date->format('d/m/Y')); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right"><?php echo e(number_format($payment->amount, 2, ',', ' ')); ?> GNF</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($payment->payment_method_label); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($payment->reference ?? '-'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php if($payment->creator): ?>
                                    <i class="fas fa-user mr-1 text-gray-400"></i>
                                    <span class="font-medium"><?php echo e($payment->creator->name); ?></span>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($payment->notes ? \Illuminate\Support\Str::limit($payment->notes, 50) : '-'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="<?php echo e(route('payments.print', $payment)); ?>" target="_blank" class="text-green-600 hover:text-green-900" title="Imprimer le reçu">
                                        <i class="fas fa-receipt"></i>
                                    </a>
                                    <a href="<?php echo e(route('payments.edit', [$quote, $payment])); ?>" class="text-blue-600 hover:text-blue-900" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('payments.destroy', [$quote, $payment])); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ?')" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-8 bg-gray-50 rounded-lg">
                <i class="fas fa-money-bill-wave text-gray-400 text-4xl mb-2"></i>
                <p class="text-gray-500">Aucun paiement enregistré pour ce devis.</p>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gdevis\resources\views/quotes/show.blade.php ENDPATH**/ ?>