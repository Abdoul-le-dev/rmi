@extends(getTemplate() .'.panel.layouts.panel_layout')

<style>
.promo_div {
  background-color: white;
  border-radius: 16px;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 20px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  position: relative;
  max-width: 900px;
  margin: auto;
  z-index: 1;
  margin-top: 10px;
  margin-bottom: 35px;
}

.promo_row {
  display: flex;
  justify-content: space-between;
  width: 100%;
  align-items: center;
}

.offer_text {
  font-family: Arial, sans-serif;
}

.offer_text .promo_h3 {
  margin: 0;
  font-size: 1.5rem;
  font-weight: bold;
}

.offer_text .promo_offer_text_span {
  font-size: 1rem;
  color: gray;
}

.countdown {
  display: flex;
  gap: 10px;
}

.time_box {
  background-color: #f5f5f5;
  padding: 10px 15px;
  border-radius: 8px;
  text-align: center;
}

.time_box .time_box_span {
  display: block;
  font-size: 1.25rem;
  font-weight: bold;
}

.time_box .time_box_small {
  font-size: 0.75rem;
  color: gray;
}

.percentage_div {
  position: absolute;
  top: 100%;
  transform: translateY(-50%);
  background-color: #783bd6;
  color: white;
  padding: 10px 20px;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(160, 125, 255, 0.6);
  font-size: 1.5rem;
  font-weight: bold;
  text-align: center;
}

.percentage_div .percentage_div_small {
  display: block;
  font-size: 0.75rem;
}
</style>

