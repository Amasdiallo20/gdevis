

<?php $__env->startSection('title', 'Détails Produit'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-900">Détails du Produit</h2>
            <div class="space-x-2">
                <a href="<?php echo e(route('products.edit', $product)); ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-edit mr-2"></i>Modifier
                </a>
                <a href="<?php echo e(route('products.index')); ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Retour
                </a>
            </div>
        </div>

        <dl class="grid grid-cols-1 gap-x-4 gap-y-6">
            <div>
                <dt class="text-sm font-medium text-gray-500">Nom</dt>
                <dd class="mt-1 text-sm text-gray-900"><?php echo e($product->name); ?></dd>
            </div>
        </dl>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gdevis\resources\views/products/show.blade.php ENDPATH**/ ?>