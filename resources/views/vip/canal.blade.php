<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canaux VIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            <div class="p-4 border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <button id="sidebarMenuToggle" class="md:hidden p-2 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <h1 class="text-xl md:text-2xl font-semibold text-gray-900 flex-1 text-center md:text-left">
                        Vos Coach Community
                    </h1>

                    <div class="flex gap-2">
                        <button class="md:hidden p-2 hover:bg-gray-100 rounded-lg transition"
                            onclick="toggleSidebarSearch()">
                            <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div class="relative group hidden md:block">
                            <button class="p-2 hover:bg-gray-100 rounded-full transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                </svg>
                            </button>
                            <div
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <a href="/profil"
                                    class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition rounded-t-lg">
                                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Profil</span>
                                </a>
                                <a href="/logout" onclick="return confirm('Voulez-vous vraiment vous déconnecter ?')"
                                    class="flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition rounded-b-lg border-t border-gray-100">
                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-medium text-red-600">Déconnexion</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barre de recherche -->
                <div id="sidebarSearchBox" class="hidden md:block">
                    <div class="relative">
                        <input type="text" placeholder="Rechercher une conversation..."
                            class="w-full bg-gray-100 rounded-full pl-10 pr-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Liste des canaux -->
            <div class="flex-1 overflow-y-auto scrollbar-hide">
                @foreach ($forums as $forum)
                    <div class="channel-item {{ $loop->first ? 'active' : '' }} flex items-center gap-2 sm:gap-3 p-3 sm:p-4 border-b border-gray-100"
                        onclick="switchChannel(this, '{{ $forum->id }}', '{{ $forum->name ?? $forum->slug }}', '{{ asset($forum->icon) }}')"
                        data-channel-id="{{ $forum->id }}" 
                        data-channel-slug="{{ $forum->slug }}"
                        data-channel-name="{{ $forum->name ?? $forum->slug }}"
                        data-channel-description="{{ $forum->description ?? 'Canal de discussion de la communauté' }}"
                        data-channel-members="300" 
                        data-channel-online="40">

                        <div class="relative flex-shrink-0">
                            @if($forum->icon)
                                <img src="{{ asset($forum->icon) }}" alt="{{ $forum->name ?? $forum->slug }}"
                                    class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover"
                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($forum->name ?? $forum->slug) }}&background=random'">
                            @else
                                <div
                                    class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($forum->name ?? $forum->slug, 0, 2)) }}
                                </div>
                            @endif
                            <span
                                class="absolute bottom-0 right-0 w-3 h-3 sm:w-3.5 sm:h-3.5 bg-green-500 border-2 border-white rounded-full"></span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="font-semibold text-sm text-gray-900 truncate">
                                    {{ $forum->name ?? $forum->slug }}
                                </h3>
                                @if($forum->topics && $forum->topics->count() > 0)
                                    <span class="text-xs text-gray-500 flex-shrink-0 ml-2">
                                        {{ \Carbon\Carbon::parse($forum->topics->last()->created_at)->format('h:i A') }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-500 flex-shrink-0 ml-2">Nouveau</span>
                                @endif
                            </div>

                            @if($forum->topics && $forum->topics->count() > 0)
                                @php
                                    $lastTopic = $forum->topics->last();
                                    $preview = $lastTopic->description ?? $lastTopic->title ?? 'Nouveau message';
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
                                alt="Channel"
                                class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl object-cover shadow-lg">
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
            <div class="bg-white border-t border-gray-200 p-3 sm:p-4 flex-shrink-0">
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
                            <div
                                class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-0.5 sm:gap-1">
                                <button class="p-1.5 sm:p-2 hover:bg-gray-100 rounded-full transition" title="Emoji">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>

                                <button onclick="document.getElementById('imageInput').click()"
                                    class="p-1.5 sm:p-2 hover:bg-gray-100 rounded-full transition"
                                    title="Ajouter une image">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </button>
                                <input type="file" id="imageInput" class="hidden" multiple accept="image/*">
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

                    <!-- Boutons de création -->
                    <div class="flex items-center gap-2 mt-3 flex-wrap">
                        <button onclick="openPollModal()"
                            class="flex items-center gap-2 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                <path fill-rule="evenodd"
                                    d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z"
                                    clip-rule="evenodd" />
                            </svg>
                            Sondage
                        </button>
                    </div>
                </div>
            </div>

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

    <!-- Modal Sondage -->
    <div class="modal-overlay" id="pollModal">
        <div class="modal-content">
            <div class="p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg">Créer un sondage</h3>
                    <button onclick="closePollModal()" class="p-2 hover:bg-gray-100 rounded-full transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <input type="text" id="pollQuestion"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 mb-3"
                    placeholder="Votre question...">

                <div id="pollOptions" class="space-y-2 mb-4">
                    <input type="text"
                        class="poll-option-input w-full px-4 py-2 border border-gray-300 rounded-lg"
                        placeholder="Option 1">
                    <input type="text"
                        class="poll-option-input w-full px-4 py-2 border border-gray-300 rounded-lg"
                        placeholder="Option 2">
                </div>

                <button onclick="addPollOption()"
                    class="text-sm text-indigo-600 hover:text-indigo-700 font-medium mb-4">
                    + Ajouter une option
                </button>

                <div class="flex justify-end gap-2">
                    <button onclick="closePollModal()"
                        class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                        Annuler
                    </button>
                    <button onclick="publishPoll()"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                        Publier
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vip/canal.js') }}"></script>

</body>

</html>