<div class="mt-35">
    <h2 class="section-title after-line"><?php echo e(trans('panel.purchase_formulas')); ?></h2>
    <div class="mt-20 d-flex flex-wrap justify-content-center">

        <!-- Card 1 -->
        <?php if(!$hasBought && $course->slug == 'devenez-trader-pro'): ?>
        <div class="col-12 col-sm-6 col-lg-4 mt-15">
        <form action="/course/pay-purchasemodel" method="post">
            <?php echo e(csrf_field()); ?>

            <input name="amount" value="<?php echo e($promo ? round(599 * ((100 - $promo->percentage) / 100), 2) : 599); ?>" type="hidden">
            <input name="subscription_id" value="" type="hidden">
            <input name="webinar_id" value="<?php echo e($course->id); ?>" type="hidden">
                <div style="min-height: 100%;" class="subscribe-plan d-flex flex-column justify-content-start position-relative bg-white align-items-center text-justify rounded-sm shadow pt-20 pb-20 px-20">
                    <div class="d-flex align-items-center justify-content-center" style="height: 80px; text-align: center;">
                        <p class="font-weight-bold font-18 text-secondary mt-5">Formation complète : Passez de zéro à expert trader</p>
                        <!-- <p><?php echo e($course->id); ?></p>
                        <p><?php echo e($course->title); ?></p> -->
                    </div>
                    <hr class="w-100 mt-3"/>
                    <div class="d-flex flex-column align-items-center mt-5 flex-grow-1">
                        <div class="d-flex align-items-end justify-content-center line-height-1">
                            <span class="font-24 text-danger" style="text-decoration: line-through;"><?php echo e($promo ? '$599' : '$750'); ?></span>
                        </div>
                        <!-- New Price -->
                        <div class="d-flex align-items-end justify-content-center line-height-1">
                            <span class="font-36 text-primary">
                                $<?php echo e($promo ? round(599 * ((100 - $promo->percentage) / 100), 2) : 599); ?>

                            </span>
                        </div>
                        <?php if($promo): ?>
                        <small class="text-secondary font-14">
                            <?php echo e($promo->percentage); ?>% off with promo!
                        </small>
                        <?php endif; ?>
                        <hr class="w-100 mt-3"/>
                        <ul class="mt-20 plan-feature px-0" style="text-align: left;">
                            <li class="mt-10">+101 modules de cours détaillés </li>
                            <li class="mt-10">+19 situations d’apprentissage </li>
                            <li class="mt-10">11 examens pour évaluer votre niveau de compréhension (Quiz)</li>
                            <li class="mt-10">Accès à vie à la formation et aux mises à jour périodiques des modules de cours</li>
                            <li class="mt-10">Accès aux outils de travail (indicateurs - Templates)</li>
                            <li class="mt-10">Attestation de suivi de formation</li>
                        </ul>
                        <div class="d-flex justify-content-center align-items-center mt-auto w-100">
                            <button type="submit" class="btn btn-primary">ACHETER</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <?php if(!$hasBought && $course->slug == 'devenez-trader-pro'): ?>
        <?php $__currentLoopData = $purchaseModels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchaseModel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <!-- Card 2 -->
            <div class="col-12 col-sm-6 col-lg-4 mt-15">
            <form action="/course/pay-purchasemodel" method="post">
                <?php echo e(csrf_field()); ?>

                <input name="amount" value="<?php echo e($promo ? number_format($purchaseModel->price * (1 - $promo->percentage / 100), 2) : $purchaseModel->price); ?>"  type="hidden">
                <input name="subscription_id" value="<?php echo e($purchaseModel->subscription->id); ?>" type="hidden">
                <input name="webinar_id" value="<?php echo e($course->id); ?>" type="hidden">
                <input name="purchase_model_id" value="<?php echo e($purchaseModel->id); ?>" type="hidden">
                <div class="subscribe-plan d-flex flex-column justify-content-start position-relative bg-white align-items-center text-justify rounded-sm shadow pt-20 pb-20 px-20 <?php echo e($purchaseModel->is_popular ? 'border-purple' : ''); ?>">
                        <!-- Popular Badge -->
                        <?php if($purchaseModel->is_popular): ?>
                        <div class="d-flex justify-content-center" style="width: 170px; position: absolute; top: -30px; left: 50%; transform: translateX(-50%); background-color: #783bd6; color: #fff; padding: 5px 10px; border-radius: 10px; font-size: 14px;">
                        <?php echo e(trans('panel.is_popular')); ?>

                        </div>
                        <?php endif; ?>
                        <div class="d-flex align-items-center justify-content-center" style="height: 80px; text-align: center;">
                            <p class="font-weight-bold font-18 text-secondary mt-5"><?php echo e($purchaseModel->title); ?></p>
                        </div>
                        <hr class="w-100 mt-3"/>
                        <div class="d-flex flex-column align-items-center mt-5 flex-grow-1">
                            <!-- Promo Discount -->
                            <?php if($promo && $promo->percentage > 0): ?>
                            <div class="d-flex align-items-end justify-content-center line-height-1">
                                <span class="font-24 text-danger" style="text-decoration: line-through;">
                                    $<?php echo e($purchaseModel->price); ?>

                                </span>
                            </div>
                            <div class="d-flex align-items-end justify-content-center line-height-1">
                                <span class="font-36 text-primary">
                                    $<?php echo e(number_format($purchaseModel->price * (1 - $promo->percentage / 100), 2)); ?>

                                </span>
                            </div>
                            <small class="text-secondary font-14">
                                <?php echo e($promo->percentage); ?>% off with promo!
                            </small>
                            <?php else: ?>
                            <!-- Actual Price -->
                            <?php if($purchaseModel->actual_price): ?>
                            <div class="d-flex align-items-end justify-content-center line-height-1">
                                <span class="font-24 text-danger" style="text-decoration: line-through;">$<?php echo e($purchaseModel->actual_price); ?></span>
                            </div>
                            <?php endif; ?>

                            <div class="d-flex align-items-end justify-content-center line-height-1">
                                <span class="font-36 text-primary">$<?php echo e($purchaseModel->price); ?></span>
                            </div>
                            <?php endif; ?>
                            <hr class="w-100 mt-3"/>
                            <ul class="mt-20 plan-feature px-0" style="text-align: left;">
                                <li class="mt-10">+101 modules de cours détaillés </li>
                                <li class="mt-10">+19 situations d’apprentissage </li>
                                <li class="mt-10">11 examens pour évaluer votre niveau de compréhension ( Quiz)</li>
                                <li class="mt-10">Accès à vie à la formation et aux mises à jour périodiques des modules de cours</li>
                                <li class="mt-10">Accès aux outils de travail (indicateurs - Templates)</li>
                                <li class="mt-10">Attestation de suivi de formation</li>
                                <li class="mt-10">Accès à la communauté vip de la RMI CLASS (<?php echo e(floor($purchaseModel->subscription->days / 30)); ?> mois)</li>
                                <li class="mt-10">Accès aux setups d’opportunités (<?php echo e(floor($purchaseModel->subscription->days / 30)); ?> mois)</li>
                                <li class="mt-10">Accès aux lives classes quotidiens avec tous les coachs (<?php echo e(floor($purchaseModel->subscription->days / 30)); ?> mois)</li>
                                <li class="mt-10">Éligible aux sessions de coaching privé One-to-one </li>
                                <li class="mt-10">Accès aux replays des sessions lives (<?php echo e(floor($purchaseModel->subscription->days / 30)); ?> mois)</li>
                                <li class="mt-10">Interaction avec la communauté (<?php echo e(floor($purchaseModel->subscription->days / 30)); ?> mois)</li>
                            </ul>
                            <div class="d-flex justify-content-center align-items-center mt-auto w-100">
                                <button type="submit" class="btn btn-primary">ACHETER</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </div>
