

<?php $__env->startSection('title', 'Modifier Matériau'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">
    <div class="card-modern overflow-hidden">
        <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-r" 
             style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?>15 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?>15 100%);">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                <div class="p-2 rounded-lg mr-3" style="background: linear-gradient(135deg, <?php echo e($settings->primary_color ?? '#3b82f6'); ?> 0%, <?php echo e($settings->secondary_color ?? '#1e40af'); ?> 100%);">
                    <i class="fas fa-tools text-white"></i>
                </div>
                Modifier Matériau
            </h2>
            <p class="mt-2 text-sm text-gray-600">Modifiez les informations du matériau</p>
        </div>

        <form action="<?php echo e(route('materials.update', $material)); ?>" method="POST" class="p-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-box mr-1 text-gray-400"></i>Nom du matériau *
                </label>
                <input type="text" name="nom" id="nom" value="<?php echo e(old('nom', $material->nom)); ?>" required
                    class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"
                    placeholder="Ex: Cadre, Vento, Sikane">
                <?php $__errorArgs = ['nom'];
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

            <div class="mt-6">
                <label for="prix_unitaire" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tag mr-1 text-gray-400"></i>Prix Unitaire (GNF) *
                </label>
                <input type="number" name="prix_unitaire" id="prix_unitaire" value="<?php echo e(old('prix_unitaire', $material->prix_unitaire)); ?>" 
                    step="0.01" min="0" required
                    class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all <?php $__errorArgs = ['prix_unitaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"
                    placeholder="Ex: 250">
                <?php $__errorArgs = ['prix_unitaire'];
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

            <div class="mt-6">
                <label for="unite" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-ruler mr-1 text-gray-400"></i>Unité *
                </label>
                <input type="text" name="unite" id="unite" value="<?php echo e(old('unite', $material->unite)); ?>" required
                    class="block w-full rounded-lg border-2 border-gray-400 bg-white px-4 py-3 text-gray-900 text-base shadow-sm focus:ring-2 focus:border-transparent transition-all <?php $__errorArgs = ['unite'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    style="focus:ring-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"
                    placeholder="Ex: barre, mètre, kg">
                <?php $__errorArgs = ['unite'];
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

            <div class="mt-6 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 border-t pt-6">
                <a href="<?php echo e(route('materials.index')); ?>" 
                   class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-2"></i>Annuler
                </a>
                <button type="submit" 
                        class="inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white transition-all hover:shadow-md"
                        style="background-color: <?php echo e($settings->primary_color ?? '#3b82f6'); ?>;"
                        onmouseover="this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-save mr-2"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gdevis\resources\views/materials/edit.blade.php ENDPATH**/ ?>