@section('content')
    @if($activeSubscribe)
        <section>
            <h2 class="section-title">{{ trans('financial.my_active_plan') }}</h2>

            @if(!empty($promo) && $promo->type == 'Subscription')
            <div class="promo_div">
                <div class="row promo_row">
                    <div class="col-8 offer_text">
                        <h3 class="promo_h3">{{ $promo->title ?? 'No Active Promo' }}</h3>
                        <span class="promo_offer_text_span">{{ $promo->description ?? '' }}</span>
                    </div>
                    <div class="col-4 countdown">
                        <div class="time_box">
                            <span class="time_box_span" id="days">0</span>
                            <small class="time_box_small">Jour</small>
                        </div>
                        <div class="time_box">
                            <span class="time_box_span" id="hours">0</span>
                            <small class="time_box_small">Heure</small>
                        </div>
                        <div class="time_box">
                            <span class="time_box_span" id="minutes">0</span>
                            <small class="time_box_small">Min</small>
                        </div>
                        <div class="time_box">
                            <span class="time_box_span" id="seconds">0</span>
                            <small class="time_box_small">Sec</small>
                        </div>
                    </div>
                </div>
                <div class="percentage_div">
                    <span>{{ $promo->percentage ?? 0 }}%</span>
                    <small class="percentage_div_small font-weight-light">OFF</small>
                </div>
            </div>
            @endif

            <div class="activities-container mt-25 p-20 p-lg-35">
                <div class="row">
                    <div class="col-4 d-flex align-items-center justify-content-center">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="/assets/default/img/activity/webinars.svg" width="64" height="64" alt="">
                            <strong class="font-30 font-weight-bold mt-5">{{ $activeSubscribe->title }}</strong>
                            <span class="font-16 text-gray font-weight-500">{{ trans('financial.active_plan') }}</span>
                        </div>
                    </div>

                    <div class="col-4 d-flex align-items-center justify-content-center">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="/assets/default/img/activity/53.svg" width="64" height="64" alt="">
                            <strong class="font-30 text-dark-blue font-weight-bold mt-5">
                                @if($activeSubscribe->infinite_use)
                                    {{ trans('update.unlimited') }}
                                @else
                                    {{ $activeSubscribe->usable_count - $activeSubscribe->used_count }}
                                @endif
                            </strong>
                            <span class="font-16 text-gray font-weight-500">{{ trans('financial.remained_downloads') }}</span>
                        </div>
                    </div>

                    <div class="col-4 d-flex align-items-center justify-content-center">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="/assets/default/img/activity/54.svg" width="64" height="64" alt="">
                            <strong class="font-30 text-dark-blue text-dark-blue font-weight-bold mt-5">{{ ($activeSubscribe->days - $dayOfUse['days']) }}:{{ sprintf('%02d', 24 - $dayOfUse['hours']) }}</strong>
                            <span class="font-16 text-gray font-weight-500">{{ trans('financial.days_remained') }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    @else
        @include(getTemplate() . '.includes.no-result',[
           'file_name' => 'subcribe.png',
           'title' => trans('financial.subcribe_no_result'),
           'hint' => nl2br(trans('financial.subcribe_no_result_hint')),
       ])
    @endif

    <section class="mt-30">
        <h2 class="section-title">{{ trans('financial.select_a_subscribe_plan') }}</h2>

        <div class="row mt-15">

            @foreach($subscribes as $subscribe)
                @php
                    $subscribeSpecialOffer = $subscribe->activeSpecialOffer();
                @endphp

                <div class="col-12 col-sm-6 col-lg-3 mt-15">
                    <div class="subscribe-plan position-relative bg-white d-flex flex-column align-items-center rounded-sm shadow pt-50 pb-20 px-20">
                        @if($subscribe->is_popular)
                            <span class="badge badge-primary badge-popular px-15 py-5">{{ trans('panel.popular') }}</span>
                        @elseif(!empty($subscribeSpecialOffer))
                            <span class="badge badge-danger badge-popular px-15 py-5">{{ trans('update.percent_off', ['percent' => $subscribeSpecialOffer->percent]) }}</span>
                        @endif

                        <div class="plan-icon">
                            <img src="{{ \App\Helpers\S3Helper::getTemporaryUrl($subscribe->icon) }}" class="img-cover" alt="">
                            {{-- <img src="{{ $subscribe->icon }}" class="img-cover" alt=""> --}}
                        </div>

                        <h3 class="mt-20 font-30 text-secondary">{{ $subscribe->title }}</h3>
                        <p class="font-weight-500 font-14 text-gray mt-10">{{ $subscribe->description }}</p>

                        <div class="d-flex align-items-start mt-30">
                            @if(!empty($subscribe->price) and $subscribe->price > 0)
                                @php
                                    $discountedPrice = !empty($promo) && $promo->type == 'Subscription' 
                                        ? $subscribe->price - ($subscribe->price * $promo->percentage / 100) 
                                        : $subscribe->price;
                                @endphp

                                @if(!empty($promo) && $promo->type == 'Subscription')
                                    <div class="d-flex align-items-end line-height-1">
                                        <span class="font-36 text-primary">{{ handlePrice($discountedPrice, true, true, false, null, true) }}</span>
                                        <span class="font-14 text-danger ml-5 text-decoration-line-through">{{ handlePrice($subscribe->price, true, true, false, null, true) }}</span>
                                    </div>
                                @elseif(!empty($subscribeSpecialOffer))
                                    <div class="d-flex align-items-end line-height-1">
                                        <span class="font-36 text-primary">{{ handlePrice($subscribe->getPrice(), true, true, false, null, true) }}</span>
                                        <span class="font-14 text-gray ml-5 text-decoration-line-through">{{ handlePrice($subscribe->price, true, true, false, null, true) }}</span>
                                    </div>
                                @else
                                    <span class="font-36 text-primary line-height-1">{{ handlePrice($subscribe->price, true, true, false, null, true) }}</span>
                                @endif
                            @else
                                <span class="font-36 text-primary line-height-1">{{ trans('public.free') }}</span>
                            @endif
                        </div>

                        @if($promo)
                            <small class="text-secondary font-14 mt-1">
                                {{ $promo->percentage }}% off with promo!
                            </small>
                        @endif

                        <ul class="mt-20 plan-feature">
                            <li class="mt-10">{{ $subscribe->days }} {{ trans('financial.days_of_subscription') }}</li>
                            <li class="mt-10">
                                {{-- @if($subscribe->infinite_use)
                                    {{ trans('update.unlimited') }}
                                @else
                                    {{ $subscribe->usable_count }}
                                @endif --}}
                                {{ trans('update.unlimited') }} <span class="ml-5">{{ trans('update.access') }}</span>
                            </li>
                            <li class="mt-10">{{trans('new_translations.live_class')}}</li>
                            <li class="mt-10">{{trans('new_translations.opportunity_setups')}}</li>
                            <li class="mt-10">{{trans('new_translations.interaction_community')}}</li>
                            <li class="mt-10">{{trans('new_translations.access_to_our_tools_indicators_templates')}}</li>
                        </ul>
                        <form action="/panel/financial/pay-subscribes" method="post" class="btn-block">
                            {{ csrf_field() }}
                            <input name="amount" value="{{ $subscribe->price }}" type="hidden">
                            <input name="id" value="{{ $subscribe->id }}" type="hidden">

                            <div class="d-flex align-items-center mt-50 w-100">
                                <button type="submit" class="btn btn-primary {{ !empty($subscribe->has_installment) ? '' : 'btn-block' }}">{{ trans('update.purchase') }}</button>

                                @if(!empty($subscribe->has_installment))
                                    <a href="/panel/financial/subscribes/{{ $subscribe->id }}/installments" class="btn btn-outline-primary flex-grow-1 ml-10">{{ trans('update.installments') }}</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/js/panel/financial/subscribes.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const endDate = new Date("{{ $promo->end_date ?? now() }}").getTime();

            function updateCountdown() {
            const now = new Date().getTime();
            const distance = endDate - now;

            if (distance > 0) {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById('days').textContent = days;
                document.getElementById('hours').textContent = hours;
                document.getElementById('minutes').textContent = minutes;
                document.getElementById('seconds').textContent = seconds;
            } else {
                clearInterval(timer);
                document.querySelector('.promo_div').style.display = 'none'; // Hide promo if expired
            }
            }

            const timer = setInterval(updateCountdown, 1000);
            updateCountdown();
        });
    </script>
@endpush
