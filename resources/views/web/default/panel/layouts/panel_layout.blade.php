<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

@php
    $rtlLanguages = !empty($generalSettings['rtl_languages']) ? $generalSettings['rtl_languages'] : [];

    $isRtl =
        (in_array(mb_strtoupper(app()->getLocale()), $rtlLanguages) or
        !empty($generalSettings['rtl_layout']) and $generalSettings['rtl_layout'] == 1);
@endphp

<head>
    @include(getTemplate() . '.includes.metas')
    <title>
        {{ $pageTitle ?? '' }}{{ !empty($generalSettings['site_name']) ? ' | ' . $generalSettings['site_name'] : '' }}
    </title>

    <!-- General CSS File -->
    <link href="/assets/default/css/font.css" rel="stylesheet">

    <link rel="stylesheet" href="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/toast/jquery.toast.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/simplebar/simplebar.css">
    <link rel="stylesheet" href="/assets/default/css/app.css">
    <link rel="stylesheet" href="/assets/default/css/panel.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @include('partials.pwa')

    @if ($isRtl)
        <link rel="stylesheet" href="/assets/default/css/rtl-app.css">
    @endif

    @stack('styles_top')
    @stack('scripts_top')

    <style>
        {!! !empty(getCustomCssAndJs('css')) ? getCustomCssAndJs('css') : '' !!} {!! getThemeFontsSettings() !!} {!! getThemeColorsSettings() !!}
    </style>

    @if (!empty($generalSettings['preloading']) and $generalSettings['preloading'] == '1')
        @include('admin.includes.preloading')
    @endif



    <!-- Ajoutez ce style dans la section <head> ou dans votre fichier CSS -->
    <style>
        /* Overlay du popup */
        .event-popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            animation: fadeIn 0.3s ease-in-out;
        }

        .event-popup-overlay.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Container du popup */
        .event-popup-container {
            background: white;
            border-radius: 16px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.4s ease-out;
            position: relative;
        }

        @media (max-width: 768px) {
            .event-popup-container {
                width: 95%;
                max-height: 85vh;
                border-radius: 12px;
            }
        }

        /* Header du popup */
        .event-popup-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 24px;
            color: white;
            position: relative;
        }

        .event-popup-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .event-popup-header p {
            margin: 8px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .event-popup-header {
                padding: 16px;
            }

            .event-popup-header h2 {
                font-size: 18px;
                padding-right: 35px;
                line-height: 1.3;
            }

            .event-popup-header p {
                font-size: 12px;
                margin-top: 6px;
            }
        }

        /* Bouton de fermeture */
        .event-popup-close {
            position: absolute;
            top: 16px;
            right: 16px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
            font-size: 20px;
        }

        .event-popup-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        @media (max-width: 768px) {
            .event-popup-close {
                top: 12px;
                right: 12px;
                width: 30px;
                height: 30px;
                font-size: 18px;
            }
        }

        /* Corps du popup */
        .event-popup-body {
            padding: 24px;
            max-height: 50vh;
            overflow-y: auto;
        }

        @media (max-width: 768px) {
            .event-popup-body {
                padding: 12px;
                max-height: 55vh;
            }
        }

        /* Liste des événements */
        .event-item {
            display: flex;
            gap: 16px;
            padding: 16px;
            border-radius: 12px;
            background: #f8f9fa;
            margin-bottom: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }

        .event-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .event-item:last-child {
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .event-item {
                gap: 10px;
                padding: 10px;
                margin-bottom: 10px;
                border-radius: 10px;
            }

            .event-item:hover {
                transform: none;
            }
        }

        .event-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .event-icon.live {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .event-icon.webinar {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .event-icon.course {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        @media (max-width: 768px) {
            .event-icon {
                width: 36px;
                height: 36px;
                font-size: 18px;
                border-radius: 8px;
            }
        }

        .event-details {
            flex: 1;
            min-width: 0;
        }

        .event-title {
            font-weight: 600;
            font-size: 16px;
            color: #2d3748;
            margin: 0 0 4px;
            line-height: 1.4;
        }

        @media (max-width: 768px) {
            .event-title {
                font-size: 13px;
                margin: 0 0 6px;
                line-height: 1.3;
            }
        }

        .event-meta {
            display: flex;
            gap: 12px;
            font-size: 13px;
            color: #718096;
            margin-bottom: 8px;
            flex-wrap: wrap;
        }

        .event-meta span {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .event-meta {
                gap: 8px;
                font-size: 11px;
                margin-bottom: 6px;
            }

            .event-meta span {
                gap: 3px;
            }
        }

        .event-description {
            font-size: 14px;
            color: #4a5568;
            line-height: 1.5;
        }

        @media (max-width: 768px) {
            .event-description {
                font-size: 11px;
                line-height: 1.4;
            }
        }

        .event-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }

        .event-badge.new {
            background: #fef3c7;
            color: #92400e;
        }

        .event-badge.live-now {
            background: #fee2e2;
            color: #991b1b;
            animation: pulse 2s infinite;
        }

        @media (max-width: 768px) {
            .event-badge {
                padding: 3px 8px;
                font-size: 10px;
                margin-top: 6px;
                border-radius: 12px;
            }
        }

        /* Footer du popup */
        .event-popup-footer {
            padding: 16px 24px;
            background: #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e2e8f0;
        }

        .event-popup-footer label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #4a5568;
            cursor: pointer;
        }

        .event-popup-footer button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            font-size: 14px;
        }

        .event-popup-footer button:hover {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .event-popup-footer {
                padding: 12px;
                flex-direction: column;
                gap: 10px;
            }

            .event-popup-footer label {
                font-size: 11px;
                order: 2;
            }

            .event-popup-footer button {
                padding: 10px 16px;
                font-size: 13px;
                width: 100%;
                order: 1;
            }

            .event-popup-footer button:hover {
                transform: none;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        /* RTL Support */
        .rtl .event-popup-close {
            right: auto;
            left: 16px;
        }

        .rtl .event-item {
            direction: rtl;
        }

        @media (max-width: 768px) {
            .rtl .event-popup-close {
                left: 12px;
            }
        }
    </style>

</head>

<body class="@if ($isRtl) rtl @endif">

    @php
        $isPanel = true;
    @endphp

    <div id="panel_app">

        @include('web.default.includes.navbar')

        <div class="d-flex justify-content-end">
            @include('web.default.panel.includes.sidebar')

            <div class="panel-content">
                @yield('content')
            </div>
        </div>

        @include('web.default.includes.advertise_modal.index')

        {{-- AI Contents --}}
        @if ($authUser->checkAccessToAIContentFeature())
            @include('web.default.panel.includes.aiContent.generator')
        @endif

    </div>
    <!-- Template JS File -->
    <script src="/assets/default/js/app.js"></script>
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/feather-icons/dist/feather.min.js"></script>
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="/assets/default/vendors/toast/jquery.toast.min.js"></script>
    <script type="text/javascript" src="/assets/default/vendors/simplebar/simplebar.min.js"></script>

    <script>
        var deleteAlertTitle = '{{ trans('public.are_you_sure') }}';
        var deleteAlertHint = '{{ trans('public.deleteAlertHint') }}';
        var deleteAlertConfirm = '{{ trans('public.deleteAlertConfirm') }}';
        var deleteAlertCancel = '{{ trans('public.cancel') }}';
        var deleteAlertSuccess = '{{ trans('public.success') }}';
        var deleteAlertFail = '{{ trans('public.fail') }}';
        var deleteAlertFailHint = '{{ trans('public.deleteAlertFailHint') }}';
        var deleteAlertSuccessHint = '{{ trans('public.deleteAlertSuccessHint') }}';
        var forbiddenRequestToastTitleLang = '{{ trans('public.forbidden_request_toast_lang') }}';
        var forbiddenRequestToastMsgLang = '{{ trans('public.forbidden_request_toast_msg_lang') }}';
        var deleteRequestLang = '{{ trans('update.delete_request') }}';
        var deleteRequestDescriptionLang = '{{ trans('update.delete_request_description') }}';
        var requestDetailsLang = '{{ trans('update.request_details') }}';
        var sendRequestLang = '{{ trans('update.send_request') }}';
        var closeLang = '{{ trans('public.close') }}';
        var generatedContentLang = '{{ trans('update.generated_content') }}';
        var copyLang = '{{ trans('public.copy') }}';
        var doneLang = '{{ trans('public.done') }}';
    </script>

    @if (session()->has('toast'))
        <script>
            (function() {
                "use strict";

                $.toast({
                    heading: '{{ session()->get('toast')['title'] ?? '' }}',
                    text: '{{ session()->get('toast')['msg'] ?? '' }}',
                    bgColor: '@if (session()->get('toast')['status'] == 'success') #43d477 @else #f63c3c @endif',
                    textColor: 'white',
                    hideAfter: 10000,
                    position: 'bottom-right',
                    icon: '{{ session()->get('toast')['status'] }}'
                });
            })(jQuery)
        </script>
    @endif

    @include('web.default.includes.purchase_notifications')

    @php
        use Carbon\Carbon;

        $category = App\Models\Category::where('slug', 'live-class')->first();

        $now = Carbon::now()->timestamp;
        $endOfWeek = Carbon::now()->endOfWeek()->timestamp;

        $webinars = App\Models\Webinar::where('category_id', $category->id)
            ->whereBetween('start_date', [$now, $endOfWeek])
            ->orderBy('start_date')
            ->get();
    @endphp


    @if ($webinars->isNotEmpty())
        <!-- Ajoutez ce HTML juste avant la fermeture de </body> -->
        <div class="event-popup-overlay" id="eventPopup">
            <div class="event-popup-container">
                <div class="event-popup-header">
                    <button class="event-popup-close" onclick="closeEventPopup()">×</button>
                    <h2>Nouveaux Événements</h2>
                    <p>Ne manquez pas ces opportunités de croissance !</p>
                </div>


                <div class="event-popup-body">

                    @foreach ($webinars as $webinar)
                        <div class="event-item" onclick="window.location.href='{{ $webinar->getUrl() }}'">
                            <div class="event-icon live">🎥</div>
                            <div class="event-details">
                                <h3 class="event-title">{{ $webinar->title }}</h3>
                                <div class="event-meta">
                                    <span>📅
                                        {{ dateTimeFormat(!empty($webinar->start_date) ? $webinar->start_date : $webinar->created_at, 'j M Y') }}</span>
                                    <span>🕐 {{ convertMinutesToHourAndMinute($webinar->duration) }}
                                        {{ trans('home.hours') }}</span>
                                </div>
                                <p class="event-description">
                                    Par : {{ $webinar->teacher->full_name }}
                                </p>
                                @php

                                    $now = Carbon::now();
                                    $start = Carbon::createFromTimestamp($webinar->start_date, 'UTC');
                                    $dtLocal = $start ->setTimezone('Africa/Porto-Novo');

                                    $diffInMinutes = $now->diffInMinutes($dtLocal , false);
                                @endphp
                                @if ($dtLocal->isToday() && $diffInMinutes > 0)
                                    @php
                                        $hours = floor($diffInMinutes / 60);
                                        $minutes = $diffInMinutes % 60;
                                    @endphp

                                    <span class="event-badge live-now">
                                        🔴 En direct dans
                                        {{ $hours > 0 ? $hours . 'h' : '' }}
                                        {{ $minutes > 0 ? $minutes . 'min' : '' }}
                                    </span>
                                @endif

                            </div>
                        </div>
                    @endforeach

                </div>

                <div class="event-popup-footer">
                    <label>
                        <input type="checkbox" id="dontShowAgain">
                        Ne plus afficher aujourd'hui
                    </label>
                    <button onclick="closeEventPopup()">J'ai compris</button>
                </div>
            </div>
        </div>
    @endif
    @stack('styles_bottom')
    @stack('scripts_bottom')

    <script src="/assets/default/js//parts/main.min.js"></script>
    <script src="/assets/default/js/panel/public.min.js"></script>
    <script src="/assets/default/js/parts/content_delete.min.js"></script>
    <script src="/assets/default/js/panel/ai-content-generator.min.js"></script>

    @stack('scripts_bottom2')

    <script>
        @if (session()->has('registration_package_limited'))
            (function() {
                "use strict";

                handleLimitedAccountModal('{!! session()->get('registration_package_limited') !!}')
            })(jQuery)

            {{ session()->forget('registration_package_limited') }}
        @endif

        {!! !empty(getCustomCssAndJs('js')) ? getCustomCssAndJs('js') : '' !!}
    </script>

    <script src="//code.tidio.co/hvd4jbgdjiilhfyfdondmrmzk82eezgf.js" async></script>



    @if ($webinars->isNotEmpty())
        <script>
            // Fonction pour afficher le popup
            function showEventPopup() {
                const popup = document.getElementById('eventPopup');

                // Vérifier si l'utilisateur a choisi de ne pas voir le popup aujourd'hui
                const dontShow = localStorage.getItem('hideEventPopup');
                const today = new Date().toDateString();

                if (dontShow === today) {
                    return; // Ne pas afficher le popup
                }

                // Afficher le popup après 2 secondes
                setTimeout(() => {
                    popup.classList.add('active');
                    document.body.style.overflow = 'hidden'; // Bloquer le scroll
                }, 2000);
            }

            // Fonction pour fermer le popup
            function closeEventPopup() {
                const popup = document.getElementById('eventPopup');
                const checkbox = document.getElementById('dontShowAgain');

                // Si la case est cochée, sauvegarder la préférence
                if (checkbox.checked) {
                    const today = new Date().toDateString();
                    localStorage.setItem('hideEventPopup', today);
                }

                popup.classList.remove('active');
                document.body.style.overflow = ''; // Rétablir le scroll
            }

            // Fermer le popup en cliquant sur l'overlay
            document.getElementById('eventPopup').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEventPopup();
                }
            });

            // Fermer avec la touche Échap
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeEventPopup();
                }
            });

            // Afficher le popup au chargement de la page
            window.addEventListener('load', showEventPopup);
        </script>
    @endif

</body>

</html>
