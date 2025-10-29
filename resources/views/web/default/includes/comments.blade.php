<div class="mt-35">
    <h2 class="section-title after-line">{{ trans('panel.purchase_formulas') }}</h2>
    <div class="mt-20 d-flex flex-wrap justify-content-center">

        <!-- Card 1 -->
        @if(!$hasBought && $course->slug == 'devenez-trader-pro')
        <div class="col-12 col-sm-6 col-lg-4 mt-15">
        <form action="/course/pay-purchasemodel" method="post">
            {{ csrf_field() }}
            <input name="amount" value="{{ $promo ? round(599 * ((100 - $promo->percentage) / 100), 2) : 599 }}" type="hidden">
            <input name="subscription_id" value="" type="hidden">
            <input name="webinar_id" value="{{$course->id}}" type="hidden">
                <div style="min-height: 100%;" class="subscribe-plan d-flex flex-column justify-content-start position-relative bg-white align-items-center text-justify rounded-sm shadow pt-20 pb-20 px-20">
                    <div class="d-flex align-items-center justify-content-center" style="height: 80px; text-align: center;">
                        <p class="font-weight-bold font-18 text-secondary mt-5">Formation complète : Passez de zéro à expert trader</p>
                        <!-- <p>{{$course->id}}</p>
                        <p>{{$course->title}}</p> -->
                    </div>
                    <hr class="w-100 mt-3"/>
                    <div class="d-flex flex-column align-items-center mt-5 flex-grow-1">
                        <div class="d-flex align-items-end justify-content-center line-height-1">
                            <span class="font-24 text-danger" style="text-decoration: line-through;">{{ $promo ? '$599' : '$750' }}</span>
                        </div>
                        <!-- New Price -->
                        <div class="d-flex align-items-end justify-content-center line-height-1">
                            <span class="font-36 text-primary">
                                ${{ $promo ? round(599 * ((100 - $promo->percentage) / 100), 2) : 599 }}
                            </span>
                        </div>
                        @if($promo)
                        <small class="text-secondary font-14">
                            {{ $promo->percentage }}% off with promo!
                        </small>
                        @endif
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
        @endif

        @if(!$hasBought && $course->slug == 'devenez-trader-pro')
        @foreach($purchaseModels as $purchaseModel)
            <!-- Card 2 -->
            <div class="col-12 col-sm-6 col-lg-4 mt-15">
            <form action="/course/pay-purchasemodel" method="post">
                {{ csrf_field() }}
                <input name="amount" value="{{ $promo ? number_format($purchaseModel->price * (1 - $promo->percentage / 100), 2) : $purchaseModel->price }}"  type="hidden">
                <input name="subscription_id" value="{{ $purchaseModel->subscription->id }}" type="hidden">
                <input name="webinar_id" value="{{$course->id}}" type="hidden">
                <input name="purchase_model_id" value="{{ $purchaseModel->id }}" type="hidden">
                <div class="subscribe-plan d-flex flex-column justify-content-start position-relative bg-white align-items-center text-justify rounded-sm shadow pt-20 pb-20 px-20 {{ $purchaseModel->is_popular ? 'border-purple' : '' }}">
                        <!-- Popular Badge -->
                        @if($purchaseModel->is_popular)
                        <div class="d-flex justify-content-center" style="width: 170px; position: absolute; top: -30px; left: 50%; transform: translateX(-50%); background-color: #783bd6; color: #fff; padding: 5px 10px; border-radius: 10px; font-size: 14px;">
                        {{ trans('panel.is_popular') }}
                        </div>
                        @endif
                        <div class="d-flex align-items-center justify-content-center" style="height: 80px; text-align: center;">
                            <p class="font-weight-bold font-18 text-secondary mt-5">{{$purchaseModel->title}}</p>
                        </div>
                        <hr class="w-100 mt-3"/>
                        <div class="d-flex flex-column align-items-center mt-5 flex-grow-1">
                            <!-- Promo Discount -->
                            @if($promo && $promo->percentage > 0)
                            <div class="d-flex align-items-end justify-content-center line-height-1">
                                <span class="font-24 text-danger" style="text-decoration: line-through;">
                                    ${{ $purchaseModel->price }}
                                </span>
                            </div>
                            <div class="d-flex align-items-end justify-content-center line-height-1">
                                <span class="font-36 text-primary">
                                    ${{ number_format($purchaseModel->price * (1 - $promo->percentage / 100), 2) }}
                                </span>
                            </div>
                            <small class="text-secondary font-14">
                                {{ $promo->percentage }}% off with promo!
                            </small>
                            @else
                            <!-- Actual Price -->
                            @if($purchaseModel->actual_price)
                            <div class="d-flex align-items-end justify-content-center line-height-1">
                                <span class="font-24 text-danger" style="text-decoration: line-through;">${{$purchaseModel->actual_price}}</span>
                            </div>
                            @endif

                            <div class="d-flex align-items-end justify-content-center line-height-1">
                                <span class="font-36 text-primary">${{$purchaseModel->price}}</span>
                            </div>
                            @endif
                            <hr class="w-100 mt-3"/>
                            <ul class="mt-20 plan-feature px-0" style="text-align: left;">
                                <li class="mt-10">+101 modules de cours détaillés </li>
                                <li class="mt-10">+19 situations d’apprentissage </li>
                                <li class="mt-10">11 examens pour évaluer votre niveau de compréhension ( Quiz)</li>
                                <li class="mt-10">Accès à vie à la formation et aux mises à jour périodiques des modules de cours</li>
                                <li class="mt-10">Accès aux outils de travail (indicateurs - Templates)</li>
                                <li class="mt-10">Attestation de suivi de formation</li>
                                <li class="mt-10">Accès à la communauté vip de la RMI CLASS ({{ floor($purchaseModel->subscription->days / 30) }} mois)</li>
                                <li class="mt-10">Accès aux setups d’opportunités ({{ floor($purchaseModel->subscription->days / 30) }} mois)</li>
                                <li class="mt-10">Accès aux lives classes quotidiens avec tous les coachs ({{ floor($purchaseModel->subscription->days / 30) }} mois)</li>
                                <li class="mt-10">Éligible aux sessions de coaching privé One-to-one </li>
                                <li class="mt-10">Accès aux replays des sessions lives ({{ floor($purchaseModel->subscription->days / 30) }} mois)</li>
                                <li class="mt-10">Interaction avec la communauté ({{ floor($purchaseModel->subscription->days / 30) }} mois)</li>
                            </ul>
                            <div class="d-flex justify-content-center align-items-center mt-auto w-100">
                                <button type="submit" class="btn btn-primary">ACHETER</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endforeach
        @endif
    </div>
