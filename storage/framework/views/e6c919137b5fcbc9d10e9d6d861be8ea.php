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
                <div class="grid gap-3 mb-6" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); align-items: stretch;">
                    <div class="bg-white border-2 border-blue-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">Total CADRE</label>
                        <div class="text-2xl font-bold whitespace-nowrap mb-1" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                            <?php echo e(number_format($materials['total_cadre'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">barres</p>
                    </div>
                    <div class="bg-white border-2 border-green-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">Total VENTO</label>
                        <div class="text-2xl font-bold text-green-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_vento'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">barres</p>
                    </div>
                    <div class="bg-white border-2 border-purple-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">Total SIKANE</label>
                        <div class="text-2xl font-bold text-purple-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_sikane'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">barres</p>
                    </div>
                    <div class="bg-white border-2 border-orange-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">Total MOUSTIQUAIRE</label>
                        <div class="text-2xl font-bold text-orange-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_moustiquaire'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">barres</p>
                    </div>
                    
                    <!-- Nouveaux matériaux ALU A82 -->
                    <?php if(isset($materials['total_fermeture_a82']) && $materials['total_fermeture_a82'] > 0): ?>
                    <div class="bg-white border-2 border-cyan-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">Fermeture A82</label>
                        <div class="text-2xl font-bold text-cyan-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_fermeture_a82'], 0, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">Paire</p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($materials['total_roulette_a82']) && $materials['total_roulette_a82'] > 0): ?>
                    <div class="bg-white border-2 border-pink-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">Roulette A82</label>
                        <div class="text-2xl font-bold text-pink-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_roulette_a82'], 0, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">Paire</p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($materials['total_roulette_moustiquaire']) && $materials['total_roulette_moustiquaire'] > 0): ?>
                    <div class="bg-white border-2 border-yellow-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">Roulette Moustiquaire</label>
                        <div class="text-2xl font-bold text-yellow-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_roulette_moustiquaire'], 0, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">Paire</p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($materials['total_equaire_moustiquaire']) && $materials['total_equaire_moustiquaire'] > 0): ?>
                    <div class="bg-white border-2 border-indigo-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">Equaire Moustiquaire</label>
                        <div class="text-2xl font-bold text-indigo-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_equaire_moustiquaire'], 0, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">Paire</p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($materials['total_joint_vitrage_m']) && $materials['total_joint_vitrage_m'] > 0): ?>
                    <div class="bg-white border-2 border-amber-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">Joint Vitrage</label>
                        <div class="text-2xl font-bold text-amber-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_joint_vitrage_m'], 2, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">m</p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($materials['total_joint_moustiquaire_m']) && $materials['total_joint_moustiquaire_m'] > 0): ?>
                    <div class="bg-white border-2 border-rose-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">Joint Moustiquaire</label>
                        <div class="text-2xl font-bold text-rose-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_joint_moustiquaire_m'], 2, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">m</p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($materials['total_vitre']) && $materials['total_vitre'] > 0): ?>
                    <div class="bg-white border-2 border-emerald-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">Vitre</label>
                        <div class="text-2xl font-bold text-emerald-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_vitre'], 2, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">feuille</p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if($has3Rails && isset($materials['total_rail'])): ?>
                <!-- Totaux pour fenêtres 3 RAILS -->
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-list-check mr-2" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                    Totaux des Matériaux - Fenêtres 3 Rails
                </h3>
                <div class="grid gap-3 mb-6" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); align-items: stretch;">
                    <div class="bg-white border-2 border-blue-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">RAIL</label>
                        <div class="text-2xl font-bold text-blue-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_rail'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">barres</p>
                    </div>
                    <div class="bg-white border-2 border-indigo-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">MONTANT</label>
                        <div class="text-2xl font-bold text-indigo-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_montant'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">barres</p>
                    </div>
                    <div class="bg-white border-2 border-green-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">BUTÉE</label>
                        <div class="text-2xl font-bold text-green-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_butee'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">barres</p>
                    </div>
                    <div class="bg-white border-2 border-teal-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">POIGNÉE</label>
                        <div class="text-2xl font-bold text-teal-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_poignee'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">barres</p>
                    </div>
                    <div class="bg-white border-2 border-purple-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">ROULETTE</label>
                        <div class="text-2xl font-bold text-purple-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_roulette'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">barres</p>
                    </div>
                    <div class="bg-white border-2 border-pink-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">TÊTE</label>
                        <div class="text-2xl font-bold text-pink-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_tete'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">barres</p>
                    </div>
                    <div class="bg-white border-2 border-orange-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">MOUSTIQUAIRE</label>
                        <div class="text-2xl font-bold text-orange-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_moustiquaire_3rails'] ?? 0, 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">barres</p>
                    </div>
                    
                    <!-- Nouveaux matériaux pour fenêtres 3 RAILS -->
                    <?php if(isset($materials['total_fermeture_3rails']) && $materials['total_fermeture_3rails'] > 0): ?>
                    <div class="bg-white border-2 border-cyan-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">Fermeture 3R</label>
                        <div class="text-2xl font-bold text-cyan-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_fermeture_3rails'], 0, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">Paire</p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($materials['total_roulette_vento_3rails']) && $materials['total_roulette_vento_3rails'] > 0): ?>
                    <div class="bg-white border-2 border-lime-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">Roulette Vento 3R</label>
                        <div class="text-2xl font-bold text-lime-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_roulette_vento_3rails'], 0, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">Paire</p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($materials['total_roulette_moustiquaire_3rails']) && $materials['total_roulette_moustiquaire_3rails'] > 0): ?>
                    <div class="bg-white border-2 border-yellow-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">Roul. Moust. 3R</label>
                        <div class="text-2xl font-bold text-yellow-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_roulette_moustiquaire_3rails'], 0, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">Paire</p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($materials['total_equaire_moustiquaire_3rails']) && $materials['total_equaire_moustiquaire_3rails'] > 0): ?>
                    <div class="bg-white border-2 border-violet-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">Equaire Moust. 3R</label>
                        <div class="text-2xl font-bold text-violet-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_equaire_moustiquaire_3rails'], 0, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">Paire</p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($materials['total_brosse_libanais_m']) && $materials['total_brosse_libanais_m'] > 0): ?>
                    <div class="bg-white border-2 border-sky-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">Brosse Libanais</label>
                        <div class="text-2xl font-bold text-sky-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_brosse_libanais_m'], 2, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">m</p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($materials['total_joint_vitrage_3rails_m']) && $materials['total_joint_vitrage_3rails_m'] > 0): ?>
                    <div class="bg-white border-2 border-amber-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">Joint Vitrage 3R</label>
                        <div class="text-2xl font-bold text-amber-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_joint_vitrage_3rails_m'], 2, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">m</p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($materials['total_joint_moustiquaire_3rails_m']) && $materials['total_joint_moustiquaire_3rails_m'] > 0): ?>
                    <div class="bg-white border-2 border-fuchsia-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">Joint Moust. 3R</label>
                        <div class="text-2xl font-bold text-fuchsia-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_joint_moustiquaire_3rails_m'], 2, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">m</p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($materials['total_grillage_moustiquaire_3rails_m']) && $materials['total_grillage_moustiquaire_3rails_m'] > 0): ?>
                    <div class="bg-white border-2 border-emerald-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">Grillage Moust. 3R</label>
                        <div class="text-2xl font-bold text-emerald-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_grillage_moustiquaire_3rails_m'], 2, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">m</p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($materials['total_vitre_3rails']) && $materials['total_vitre_3rails'] > 0): ?>
                    <div class="bg-white border-2 border-slate-200 rounded-lg p-3.5 shadow-md flex flex-col justify-between">
                        <label class="block text-xs font-semibold text-gray-700 mb-2 whitespace-nowrap">Vitre 3R</label>
                        <div class="text-2xl font-bold text-slate-700 whitespace-nowrap mb-1">
                            <?php echo e(number_format($materials['total_vitre_3rails'], 2, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 font-medium">feuille</p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <?php endif; ?>
                
                <?php if(isset($materials['total_cadre_porte']) && ($materials['total_cadre_porte'] > 0 || $materials['total_battant_porte'] > 0 || $materials['total_division'] > 0 || ($materials['total_brosse_a82_porte_m'] ?? 0) > 0 || ($materials['total_pomelles'] ?? 0) > 0 || ($materials['total_vitre_porte'] ?? 0) > 0 || ($materials['total_joint_vitrage_porte_m'] ?? 0) > 0)): ?>
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-door-open mr-2" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                    Totaux des Matériaux - Portes
                </h3>
                <div class="grid gap-2.5 mb-6" style="grid-template-columns: repeat(auto-fit, minmax(140px, max-content)); justify-content: start;">
                    <?php if(isset($materials['total_cadre_porte']) && $materials['total_cadre_porte'] > 0): ?>
                    <div class="bg-white border-2 border-indigo-200 rounded-lg p-2.5 shadow-md flex flex-col min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-600 mb-1 whitespace-nowrap">Total CADRE PORTE</label>
                        <div class="text-lg font-bold text-indigo-700 whitespace-nowrap">
                            <?php echo e(number_format($materials['total_cadre_porte'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">barres</p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($materials['total_battant_porte']) && $materials['total_battant_porte'] > 0): ?>
                    <div class="bg-white border-2 border-teal-200 rounded-lg p-2.5 shadow-md flex flex-col min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-600 mb-1 whitespace-nowrap">Total BATTANT PORTE</label>
                        <div class="text-lg font-bold text-teal-700 whitespace-nowrap">
                            <?php echo e(number_format($materials['total_battant_porte'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">barres</p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($materials['total_division']) && $materials['total_division'] > 0): ?>
                    <div class="bg-white border-2 border-pink-200 rounded-lg p-2.5 shadow-md flex flex-col min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-600 mb-1 whitespace-nowrap">Total DIVISION</label>
                        <div class="text-lg font-bold text-pink-700 whitespace-nowrap">
                            <?php echo e(number_format($materials['total_division'], 3, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">barres</p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($materials['total_brosse_a82_porte_m']) && $materials['total_brosse_a82_porte_m'] > 0): ?>
                    <div class="bg-white border-2 border-cyan-200 rounded-lg p-2.5 shadow-md flex flex-col min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-600 mb-1 whitespace-nowrap">Brosse A82</label>
                        <div class="text-lg font-bold text-cyan-700 whitespace-nowrap">
                            <?php echo e(number_format($materials['total_brosse_a82_porte_m'], 2, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">m</p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($materials['total_pomelles']) && $materials['total_pomelles'] > 0): ?>
                    <div class="bg-white border-2 border-purple-200 rounded-lg p-2.5 shadow-md flex flex-col min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-600 mb-1 whitespace-nowrap">Pomelles</label>
                        <div class="text-lg font-bold text-purple-700 whitespace-nowrap">
                            <?php echo e(number_format($materials['total_pomelles'], 0, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">unité</p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($materials['total_vitre_porte']) && $materials['total_vitre_porte'] > 0): ?>
                    <div class="bg-white border-2 border-emerald-200 rounded-lg p-2.5 shadow-md flex flex-col min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-600 mb-1 whitespace-nowrap">Vitre</label>
                        <div class="text-lg font-bold text-emerald-700 whitespace-nowrap">
                            <?php echo e(number_format($materials['total_vitre_porte'], 2, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">feuille</p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($materials['total_joint_vitrage_porte_m']) && $materials['total_joint_vitrage_porte_m'] > 0): ?>
                    <div class="bg-white border-2 border-amber-200 rounded-lg p-2.5 shadow-md flex flex-col min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-600 mb-1 whitespace-nowrap">Joint Vitrage</label>
                        <div class="text-lg font-bold text-amber-700 whitespace-nowrap">
                            <?php echo e(number_format($materials['total_joint_vitrage_porte_m'], 2, ',', ' ')); ?>

                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">m</p>
                    </div>
                    <?php endif; ?>
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
                                <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-box mr-1 text-[9px]"></i>Produit
                                </th>
                                <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-ruler mr-1 text-[9px]"></i>Dimensions
                                </th>
                                <th class="px-4 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-hashtag mr-1 text-[9px]"></i>Nb
                                </th>
                                <th class="px-4 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-tag mr-1 text-[9px]"></i>Type
                                </th>
                                <?php if(isset($materials['fenetres_details'][0]['type']) && $materials['fenetres_details'][0]['type'] === '3_rails'): ?>
                                <!-- Colonnes pour fenêtres 3 RAILS -->
                                <th class="px-3 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">RAIL</th>
                                <th class="px-3 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">MONTANT</th>
                                <th class="px-3 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">BUTÉE</th>
                                <th class="px-3 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">POIGNÉE</th>
                                <th class="px-3 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">ROULETTE</th>
                                <th class="px-3 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">TÊTE</th>
                                <th class="px-3 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">MOUSTIQUAIRE</th>
                                <!-- Nouveaux matériaux pour fenêtres 3 RAILS -->
                                <th class="px-2 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">Ferm. 3R</th>
                                <th class="px-2 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">Roul. Vento</th>
                                <th class="px-2 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">Roul. Moust.</th>
                                <th class="px-2 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">Equaire</th>
                                <th class="px-2 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">Brosse Lib.</th>
                                <th class="px-2 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">Joint Vit.</th>
                                <th class="px-2 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">Joint Moust.</th>
                                <th class="px-2 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">Grillage</th>
                                <th class="px-2 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">Vitre 3R</th>
                                <?php else: ?>
                                <!-- Colonnes pour fenêtres ALU 82 -->
                                <th class="px-3 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-cube mr-1 text-[9px]"></i>CADRE
                                </th>
                                <th class="px-3 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-cube mr-1 text-[9px]"></i>VENTO
                                </th>
                                <th class="px-3 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-cube mr-1 text-[9px]"></i>SIKANE
                                </th>
                                <th class="px-3 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-cube mr-1 text-[9px]"></i>MOUSTIQUAIRE
                                </th>
                                <!-- Nouveaux matériaux ALU A82 -->
                                <th class="px-2 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">Ferm. A82</th>
                                <th class="px-2 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">Roul. A82</th>
                                <th class="px-2 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">Roul. Moust.</th>
                                <th class="px-2 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">Equaire</th>
                                <th class="px-2 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">Joint Vit.</th>
                                <th class="px-2 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">Joint Moust.</th>
                                <th class="px-2 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">Vitre</th>
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
                                    <p class="text-xs text-gray-400">barres</p>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-indigo-700">
                                        <?php echo e(number_format($detail['details']['montant'], 3, ',', ' ')); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">barres</p>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-green-700">
                                        <?php echo e(number_format($detail['details']['butee'], 3, ',', ' ')); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">barres</p>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-teal-700">
                                        <?php echo e(number_format($detail['details']['poignee'], 3, ',', ' ')); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">barres</p>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-purple-700">
                                        <?php echo e(number_format($detail['details']['roulette'], 3, ',', ' ')); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">barres</p>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-pink-700">
                                        <?php echo e(number_format($detail['details']['tete'], 3, ',', ' ')); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">barres</p>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-orange-700">
                                        <?php echo e(number_format($detail['details']['moustiquaire'], 3, ',', ' ')); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">barres</p>
                                </td>
                                <!-- Nouveaux matériaux pour fenêtres 3 RAILS -->
                                <td class="px-2 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs font-semibold text-cyan-700">
                                        <?php echo e(isset($detail['fermeture_3rails']) ? number_format($detail['fermeture_3rails'], 0, ',', ' ') : '0'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">Paire</p>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs font-semibold text-lime-700">
                                        <?php echo e(isset($detail['roulette_vento_3rails']) ? number_format($detail['roulette_vento_3rails'], 0, ',', ' ') : '0'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">Paire</p>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs font-semibold text-yellow-700">
                                        <?php echo e(isset($detail['roulette_moustiquaire_3rails']) ? number_format($detail['roulette_moustiquaire_3rails'], 0, ',', ' ') : '0'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">Paire</p>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs font-semibold text-violet-700">
                                        <?php echo e(isset($detail['equaire_moustiquaire_3rails']) ? number_format($detail['equaire_moustiquaire_3rails'], 0, ',', ' ') : '0'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">Paire</p>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs font-semibold text-sky-700">
                                        <?php echo e(isset($detail['brosse_libanais_m']) ? number_format($detail['brosse_libanais_m'], 2, ',', ' ') : '0'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">m</p>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs font-semibold text-amber-700">
                                        <?php echo e(isset($detail['joint_vitrage_3rails_m']) ? number_format($detail['joint_vitrage_3rails_m'], 2, ',', ' ') : '0'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">m</p>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs font-semibold text-fuchsia-700">
                                        <?php echo e(isset($detail['joint_moustiquaire_3rails_m']) ? number_format($detail['joint_moustiquaire_3rails_m'], 2, ',', ' ') : '0'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">m</p>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs font-semibold text-emerald-700">
                                        <?php echo e(isset($detail['grillage_moustiquaire_3rails_m']) ? number_format($detail['grillage_moustiquaire_3rails_m'], 2, ',', ' ') : '0'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">m</p>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs font-semibold text-slate-700">
                                        <?php echo e(isset($detail['vitre_3rails']) ? number_format($detail['vitre_3rails'], 2, ',', ' ') : '0'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">feuille</p>
                                </td>
                                <?php else: ?>
                                <!-- Affichage pour fenêtres ALU 82 -->
                                <td class="px-3 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                                        <?php echo e(number_format($detail['cadre'], 3, ',', ' ')); ?>

                                    </span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-green-700">
                                        <?php echo e(number_format($detail['vento'], 3, ',', ' ')); ?>

                                    </span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-purple-700">
                                        <?php echo e(number_format($detail['sikane'], 3, ',', ' ')); ?>

                                    </span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-orange-700">
                                        <?php echo e(number_format($detail['moustiquaire'], 3, ',', ' ')); ?>

                                    </span>
                                </td>
                                <!-- Nouveaux matériaux ALU A82 -->
                                <td class="px-2 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs font-semibold text-cyan-700">
                                        <?php echo e(isset($detail['fermeture_a82']) ? number_format($detail['fermeture_a82'], 0, ',', ' ') : '0'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">Paire</p>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs font-semibold text-pink-700">
                                        <?php echo e(isset($detail['roulette_a82']) ? number_format($detail['roulette_a82'], 0, ',', ' ') : '0'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">Paire</p>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs font-semibold text-yellow-700">
                                        <?php echo e(isset($detail['roulette_moustiquaire']) ? number_format($detail['roulette_moustiquaire'], 0, ',', ' ') : '0'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">Paire</p>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs font-semibold text-indigo-700">
                                        <?php echo e(isset($detail['equaire_moustiquaire']) ? number_format($detail['equaire_moustiquaire'], 0, ',', ' ') : '0'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">Paire</p>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs font-semibold text-teal-700">
                                        <?php echo e(isset($detail['brosse_a82_m']) ? number_format($detail['brosse_a82_m'], 2, ',', ' ') : '0,00'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">m</p>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs font-semibold text-amber-700">
                                        <?php echo e(isset($detail['joint_vitrage_m']) ? number_format($detail['joint_vitrage_m'], 2, ',', ' ') : '0,00'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">m</p>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs font-semibold text-rose-700">
                                        <?php echo e(isset($detail['joint_moustiquaire_m']) ? number_format($detail['joint_moustiquaire_m'], 2, ',', ' ') : '0,00'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">m</p>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs font-semibold text-emerald-700">
                                        <?php echo e(isset($detail['vitre']) ? number_format($detail['vitre'], 2, ',', ' ') : '0,00'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">feuille</p>
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
                                <th class="px-6 py-2 text-left text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-box mr-1 text-[9px]"></i>Produit
                                </th>
                                <th class="px-6 py-2 text-left text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-ruler mr-1 text-[9px]"></i>Dimensions
                                </th>
                                <th class="px-6 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-hashtag mr-1 text-[9px]"></i>Nb Portes
                                </th>
                                <th class="px-6 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-tag mr-1 text-[9px]"></i>Type
                                </th>
                                <th class="px-6 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-cube mr-1 text-[9px]"></i>CADRE PORTE
                                </th>
                                <th class="px-6 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-cube mr-1 text-[9px]"></i>BATTANT PORTE
                                </th>
                                <th class="px-6 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-cube mr-1 text-[9px]"></i>DIVISION
                                </th>
                                <th class="px-6 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-brush mr-1 text-[9px]"></i>Brosse A82
                                </th>
                                <th class="px-6 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-link mr-1 text-[9px]"></i>Pomelles
                                </th>
                                <th class="px-6 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-window-maximize mr-1 text-[9px]"></i>Vitre
                                </th>
                                <th class="px-6 py-2 text-center text-[10px] font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-seal mr-1 text-[9px]"></i>Joint Vitrage
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
                                    <p class="text-xs text-gray-400">barres</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-teal-700">
                                        <?php echo e(number_format($detail['battant_porte'], 3, ',', ' ')); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">barres</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-pink-700">
                                        <?php echo e(number_format($detail['division'], 3, ',', ' ')); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">barres</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-semibold text-cyan-700">
                                        <?php echo e(isset($detail['brosse_a82_m']) ? number_format($detail['brosse_a82_m'], 2, ',', ' ') : '0'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">m</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-semibold text-purple-700">
                                        <?php echo e(isset($detail['pomelles']) ? number_format($detail['pomelles'], 0, ',', ' ') : '0'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">unité</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-semibold text-emerald-700">
                                        <?php echo e(isset($detail['vitre_m2']) ? number_format($detail['vitre_m2'], 2, ',', ' ') : '0'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">feuille</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-semibold text-amber-700">
                                        <?php echo e(isset($detail['joint_vitrage_m']) ? number_format($detail['joint_vitrage_m'], 2, ',', ' ') : '0'); ?>

                                    </span>
                                    <p class="text-xs text-gray-400">m</p>
                                </td>
                                    <p class="text-xs text-gray-400">feuille</p>
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
                                        <i class="fas fa-ruler mr-1"></i>Unité
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
                                        <span class="text-sm text-gray-600">
                                            <?php echo e($materiau['unite'] ?? '-'); ?>

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
                                    <td colspan="4" class="px-6 py-4 text-right text-sm font-bold text-gray-900">
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