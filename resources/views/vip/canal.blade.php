<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canaux VIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('assets/vip/vip.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">




    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .channel-item {
            cursor: pointer;
            transition: all 0.2s;
        }

        .channel-item:hover {
            background: #f3f4f6;
        }

        .channel-item.active {
            background: #ede9fe;
            border-left: 3px solid #8b5cf6;
        }

        /* Modal styles */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 100;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 1rem;
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        /* Animation slide down */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slideDown {
            animation: slideDown 0.3s ease-out;
        }

        /* Dropdown menu */
        .dropdown-menu {
            display: none;
        }

        /* Post styles */
        .post-bubble {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
        }

        .post-bubble:hover {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        }

        /* Conteneur des messages */
        #messagesContainer {
            background: #f9fafb;
        }

        /* Assure que le container prend toute la hauteur disponible */
        .messages-wrapper {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
    </style>
</head>

<body class="bg-gray-50 h-screen overflow-hidden">

    <div class="flex h-screen">

        <!-- SIDEBAR GAUCHE -->
        <div id="sidebar"
            class="fixed md:relative inset-y-0 left-0 w-full sm:w-80 md:w-96 lg:w-[420px] bg-white border-r border-gray-200 flex flex-col transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-40">

            <!-- Header Sidebar -->
            <div class="p-2 border-b border-gray-200 my-2">
                <div class="flex justify-between items-center mb-4">
                    <button id="mobile-menu-btn" class="md:hidden p-2 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div class="flex ">

                        <a href="/vip" class="hidden md:flex p-2 rounded-lg hover:bg-gray-100 transition text-gray-600"
                        title="Retour VIP">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                       </a>

                       <h1 class="text-base md:text-lg font-medium text-black uppercase tracking-wide p-1">
                        Coach Community
                       </h1>

                    </div>





                    <div class="flex gap-2">
                        

                        <div class="relative group hidden md:block">
                            <button class="p-2 hover:bg-gray-100 rounded-full transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                </svg>
                            </button>
                           
                        </div>
                    </div>
                </div>

                <!-- Barre de recherche -->
                
            </div>

            <!-- Liste des canaux -->
            <div class="flex-1 overflow-y-auto scrollbar-hide">
                @foreach ($forums as $forum)
                    <div class="my-2 channel-item {{ $loop->first ? 'active' : '' }} flex items-center gap-2 sm:gap-3 p-3 sm:p-4 border-b border-gray-100"
                        onclick="switchChannel(this, '{{ $forum->forum_id}}', '{{ $forum->title  }}', '{{ asset($forum->forum->icon) }}')"
                        data-channel-id="{{ $forum->forum_id}}" data-channel-slug="{{ $forum->forum->slug }}"
                        data-channel-name="{{ $forum->title ?? $forum->forum->slug }}"
                        data-channel-description="{{ $forum->description ?? 'Canal de discussion de la communauté' }}"
                        data-channel-members="300" data-channel-online="40">

                        <div class="relative flex-shrink-0 my-2">
                            @if($forum->forum->icon)
                                <img src="{{$forum->forum->getAvatar(48) }}" alt="{{ $forum->title ?? $forum->forum->slug }}"
                                    class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover"
                                    onerror="this.src='https://ui-avatars.com/api/?name={{ strtoupper(substr($forum->title ?? $forum->forum->slug, 0, 2)) }}&background=random'">
                            @else
                                <div
                                    class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($forum->title ?? $forum->forum->slug, 0, 2)) }}
                                </div>
                            @endif
                            <span
                                class="absolute bottom-0 right-0 w-3 h-3 sm:w-3.5 sm:h-3.5 bg-green-500 border-2 border-white rounded-full"></span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="font-semibold text-sm text-gray-900 truncate">
                                    {{ $forum->title ?? $forum->forum->slug }}
                                </h3>
                                @if($forum->topics && $forum->topics->count() > 0)
                                    <span class="text-xs text-gray-500 flex-shrink-0 ml-2">
                                        {{ \Carbon\Carbon::parse($forum->topics->last()->created_at)->format('h:i A') }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-500 flex-shrink-0 ml-2"></span>
                                @endif
                            </div>

                            @if($forum->topics && $forum->topics->count() > 0)
                                @php
                                    $lastTopic = $forum->topics->last();
                                    $preview = $lastTopic->content ?? 'Nouveau message';
                                @endphp
                                <p class="text-xs text-gray-500 truncate">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($preview), 45) }}
                                </p>
                            @else
                                <p class="text-xs text-gray-500 truncate">Aucun message pour le moment</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- ZONE PRINCIPALE -->
        <div class="flex-1 flex flex-col bg-gray-50">

            <!-- HEADER CANAL -->
            <div class="bg-white border-b border-gray-200 px-3 sm:px-4 md:px-6 py-3 md:py-4 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 sm:gap-3 md:gap-4 flex-1 min-w-0">
                        <button id="backButton"
                            class="md:hidden p-2 hover:bg-gray-100 rounded-full transition flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <div class="relative flex-shrink-0">
                            <img id="channelHeaderImage"
                                src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=100&h=100&fit=crop"
                                alt="Channel" class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl object-cover shadow-lg">
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5 sm:gap-2 mb-0.5 sm:mb-1">
                                <h2 id="channelHeaderName"
                                    class="font-bold text-sm sm:text-base md:text-lg text-gray-900 truncate">Welcome
                                </h2>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-1 sm:gap-2 flex-shrink-0">
                        <button onclick="refreshMessages()" title="Rafraîchir les messages"
                            class="p-1.5 sm:p-2 hover:bg-gray-100 rounded-full transition group">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600 group-hover:rotate-180 transition-transform duration-500"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>

                        <button onclick="toggleChannelInfo()"
                            class="p-1.5 sm:p-2 hover:bg-gray-100 rounded-full transition">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ZONE CONTENU - Centré avec max-width -->
            <div id="messagesContainer" class="flex-1 overflow-y-auto scrollbar-hide bg-gray-50">
                <!-- Container centré pour les messages -->
                <div class="max-w-4xl mx-auto px-4 py-6 space-y-4">
                    <!-- Les posts seront chargés ici par JavaScript -->
                    <div class="flex justify-center items-center min-h-[400px]">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                    </div>
                </div>
            </div>


            <!-- BARRE DE MESSAGE FIXE -->

            @if (auth()->user()->role_name != 'user')

                                        
                                            
                                      
            <div class="bg-white border-t border-gray-200 p-3 sm:p-4 flex-shrink-0"
                data-forum-id="{{ $forum->forum_id ?? '' }}">
                <div class="max-w-4xl mx-auto">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <!-- Avatar utilisateur -->
                        <div
                            class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-semibold text-xs sm:text-sm flex-shrink-0">
                            {{ strtoupper(substr(auth()->user()->full_name ?? 'U', 0, 2)) }}
                        </div>

                        <!-- Input message -->
                        <div class="flex-1 relative">
                            <input type="text" id="messageInput" placeholder="Écrivez un message..."
                                class="w-full px-3 sm:px-4 py-2 sm:py-3 pr-24 sm:pr-32 border border-gray-300 rounded-full focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition text-sm sm:text-base"
                                onkeypress="handleMessageKeyPress(event)">

                            <!-- Boutons à l'intérieur de l'input -->
                            <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-0.5 sm:gap-1">
                                <!-- Bouton Sondage (avec indicateur visuel) -->
                                <button onclick="openPollModal()"
                                    class="p-1.5 sm:p-2 hover:bg-gray-100 rounded-full transition relative"
                                    title="Créer un sondage">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <!-- L'indicateur sera ajouté dynamiquement ici -->
                                </button>

                                <!-- Bouton Image (avec indicateur visuel + compteur) -->
                                <button onclick="document.getElementById('imageInput').click()"
                                    class="p-1.5 sm:p-2 hover:bg-gray-100 rounded-full transition relative"
                                    title="Ajouter une image">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <!-- L'indicateur avec compteur sera ajouté dynamiquement ici -->
                                </button>
                                <input type="file" id="imageInput" class="hidden" multiple accept="image/*,video/*">
                            </div>
                        </div>

                        <!-- Bouton Envoyer -->
                        <button onclick="sendQuickMessage()"
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-indigo-600 hover:bg-indigo-700 rounded-full flex items-center justify-center transition flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            @endif

            <!-- MODAL SONDAGE -->
            <div id="poll-modal"
                class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold">📊 Créer un sondage</h3>
                            <button onclick="closePollModal()"
                                class="text-white hover:bg-white/20 rounded-full p-2 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="p-6 space-y-4">
                        <!-- Question -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Question</label>
                            <input type="text" id="poll-question" placeholder="Quelle est votre question ?"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                        </div>

                        <!-- Options -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                            <div id="poll-options" class="space-y-2">
                                <input type="text" placeholder="Option 1"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                                <input type="text" placeholder="Option 2"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                                <input type="text" placeholder="Option 3 (facultatif)"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                                <input type="text" placeholder="Option 4 (facultatif)"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-between gap-3">
                        <button onclick="deletePoll()"
                            class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition font-medium">
                            Supprimer
                        </button>
                        <div class="flex gap-2">
                            <button onclick="closePollModal()"
                                class="px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition font-medium">
                                Annuler
                            </button>
                            <button onclick="savePoll()"
                                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition font-medium">
                                Ajouter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Le conteneur de preview des médias sera inséré dynamiquement ici -->

            <style>
                /* Animations pour les indicateurs */
                @keyframes pulse {

                    0%,
                    100% {
                        opacity: 1;
                    }

                    50% {
                        opacity: 0.5;
                    }
                }

                .animate-pulse {
                    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
                }

                /* Indicateur sondage (vert) */
                .poll-indicator {
                    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
                }

                /* Indicateur média (bleu) */
                .media-indicator {
                    animation: none;
                    /* Pas d'animation pour le compteur */
                }

                /* Animation shake */
                @keyframes shake {

                    0%,
                    100% {
                        transform: translateX(0);
                    }

                    10%,
                    30%,
                    50%,
                    70%,
                    90% {
                        transform: translateX(-5px);
                    }

                    20%,
                    40%,
                    60%,
                    80% {
                        transform: translateX(5px);
                    }
                }

                .shake-animation {
                    animation: shake 0.5s ease-in-out;
                }

                /* Animation rotation rapide */
                @keyframes spin-fast {
                    from {
                        transform: rotate(0deg);
                    }

                    to {
                        transform: rotate(360deg);
                    }
                }

                .animate-spin-fast {
                    animation: spin-fast 0.6s linear infinite;
                }

                /* Animation bounce */
                @keyframes bounce-once {

                    0%,
                    100% {
                        transform: translateY(0);
                    }

                    50% {
                        transform: translateY(-10px);
                    }
                }

                .animate-bounce-once {
                    animation: bounce-once 0.5s ease;
                }

                /* Animation aspiration */
                @keyframes message-aspiration {
                    0% {
                        opacity: 1;
                        transform: scale(1);
                    }

                    50% {
                        opacity: 0.8;
                        transform: scale(0.99);
                    }

                    100% {
                        opacity: 1;
                        transform: scale(1);
                    }
                }

                .message-aspiration {
                    animation: message-aspiration 0.8s ease-in-out;
                }

                /* Animation pulsation envoi */
                @keyframes pulse-sending {

                    0%,
                    100% {
                        transform: scale(0.95);
                        opacity: 1;
                    }

                    50% {
                        transform: scale(1);
                        opacity: 0.9;
                    }
                }

                .sending-pulse {
                    animation: pulse-sending 1.2s ease-in-out infinite;
                }

                /* Glow succès */
                @keyframes success-glow {

                    0%,
                    100% {
                        box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.7);
                    }

                    50% {
                        box-shadow: 0 0 20px 10px rgba(99, 102, 241, 0.3);
                    }
                }

                .success-glow {
                    animation: success-glow 0.6s ease-in-out;
                }
            </style>

            {{-- Variable globale pour le forum_id --}}
            <script>
                window.currentForumId = {{ $forum->forum_id ?? 'null' }};
            </script>
        </div>
    </div>

    <!-- Modal Info Canal -->
    <div class="modal-overlay" id="channelInfoModal">
        <div class="modal-content max-w-md">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <img id="channelInfoImage" src="" alt="Channel" class="w-16 h-16 rounded-xl object-cover">
                        <div>
                            <h3 id="channelInfoName" class="font-bold text-lg text-gray-900"></h3>
                            <div class="flex items-center gap-3 text-sm text-gray-600 mt-1">
                                <span id="channelInfoMembers" class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                    </svg>
                                </span>
                                <span id="channelInfoOnline" class="flex items-center gap-1 text-green-600">
                                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <button onclick="closeChannelInfo()" class="p-2 hover:bg-gray-100 rounded-full transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mb-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Description</h4>
                    <p id="channelInfoDescription" class="text-sm text-gray-600 leading-relaxed"></p>
                </div>
            </div>
        </div>
    </div>



    @include('vip.composants.mobile')

    <script src="{{ asset('assets/vip/canal.js') }}"></script>
    <script src="{{ asset('assets/vip/vip.js') }}"></script>
    <script id="data" type="application/json" src="{{ asset('assets/vip/event.js') }}"></script>

</body>

</html>