</div>

<div class="mt-35">
    <h2 class="section-title after-line">{{ trans('panel.comments') }} <span class="ml-5">({{ $comments->count() }})</span></h2>

    <div class="mt-20">
        <form action="/comments/store" method="post">

            <input type="hidden" name="_token" value=" {{ csrf_token() }}">
            <input type="hidden" id="commentItemId" name="item_id" value="{{ $inputValue }}">
            <input type="hidden" id="commentItemName" name="item_name" value="{{ $inputName }}">

            <div class="form-group">
                <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" rows="10"></textarea>
                <div class="invalid-feedback">@error('comment') {{ $message }} @enderror</div>
            </div>
            <button type="submit" class="btn btn-sm btn-primary">{{ trans('product.post_comment') }}</button>
        </form>
    </div>

    @if(!empty(session()->has('msg')))
        <div class="alert alert-success my-25">
            {{ session()->get('msg') }}
        </div>
    @endif

    @if($comments)
        @foreach($comments as $comment)
            <div class="comments-card shadow-lg rounded-sm border px-20 py-15 mt-30" data-address="/comments/{{ $comment->id }}/reply" data-csrf="{{ csrf_token() }}" data-id="{{ $comment->id }}">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="user-inline-avatar d-flex align-items-center mt-10">
                        <div class="avatar bg-gray200">
                            <img src="{{ $comment->user->getAvatar() }}" class="img-cover" alt="">
                        </div>
                        <div class="d-flex flex-column ml-5">
                            <span class="font-weight-500 text-secondary">{{ $comment->user->full_name }}</span>
                            <span class="font-12 text-gray">
                                @if(!$comment->user->isUser() and !empty($course) and ($course->creator_id == $comment->user_id or $course->teacher_id == $comment->user_id))
                                    {{ trans('panel.teacher') }}
                                @elseif($comment->user->isUser() or (!empty($course) and $course->checkUserHasBought($comment->user)))
                                    {{ trans('quiz.student') }}
                                @elseif($comment->user->isAdmin())
                                    {{ trans('panel.staff') }}
                                @else
                                    {{ trans('panel.user') }}
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <span class="font-12 text-gray mr-10">{{ dateTimeFormat($comment->created_at, 'j M Y | H:i') }}</span>

                        <div class="btn-group dropdown table-actions">
                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i data-feather="more-vertical" height="20"></i>
                            </button>
                            <div class="dropdown-menu">
                                <button type="button" class="btn-transparent webinar-actions d-block text-hover-primary reply-comment">{{ trans('panel.reply') }}</button>
                                <button type="button" data-item-id="{{ $inputValue }}" data-comment-id="{{ $comment->id }}" class="btn-transparent webinar-actions d-block mt-10 text-hover-primary report-comment">{{ trans('panel.report') }}</button>

                                @if(auth()->check() and auth()->user()->id == $comment->user_id)
                                    <a href="/comments/{{ $comment->id }}/delete" class="webinar-actions d-block mt-10 text-hover-primary">{{ trans('public.delete') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="font-14 mt-20 text-gray">
                    {!! nl2br(clean($comment->comment)) !!}
                </div>

                @if(!empty($comment->replies) and $comment->replies->count() > 0)
                    @foreach($comment->replies as $reply)
                        <div class="rounded-sm border px-20 py-15 mt-30">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="user-inline-avatar d-flex align-items-center mt-10">
                                    <div class="avatar bg-gray200">
                                        <img src="{{ $reply->user->getAvatar() }}" class="img-cover" alt="">
                                    </div>
                                    <div class="d-flex flex-column ml-5">
                                        <span class="font-weight-500 text-secondary">{{ $reply->user->full_name }}</span>
                                        <span class="font-12 text-gray">
                                            @if(!$reply->user->isUser() and !empty($course) and ($course->creator_id == $reply->user_id or $course->teacher_id == $reply->user_id))
                                                {{ trans('panel.teacher') }}
                                            @elseif($reply->user->isUser() or (!empty($course) and $course->checkUserHasBought($reply->user)))
                                                {{ trans('quiz.student') }}
                                            @elseif($reply->user->isAdmin())
                                                {{ trans('panel.staff') }}
                                            @else
                                                {{ trans('panel.user') }}
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <span class="font-12 text-gray mr-10">{{ dateTimeFormat($reply->created_at, 'j M Y | H:i') }}</span>

                                    <div class="btn-group dropdown table-actions">
                                        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i data-feather="more-vertical" height="20"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <button type="button" class="btn-transparent webinar-actions d-block text-hover-primary reply-comment">{{ trans('panel.reply') }}</button>
                                            <button type="button" data-item-id="{{ $inputValue }}" data-comment-id="{{ $reply->id }}" class="btn-transparent webinar-actions d-block mt-10 text-hover-primary report-comment">{{ trans('panel.report') }}</button>

                                            @if(auth()->check() and auth()->user()->id == $reply->user_id)
                                                <a href="/comments/{{ $reply->id }}/delete" class="webinar-actions d-block mt-10 text-hover-primary">{{ trans('public.delete') }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="font-14 mt-20 text-gray">
                                {!! nl2br(clean($reply->comment)) !!}
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        @endforeach
    @endif
</div>
