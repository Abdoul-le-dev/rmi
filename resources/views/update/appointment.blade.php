<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="HTML5 Template" />
    <meta name="description" content="RMI Class - Plateforme de formation en Trading" />
    <meta name="author" content="https://www.neroon.me/" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Royal Market Invest Class - À Propos</title>

    <link rel="shortcut icon" href="update/images/favicon.png" />
    <link rel="stylesheet" type="text/css" href="update/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="update/css/animate.css" />
    <link rel="stylesheet" type="text/css" href="update/css/fontello.css" />
    <link rel="stylesheet" type="text/css" href="update/css/flaticon.css" />
    <link rel="stylesheet" type="text/css" href="update/css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="update/css/themify-icons.css" />
    <link rel="stylesheet" type="text/css" href="update/css/slick.css">
    <link rel="stylesheet" type="text/css" href="update/css/prettyPhoto.css">
    <link rel="stylesheet" type="text/css" href="update/css/shortcodes.css" />
    <link rel="stylesheet" type="text/css" href="update/css/main.css" />
    <link rel="stylesheet" type="text/css" href="update/css/megamenu.css" />
    <link rel="stylesheet" type="text/css" href="update/css/responsive.css" />
    @include('partials.pwa')

    <style>
        .appointment-form-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 0;
        }
        .appointment-form-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-appointment {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 40px;
            color: white;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s;
        }
        .btn-appointment:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .form-icon {
            color: #667eea;
            font-size: 48px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="page">
        <!--header start-->
        <header id="masthead" class="header ttm-header-style-01">
            <!-- top_bar -->
            <div class="top_bar ttm-textcolor-white clearfix">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 d-flex flex-row align-items-center">
                            <div class="top_bar_contact_item">
                                <div class="top_bar_icon"><i class="fa fa-envelope-o"></i></div>
                                <a href="mailto:Contact@royalmarketsinv.com">Contact@royalmarketsinv.com</a>
                            </div>
                            <div class="top_bar_contact_item">
                                <div class="top_bar_icon"><i class="fa fa-map-marker"></i></div>Bénin, Afrique de l'Ouest
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex flex-row align-items-center">
                            <div class="top_bar_contact_item ml-auto">
                                <div class="top_bar_social d-flex align-items-center">
                                    <span class="mr-2">Suivez-nous :</span>
                                    <ul class="social-icons">
                                        <li><a href="#" rel="noopener" aria-label="facebook"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="#" rel="noopener" aria-label="twitter"><i class="fa fa-twitter"></i></a></li>
                                        <li><a href="#" rel="noopener" aria-label="flickr"><i class="fa fa-flickr"></i></a></li>
                                        <li><a href="#" rel="noopener" aria-label="linkedin"><i class="fa fa-linkedin"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="top_bar_contact_item">
                                <a href="https://app.rmiclass.net/panel">Connexion</a>
                            </div>
                            <div class="top_bar_contact_item">
                                <a href="https://app.rmiclass.net/register">S'enregistrer</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- site-header-menu -->
            <div id="site-header-menu" class="site-header-menu ttm-textcolor-dark">
                <div class="site-header-menu-inner ttm-stickable-header">
                    <div class="container">
                        <div class="ttm-bg ttm-col-bgcolor-yes ttm-right-span ttm-bgcolor-white z-index-2">
                            <div class="ttm-col-wrapper-bg-layer ttm-bg-layer"></div>
                            <div class="layer-content">
                                <div class="site-navigation d-flex flex-row">
                                    <div class="site-branding">
                                        <a class="home-link" href="https://rmiclass.net/" title="Uniaro" rel="home">
                                            <img id="logo-img" class="img-fluid auto_size" alt="image" width="136" height="45" src="update/images/logo_rmi.png">
                                        </a>
                                    </div>
                                    <div class="btn-show-menu-mobile menubar menubar--squeeze">
                                        <span class="menubar-box">
                                            <span class="menubar-inner"></span>
                                        </span>
                                    </div>
                                    <nav class="main-menu menu-mobile" id="menu">
                                        <ul class="menu">
                                            <li class="mega-menu-item">
                                                <a href="https://rmiclass.net/">Accueil</a>
                                            </li>
                                            <li class="mega-menu-item">
                                                <a href="#" class="mega-menu-link">Formations</a>
                                                <ul class="mega-submenu">
                                                    <li><a href="https://app.rmiclass.net/course/Initiation-au-Trading">Initiation au Trading</a></li>
                                                    <li><a href="https://app.rmiclass.net/course/devenez-trader-pro">Formation Complète en Trading</a></li>
                                                </ul>
                                            </li>
                                            <li class="mega-menu-item active">
                                                <a href="#" class="mega-menu-link">RMI Class</a>
                                                <ul class="mega-submenu">
                                                    <li><a href="https://rmiclass.net/a-propos">A propos</a></li>
                                                    <li><a href="#">Partner Program</a></li>
                                                    <li><a href="https://rmiclass.net/faq">Faq</a></li>
                                                    <li><a href="/rendez-vous">Rendez-vous</a></li>
                                                </ul>
                                            </li>
                                            <li class="mega-menu-item">
                                                <a href="https://rmiclass.net/instructeurs">Instructeurs</a>
                                            </li>
                                            <li class="mega-menu-item">
                                                <a href="https://rmiclass.net/communaute" class="mega-menu-link">Communauté</a>
                                            </li>
                                        </ul>
                                    </nav>
                                    <div class="header_extra d-flex flex-row align-items-center justify-content-end ml-auto">
                                        <div class="header_search">
                                            <a href="#" class="btn-default search_btn" rel="noopener" aria-label="search"><i class="fa fa-search"></i></a>
                                            <div class="header_search_content">
                                                <form id="searchbox" method="get" action="#">
                                                    <input class="search_query" type="text" id="search_query_top" name="s" placeholder="Enter Keyword" value="">
                                                    <button type="submit" class="btn close-search" aria-label="Right Align"><i class="fa fa-search"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="header_btn">
                                            <a class="ttm-btn ttm-btn-style-fill ttm-icon-btn-right ttm-btn-size-md ttm-btn-color-skincolor" href="https://app.rmiclass.net/panel">Démarrez<i class="flaticon-right-arrow-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- page-title -->
        <div class="ttm-page-title-row">
            <div class="ttm-page-title-row-inner ttm-bgcolor-darkgrey">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <div class="page-title-heading">
                                <h2 class="title">Rendez-vous</h2>
                            </div>
                            <div class="breadcrumb-wrapper">
                                <span>
                                    <i class="ti ti-home"></i>
                                    <a title="Homepage" href="https://rmiclass.net/">Accueil</a>
                                </span>
                                <span>Rendez-vous</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--site-main start-->
        <div class="site-main">
           

            <!-- SECTION FORMULAIRE DE RENDEZ-VOUS -->
            <section class="appointment-form-section">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="appointment-form-container">
                                <div class="text-center mb-4">
                                    <i class="fa fa-calendar-check-o form-icon"></i>
                                    <h2 class="mb-3">Réservez un Rendez-vous</h2>
                                    <p class="text-muted">Besoin d'un accompagnement personnalisé ? Prenez rendez-vous avec un de nos experts en trading.</p>
                                </div>

                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fa fa-check-circle"></i> {{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                             
                                    <form action="{{ route('appointments.store') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="full_name"><i class="fa fa-user"></i> Nom complet *</label>
                                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                                           id="full_name" name="full_name" value="{{ old('full_name') }}" 
                                                           placeholder="Ex: Jean Dupont" required>
                                                    @error('full_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email"><i class="fa fa-envelope"></i> Email *</label>
                                                    <input type="text" class="form-control @error('email') is-invalid @enderror" 
                                                           id="email" name="email" value="{{ old('email') }}" 
                                                           placeholder="Ex: jean.dupont@example.com" required>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="subject"><i class="fa fa-tag"></i> Sujet du rendez-vous *</label>
                                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                                           id="subject" name="subject" value="{{ old('subject') }}" 
                                                           placeholder="Ex: Coaching personnalisé en trading" required>
                                                    @error('subject')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="appointment_date"><i class="fa fa-calendar"></i> Date et heure souhaitées *</label>
                                                    <input type="datetime-local" class="form-control @error('appointment_date') is-invalid @enderror" 
                                                           id="appointment_date" name="appointment_date" 
                                                           value="{{ old('appointment_date') }}" 
                                                           min="{{ now()->addHours(2)->format('Y-m-d\TH:i') }}" required>
                                                    @error('appointment_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="duration_minutes"><i class="fa fa-clock-o"></i> Durée estimée *</label>
                                                    <select class="form-control @error('duration_minutes') is-invalid @enderror" 
                                                            id="duration_minutes" name="duration_minutes" required>
                                                        <option value="30" {{ old('duration_minutes') == 30 ? 'selected' : '' }}>30 minutes</option>
                                                        <option value="60" {{ old('duration_minutes', 60) == 60 ? 'selected' : '' }}>1 heure</option>
                                                        <option value="90" {{ old('duration_minutes') == 90 ? 'selected' : '' }}>1 heure 30</option>
                                                        <option value="120" {{ old('duration_minutes') == 120 ? 'selected' : '' }}>2 heures</option>
                                                    </select>
                                                    @error('duration_minutes')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="message"><i class="fa fa-comment"></i> Décrivez vos besoins *</label>
                                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                                              id="message" name="message" rows="5" 
                                                              placeholder="Expliquez brièvement ce que vous souhaitez aborder lors de ce rendez-vous..." 
                                                              required>{{ old('message') }}</textarea>
                                                    @error('message')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">Maximum 1000 caractères</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-appointment">
                                                <i class="fa fa-paper-plane"></i> Envoyer la demande
                                            </button>
                                        </div>

                                        <p class="text-center text-muted mt-3 mb-0">
                                            <small><i class="fa fa-info-circle"></i> Vous recevrez une confirmation par email une fois votre rendez-vous validé.</small>
                                        </p>
                                    </form>
                              
                            </div>
                        </div>
                    </div>
                </div>
            </section>

         
        </div>

        <!--footer start-->
        <footer class="footer ttm-bgcolor-darkgrey ttm-textcolor-white clearfix">
            <div class="second-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 widget-area">
                            <div class="widget widget_text clearfix">
                                <div class="footer-logo">
                                    <img id="footer-logo-img" class="img-fluid auto_size" alt="image" width="136" height="45" src="update/images/logoblanc.png">
                                </div>
                                <div class="textwidget widget-text">
                                    <p class="pb-10 pr-30 res-575-pr-0">La Rmi class est une plateforme d'éducation et d'accompagnement en ligne dans le domaine du Trading sur les marchés financiers.</p>
                                </div>
                            </div>
                            <div class="widget widget_timing clearfix">
                                <h3 class="widget-title">Disponibilité de nos Coachs</h3>
                                <div class="ttm-timelist-block-wrapper">
                                    <ul class="ttm-timelist-block pr-15">
                                        <li>Lun - sam <span class="service-time">09H - 17H</span></li>
                                        <li>Dimanche<span class="service-time">Fermé</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 widget-area">
                            <div class="widget widget_nav_menu clearfix">
                                <h3 class="widget-title">Nos domaines de Formation</h3>
                                <ul id="menu-footer-quick-links">
                                    <li><a href="https://app.rmiclass.net/course/devenez-trader-pro">Trading pour débutant</a></li>
                                    <li><a href="https://app.rmiclass.net/course/devenez-trader-expert">Trading pour trader avancé</a></li>
                                    <li><a href="#">Forex</a></li>
                                    <li><a href="#">Indices synthétiques</a></li>
                                    <li><a href="#">Cryptomonnaie</a></li>
                                    <li><a href="#">Gestion de Risque</a></li>
                                    <li><a href="#">Le management des émotions</a></li>
                                    <li><a href="#">Le développement personnel</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 widget-area">
                            <div class="widget widget_nav_menu clearfix">
                                <h3 class="widget-title">Contactez-nous</h3>
                                <ul class="widget_contact_wrapper">
                                    <li><h3>location:</h3> Afrique, Bénin , Cotonou , Q Sedjro St Michel, C557 .</li>
                                    <li><h3>Email:</h3><a href="mailto:support@rmiclass.net">support@rmiclass.net</a></li>
                                    <li><h3>Call Us:</h3>+229 99009193 <br /> +229 97203188</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 widget-area">
                            <div class="widget widget-recent-post clearfix">
                                <h3 class="widget-title">Nos récentes Formation</h3>
                                <ul class="widget-post ttm-recent-post-list pr-5">
                                    <li>
                                        <a href="#"><img class="img-fluid" alt="post-img" src="update/images/b1.jpg"></a>
                                        <span class="post-date">12 Juin, 2024</span>
                                        <a href="#">Formation complète en Trading</a>
                                    </li>
                                    <li>
                                        <a href="#"><img class="img-fluid" alt="post-img" src="update/images/b2.jpg"></a>
                                        <span class="post-date">28 Juillet, 2024</span>
                                        <a href="#">Devenir Expert Trader. Formation complète</a>
                                    </li>
                                    <li>
                                        <a href="#"><img class="img-fluid" alt="post-img" src="update/images/b3.jpg"></a>
                                        <span class="post-date">À venir en 2024</span>
                                        <a href="#">Cryptomaster</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bottom-footer-text">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="bottom-lt-side-footer">
                                <div class="pb-5">Copyright © 2024 RMI Class</div>
                                <div>
                                    <ul id="menu-footer-menu" class="footer-nav-menu">
                                        <li><a href="#">Coaching Privé</a></li>
                                        <li><a href="/privacy">Politique de Confidentialité</a></li>
                                        <li><a href="/cgu">Conditions Générales d'Utilisation</a></li>
                                        <li><a href="#">Contact</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="bottom-rt-side-footer">
                                <div class="d-sm-flex flex-row align-items-center justify-content-lg-end justify-content-center">
                                    <div class="ml-30 pl-30 border-left res-575-ml-0 res-575-pl-0 res-575-mt-20"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <a id="totop" href="#top">
            <i class="fa fa-angle-up"></i>
        </a>
    </div>

    <script src="update/js/jquery-3.6.0.min.js"></script>
    <script src="update/js/jquery-migrate-3.3.2.min.js"></script>
    <script src="update/js/bootstrap.min.js"></script>
    <script src="update/js/jquery.easing.js"></script>
    <script src="update/js/jquery-waypoints.js"></script>
    <script src="update/js/jquery-validate.js"></script>
    <script src="update/js/jquery.prettyPhoto.js"></script>
    <script src="update/js/slick.min.js"></script>
    <script src="update/js/numinate.min.js"></script>
    <script src="update/js/imagesloaded.min.js"></script>
    <script src="update/js/jquery-isotope.js"></script>
    <script src="update/js/main.js"></script>
</body>
</html>