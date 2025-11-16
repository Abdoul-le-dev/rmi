<?php $__env->startSection('content'); ?>
    <section class="section">
        <div class="section-header">
            <h1><?php echo e(trans('admin/main.list_promo')); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(getAdminPanelUrl()); ?>"><?php echo e(trans('admin/main.dashboard')); ?></a>
                </div>
                <div class="breadcrumb-item"><?php echo e(trans('admin/main.list_promo')); ?></div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped font-14">
                                <tr>
                                    <th class="text-left"><?php echo e(trans('admin/main.title')); ?></th>
                                    <th class="text-center"><?php echo e(trans('admin/main.percentage')); ?></th>
                                    <th class="text-center"><?php echo e(trans('admin/main.start_date')); ?></th>
                                    <th class="text-center"><?php echo e(trans('admin/main.end_date')); ?></th>
                                    <th><?php echo e(trans('admin/main.actions')); ?></th>
                                </tr>
                                <?php $__currentLoopData = $promos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="text-left"><?php echo e($promo->title); ?></td>
                                        <td class="text-center"><?php echo e($promo->percentage); ?>%</td>
                                        <td class="text-center"><?php echo e($promo->start_date); ?></td>
                                        <td class="text-center"><?php echo e($promo->end_date); ?></td>
                                        <td>
                                            <a href="<?php echo e(getAdminPanelUrl()); ?>/financial/promo/<?php echo e($promo->id); ?>/edit" class="btn-sm" data-toggle="tooltip" data-placement="top" title="<?php echo e(trans('admin/main.edit')); ?>">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="<?php echo e(getAdminPanelUrl()); ?>/financial/promo/<?php echo e($promo->id); ?>/delete" class="btn-sm" data-toggle="tooltip" data-placement="top" title="<?php echo e(trans('admin/main.delete')); ?>">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/rmi/resources/views/admin/financial/promo/lists.blade.php ENDPATH**/ ?>