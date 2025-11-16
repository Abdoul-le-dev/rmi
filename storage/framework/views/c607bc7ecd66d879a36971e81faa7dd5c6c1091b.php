<?php $__env->startSection('content'); ?>
    <section class="section">
        <div class="section-header">
            <h1><?php echo e(trans('admin/main.new_promo')); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(getAdminPanelUrl()); ?>"><?php echo e(trans('admin/main.dashboard')); ?></a>
                </div>
                <div class="breadcrumb-item"><?php echo e(trans('admin/main.new_promo')); ?></div>
            </div>
        </div>

        <div class="section-body card">
            <div class="d-flex align-items-center justify-content-between">
                <div class="">
                    <h2 class="section-title ml-4"><?php echo e(!empty($promo) ? trans('admin/main.edit_promo') : trans('admin/main.new_promo')); ?></h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card-body">
                        <form action="<?php echo e(getAdminPanelUrl()); ?>/financial/promo/<?php echo e(empty($promo) ? 'store' : $promo->id . '/update'); ?>" method="POST">
                            <?php echo e(csrf_field()); ?>

                            <div class="form-group">
                                <label><?php echo e(trans('admin/main.title')); ?></label>
                                <input type="text" name="title" class="form-control" value="<?php echo e($promo->title ?? old('title')); ?>" required>
                            </div>
                            <div class="form-group">
                                <label><?php echo e(trans('admin/main.description')); ?></label>
                                <textarea name="description" class="form-control" required><?php echo e($promo->description ?? old('description')); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label><?php echo e(trans('admin/main.percentage')); ?></label>
                                <input type="number" name="percentage" class="form-control" value="<?php echo e($promo->percentage ?? old('percentage')); ?>" required>
                            </div>
                            <div class="form-group">
                                <label><?php echo e(trans('admin/main.start_date')); ?></label>
                                <input type="date" name="start_date" class="form-control" value="<?php echo e($promo->start_date ?? old('start_date')); ?>" required>
                            </div>
                            <div class="form-group">
                                <label><?php echo e(trans('admin/main.end_date')); ?></label>
                                <input type="date" name="end_date" class="form-control" value="<?php echo e($promo->end_date ?? old('end_date')); ?>" required>
                            </div>
                            <div class="form-group">
                                <label><?php echo e(trans('admin/main.status')); ?></label>
                                <select name="status" class="form-control" required>
                                    <option value="active" <?php echo e((isset($promo) && $promo->status == 'active') ? 'selected' : ''); ?>><?php echo e(trans('admin/main.active')); ?></option>
                                    <option value="inactive" <?php echo e((isset($promo) && $promo->status == 'inactive') ? 'selected' : ''); ?>><?php echo e(trans('admin/main.inactive')); ?></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?php echo e(trans('admin/main.type')); ?></label>
                                <select name="type" class="form-control <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($type); ?>" 
                                            <?php echo e(!empty($promo) && $promo->type == $type ? 'selected' : ''); ?>>
                                            <?php echo e($type); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback">
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <button type="submit" class="btn btn-primary"><?php echo e(trans('admin/main.submit')); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/rmi/resources/views/admin/financial/promo/new.blade.php ENDPATH**/ ?>