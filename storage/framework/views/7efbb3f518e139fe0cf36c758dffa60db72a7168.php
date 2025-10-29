<?php $__env->startSection('body'); ?>
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <h1>Payment Successful</h1>
        <p>Dear <?php echo e($order->user->full_name); ?>,</p>
        <p>Your payment for order #<?php echo e($order->id); ?> was successful. Thank you for your purchase!</p>
        <p>Your Purchase Details:</p>
        <ul>
            <li>Order ID: <?php echo e($order->id); ?></li>
            <li>Amount Paid: <?php echo e($order->total_amount); ?></li>
            <li>Products Purchased:</li>
            <ul>
                <?php $__currentLoopData = $carts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $cartItemInfo = $cart->getItemInfo();
                    ?>
                    <li>
                        <?php echo e($cartItemInfo['title']); ?>

                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </ul>
        <p>Thank you for shopping with us!</p>
    </td>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('web.default.layouts.email', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\RMI Class- AWS\resources\views/web/default/emails/payment_success.blade.php ENDPATH**/ ?>