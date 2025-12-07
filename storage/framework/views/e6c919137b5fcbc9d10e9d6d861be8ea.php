

<?php $__env->startSection('title', 'Calcul des Matériaux'); ?>

<?php $__env->startSection('content'); ?>
<div class="card-modern overflow-hidden">
    <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
         style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>15 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>15 100%);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                    <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                        <i class="fas fa-calculator text-white"></i>
                    </div>
                    Calcul des Matériaux
                </h2>
                <p class="mt-2 text-sm text-gray-600">Calculez automatiquement les matériaux nécessaires pour un devis</p>
            </div>
        </div>
    </div>

    <div class="p-6">
        <!-- Formulaire de sélection du devis -->
        <form method="GET" action="<?php echo e(route('quotes.calculate-materials')); ?>" class="mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label for="quote_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-file-invoice mr-1"></i>Sélectionner un devis *
                    </label>
                    <select name="quote_id" id="quote_id" required
                        class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all"
                        style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"%23374151\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"6 9 12 15 18 9\"></polyline></svg>'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1em 1em; padding-right: 2.5rem;">
                        <option value="">-- Sélectionner un devis --</option>
                        <?php $__currentLoopData = $quotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($quote->id); ?>" <?php echo e(request('quote_id') == $quote->id ? 'selected' : ''); ?>>
                                <?php echo e($quote->quote_number); ?> - <?php echo e($quote->client->name ?? 'N/A'); ?> (<?php echo e($quote->date->format('d/m/Y')); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full btn-primary inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300"
                            onmouseover="this.style.transform='translateY(-2px)'"
                            onmouseout="this.style.transform='translateY(0)'">
                        <i class="fas fa-calculator mr-2"></i>Calculer
                    </button>
                </div>
            </div>
        </form>

        <?php if($selectedQuote && $materials): ?>
            <!-- Informations du devis -->
            <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            <i class="fas fa-file-invoice mr-2" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                            Devis : <?php echo e($selectedQuote->quote_number); ?>

                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="font-semibold">Client :</span> <?php echo e($selectedQuote->client->name ?? 'N/A'); ?>

                            </div>
                            <div>
                                <span class="font-semibold">Date :</span> <?php echo e($selectedQuote->date->format('d/m/Y')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="<?php echo e(route('quotes.print-materials', $selectedQuote)); ?>?pdf=1" 
                           target="_blank"
                           class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300 bg-teal-600 hover:bg-teal-700"
                           onmouseover="this.style.transform='translateY(-2px)'"
                           onmouseout="this.style.transform='translateY(0)'">
                            <i class="fas fa-print mr-2"></i>Imprimer
                        </a>
                    </div>
                </div>
            </div>

            <!-- Résultats du calcul -->
            <div class="mb-6">
                <?php if(count($materials['fenetres_details']) > 0): ?>
                <?php
                    $has3Rails = collect($materials['fenetres_details'])->contains(function($detail) {
                        return isset($detail['type']) && $detail['type'] === '3_rails';
                    });
                    $hasAlu82 = collect($materials['fenetres_details'])->contains(function($detail) {
                        return !isset($detail['type']) || $detail['type'] === 'alu_82';
                    });
                ?>
                
                <?php if($hasAlu82): ?>
                <!-- Totaux pour fenêtres ALU 82 -->
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-list-check mr-2" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                    Totaux des Matériaux - Fenêtres Alu 82
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white border-2 border-blue-200 rounded-lg p-4 shadow-md">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Total CADRE</label>
                        <div class="text-2xl font-bold" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                            <?php echo e(number_format($materials['total_cadre'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-1">barres</p>
                    </div>
                    <div class="bg-white border-2 border-green-200 rounded-lg p-4 shadow-md">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Total VENTO</label>
                        <div class="text-2xl font-bold text-green-700">
                            <?php echo e(number_format($materials['total_vento'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-1">barres</p>
                    </div>
                    <div class="bg-white border-2 border-purple-200 rounded-lg p-4 shadow-md">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Total SIKANE</label>
                        <div class="text-2xl font-bold text-purple-700">
                            <?php echo e(number_format($materials['total_sikane'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-1">barres</p>
                    </div>
                    <div class="bg-white border-2 border-orange-200 rounded-lg p-4 shadow-md">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Total MOUSTIQUAIRE</label>
                        <div class="text-2xl font-bold text-orange-700">
                            <?php echo e(number_format($materials['total_moustiquaire'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-1">barres</p>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if($has3Rails && isset($materials['total_rail'])): ?>
                <!-- Totaux pour fenêtres 3 RAILS -->
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-list-check mr-2" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                    Totaux des Matériaux - Fenêtres 3 Rails
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3 mb-6">
                    <div class="bg-white border-2 border-blue-200 rounded-lg p-3 shadow-md">
                        <label class="block text-xs font-medium text-gray-600 mb-1">RAIL</label>
                        <div class="text-xl font-bold text-blue-700">
                            <?php echo e(number_format($materials['total_rail'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-1">barres</p>
                    </div>
                    <div class="bg-white border-2 border-indigo-200 rounded-lg p-3 shadow-md">
                        <label class="block text-xs font-medium text-gray-600 mb-1">MONTANT</label>
                        <div class="text-xl font-bold text-indigo-700">
                            <?php echo e(number_format($materials['total_montant'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-1">barres</p>
                    </div>
                    <div class="bg-white border-2 border-green-200 rounded-lg p-3 shadow-md">
                        <label class="block text-xs font-medium text-gray-600 mb-1">BUTÉE</label>
                        <div class="text-xl font-bold text-green-700">
                            <?php echo e(number_format($materials['total_butee'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-1">barres</p>
                    </div>
                    <div class="bg-white border-2 border-teal-200 rounded-lg p-3 shadow-md">
                        <label class="block text-xs font-medium text-gray-600 mb-1">POIGNÉE</label>
                        <div class="text-xl font-bold text-teal-700">
                            <?php echo e(number_format($materials['total_poignee'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-1">barres</p>
                    </div>
                    <div class="bg-white border-2 border-purple-200 rounded-lg p-3 shadow-md">
                        <label class="block text-xs font-medium text-gray-600 mb-1">ROULETTE</label>
                        <div class="text-xl font-bold text-purple-700">
                            <?php echo e(number_format($materials['total_roulette'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-1">barres</p>
                    </div>
                    <div class="bg-white border-2 border-pink-200 rounded-lg p-3 shadow-md">
                        <label class="block text-xs font-medium text-gray-600 mb-1">TÊTE</label>
                        <div class="text-xl font-bold text-pink-700">
                            <?php echo e(number_format($materials['total_tete'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-1">barres</p>
                    </div>
                    <div class="bg-white border-2 border-orange-200 rounded-lg p-3 shadow-md">
                        <label class="block text-xs font-medium text-gray-600 mb-1">MOUSTIQUAIRE</label>
                        <div class="text-xl font-bold text-orange-700">
                            <?php echo e(number_format($materials['total_moustiquaire_3rails'] ?? 0, 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-1">barres</p>
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>
                
                <?php if(isset($materials['total_cadre_porte']) && ($materials['total_cadre_porte'] > 0 || $materials['total_battant_porte'] > 0 || $materials['total_division'] > 0)): ?>
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-door-open mr-2" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                    Totaux des Matériaux - Portes
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div class="bg-white border-2 border-indigo-200 rounded-lg p-4 shadow-md">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Total CADRE PORTE</label>
                        <div class="text-2xl font-bold text-indigo-700">
                            <?php echo e(number_format($materials['total_cadre_porte'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-1">barres</p>
                    </div>
                    <div class="bg-white border-2 border-teal-200 rounded-lg p-4 shadow-md">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Total BATTANT PORTE</label>
                        <div class="text-2xl font-bold text-teal-700">
                            <?php echo e(number_format($materials['total_battant_porte'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-1">barres</p>
                    </div>
                    <div class="bg-white border-2 border-pink-200 rounded-lg p-4 shadow-md">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Total DIVISION</label>
                        <div class="text-2xl font-bold text-pink-700">
                            <?php echo e(number_format($materials['total_division'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-1">barres</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Détails par ligne - Fenêtres -->
            <?php if(count($materials['fenetres_details']) > 0): ?>
            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-window-maximize mr-2" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                    Détails par Ligne de Fenêtre
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-box mr-2"></i>Produit
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-ruler mr-2"></i>Dimensions
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-hashtag mr-2"></i>Nb
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-tag mr-2"></i>Type
                                </th>
                                <?php if(isset($materials['fenetres_details'][0]['type']) && $materials['fenetres_details'][0]['type'] === '3_rails'): ?>
                                <!-- Colonnes pour fenêtres 3 RAILS -->
                                <th class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">RAIL</th>
                                <th class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">MONTANT</th>
                                <th class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">BUTÉE</th>
                                <th class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">POIGNÉE</th>
                                <th class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">ROULETTE</th>
                                <th class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">TÊTE</th>
                                <th class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">MOUSTIQUAIRE</th>
                                <?php else: ?>
                                <!-- Colonnes pour fenêtres ALU 82 -->
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-cube mr-2"></i>CADRE
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-cube mr-2"></i>VENTO
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-cube mr-2"></i>SIKANE
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-cube mr-2"></i>MOUSTIQUAIRE
                                </th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $materials['fenetres_details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="table-row-hover">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">
                                        <?php echo e($detail['line']->product->name ?? $detail['line']->description); ?>

                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">
                                        <?php echo e(number_format($detail['line']->width, 0)); ?> × <?php echo e(number_format($detail['line']->height, 0)); ?> cm
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?php echo e($detail['nombre_fenetres']); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full <?php echo e(isset($detail['type']) && $detail['type'] === '3_rails' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'); ?>">
                                        <?php echo e(isset($detail['type']) && $detail['type'] === '3_rails' ? '3 RAILS' : 'ALU 82'); ?>

                                    </span>
                                </td>
                                <?php if(isset($detail['type']) && $detail['type'] === '3_rails' && isset($detail['details'])): ?>
                                <!-- Affichage pour fenêtres 3 RAILS -->
                                <td class="px-3 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-blue-700">
                                        <?php echo e(number_format($detail['details']['rail'], 3, ',', ' ')); ?>

                                    </span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-indigo-700">
                                        <?php echo e(number_format($detail['details']['montant'], 3, ',', ' ')); ?>

                                    </span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-green-700">
                                        <?php echo e(number_format($detail['details']['butee'], 3, ',', ' ')); ?>

                                    </span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-teal-700">
                                        <?php echo e(number_format($detail['details']['poignee'], 3, ',', ' ')); ?>

                                    </span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-purple-700">
                                        <?php echo e(number_format($detail['details']['roulette'], 3, ',', ' ')); ?>

                                    </span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-pink-700">
                                        <?php echo e(number_format($detail['details']['tete'], 3, ',', ' ')); ?>

                                    </span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-orange-700">
                                        <?php echo e(number_format($detail['details']['moustiquaire'], 3, ',', ' ')); ?>

                                    </span>
                                </td>
                                <?php else: ?>
                                <!-- Affichage pour fenêtres ALU 82 -->
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                                        <?php echo e(number_format($detail['cadre'], 3, ',', ' ')); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-green-700">
                                        <?php echo e(number_format($detail['vento'], 3, ',', ' ')); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-purple-700">
                                        <?php echo e(number_format($detail['sikane'], 3, ',', ' ')); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-orange-700">
                                        <?php echo e(number_format($detail['moustiquaire'], 3, ',', ' ')); ?>

                                    </span>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Détails par ligne - Portes -->
            <?php if(isset($materials['portes_details']) && count($materials['portes_details']) > 0): ?>
            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-door-open mr-2" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                    Détails par Ligne de Porte
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-box mr-2"></i>Produit
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-ruler mr-2"></i>Dimensions
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-hashtag mr-2"></i>Nb Portes
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-tag mr-2"></i>Type
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-cube mr-2"></i>CADRE PORTE
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-cube mr-2"></i>BATTANT PORTE
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-cube mr-2"></i>DIVISION
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $materials['portes_details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="table-row-hover">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">
                                        <?php echo e($detail['line']->product->name ?? $detail['line']->description); ?>

                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">
                                        <?php echo e(number_format($detail['line']->width, 0)); ?> cm × <?php echo e(number_format($detail['line']->height, 0)); ?> cm
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                        <?php echo e($detail['nombre_portes']); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full <?php echo e($detail['type'] === '1_battant' ? 'bg-teal-100 text-teal-800' : 'bg-pink-100 text-pink-800'); ?>">
                                        <?php echo e($detail['type'] === '1_battant' ? '1 BATTANT' : '2 BATTANTS'); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-indigo-700">
                                        <?php echo e(number_format($detail['cadre_porte'], 3, ',', ' ')); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-teal-700">
                                        <?php echo e(number_format($detail['battant_porte'], 3, ',', ' ')); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-pink-700">
                                        <?php echo e(number_format($detail['division'], 3, ',', ' ')); ?>

                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Tableau récapitulatif avec prix -->
            <?php if(isset($materials['materiaux_avec_prix']) && count($materials['materiaux_avec_prix']) > 0): ?>
            <div class="mt-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-table mr-2" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                        Récapitulatif des Matériaux avec Prix
                    </h3>
                    <a href="<?php echo e(route('quotes.export-materials', ['quote_id' => $selectedQuote->id])); ?>" 
                       class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300 bg-green-600 hover:bg-green-700"
                       onmouseover="this.style.transform='translateY(-2px)'"
                       onmouseout="this.style.transform='translateY(0)'">
                        <i class="fas fa-file-excel mr-2"></i>Exporter en Excel
                    </a>
                </div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        <i class="fas fa-box mr-1"></i>Nom Matériau
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        <i class="fas fa-calculator mr-1"></i>Quantité
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        <i class="fas fa-tag mr-1"></i>Prix Unitaire (GNF)
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        <i class="fas fa-money-bill-wave mr-1"></i>Total par Ligne (GNF)
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__currentLoopData = $materials['materiaux_avec_prix']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $materiau): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">
                                            <?php echo e($materiau['nom']); ?>

                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            <?php echo e(number_format($materiau['quantite'], 3, ',', ' ')); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm font-bold text-gray-700">
                                            <?php echo e(number_format($materiau['prix_unitaire'], 0, ',', ' ')); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm font-bold" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                                            <?php echo e(number_format($materiau['total_ligne'], 0, ',', ' ')); ?>

                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot class="bg-gray-100">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-900">
                                        <i class="fas fa-calculator mr-2"></i>Total Général des Matériaux :
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-lg font-extrabold px-4 py-2 rounded-lg inline-block" 
                                              style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%); color: white;">
                                            <?php echo e(number_format($materials['total_general'] ?? 0, 0, ',', ' ')); ?> GNF
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(count($materials['fenetres_details']) == 0 && (!isset($materials['portes_details']) || count($materials['portes_details']) == 0)): ?>
            <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-6 text-center">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-3xl mb-3"></i>
                <p class="text-lg font-semibold text-gray-900">Aucune fenêtre ou porte trouvée dans ce devis</p>
                <p class="text-sm text-gray-600 mt-2">Ce devis ne contient pas de lignes de type "fenêtre" ou "porte" avec des dimensions valides.</p>
            </div>
            <?php endif; ?>
        <?php elseif($selectedQuote): ?>
            <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-6">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-3xl mb-3"></i>
                <p class="text-lg font-semibold text-gray-900 mb-2">Aucune fenêtre ou porte trouvée dans ce devis</p>
                <p class="text-sm text-gray-600 mb-4">Ce devis ne contient pas de lignes de type "fenêtre" ou "porte" avec des dimensions valides.</p>
                
                <?php if($materials && isset($materials['debug'])): ?>
                <div class="mt-4 p-4 bg-white rounded border border-yellow-300">
                    <p class="text-xs font-semibold text-gray-700 mb-2">Informations de débogage :</p>
                    <p class="text-xs text-gray-600 mb-1">Total lignes: <?php echo e($materials['debug']['total_lines']); ?></p>
                    <p class="text-xs text-gray-600 mb-3">Lignes produits: <?php echo e($materials['debug']['product_lines']); ?></p>
                    
                    <?php if(isset($materials['debug']['lines_checked']) && count($materials['debug']['lines_checked']) > 0): ?>
                    <div class="mt-3">
                        <p class="text-xs font-semibold text-gray-700 mb-2">Détails des lignes produits :</p>
                        <div class="space-y-2">
                            <?php $__currentLoopData = $materials['debug']['lines_checked']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lineInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-2 bg-gray-50 rounded text-xs">
                                <p><strong>Ligne #<?php echo e($lineInfo['id']); ?>:</strong> <?php echo e($lineInfo['product_name']); ?></p>
                                <p class="text-gray-600">Description: <?php echo e($lineInfo['description'] ?? 'N/A'); ?></p>
                                <p class="text-gray-600">Contient "fenêtre": 
                                    <span class="<?php echo e($lineInfo['has_fenetre'] ? 'text-green-600 font-bold' : 'text-red-600'); ?>">
                                        <?php echo e($lineInfo['has_fenetre'] ? 'Oui' : 'Non'); ?>

                                    </span>
                                </p>
                                <p class="text-gray-600">Contient "porte": 
                                    <span class="<?php echo e(isset($lineInfo['has_porte']) && $lineInfo['has_porte'] ? 'text-green-600 font-bold' : 'text-red-600'); ?>">
                                        <?php echo e(isset($lineInfo['has_porte']) && $lineInfo['has_porte'] ? 'Oui' : 'Non'); ?>

                                    </span>
                                </p>
                                <p class="text-gray-600">Dimensions valides: 
                                    <span class="<?php echo e($lineInfo['has_dimensions'] ? 'text-green-600 font-bold' : 'text-red-600'); ?>">
                                        <?php echo e($lineInfo['has_dimensions'] ? 'Oui' : 'Non'); ?>

                                    </span>
                                    <?php if(!$lineInfo['has_dimensions']): ?>
                                        (Largeur: <?php echo e($lineInfo['width'] ?? '0'); ?> cm, Hauteur: <?php echo e($lineInfo['height'] ?? '0'); ?> cm)
                                    <?php endif; ?>
                                </p>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <div class="mt-4 p-3 bg-white rounded border border-yellow-300">
                    <p class="text-xs font-semibold text-gray-700 mb-2">Vérifications nécessaires :</p>
                    <ul class="text-xs text-gray-600 list-disc list-inside space-y-1">
                        <li>Le produit doit contenir "fenêtre" ou "porte" dans son nom ou sa description</li>
                        <li>Pour les portes, spécifier "PORTE 1 BATTANT" ou "PORTE 2 BATTANTS"</li>
                        <li>La ligne doit être de type "produit"</li>
                        <li>Les dimensions (largeur et hauteur) doivent être renseignées et supérieures à 0</li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Si un quote_id est présent dans l'URL, déclencher automatiquement le calcul
    const urlParams = new URLSearchParams(window.location.search);
    const quoteId = urlParams.get('quote_id');
    
    if (quoteId) {
        // S'assurer que le select est bien sélectionné
        const selectElement = document.getElementById('quote_id');
        if (selectElement && selectElement.value !== quoteId) {
            selectElement.value = quoteId;
        }
        
        // Si les résultats ne sont pas encore affichés, soumettre automatiquement le formulaire
        // (cela se fait déjà côté serveur, mais on peut forcer le scroll vers les résultats)
        const resultsSection = document.querySelector('[id*="materials"]');
        if (resultsSection) {
            setTimeout(() => {
                resultsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 300);
        }
    }
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gdevis\resources\views/quotes/calculate-materials.blade.php ENDPATH**/ ?>