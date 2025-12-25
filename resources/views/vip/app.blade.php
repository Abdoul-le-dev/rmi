
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Trade Hub - Plateforme de formation en trading">
    <meta name="author" content="Trade Hub">
    <title>Trade Hub - Plateforme de Formation Trading</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS Personnalisé -->
    <link rel="stylesheet" href="styles.css">
    {{-- CSS global --}}
    <link rel="stylesheet" href="{{ asset('assets/vip/vip.css') }}">

    {{-- JS global --}}
    <script src="{{ asset('assets/vip/vip.js') }}"></script>
    
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    <!-- ========================================
         HEADER PRINCIPAL
    ======================================== -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Logo et titre -->
                <div class="flex items-center space-x-3">
                    <!-- Bouton menu mobile -->
                    <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors" aria-label="Menu mobile">
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
                            aria-label="Rechercher"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Actions utilisateur -->
                <div class="flex items-center space-x-4">
                    <!-- Icône recherche mobile -->
                    <button class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors" aria-label="Rechercher">
                        <i class="fas fa-search text-gray-600"></i>
                    </button>

                    <!-- Notifications -->
                    <button id="notifications-btn" class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors" aria-label="Notifications">
                        <i class="fas fa-bell text-gray-600 text-xl"></i>
                        <span class="notification-badge pulse-dot">5</span>
                    </button>

                    <!-- Messages -->
                    <button id="messages-btn" class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors" aria-label="Messages">
                        <i class="fas fa-comment-dots text-gray-600 text-xl"></i>
                        <span class="notification-badge pulse-dot">3</span>
                    </button>

                    <!-- Avatar utilisateur -->
                    <div class="relative">
                        <button id="user-menu-btn" class="flex items-center space-x-2 p-1 rounded-lg hover:bg-gray-100 transition-colors" aria-label="Menu utilisateur">
                            <img 
                                src="https://i.pravatar.cc/150?img=12" 
                                alt="Avatar utilisateur" 
                                class="w-9 h-9 rounded-full object-cover ring-2 ring-indigo-500"
                            >
                        </button>
                        
                        <!-- Dropdown menu utilisateur -->
                        <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 py-2 scale-in">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">CHOUTI Abdoulaye</p>
                                <p class="text-xs text-gray-500">abdoulaye@tradehub.com</p>
                            </div>
                            <a href="#" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-user w-5 text-gray-400"></i>
                                <span class="ml-3">Votre compte</span>
                            </a>
                            <a href="#" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-envelope w-5 text-gray-400"></i>
                                <span class="ml-3">Vos messages</span>
                            </a>
                            <a href="#" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-bell w-5 text-gray-400"></i>
                                <span class="ml-3">Notifications</span>
                            </a>
                            <a href="#" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-question-circle w-5 text-gray-400"></i>
                                <span class="ml-3">Centre d'aide</span>
                            </a>
                            <hr class="my-2">
                            <a href="#" class="flex items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <i class="fas fa-sign-out-alt w-5"></i>
                                <span class="ml-3">Déconnexion</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ========================================
         PANEL NOTIFICATIONS (Coulissant)
    ======================================== -->
    <div id="notifications-panel" class="slide-panel">
        <div class="h-full flex flex-col">
            <!-- Header du panel -->
            <div class="p-4 border-b border-gray-200 flex items-center justify-between bg-white">
                <h2 class="text-lg font-bold text-gray-900">Notifications</h2>
                <button id="close-notifications" class="p-2 hover:bg-gray-100 rounded-lg transition-colors" aria-label="Fermer notifications">
                    <i class="fas fa-times text-gray-600"></i>
                </button>
            </div>

            <!-- Notification mise en avant -->
            <div class="p-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-rocket text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-sm mb-1">Cher utilisateur, Nous avons mis en place de nouvelles fonctionnalités</p>
                        <p class="text-xs text-indigo-100">Allez les consulter !</p>
                    </div>
                    <i class="fas fa-chevron-right text-white/70"></i>
                </div>
            </div>

            <!-- Liste des notifications -->
            <div class="flex-1 overflow-y-auto custom-scrollbar bg-gray-50">
                <div class="p-4 space-y-3">
                    
                    <!-- Notification 1 -->
                    <div class="bg-white rounded-lg p-3 card-hover cursor-pointer border border-gray-100">
                        <div class="flex items-start space-x-3">
                            <img src="https://i.pravatar.cc/150?img=20" alt="Jardis AMÉGAN" class="w-10 h-10 rounded-full flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    <span class="font-semibold">Jardis AMÉGAN</span> souhaite collaborer avec vous
                                </p>
                                <p class="text-xs text-gray-500 mt-1">il y a 5 jours</p>
                            </div>
                        </div>
                    </div>

                    <!-- Notification 2 -->
                    <div class="bg-white rounded-lg p-3 card-hover cursor-pointer border border-gray-100">
                        <div class="flex items-start space-x-3">
                            <img src="https://i.pravatar.cc/150?img=25" alt="Judith KOSSOU" class="w-10 h-10 rounded-full flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    <span class="font-semibold">Judith KOSSOU</span> vous invite à rejoindre une session
                                </p>
                                <p class="text-xs text-gray-500 mt-1">il y a 5 jours</p>
                            </div>
                        </div>
                    </div>

                    <!-- Notification 3 -->
                    <div class="bg-white rounded-lg p-3 card-hover cursor-pointer border border-gray-100">
                        <div class="flex items-start space-x-3">
                            <img src="https://i.pravatar.cc/150?img=30" alt="Patrick HOUNGUE" class="w-10 h-10 rounded-full flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    <span class="font-semibold">Patrick HOUNGUE</span> vous invite à rejoindre une session
                                </p>
                                <p class="text-xs text-gray-500 mt-1">il y a 5 jours</p>
                            </div>
                        </div>
                    </div>

                    <!-- Notification 4 -->
                    <div class="bg-white rounded-lg p-3 card-hover cursor-pointer border border-gray-100">
                        <div class="flex items-start space-x-3">
                            <img src="https://i.pravatar.cc/150?img=35" alt="Josué ZINSALO" class="w-10 h-10 rounded-full flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    <span class="font-semibold">Josué ZINSALO</span> vous souhaite la bienvenue
                                </p>
                                <p class="text-xs text-gray-500 mt-1">il y a 5 jours</p>
                            </div>
                        </div>
                    </div>

                    <!-- Notification 5 -->
                    <div class="bg-white rounded-lg p-3 card-hover cursor-pointer border border-gray-100">
                        <div class="flex items-start space-x-3">
                            <img src="https://i.pravatar.cc/150?img=40" alt="Jack TITOUAN" class="w-10 h-10 rounded-full flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    <span class="font-semibold">Jack TITOUAN</span> vous invite à rejoindre une session
                                </p>
                                <p class="text-xs text-gray-500 mt-1">il y a 5 jours</p>
                            </div>
                        </div>
                    </div>

                    <!-- Notification 6 -->
                    <div class="bg-white rounded-lg p-3 card-hover cursor-pointer border border-gray-100">
                        <div class="flex items-start space-x-3">
                            <img src="https://i.pravatar.cc/150?img=45" alt="Edite ANASSOU" class="w-10 h-10 rounded-full flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    <span class="font-semibold">Edite ANASSOU</span> vous invite à rejoindre une session
                                </p>
                                <p class="text-xs text-gray-500 mt-1">il y a 5 jours</p>
                            </div>
                        </div>
                    </div>

                    <!-- Notification 7 -->
                    <div class="bg-white rounded-lg p-3 card-hover cursor-pointer border border-gray-100">
                        <div class="flex items-start space-x-3">
                            <img src="https://i.pravatar.cc/150?img=50" alt="Peter" class="w-10 h-10 rounded-full flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    Hey Peter, we've got an even Ipsum is simply dummy text of the printing and typesetting industry.
                                </p>
                                <p class="text-xs text-gray-500 mt-1">il y a 1 mois</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- ========================================
         PANEL MESSAGES (Coulissant)
    ======================================== -->
    <div id="messages-panel" class="slide-panel">
        <div class="h-full flex">
            
            <!-- Liste des conversations (Sidebar) -->
            <div id="conversations-list" class="w-full lg:w-80 bg-white border-r border-gray-200 flex flex-col transition-all">
                <!-- Header -->
                <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">Messages</h2>
                    <div class="flex items-center space-x-2">
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors" aria-label="Nouveau message">
                            <i class="fas fa-edit text-gray-600"></i>
                        </button>
                        <button id="close-messages" class="p-2 hover:bg-gray-100 rounded-lg transition-colors" aria-label="Fermer messages">
                            <i class="fas fa-times text-gray-600"></i>
                        </button>
                    </div>
                </div>

                <!-- Recherche dans les conversations -->
                <div class="p-3 border-b border-gray-200">
                    <div class="relative">
                        <input 
                            type="text" 
                            placeholder="Rechercher une conversation..." 
                            class="w-full pl-10 pr-4 py-2 bg-gray-100 border-0 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                            aria-label="Rechercher conversation"
                        >
                        <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                    </div>
                </div>

                <!-- Liste des conversations -->
                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    
                    <!-- Conversation 1 (Active) -->
                    <div class="conversation-item active cursor-pointer p-4 border-b border-gray-100" data-conversation="1">
                        <div class="flex items-center space-x-3">
                            <div class="relative flex-shrink-0">
                                <img src="https://i.pravatar.cc/150?img=15" alt="HOUESSOU Amour" class="w-12 h-12 rounded-full">
                                <div class="status-online"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="font-semibold text-sm text-gray-900 truncate">HOUESSOU Amour</p>
                                    <span class="text-xs text-gray-500">4:30 PM</span>
                                </div>
                                <p class="text-xs text-gray-500 truncate">Neque porro quisquam est qui dolorem ipsu...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Conversation 2 -->
                    <div class="conversation-item cursor-pointer p-4 border-b border-gray-100" data-conversation="2">
                        <div class="flex items-center space-x-3">
                            <div class="relative flex-shrink-0">
                                <img src="https://i.pravatar.cc/150?img=22" alt="AGLA Marinette" class="w-12 h-12 rounded-full">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="font-semibold text-sm text-gray-900 truncate">AGLA Marinette</p>
                                    <span class="text-xs text-gray-500">4:30 PM</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-video text-indigo-600 text-xs"></i>
                                    <p class="text-xs text-gray-500 truncate">Vidéo</p>
                                    <div class="unread-indicator ml-auto"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conversation 3 -->
                    <div class="conversation-item cursor-pointer p-4 border-b border-gray-100" data-conversation="3">
                        <div class="flex items-center space-x-3">
                            <div class="relative flex-shrink-0">
                                <img src="https://i.pravatar.cc/150?img=28" alt="ZOSSOU Lionnel" class="w-12 h-12 rounded-full">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="font-semibold text-sm text-gray-900 truncate">ZOSSOU Lionnel</p>
                                    <span class="text-xs text-gray-500">4:30 PM</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-file text-gray-400 text-xs"></i>
                                    <p class="text-xs text-gray-500 truncate">Neque porro quisquam est qui dolorem ipsu...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conversation 4 -->
                    <div class="conversation-item cursor-pointer p-4 border-b border-gray-100" data-conversation="4">
                        <div class="flex items-center space-x-3">
                            <div class="relative flex-shrink-0">
                                <img src="https://i.pravatar.cc/150?img=33" alt="Tranquillin AKOTEGNON" class="w-12 h-12 rounded-full">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="font-semibold text-sm text-gray-900 truncate">Tranquillin AKOTEGNON</p>
                                    <span class="text-xs text-gray-500">4:30 PM</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-volume-up text-indigo-600 text-xs"></i>
                                    <p class="text-xs text-gray-500 truncate">Audio</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conversation 5 -->
                    <div class="conversation-item cursor-pointer p-4 border-b border-gray-100" data-conversation="5">
                        <div class="flex items-center space-x-3">
                            <div class="relative flex-shrink-0">
                                <img src="https://i.pravatar.cc/150?img=38" alt="Fadel ABOUDOU" class="w-12 h-12 rounded-full">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="font-semibold text-sm text-gray-900 truncate">Fadel ABOUDOU</p>
                                    <span class="text-xs text-gray-500">4:30 PM</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-handshake text-yellow-500 text-xs"></i>
                                    <p class="text-xs text-gray-500 truncate">Félicitations pour la conclusion</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conversation 6 -->
                    <div class="conversation-item cursor-pointer p-4 border-b border-gray-100" data-conversation="6">
                        <div class="flex items-center space-x-3">
                            <div class="relative flex-shrink-0">
                                <img src="https://i.pravatar.cc/150?img=42" alt="Micheal Scott" class="w-12 h-12 rounded-full">
                                <div class="status-online"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="font-semibold text-sm text-gray-900 truncate">Micheal Scott</p>
                                    <span class="text-xs text-gray-500">4:30 PM</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-phone text-green-500 text-xs"></i>
                                    <p class="text-xs text-gray-500 truncate">Voice call</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conversation 7 -->
                    <div class="conversation-item cursor-pointer p-4 border-b border-gray-100" data-conversation="7">
                        <div class="flex items-center space-x-3">
                            <div class="relative flex-shrink-0">
                                <img src="https://i.pravatar.cc/150?img=47" alt="Geoffroy AHIDOMONHAN" class="w-12 h-12 rounded-full">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="font-semibold text-sm text-gray-900 truncate">Geoffroy AHIDOMONHAN</p>
                                    <span class="text-xs text-gray-500">4:30 PM</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-smile text-gray-400 text-xs"></i>
                                    <p class="text-xs text-gray-500 truncate">Sticker</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conversation 8 -->
                    <div class="conversation-item cursor-pointer p-4 border-b border-gray-100" data-conversation="8">
                        <div class="flex items-center space-x-3">
                            <div class="relative flex-shrink-0">
                                <img src="https://i.pravatar.cc/150?img=52" alt="SOSSA Gustave" class="w-12 h-12 rounded-full">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="font-semibold text-sm text-gray-900 truncate">SOSSA Gustave</p>
                                    <span class="text-xs text-gray-500">4:30 PM</span>
                                </div>
                                <p class="text-xs text-gray-500 truncate">Ce message a été supprimé</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Zone de conversation (Desktop uniquement) -->
            <div id="conversation-detail" class="hidden lg:flex flex-1 flex-col bg-gray-50">
                <!-- Header de la conversation -->
                <div class="bg-white p-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/150?img=15" alt="HOUESSOU Amour" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-semibold text-sm text-gray-900">HOUESSOU Amour</p>
                            <p class="text-xs text-green-600">En ligne</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors" aria-label="Appel vidéo">
                            <i class="fas fa-video text-gray-600"></i>
                        </button>
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors" aria-label="Appel audio">
                            <i class="fas fa-phone text-gray-600"></i>
                        </button>
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors" aria-label="Plus d'options">
                            <i class="fas fa-ellipsis-v text-gray-600"></i>
                        </button>
                    </div>
                </div>

                <!-- Messages -->
                <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-4">
                    
                    <!-- Message reçu -->
                    <div class="flex items-start space-x-2">
                        <img src="https://i.pravatar.cc/150?img=15" alt="HOUESSOU Amour" class="w-8 h-8 rounded-full flex-shrink-0">
                        <div class="flex flex-col">
                            <div class="message-bubble received px-4 py-2">
                                <p class="text-sm">Oui c'est disponible</p>
                            </div>
                            <span class="text-xs text-gray-400 mt-1 ml-2">4:52 pm</span>
                        </div>
                    </div>

                    <!-- Message envoyé -->
                    <div class="flex items-end justify-end space-x-2">
                        <div class="flex flex-col items-end">
                            <div class="message-bubble sent px-4 py-2">
                                <p class="text-sm">Bien sûr ! Je t'envoie quelques images !</p>
                            </div>
                            <span class="text-xs text-gray-400 mt-1 mr-2">4:52 pm</span>
                        </div>
                    </div>

                    <!-- Message avec image -->
                    <div class="flex items-end justify-end space-x-2">
                        <div class="flex flex-col items-end max-w-xs">
                            <div class="rounded-2xl overflow-hidden shadow-lg">
                                <img src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=400" alt="Image partagée" class="w-full h-48 object-cover">
                            </div>
                            <div class="mt-2 bg-gray-100 rounded-lg px-3 py-1 flex items-center space-x-2">
                                <i class="fas fa-images text-indigo-600 text-xs"></i>
                                <span class="text-xs text-gray-600">Sticker Pack Name</span>
                            </div>
                            <span class="text-xs text-gray-400 mt-1 mr-2">4:52 pm</span>
                        </div>
                    </div>

                    <!-- Stickers -->
                    <div class="flex items-end justify-end">
                        <div class="flex flex-col items-end">
                            <div class="flex items-center space-x-2">
                                <div class="text-5xl">🦁</div>
                                <div class="text-5xl">🤡</div>
                                <div class="text-5xl">🤖</div>
                            </div>
                            <div class="flex items-center space-x-2 mt-2">
                                <div class="text-5xl">🐯</div>
                                <div class="text-5xl">🎪</div>
                                <div class="text-5xl">🤡</div>
                            </div>
                            <div class="mt-2 bg-gray-100 rounded-lg px-3 py-1 flex items-center space-x-1">
                                <i class="fas fa-reply text-gray-500 text-xs"></i>
                                <span class="text-xs text-gray-600">4 Replies</span>
                            </div>
                            <span class="text-xs text-gray-400 mt-1 mr-2">4:52 pm</span>
                        </div>
                    </div>

                    <!-- Message reçu -->
                    <div class="flex items-start space-x-2">
                        <img src="https://i.pravatar.cc/150?img=15" alt="HOUESSOU Amour" class="w-8 h-8 rounded-full flex-shrink-0">
                        <div class="flex flex-col">
                            <div class="message-bubble received px-4 py-2">
                                <p class="text-sm">Merci ! Cela me semble correct.</p>
                            </div>
                            <span class="text-xs text-gray-400 mt-1 ml-2">4:52 pm</span>
                        </div>
                    </div>

                    <!-- Messages envoyés multiples -->
                    <div class="flex flex-col items-end space-y-2">
                        <div class="message-bubble sent px-4 py-2">
                            <p class="text-sm">Salut, les parts dont tu m'avais parlé, sont dispo?</p>
                        </div>
                        <div class="message-bubble sent px-4 py-2">
                            <p class="text-sm">Super, puis-je avoir un aperçu</p>
                        </div>
                        <div class="message-bubble sent px-4 py-2">
                            <p class="text-sm">Je les prends !</p>
                        </div>
                        <span class="text-xs text-gray-400 mr-2">4:55 pm</span>
                    </div>

                    <!-- Indicateur Today -->
                    <div class="flex items-center justify-center my-4">
                        <div class="bg-gray-200 rounded-full px-3 py-1">
                            <span class="text-xs text-gray-600">Today</span>
                        </div>
                    </div>

                    <!-- Dernier message -->
                    <div class="flex items-end justify-end space-x-2">
                        <div class="flex flex-col items-end">
                            <div class="message-bubble sent px-4 py-2">
                                <p class="text-sm">Je les prends !</p>
                            </div>
                            <span class="text-xs text-gray-400 mt-1 mr-2">4:55 pm</span>
                        </div>
                    </div>

                </div>

                <!-- Zone de saisie -->
                <div class="bg-white border-t border-gray-200 p-4">
                    <div class="flex items-center space-x-2 mb-3">
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors" aria-label="Ajouter fichier">
                            <i class="fas fa-plus-circle text-gray-400"></i>
                        </button>
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors" aria-label="Message vocal">
                            <i class="fas fa-microphone text-gray-400"></i>
                        </button>
                        <button id="emoji-btn" class="p-2 hover:bg-gray-100 rounded-lg transition-colors" aria-label="Emoji">
                            <i class="far fa-smile text-gray-400"></i>
                        </button>
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors" aria-label="Ajouter image">
                            <i class="far fa-image text-gray-400"></i>
                        </button>
                    </div>
                    <div class="flex items-end space-x-2">
                        <textarea 
                            placeholder="Type your message..." 
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                            rows="2"
                            aria-label="Message"
                        ></textarea>
                        <button class="p-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors btn-ripple" aria-label="Envoyer message">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Overlay pour les panels -->
    <div id="panel-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-30 modal-overlay"></div>

    <!-- ========================================
         LAYOUT PRINCIPAL
    ======================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- SIDEBAR GAUCHE -->
            <aside class="hidden lg:block lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-24">
                    
                    <!-- Profil utilisateur -->
                    <div class="p-6 text-center border-b border-gray-200 gradient-bg">
                        <div class="relative inline-block mb-3">
                            <img 
                                src="https://i.pravatar.cc/150?img=12" 
                                alt="Profil CHOUTI Abdoulaye" 
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

            <!-- COLONNE CENTRALE - FEED -->
            <main class="lg:col-span-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">
                        Bienvenue sur Trade Hub ! 🚀
                    </h2>
                    <p class="text-gray-600 mb-4">
                        Votre plateforme de formation en trading. Cliquez sur les icônes en haut à droite pour accéder aux notifications et messages.
                    </p>
                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                        <h3 class="font-semibold text-indigo-900 mb-2">Fonctionnalités disponibles :</h3>
                        <ul class="space-y-1 text-sm text-indigo-700">
                            <li>🔔 Panel de notifications avec liste complète</li>
                            <li>💬 Panel de messages avec conversations</li>
                            <li>👤 Menu utilisateur avec options</li>
                            <li>📱 Interface responsive (mobile & desktop)</li>
                        </ul>
                    </div>
                </div>
            </main>

            <!-- SIDEBAR DROITE - SUGGESTIONS -->
            <aside class="hidden lg:block lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sticky top-24">
                    <h3 class="font-bold text-gray-900 mb-4">Personnes que vous pourriez connaître</h3>
                    <div class="space-y-4">
                        <!-- Suggestion 1 -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <img src="https://i.pravatar.cc/150?img=11" alt="Charbel MEHINTO" class="w-10 h-10 rounded-full">
                                <div>
                                    <p class="font-medium text-sm">Charbel MEHINTO</p>
                                    <p class="text-xs text-gray-500">Trader</p>
                                </div>
                            </div>
                            <button class="px-4 py-1.5 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-all btn-ripple">
                                Suivre
                            </button>
                        </div>
                        
                        <!-- Suggestion 2 -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <img src="https://i.pravatar.cc/150?img=22" alt="Junias SIHOU" class="w-10 h-10 rounded-full">
                                <div>
                                    <p class="font-medium text-sm">Junias SIHOU</p>
                                    <p class="text-xs text-gray-500">Analyste</p>
                                </div>
                            </div>
                            <button class="px-4 py-1.5 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-all btn-ripple">
                                Suivre
                            </button>
                        </div>
                        
                        <!-- Suggestion 3 -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <img src="https://i.pravatar.cc/150?img=35" alt="Pierre SOUROU" class="w-10 h-10 rounded-full">
                                <div>
                                    <p class="font-medium text-sm">Pierre SOUROU</p>
                                    <p class="text-xs text-gray-500">Coach</p>
                                </div>
                            </div>
                            <button class="px-4 py-1.5 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-all btn-ripple">
                                Suivre
                            </button>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <!-- ========================================
         MENU MOBILE
    ======================================== -->
    <div id="mobile-menu" class="fixed inset-0 z-50 hidden">
        <div class="modal-overlay absolute inset-0" id="mobile-menu-overlay"></div>
        <div class="slide-in-left absolute left-0 top-0 bottom-0 w-80 bg-white shadow-2xl overflow-y-auto">
            <div class="gradient-bg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold">Menu</h3>
                    <button id="close-mobile-menu" class="p-2 hover:bg-white/20 rounded-lg transition-colors" aria-label="Fermer menu">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="flex items-center space-x-3">
                    <img src="https://i.pravatar.cc/150?img=12" alt="CHOUTI Abdoulaye" class="w-16 h-16 rounded-full border-3 border-white">
                    <div>
                        <p class="font-bold">CHOUTI Abdoulaye</p>
                        <p class="text-sm text-indigo-100">Modérateur</p>
                    </div>
                </div>
            </div>
            
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
                    <span class="font-medium">Localisations</span>
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

    <!-- JavaScript -->
    <script src="script.js"></script>
</body>
</html>