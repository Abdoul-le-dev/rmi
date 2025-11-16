<?php $__env->startSection('content'); ?>
    <section class="section">
        <div class="section-header">
            <h1><?php echo e(trans('admin/main.purchase_packages')); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(getAdminPanelUrl()); ?>"><?php echo e(trans('admin/main.dashboard')); ?></a>
                </div>
                <div class="breadcrumb-item"><?php echo e(trans('admin/main.purchase_packages')); ?></div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <!-- <th>#</th> -->
                                        <th class="text-left"><?php echo e(trans('admin/main.title')); ?></th>
                                        <th class="text-left"><?php echo e(trans('admin/main.sales')); ?></th>
                                        <th class="text-center"><?php echo e(trans('admin/main.price')); ?></th>
                                        <th class="text-center"><?php echo e(trans('admin/main.created_at')); ?></th>
                                        <th><?php echo e(trans('admin/main.actions')); ?></th>
                                    </tr>

                                    <?php $__currentLoopData = $purchasemodel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchasemodels): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <!-- <td>
                                                <img src="<?php echo e($purchasemodels->icon); ?>" width="50" height="50" alt="">
                                            </td> -->
                                            <td class="text-left"><?php echo e($purchasemodels->title); ?></td>
                                            <td class="text-left"><?php echo e($purchasemodels->purchase_sales_count); ?></td>
                                            <td class="text-center"><?php echo e(handlePrice($purchasemodels->price)); ?></td>

                                            <td class="text-center"><?php echo e(dateTimeFormat($purchasemodels->created_at, 'Y M j | H:i')); ?></td>
                                            <td>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_subscribe_edit')): ?>
                                                    <a href="<?php echo e(getAdminPanelUrl()); ?>/financial/purchase-model/<?php echo e($purchasemodels->id); ?>/edit" class="btn-sm" data-toggle="tooltip" data-placement="top" title="<?php echo e(trans('admin/main.edit')); ?>">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_subscribe_delete')): ?>
                                                    <?php echo $__env->make('admin.includes.delete_button',['url' => getAdminPanelUrl().'/financial/purchase-model/'. $purchasemodels->id.'/delete','btnClass' => 'btn-sm'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            <?php echo e($purchasemodel->appends(request()->input())->links()); ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h5><?php echo e(trans('admin/main.hints')); ?></h5></div>
            <div class="row">
                <div class="col-md-6">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold"><?php echo e(trans('admin/main.subscribes_list_hint_title_1')); ?></div>
                        <div class=" text-small font-600-bold"><?php echo e(trans('admin/main.subscribes_list_hint_description_1')); ?></div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold"><?php echo e(trans('admin/main.subscribes_list_hint_title_2')); ?></div>
                        <div class=" text-small font-600-bold"><?php echo e(trans('admin/main.subscribes_list_hint_description_2')); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/rmi/resources/views/admin/financial/purchase/lists.blade.php ENDPATH**/ ?>