<?php $__env->startSection('title', 'Paramètres'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-slate-50">
        <h2 class="text-2xl font-bold text-gray-900">
            <i class="fas fa-cog mr-2 text-gray-600"></i>Paramètres de l'Entreprise
        </h2>
        <p class="mt-1 text-sm text-gray-600">Configurez les informations et l'apparence de votre application</p>
    </div>

        <form action="<?php echo e(route('settings.update')); ?>" method="POST" enctype="multipart/form-data" class="p-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- Informations de l'entreprise -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                    <i class="fas fa-building mr-2 text-blue-500"></i>Informations de l'Entreprise
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="company_name" class="block text-sm font-medium text-gray-700">Nom de l'entreprise *</label>
                        <input type="text" name="company_name" id="company_name" value="<?php echo e(old('company_name', $settings->company_name)); ?>"
                            class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all"
                            style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                    </div>

                    <div>
                        <label for="activity" class="block text-sm font-medium text-gray-700">Activité</label>
                        <input type="text" name="activity" id="activity" value="<?php echo e(old('activity', $settings->activity)); ?>"
                            class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all"
                            style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                        <input type="text" name="phone" id="phone" value="<?php echo e(old('phone', $settings->phone)); ?>"
                            class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all"
                            style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email (optionnel)</label>
                        <input type="email" name="email" id="email" value="<?php echo e(old('email', $settings->email)); ?>"
                            class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all"
                            style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                    </div>

                    <div>
                        <label for="rccm" class="block text-sm font-medium text-gray-700">RCCM (optionnel)</label>
                        <input type="text" name="rccm" id="rccm" value="<?php echo e(old('rccm', $settings->rccm)); ?>"
                            class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all"
                            style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                        <textarea name="address" id="address" rows="3"
                            class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all resize-y"
                            style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"><?php echo e(old('address', $settings->address)); ?></textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                        <?php if($settings->logo): ?>
                            <div class="mt-2 mb-4">
                                <img src="<?php echo e(asset('storage/' . $settings->logo)); ?>" alt="Logo" class="h-20 w-auto" 
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <p class="text-sm text-red-500 mt-2" style="display:none;">Logo non trouvé: <?php echo e($settings->logo); ?></p>
                                <p class="text-sm text-gray-500 mt-2">Logo actuel (chemin: <?php echo e($settings->logo); ?>)</p>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="logo" id="logo" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-1 text-sm text-gray-500">Formats acceptés: JPEG, PNG, JPG, GIF, SVG (max 2MB)</p>
                    </div>
                </div>
            </div>

            <!-- Couleurs de la plateforme -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                    <i class="fas fa-palette mr-2 text-purple-500"></i>Couleurs de la Plateforme
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">Couleur Principale</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="primary_color" id="primary_color" value="<?php echo e(old('primary_color', $settings->primary_color ?? '#3b82f6')); ?>"
                                class="h-10 w-20 rounded border-gray-300 cursor-pointer">
                            <input type="text" value="<?php echo e(old('primary_color', $settings->primary_color ?? '#3b82f6')); ?>" 
                                id="primary_color_text" readonly
                                class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-50">
                        </div>
                    </div>

                    <div>
                        <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-2">Couleur Secondaire</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="secondary_color" id="secondary_color" value="<?php echo e(old('secondary_color', $settings->secondary_color ?? '#1e40af')); ?>"
                                class="h-10 w-20 rounded border-gray-300 cursor-pointer">
                            <input type="text" value="<?php echo e(old('secondary_color', $settings->secondary_color ?? '#1e40af')); ?>" 
                                id="secondary_color_text" readonly
                                class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-50">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Couleurs d'impression -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                    <i class="fas fa-print mr-2 text-green-500"></i>Couleurs des Documents d'Impression
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="print_header_color" class="block text-sm font-medium text-gray-700 mb-2">Couleur des En-têtes</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="print_header_color" id="print_header_color" value="<?php echo e(old('print_header_color', $settings->print_header_color ?? '#14b8a6')); ?>"
                                class="h-10 w-20 rounded border-gray-300 cursor-pointer">
                            <input type="text" value="<?php echo e(old('print_header_color', $settings->print_header_color ?? '#14b8a6')); ?>" 
                                id="print_header_color_text" readonly
                                class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-50">
                        </div>
                    </div>

                    <div>
                        <label for="print_text_color" class="block text-sm font-medium text-gray-700 mb-2">Couleur du Texte (sur en-têtes)</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="print_text_color" id="print_text_color" value="<?php echo e(old('print_text_color', $settings->print_text_color ?? '#ffffff')); ?>"
                                class="h-10 w-20 rounded border-gray-300 cursor-pointer">
                            <input type="text" value="<?php echo e(old('print_text_color', $settings->print_text_color ?? '#ffffff')); ?>" 
                                id="print_text_color_text" readonly
                                class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-50">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 border-t pt-6">
                <a href="<?php echo e(route('quotes.index')); ?>" 
                   class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-2"></i>Annuler
                </a>
                <button type="submit" 
                        class="inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white transition-all hover:shadow-md"
                        style="background-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"
                        onmouseover="this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-save mr-2"></i>Enregistrer les Paramètres
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Synchroniser les champs couleur avec les inputs texte
    document.getElementById('primary_color').addEventListener('input', function(e) {
        document.getElementById('primary_color_text').value = e.target.value;
    });
    
    document.getElementById('secondary_color').addEventListener('input', function(e) {
        document.getElementById('secondary_color_text').value = e.target.value;
    });
    
    document.getElementById('print_header_color').addEventListener('input', function(e) {
        document.getElementById('print_header_color_text').value = e.target.value;
    });
    
    document.getElementById('print_text_color').addEventListener('input', function(e) {
        document.getElementById('print_text_color_text').value = e.target.value;
    });
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gdevis\resources\views/settings/index.blade.php ENDPATH**/ ?>