</div>

<div class="mt-35">
    <h2 class="section-title after-line"><?php echo e(trans('panel.comments')); ?> <span class="ml-5">(<?php echo e($comments->count()); ?>)</span></h2>

    <div class="mt-20">
        <form action="/comments/store" method="post">

            <input type="hidden" name="_token" value=" <?php echo e(csrf_token()); ?>">
            <input type="hidden" id="commentItemId" name="item_id" value="<?php echo e($inputValue); ?>">
            <input type="hidden" id="commentItemName" name="item_name" value="<?php echo e($inputName); ?>">

            <div class="form-group">
                <textarea name="comment" class="form-control <?php $__errorArgs = ['comment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="10"></textarea>
                <div class="invalid-feedback"><?php $__errorArgs = ['comment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <?php echo e($message); ?> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
            </div>
            <button type="submit" class="btn btn-sm btn-primary"><?php echo e(trans('product.post_comment')); ?></button>
        </form>
    </div>

    <?php if(!empty(session()->has('msg'))): ?>
        <div class="alert alert-success my-25">
            <?php echo e(session()->get('msg')); ?>

        </div>
    <?php endif; ?>

    <?php if($comments): ?>
        <?php $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="comments-card shadow-lg rounded-sm border px-20 py-15 mt-30" data-address="/comments/<?php echo e($comment->id); ?>/reply" data-csrf="<?php echo e(csrf_token()); ?>" data-id="<?php echo e($comment->id); ?>">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="user-inline-avatar d-flex align-items-center mt-10">
                        <div class="avatar bg-gray200">
                            <img src="<?php echo e($comment->user->getAvatar()); ?>" class="img-cover" alt="">
                        </div>
                        <div class="d-flex flex-column ml-5">
                            <span class="font-weight-500 text-secondary"><?php echo e($comment->user->full_name); ?></span>
                            <span class="font-12 text-gray">
                                <?php if(!$comment->user->isUser() and !empty($course) and ($course->creator_id == $comment->user_id or $course->teacher_id == $comment->user_id)): ?>
                                    <?php echo e(trans('panel.teacher')); ?>

                                <?php elseif($comment->user->isUser() or (!empty($course) and $course->checkUserHasBought($comment->user))): ?>
                                    <?php echo e(trans('quiz.student')); ?>

                                <?php elseif($comment->user->isAdmin()): ?>
                                    <?php echo e(trans('panel.staff')); ?>

                                <?php else: ?>
                                    <?php echo e(trans('panel.user')); ?>

                                <?php endif; ?>
                            </span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <span class="font-12 text-gray mr-10"><?php echo e(dateTimeFormat($comment->created_at, 'j M Y | H:i')); ?></span>

                        <div class="btn-group dropdown table-actions">
                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i data-feather="more-vertical" height="20"></i>
                            </button>
                            <div class="dropdown-menu">
                                <button type="button" class="btn-transparent webinar-actions d-block text-hover-primary reply-comment"><?php echo e(trans('panel.reply')); ?></button>
                                <button type="button" data-item-id="<?php echo e($inputValue); ?>" data-comment-id="<?php echo e($comment->id); ?>" class="btn-transparent webinar-actions d-block mt-10 text-hover-primary report-comment"><?php echo e(trans('panel.report')); ?></button>

                                <?php if(auth()->check() and auth()->user()->id == $comment->user_id): ?>
                                    <a href="/comments/<?php echo e($comment->id); ?>/delete" class="webinar-actions d-block mt-10 text-hover-primary"><?php echo e(trans('public.delete')); ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="font-14 mt-20 text-gray">
                    <?php echo nl2br(clean($comment->comment)); ?>

                </div>

                <?php if(!empty($comment->replies) and $comment->replies->count() > 0): ?>
                    <?php $__currentLoopData = $comment->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-sm border px-20 py-15 mt-30">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="user-inline-avatar d-flex align-items-center mt-10">
                                    <div class="avatar bg-gray200">
                                        <img src="<?php echo e($reply->user->getAvatar()); ?>" class="img-cover" alt="">
                                    </div>
                                    <div class="d-flex flex-column ml-5">
                                        <span class="font-weight-500 text-secondary"><?php echo e($reply->user->full_name); ?></span>
                                        <span class="font-12 text-gray">
                                            <?php if(!$reply->user->isUser() and !empty($course) and ($course->creator_id == $reply->user_id or $course->teacher_id == $reply->user_id)): ?>
                                                <?php echo e(trans('panel.teacher')); ?>

                                            <?php elseif($reply->user->isUser() or (!empty($course) and $course->checkUserHasBought($reply->user))): ?>
                                                <?php echo e(trans('quiz.student')); ?>

                                            <?php elseif($reply->user->isAdmin()): ?>
                                                <?php echo e(trans('panel.staff')); ?>

                                            <?php else: ?>
                                                <?php echo e(trans('panel.user')); ?>

                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <span class="font-12 text-gray mr-10"><?php echo e(dateTimeFormat($reply->created_at, 'j M Y | H:i')); ?></span>

                                    <div class="btn-group dropdown table-actions">
                                        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i data-feather="more-vertical" height="20"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <button type="button" class="btn-transparent webinar-actions d-block text-hover-primary reply-comment"><?php echo e(trans('panel.reply')); ?></button>
                                            <button type="button" data-item-id="<?php echo e($inputValue); ?>" data-comment-id="<?php echo e($reply->id); ?>" class="btn-transparent webinar-actions d-block mt-10 text-hover-primary report-comment"><?php echo e(trans('panel.report')); ?></button>

                                            <?php if(auth()->check() and auth()->user()->id == $reply->user_id): ?>
                                                <a href="/comments/<?php echo e($reply->id); ?>/delete" class="webinar-actions d-block mt-10 text-hover-primary"><?php echo e(trans('public.delete')); ?></a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="font-14 mt-20 text-gray">
                                <?php echo nl2br(clean($reply->comment)); ?>

                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
</div>
<?php /**PATH /var/www/rmi/resources/views/web/default/includes/comments.blade.php ENDPATH**/ ?>