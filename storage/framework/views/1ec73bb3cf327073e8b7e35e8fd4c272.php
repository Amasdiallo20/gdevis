<?php $__env->startSection('title', 'Détails Client'); ?>

<?php $__env->startSection('content'); ?>
<div class="card-modern overflow-hidden">
    <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
         style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>15 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>15 100%);">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                    <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                        <i class="fas fa-user-circle text-white"></i>
                    </div>
                    Détails du Client
                </h2>
                <p class="mt-2 text-sm text-gray-600">Informations complètes du client</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="<?php echo e(route('clients.edit', $client)); ?>" 
                   class="btn-primary inline-flex items-center justify-center px-5 py-2.5 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300"
                   onmouseover="this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-edit mr-2"></i>Modifier
                </a>
                <a href="<?php echo e(route('clients.index')); ?>" 
                   class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 rounded-lg shadow-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-all duration-300"
                   onmouseover="this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>
    
    <div class="px-6 py-6">
        <!-- Informations principales -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
            <!-- Carte Informations de contact -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-address-card mr-2" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                    Informations de Contact
                </h3>
                <dl class="space-y-4">
                    <div class="flex items-start">
                        <dt class="text-sm font-semibold text-gray-600 w-28 flex-shrink-0">
                            <i class="fas fa-user mr-2 text-gray-400"></i>Nom:
                        </dt>
                        <dd class="text-sm font-bold text-gray-900"><?php echo e($client->name); ?></dd>
                    </div>
                    <?php if($client->email): ?>
                    <div class="flex items-start">
                        <dt class="text-sm font-semibold text-gray-600 w-28 flex-shrink-0">
                            <i class="fas fa-envelope mr-2 text-gray-400"></i>Email:
                        </dt>
                        <dd class="text-sm text-gray-900 break-words"><?php echo e($client->email); ?></dd>
                    </div>
                    <?php endif; ?>
                    <?php if($client->phone): ?>
                    <div class="flex items-start">
                        <dt class="text-sm font-semibold text-gray-600 w-28 flex-shrink-0">
                            <i class="fas fa-phone mr-2 text-gray-400"></i>Téléphone:
                        </dt>
                        <dd class="text-sm text-gray-900"><?php echo e($client->phone); ?></dd>
                    </div>
                    <?php endif; ?>
                </dl>
            </div>

            <!-- Carte Adresse -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-6 rounded-xl border border-purple-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-map-marker-alt mr-2" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
                    Adresse
                </h3>
                <dl class="space-y-4">
                    <?php if($client->address): ?>
                    <div class="flex items-start">
                        <dt class="text-sm font-semibold text-gray-600 w-28 flex-shrink-0">
                            <i class="fas fa-home mr-2 text-gray-400"></i>Adresse:
                        </dt>
                        <dd class="text-sm text-gray-900"><?php echo e($client->address); ?></dd>
                    </div>
                    <?php endif; ?>
                    <?php if($client->city): ?>
                    <div class="flex items-start">
                        <dt class="text-sm font-semibold text-gray-600 w-28 flex-shrink-0">
                            <i class="fas fa-city mr-2 text-gray-400"></i>Ville:
                        </dt>
                        <dd class="text-sm text-gray-900"><?php echo e($client->city); ?></dd>
                    </div>
                    <?php endif; ?>
                    <?php if($client->postal_code): ?>
                    <div class="flex items-start">
                        <dt class="text-sm font-semibold text-gray-600 w-28 flex-shrink-0">
                            <i class="fas fa-mail-bulk mr-2 text-gray-400"></i>Code Postal:
                        </dt>
                        <dd class="text-sm text-gray-900"><?php echo e($client->postal_code); ?></dd>
                    </div>
                    <?php endif; ?>
                    <?php if($client->country): ?>
                    <div class="flex items-start">
                        <dt class="text-sm font-semibold text-gray-600 w-28 flex-shrink-0">
                            <i class="fas fa-globe mr-2 text-gray-400"></i>Pays:
                        </dt>
                        <dd class="text-sm text-gray-900"><?php echo e($client->country); ?></dd>
                    </div>
                    <?php endif; ?>
                </dl>
            </div>
        </div>

        <?php if($client->quotes->count() > 0): ?>
        <div class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                        <i class="fas fa-file-invoice text-white"></i>
                    </div>
                    Devis associés
                    <span class="ml-3 px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-700">
                        <?php echo e($client->quotes->count()); ?>

                    </span>
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fas fa-hashtag mr-2"></i>Numéro
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fas fa-calendar mr-2"></i>Date
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fas fa-info-circle mr-2"></i>Statut
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fas fa-cog mr-2"></i>Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $client->quotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="table-row-hover">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-lg flex items-center justify-center mr-3"
                                         style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                                        <i class="fas fa-file-invoice text-white text-xs"></i>
                                    </div>
                                    <div class="text-sm font-bold text-gray-900"><?php echo e($quote->quote_number); ?></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <i class="fas fa-calendar-alt mr-2 text-gray-400"></i><?php echo e($quote->date->format('d/m/Y')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                    $statusConfig = [
                                        'draft' => ['label' => 'Brouillon', 'class' => 'bg-gray-100 text-gray-800 border-gray-200'],
                                        'sent' => ['label' => 'Envoyé', 'class' => 'bg-blue-100 text-blue-800 border-blue-200'],
                                        'accepted' => ['label' => 'Accepté', 'class' => 'bg-green-100 text-green-800 border-green-200'],
                                        'validated' => ['label' => 'Validé', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-200'],
                                        'rejected' => ['label' => 'Refusé', 'class' => 'bg-red-100 text-red-800 border-red-200'],
                                    ];
                                    $status = $statusConfig[$quote->status] ?? ['label' => ucfirst($quote->status), 'class' => 'bg-gray-100 text-gray-800 border-gray-200'];
                                ?>
                                <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-lg shadow-sm border <?php echo e($status['class']); ?>">
                                    <?php echo e($status['label']); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="<?php echo e(route('quotes.show', $quote)); ?>" 
                                   class="text-blue-600 hover:text-blue-800 p-2.5 rounded-lg hover:bg-blue-50 transition-all duration-200 transform hover:scale-110 inline-flex items-center"
                                   title="Voir le devis">
                                    <i class="fas fa-eye mr-1"></i>Voir
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php else: ?>
        <div class="mt-8 text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4"
                 style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>20 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>20 100%);">
                <i class="fas fa-file-invoice text-4xl" style="color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"></i>
            </div>
            <p class="text-lg font-bold text-gray-900">Aucun devis associé</p>
            <p class="text-sm text-gray-500 mt-2">Ce client n'a pas encore de devis</p>
            <a href="<?php echo e(route('quotes.create')); ?>?client_id=<?php echo e($client->id); ?>" 
               class="mt-4 btn-primary inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white transition-all duration-300"
               onmouseover="this.style.transform='translateY(-2px)'"
               onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-plus mr-2"></i>Créer un devis pour ce client
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>









<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gdevis\resources\views/clients/show.blade.php ENDPATH**/ ?>