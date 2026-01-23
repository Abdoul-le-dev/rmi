<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade Hub - Plateforme de Formation Trading</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ========================================
           CONFIGURATION TAILWIND PERSONNALISÉE
        ======================================== */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }

        /* Variables CSS personnalisées */
        :root {
            --primary-color: #6366F1;
            --primary-hover: #4F46E5;
            --secondary-color: #F3F4F6;
            --text-primary: #111827;
            --text-secondary: #6B7280;
            --border-color: #E5E7EB;
            --success-color: #10B981;
        }

        /* ========================================
           ANIMATIONS PERSONNALISÉES
        ======================================== */
        
        /* Animation de fade-in pour les éléments */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animation de pulse pour les notifications */
        @keyframes pulse-notification {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        /* Animation de slide pour le menu mobile */
        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
            }
            to {
                transform: translateX(0);
            }
        }

        /* Animation de hover pour les boutons */
        @keyframes buttonHover {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        /* ========================================
           CLASSES UTILITAIRES
        ======================================== */
        
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        .pulse-dot {
            animation: pulse-notification 2s infinite;
        }

        .slide-in {
            animation: slideInLeft 0.3s ease-out;
        }

        /* Effet de hover sur les cartes */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Effet de ripple sur les boutons */
        .btn-ripple {
            position: relative;
            overflow: hidden;
        }

        .btn-ripple::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-ripple:active::before {
            width: 300px;
            height: 300px;
        }

        /* Scrollbar personnalisée */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #F3F4F6;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #D1D5DB;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #9CA3AF;
        }

        /* Badge de notification */
        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            min-width: 18px;
            height: 18px;
            background: #EF4444;
            color: white;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 600;
            border: 2px solid white;
        }

        /* Gradient de fond */
        .gradient-bg {
            background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
        }

        /* Avatar avec border animé */
        .avatar-border {
            position: relative;
        }

        .avatar-border::before {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 50%;
            background: linear-gradient(45deg, #6366F1, #8B5CF6, #EC4899);
            z-index: -1;
            animation: rotate 3s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        /* Menu mobile overlay */
        .mobile-menu-overlay {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        /* Post card shadow */
        .post-card {
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .post-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            border-color: var(--primary-color);
        }

        /* Button loading animation */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 1s linear infinite;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    <!-- ========================================
         HEADER PRINCIPAL
    ======================================== -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Logo et titre -->
                <div class="flex items-center space-x-3">
                    <!-- Bouton menu mobile -->
                    <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-bars text-gray-600 text-xl"></i>
                    </button>
                    
                    <!-- Logo -->
                    <div class="flex items-center space-x-2 cursor-pointer">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-lg">
                            <i class="fas fa-play text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold">
                            Trade <span class="text-indigo-600">Hub</span>
                        </span>
                    </div>
                </div>

                <!-- Barre de recherche (desktop uniquement) -->
                <div class="hidden md:flex flex-1 max-w-md mx-8">
                    <div class="relative w-full">
                        <input 
                            type="text" 
                            placeholder="Rechercher..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Actions utilisateur -->
                <div class="flex items-center space-x-4">
                    <!-- Icône recherche mobile -->
                    <button class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-search text-gray-600"></i>
                    </button>

                    <!-- Notifications -->
                    <button class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-bell text-gray-600 text-xl"></i>
                        <span class="notification-badge pulse-dot">3</span>
                    </button>

                    <!-- Messages -->
                    <button class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-comment-dots text-gray-600 text-xl"></i>
                        <span class="notification-badge pulse-dot">2</span>
                    </button>

                    <!-- Avatar utilisateur -->
                    <div class="relative">
                        <button id="user-menu-btn" class="flex items-center space-x-2 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                            <img 
                                src="https://i.pravatar.cc/150?img=12" 
                                alt="Avatar" 
                                class="w-9 h-9 rounded-full object-cover ring-2 ring-indigo-500"
                            >
                        </button>
                        
                        <!-- Dropdown menu (caché par défaut) -->
                        <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mon Profil</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Paramètres</a>
                            <hr class="my-2">
                            <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Déconnexion</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ========================================
         LAYOUT PRINCIPAL (3 COLONNES)
    ======================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- ========================================
                 SIDEBAR GAUCHE - NAVIGATION
            ======================================== -->
            <aside class="hidden lg:block lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-24">
                    
                    <!-- Profil utilisateur -->
                    <div class="p-6 text-center border-b border-gray-200 gradient-bg">
                        <div class="relative inline-block mb-3">
                            <img 
                                src="https://i.pravatar.cc/150?img=12" 
                                alt="Profil" 
                                class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg mx-auto"
                            >
                            <div class="absolute bottom-0 right-0 w-6 h-6 bg-green-500 border-3 border-white rounded-full"></div>
                        </div>
                        <h3 class="font-bold text-white text-lg">CHOUTI Abdoulaye</h3>
                        <p class="text-indigo-100 text-sm">Modérateur</p>
                        <div class="flex items-center justify-center space-x-1 mt-1">
                            <i class="fas fa-check-circle text-white text-xs"></i>
                            <span class="text-white text-xs">Vérifié</span>
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="grid grid-cols-3 gap-4 p-4 bg-gray-50">
                        <div class="text-center">
                            <p class="font-bold text-gray-900 text-lg">500k</p>
                            <p class="text-xs text-gray-500">Post</p>
                        </div>
                        <div class="text-center border-x border-gray-200">
                            <p class="font-bold text-gray-900 text-lg">23.5M</p>
                            <p class="text-xs text-gray-500">Followers</p>
                        </div>
                        <div class="text-center">
                            <p class="font-bold text-gray-900 text-lg">50</p>
                            <p class="text-xs text-gray-500">Following</p>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav class="p-3">
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-indigo-50 text-indigo-600 mb-2 transition-all hover:bg-indigo-100">
                            <i class="fas fa-home text-lg"></i>
                            <span class="font-medium">Communautés</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 mb-2 transition-all">
                            <i class="fas fa-book text-lg"></i>
                            <span class="font-medium">Classrooms</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 mb-2 transition-all">
                            <i class="fas fa-calendar-alt text-lg"></i>
                            <span class="font-medium">Événements</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 mb-2 transition-all">
                            <i class="fas fa-map-marker-alt text-lg"></i>
                            <span class="font-medium">Localisations</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 mb-2 transition-all">
                            <i class="fas fa-users text-lg"></i>
                            <span class="font-medium">Vos Sessions</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 mb-2 transition-all">
                            <i class="fas fa-cog text-lg"></i>
                            <span class="font-medium">Paramètres</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 transition-all">
                            <i class="fas fa-question-circle text-lg"></i>
                            <span class="font-medium">Centre d'aide</span>
                        </a>
                    </nav>
                </div>
            </aside>

            <!-- ========================================
                 COLONNE CENTRALE - FEED
            ======================================== -->
            <main class="lg:col-span-6">
                
                <!-- Message de bienvenue -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">
                        Bienvenue Abdoulaye 👋 ? Que pensez-vous aujourd'hui ?
                    </h2>
                    <p class="text-sm text-gray-500 mb-4">
                        Standard glass, 3.8GHz 8-core 10th-generation Intel Core i7 processor, Turbo Boost up to 5.0GHz, 16GB 2666MHz DDR4 memory...
                    </p>
                    
                    <!-- Zone de création de post -->
                    <div class="space-y-4">
                        <textarea 
                            placeholder="Partagez vos idées, stratégies de trading..." 
                            class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                            rows="3"
                        ></textarea>
                        
                        <!-- Actions de post -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <button class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Emoji">
                                    <i class="far fa-smile text-gray-600 text-xl"></i>
                                </button>
                                <button class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Image">
                                    <i class="far fa-image text-gray-600 text-xl"></i>
                                </button>
                                <button class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Vidéo">
                                    <i class="fas fa-video text-gray-600 text-xl"></i>
                                </button>
                                <button class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Pièce jointe">
                                    <i class="fas fa-paperclip text-gray-600 text-xl"></i>
                                </button>
                                <button class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Localisation">
                                    <i class="fas fa-map-marker-alt text-gray-600 text-xl"></i>
                                </button>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button class="px-5 py-2 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition-all">
                                    Brouillon
                                </button>
                                <button class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-all btn-ripple">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Envoyer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Posts Feed -->
                <div class="space-y-4">
                    
                    <!-- Post 1 -->
                    <article class="bg-white rounded-xl shadow-sm post-card p-6 fade-in">
                        <!-- Header du post -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <img 
                                    src="https://i.pravatar.cc/150?img=33" 
                                    alt="Junior TOSSA" 
                                    class="w-12 h-12 rounded-full object-cover"
                                >
                                <div>
                                    <h4 class="font-semibold text-gray-900">Junior TOSSA</h4>
                                    <p class="text-sm text-gray-500">Modérateur</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="text-xs text-gray-400">il y a 1 min</span>
                                <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                    <i class="fas fa-ellipsis-h text-gray-400"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Contenu du post -->
                        <div class="mb-4">
                            <p class="text-gray-700 leading-relaxed mb-2">
                                🚀👍<br>
                                Le marché ne dort jamais... mais ton plan de trading, oui : il doit être clair et discipliné.
                            </p>
                            <p class="text-indigo-600 font-semibold">
                                Trade avec la tête, pas avec le stress !
                            </p>
                        </div>

                        <!-- Interactions -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center space-x-6">
                                <button class="flex items-center space-x-2 text-gray-500 hover:text-indigo-600 transition-colors">
                                    <i class="far fa-comment"></i>
                                    <span class="text-sm font-medium">47.5k</span>
                                </button>
                                <button class="flex items-center space-x-2 text-gray-500 hover:text-red-500 transition-colors">
                                    <i class="far fa-heart"></i>
                                    <span class="text-sm font-medium">650.3k</span>
                                </button>
                            </div>
                            <div class="flex -space-x-2">
                                <img src="https://i.pravatar.cc/150?img=1" class="w-8 h-8 rounded-full border-2 border-white" alt="">
                                <img src="https://i.pravatar.cc/150?img=2" class="w-8 h-8 rounded-full border-2 border-white" alt="">
                                <img src="https://i.pravatar.cc/150?img=3" class="w-8 h-8 rounded-full border-2 border-white" alt="">
                                <img src="https://i.pravatar.cc/150?img=4" class="w-8 h-8 rounded-full border-2 border-white" alt="">
                            </div>
                        </div>
                    </article>

                    <!-- Post 2 avec image -->
                    <article class="bg-white rounded-xl shadow-sm post-card p-6 fade-in">
                        <!-- Header du post -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <img 
                                    src="https://i.pravatar.cc/150?img=45" 
                                    alt="Zeynab HOUNGBE" 
                                    class="w-12 h-12 rounded-full object-cover"
                                >
                                <div>
                                    <h4 class="font-semibold text-gray-900">Zeynab HOUNGBE</h4>
                                    <p class="text-sm text-gray-500">Coach</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="text-xs text-gray-400">il y a 2 heures</span>
                                <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                    <i class="fas fa-ellipsis-h text-gray-400"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Contenu du post -->
                        <div class="mb-4">
                            <p class="text-gray-700 leading-relaxed mb-4">
                                🚀🔥<br>
                                Un bon trade, c'est 80% de patience et 20% d'action.<br>
                                Attends le bon signal, pas le frisson.
                            </p>
                            
                            <!-- Image du post -->
                            <div class="rounded-lg overflow-hidden">
                                <img 
                                    src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800" 
                                    alt="Bitcoin" 
                                    class="w-full h-64 object-cover hover:scale-105 transition-transform duration-300"
                                >
                            </div>
                        </div>

                        <!-- Interactions -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center space-x-6">
                                <button class="flex items-center space-x-2 text-gray-500 hover:text-indigo-600 transition-colors">
                                    <i class="far fa-comment"></i>
                                    <span class="text-sm font-medium">234</span>
                                </button>
                                <button class="flex items-center space-x-2 text-gray-500 hover:text-red-500 transition-colors">
                                    <i class="far fa-heart"></i>
                                    <span class="text-sm font-medium">5.2k</span>
                                </button>
                            </div>
                            <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                <i class="fas fa-share text-gray-500"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Post 3 -->
                    <article class="bg-white rounded-xl shadow-sm post-card p-6 fade-in">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <img 
                                    src="https://i.pravatar.cc/150?img=68" 
                                    alt="Sarah MARTIN" 
                                    class="w-12 h-12 rounded-full object-cover"
                                >
                                <div>
                                    <h4 class="font-semibold text-gray-900">Sarah MARTIN</h4>
                                    <p class="text-sm text-gray-500">Trader Pro</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="text-xs text-gray-400">il y a 5 heures</span>
                                <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                    <i class="fas fa-ellipsis-h text-gray-400"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <p class="text-gray-700 leading-relaxed">
                                📊 Analyse du jour : Le marché montre des signes de consolidation. 
                                C'est le moment parfait pour affiner votre stratégie et attendre les bonnes opportunités. 
                                <span class="text-indigo-600 font-semibold">#Trading #Strategy #Patience</span>
                            </p>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center space-x-6">
                                <button class="flex items-center space-x-2 text-gray-500 hover:text-indigo-600 transition-colors">
                                    <i class="far fa-comment"></i>
                                    <span class="text-sm font-medium">89</span>
                                </button>
                                <button class="flex items-center space-x-2 text-gray-500 hover:text-red-500 transition-colors">
                                    <i class="far fa-heart"></i>
                                    <span class="text-sm font-medium">1.8k</span>
                                </button>
                            </div>
                            <div class="flex -space-x-2">
                                <img src="https://i.pravatar.cc/150?img=5" class="w-8 h-8 rounded-full border-2 border-white" alt="">
                                <img src="https://i.pravatar.cc/150?img=6" class="w-8 h-8 rounded-full border-2 border-white" alt="">
                                <img src="https://i.pravatar.cc/150?img=7" class="w-8 h-8 rounded-full border-2 border-white" alt="">
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Bouton charger plus -->
                <div class="mt-6 text-center">
                    <button class="px-6 py-3 bg-white border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition-all">
                        Charger plus de posts
                    </button>
                </div>
            </main>

            <!-- ========================================
                 SIDEBAR DROITE - SUGGESTIONS
            ======================================== -->
            <aside class="hidden lg:block lg:col-span-3">
                <div class="sticky top-24 space-y-6">
                    
                    <!-- Personnes à suivre -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="font-bold text-gray-900">Personnes que vous pourriez connaître</h3>
                        </div>
                        <div class="p-4 space-y-4 max-h-96 overflow-y-auto custom-scrollbar">
                            <!-- Personne 1 -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <img 
                                        src="https://i.pravatar.cc/150?img=11" 
                                        alt="Charbel MEHINTO" 
                                        class="w-10 h-10 rounded-full object-cover"
                                    >
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Charbel MEHINTO</p>
                                        <p class="text-xs text-gray-500">Trader</p>
                                    </div>
                                </div>
                                <button class="px-4 py-1.5 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-all btn-ripple">
                                    Suivre
                                </button>
                            </div>

                            <!-- Personne 2 -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <img 
                                        src="https://i.pravatar.cc/150?img=22" 
                                        alt="Junias SIHOU" 
                                        class="w-10 h-10 rounded-full object-cover"
                                    >
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Junias SIHOU</p>
                                        <p class="text-xs text-gray-500">Analyste</p>
                                    </div>
                                </div>
                                <button class="px-4 py-1.5 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-all btn-ripple">
                                    Suivre
                                </button>
                            </div>

                            <!-- Personne 3 -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <img 
                                        src="https://i.pravatar.cc/150?img=35" 
                                        alt="Pierre SOUROU" 
                                        class="w-10 h-10 rounded-full object-cover"
                                    >
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Pierre SOUROU</p>
                                        <p class="text-xs text-gray-500">Coach</p>
                                    </div>
                                </div>
                                <button class="px-4 py-1.5 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-all btn-ripple">
                                    Suivre
                                </button>
                            </div>

                            <!-- Personne 4 -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <img 
                                        src="https://i.pravatar.cc/150?img=47" 
                                        alt="Samson R. VOOBE" 
                                        class="w-10 h-10 rounded-full object-cover"
                                    >
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Samson R. VOOBE</p>
                                        <p class="text-xs text-gray-500">Expert</p>
                                    </div>
                                </div>
                                <button class="px-4 py-1.5 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-all btn-ripple">
                                    Suivre
                                </button>
                            </div>

                            <!-- Personne 5 -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <img 
                                        src="https://i.pravatar.cc/150?img=58" 
                                        alt="Julien HOUNGBO" 
                                        class="w-10 h-10 rounded-full object-cover"
                                    >
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Julien HOUNGBO</p>
                                        <p class="text-xs text-gray-500">Mentor</p>
                                    </div>
                                </div>
                                <button class="px-4 py-1.5 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-all btn-ripple">
                                    Suivre
                                </button>
                            </div>

                            <!-- Personne 6 -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <img 
                                        src="https://i.pravatar.cc/150?img=64" 
                                        alt="Fabrice SOSSA" 
                                        class="w-10 h-10 rounded-full object-cover"
                                    >
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Fabrice SOSSA</p>
                                        <p class="text-xs text-gray-500">Formateur</p>
                                    </div>
                                </div>
                                <button class="px-4 py-1.5 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-all btn-ripple">
                                    Suivre
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Sessions recommandées -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="font-bold text-gray-900">Sessions Recommandées pour vous</h3>
                        </div>
                        <div class="p-4 space-y-3">
                            <!-- Session 1 -->
                            <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-check text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Traders Profession</p>
                                        <p class="text-xs text-gray-500">12.5k membres</p>
                                    </div>
                                </div>
                                <button class="px-3 py-1.5 border border-indigo-600 text-indigo-600 text-xs rounded-lg hover:bg-indigo-50 transition-all">
                                    Rejoindre
                                </button>
                            </div>

                            <!-- Session 2 -->
                            <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-chart-line text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Crypto Currency</p>
                                        <p class="text-xs text-gray-500">8.2k membres</p>
                                    </div>
                                </div>
                                <button class="px-3 py-1.5 border border-indigo-600 text-indigo-600 text-xs rounded-lg hover:bg-indigo-50 transition-all">
                                    Rejoindre
                                </button>
                            </div>

                            <!-- Session 3 -->
                            <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-users text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Global Trade Union</p>
                                        <p class="text-xs text-gray-500">15.8k membres</p>
                                    </div>
                                </div>
                                <button class="px-3 py-1.5 border border-indigo-600 text-indigo-600 text-xs rounded-lg hover:bg-indigo-50 transition-all">
                                    Rejoindre
                                </button>
                            </div>

                            <!-- Session 4 -->
                            <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-star text-yellow-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Solaris Traders</p>
                                        <p class="text-xs text-gray-500">6.4k membres</p>
                                    </div>
                                </div>
                                <button class="px-3 py-1.5 border border-indigo-600 text-indigo-600 text-xs rounded-lg hover:bg-indigo-50 transition-all">
                                    Rejoindre
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <!-- ========================================
         MENU MOBILE (Overlay)
    ======================================== -->
    <div id="mobile-menu" class="fixed inset-0 z-50 hidden">
        <!-- Overlay -->
        <div class="mobile-menu-overlay absolute inset-0" id="mobile-menu-overlay"></div>
        
        <!-- Menu sidebar -->
        <div class="slide-in absolute left-0 top-0 bottom-0 w-80 bg-white shadow-2xl overflow-y-auto">
            <!-- Header -->
            <div class="gradient-bg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold">Menu</h3>
                    <button id="close-mobile-menu" class="p-2 hover:bg-white/20 rounded-lg transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="flex items-center space-x-3">
                    <img 
                        src="https://i.pravatar.cc/150?img=12" 
                        alt="Profil" 
                        class="w-16 h-16 rounded-full border-3 border-white"
                    >
                    <div>
                        <p class="font-bold">CHOUTI Abdoulaye</p>
                        <p class="text-sm text-indigo-100">Modérateur</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="p-4">
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-indigo-50 text-indigo-600 mb-2">
                    <i class="fas fa-home text-lg w-6"></i>
                    <span class="font-medium">Communautés</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 mb-2">
                    <i class="fas fa-book text-lg w-6"></i>
                    <span class="font-medium">Classrooms</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 mb-2">
                    <i class="fas fa-calendar-alt text-lg w-6"></i>
                    <span class="font-medium">Événements</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 mb-2">
                    <i class="fas fa-map-marker-alt text-lg w-6"></i>
                    <span class="font-medium">Localisations des Membres</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 mb-2">
                    <i class="fas fa-users text-lg w-6"></i>
                    <span class="font-medium">Vos Sessions</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 mb-2">
                    <i class="fas fa-cog text-lg w-6"></i>
                    <span class="font-medium">Paramètres</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-question-circle text-lg w-6"></i>
                    <span class="font-medium">Centre d'aide</span>
                </a>
            </nav>

            <!-- Stats -->
            <div class="mx-4 my-6 p-4 bg-gray-50 rounded-lg">
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="font-bold text-lg">500k</p>
                        <p class="text-xs text-gray-500">Posts</p>
                    </div>
                    <div class="border-x border-gray-300">
                        <p class="font-bold text-lg">23.5M</p>
                        <p class="text-xs text-gray-500">Followers</p>
                    </div>
                    <div>
                        <p class="font-bold text-lg">50</p>
                        <p class="text-xs text-gray-500">Following</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================
         JAVASCRIPT
    ======================================== -->
    <script>
        // ========================================
        // GESTION DU MENU MOBILE
        // ========================================
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const closeMobileMenu = document.getElementById('close-mobile-menu');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');

        // Ouvrir le menu mobile
        mobileMenuBtn?.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });

        // Fermer le menu mobile
        const closeMobileMenuFn = () => {
            mobileMenu.classList.add('hidden');
            document.body.style.overflow = 'auto';
        };

        closeMobileMenu?.addEventListener('click', closeMobileMenuFn);
        mobileMenuOverlay?.addEventListener('click', closeMobileMenuFn);

        // ========================================
        // GESTION DU DROPDOWN UTILISATEUR
        // ========================================
        const userMenuBtn = document.getElementById('user-menu-btn');
        const userDropdown = document.getElementById('user-dropdown');

        userMenuBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });

        // Fermer le dropdown en cliquant ailleurs
        document.addEventListener('click', (e) => {
            if (!userMenuBtn?.contains(e.target) && !userDropdown?.contains(e.target)) {
                userDropdown?.classList.add('hidden');
            }
        });

        // ========================================
        // ANIMATIONS AU SCROLL
        // ========================================
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                }
            });
        }, observerOptions);

        // Observer tous les posts
        document.querySelectorAll('article').forEach(article => {
            observer.observe(article);
        });

        // ========================================
        // GESTION DES LIKES
        // ========================================
        document.querySelectorAll('.fa-heart').forEach(heart => {
            heart.parentElement.addEventListener('click', function(e) {
                e.preventDefault();
                const icon = this.querySelector('.fa-heart');
                
                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas', 'text-red-500');
                    
                    // Animation de pulsation
                    icon.style.transform = 'scale(1.3)';
                    setTimeout(() => {
                        icon.style.transform = 'scale(1)';
                    }, 200);
                } else {
                    icon.classList.remove('fas', 'text-red-500');
                    icon.classList.add('far');
                }
            });
        });

        // ========================================
        // SMOOTH SCROLL
        // ========================================
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // ========================================
        // LAZY LOADING DES IMAGES
        // ========================================
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src || img.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }

        // ========================================
        // NOTIFICATION TOAST (Exemple)
        // ========================================
        function showNotification(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 px-6 py-4 rounded-lg shadow-lg text-white z-50 fade-in ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' : 
                'bg-indigo-500'
            }`;
            toast.textContent = message;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Exemple d'utilisation
        // showNotification('Bienvenue sur Trade Hub !', 'success');

        // ========================================
        // GESTION DU BOUTON "SUIVRE"
        // ========================================
        document.querySelectorAll('button:contains("Suivre")').forEach(btn => {
            btn.addEventListener('click', function() {
                if (this.textContent.trim() === 'Suivre') {
                    this.textContent = 'Suivi';
                    this.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                    this.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                    showNotification('Vous suivez maintenant cet utilisateur', 'success');
                } else {
                    this.textContent = 'Suivre';
                    this.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                    this.classList.add('bg-indigo-600', 'hover:bg-indigo-700', 'text-white');
                }
            });
        });

        // ========================================
        // PRÉCHARGEMENT DES IMAGES
        // ========================================
        function preloadImages() {
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                const src = img.dataset.src || img.src;
                if (src) {
                    const preloadImg = new Image();
                    preloadImg.src = src;
                }
            });
        }

        // Précharger au chargement de la page
        window.addEventListener('load', preloadImages);

        // ========================================
        // PERFORMANCE MONITORING
        // ========================================
        if ('performance' in window) {
            window.addEventListener('load', () => {
                const perfData = window.performance.timing;
                const pageLoadTime = perfData.loadEventEnd - perfData.navigationStart;
                console.log(`Page chargée en ${pageLoadTime}ms`);
            });
        }
    </script>
</body>
</html>