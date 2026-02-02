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

        /* Styles pour la barre de création */
        .create-post-bar {
            background: white;
            border-top: 1px solid #e5e7eb;
            padding: 1rem;
        }

        .post-type-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
            border: 1px solid #e5e7eb;
        }

        .post-type-btn:hover {
            background: #f3f4f6;
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

        .media-preview {
            position: relative;
            display: inline-block;
        }

        .media-preview img,
        .media-preview video {
            max-width: 100%;
            border-radius: 0.5rem;
        }

        .remove-media {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            border-radius: 50%;
            width: 1.5rem;
            height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .remove-media:hover {
            background: rgba(0, 0, 0, 0.8);
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
    </style>
</head>

<body class="bg-gray-50 h-screen overflow-hidden">

    <div class="flex h-screen">

        <!-- SIDEBAR GAUCHE - Liste des canaux -->
        <div id="sidebar"
            class="fixed md:relative inset-y-0 left-0 w-full sm:w-80 md:w-96 lg:w-[420px] bg-white border-r border-gray-200 flex flex-col transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-40">

            <!-- Header Sidebar -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <!-- Hamburger Menu (visible uniquement sur mobile) -->
                    <button id="sidebarMenuToggle" class="md:hidden p-2 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <h1 class="text-xl md:text-2xl font-semibold text-gray-900 flex-1 text-center md:text-left">Vos
                        Coach Community</h1>

                    <div class="flex gap-2">
                        <!-- Bouton Recherche (visible sur mobile) -->
                        <button class="md:hidden p-2 hover:bg-gray-100 rounded-lg transition"
                            onclick="toggleSidebarSearch()">
                            <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Menu dropdown (visible uniquement sur desktop) -->
                        <div class="relative group hidden md:block">
                            <button class="p-2 hover:bg-gray-100 rounded-full transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                </svg>
                            </button>
                            <!-- Menu déroulant -->
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
                                <a href="/parametres"
                                    class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition">
                                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Paramètres</span>
                                </a>
                                <a href="/notifications"
                                    class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition">
                                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Notifications</span>
                                </a>
                                <a href="/aide" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition">
                                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Aide</span>
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

                <!-- Barre de recherche (visible uniquement sur desktop par défaut) -->
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

                <!-- Canal 1 - Actif - Binance Trading 2024 -->

                @foreach ($forums as $forum)
                    <div class="channel-item {{ $loop->first ? 'active' : '' }} flex items-center gap-2 sm:gap-3 p-3 sm:p-4 border-b border-gray-100 my-4"
                        onclick="switchChannel(this, '{{ $forum->id }}', '{{ $forum->name ?? $forum->slug }}', '{{ asset($forum->icon) }}')"
                        data-channel-id="{{ $forum->id }}" data-channel-slug="{{ $forum->slug }}"
                        data-channel-name="{{ $forum->name ?? $forum->slug }}"
                        data-channel-description="{{ $forum->description ?? 'Canal de discussion de la communauté' }}"
                        data-channel-members="300" data-channel-online="40">

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
                                <h3 class="font-semibold text-sm text-gray-900 truncate">{{ $forum->name ?? $forum->slug }}
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

        <!-- ZONE PRINCIPALE - Header Canal + Contenu -->
        <div class="flex-1 flex flex-col bg-gray-50">

            <!-- HEADER CANAL (inspiré de l'image 2) -->
            <div class="bg-white border-b border-gray-200 px-3 sm:px-4 md:px-6 py-3 md:py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 sm:gap-3 md:gap-4 flex-1 min-w-0">
                        <!-- Bouton Retour (visible uniquement sur mobile) -->
                        <button id="backButton"
                            class="md:hidden p-2 hover:bg-gray-100 rounded-full transition flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <!-- Avatar Canal avec Image -->
                        <div class="relative flex-shrink-0">
                            <img src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=100&h=100&fit=crop"
                                alt="Trading Community"
                                class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl object-cover shadow-lg">
                        </div>

                        <!-- Info Canal -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5 sm:gap-2 mb-0.5 sm:mb-1">
                                <h2 class="font-bold text-sm sm:text-base md:text-lg text-gray-900 truncate">Welcome</h2></h2>
                                
                            </div>
                            
                        </div>
                    </div>

                    <!-- Actions Header -->
                    <div class="flex items-center gap-1 sm:gap-2 flex-shrink-0">
                        <!-- Bouton Info (visible sur mobile et desktop) -->
                        <button onclick="toggleChannelInfo()"
                            class="p-1.5 sm:p-2 hover:bg-gray-100 rounded-full transition">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Bouton Recherche (caché sur mobile) -->
                        <button onclick="toggleSearchBox()"
                            class="hidden md:flex p-1.5 sm:p-2 hover:bg-gray-100 rounded-full transition">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Menu 3 points (visible uniquement sur mobile, à droite du i) -->
                        <button class="md:hidden p-1.5 hover:bg-gray-100 rounded-full transition">
                            <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Search Box (masqué par défaut, visible uniquement sur desktop) -->
                <div id="searchBox" class="hidden mt-3 animate-slideDown">
                    <div class="relative">
                        <input type="text" id="channelSearch"
                            class="w-full px-4 py-2.5 pl-10 bg-gray-100 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            placeholder="Rechercher dans le canal..." onkeypress="handleChannelSearch(event)">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- ZONE CONTENU -->
           
        
            <div id="messagesContainer" class="flex-1 overflow-y-auto p-6 " >
                    <!-- Les posts seront chargés ici par JavaScript -->
                    
            </div>
          

            <!-- BARRE DE MESSAGE (style Telegram/Discord) -->
            <div class="border-t border-gray-200 bg-white p-3 sm:p-4">
                <div class="max-w-3xl mx-auto">
                    <div class="flex items-center gap-2 bg-gray-100 rounded-full px-4 py-2.5">
                        <!-- Emoji Button -->
                        <button class="text-gray-500 hover:text-indigo-600 transition flex-shrink-0"
                            onclick="toggleEmojiPicker()">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-5-9c.83 0 1.5-.67 1.5-1.5S7.83 8 7 8s-1.5.67-1.5 1.5S6.17 11 7 11zm10 0c.83 0 1.5-.67 1.5-1.5S17.83 8 17 8s-1.5.67-1.5 1.5.67 1.5 1.5 1.5zm-5 5c2.33 0 4.32-1.45 5.12-3.5h-1.67c-.69 1.19-1.97 2-3.45 2s-2.76-.81-3.45-2H6.88c.8 2.05 2.79 3.5 5.12 3.5z" />
                            </svg>
                        </button>

                        <!-- Input Message -->
                        <input type="text" id="messageInput"
                            class="flex-1 bg-transparent outline-none text-sm placeholder-gray-500"
                            placeholder="Type your message..." onkeypress="handleMessageSubmit(event)">

                        <!-- Attach Button -->
                        <button class="text-gray-500 hover:text-indigo-600 transition flex-shrink-0"
                            onclick="openAttachMenu()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                        </button>

                        <!-- Media Button -->
                        <button class="text-gray-500 hover:text-indigo-600 transition flex-shrink-0"
                            onclick="document.getElementById('quickMediaInput').click()">
                            <input type="file" id="quickMediaInput" accept="image/*,video/*" multiple class="hidden"
                                onchange="handleQuickMediaUpload(event)">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </button>

                        <!-- Send Button -->
                        <button
                            class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-2 transition flex-shrink-0"
                            onclick="sendMessage()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </div>

                    <!-- Attach Menu (dropdown) -->
                    <div id="attachMenu"
                        class="hidden absolute bottom-20 right-6 bg-white rounded-xl shadow-lg border border-gray-200 p-2 min-w-[200px]">
                        <button onclick="openMediaModal()"
                            class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 rounded-lg transition text-left">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Photo/Vidéo</p>
                                <p class="text-xs text-gray-500">Partager des médias</p>
                            </div>
                        </button>
                        <button onclick="openPollModal()"
                            class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 rounded-lg transition text-left">
                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Sondage</p>
                                <p class="text-xs text-gray-500">Créer un vote</p>
                            </div>
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

    <!-- Modal Texte -->
    <div class="modal-overlay" id="textModal">
        <div class="modal-content">
            <div class="p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg">Créer un post</h3>
                    <button onclick="closeTextModal()" class="p-2 hover:bg-gray-100 rounded-full transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <textarea id="textContent"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-none"
                    rows="6" placeholder="Quoi de neuf ?"></textarea>

                <div class="flex justify-end gap-2 mt-4">
                    <button onclick="closeTextModal()"
                        class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                        Annuler
                    </button>
                    <button onclick="publishTextPost()"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                        Publier
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Média -->
    <div class="modal-overlay" id="mediaModal">
        <div class="modal-content">
            <div class="p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg">Ajouter des médias</h3>
                    <button onclick="closeMediaModal()" class="p-2 hover:bg-gray-100 rounded-full transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <textarea id="mediaContent"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-none mb-4"
                    rows="4" placeholder="Ajoutez une légende..."></textarea>

                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center mb-4">
                    <input type="file" id="mediaInput" accept="image/*,video/*" multiple class="hidden"
                        onchange="handleMediaUpload(event)">
                    <button onclick="document.getElementById('mediaInput').click()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Sélectionner des fichiers
                    </button>
                    <p class="text-xs text-gray-500 mt-2">Photos ou vidéos</p>
                </div>

                <div id="mediaPreview" class="grid grid-cols-2 gap-2 mb-4"></div>

                <div class="flex justify-end gap-2">
                    <button onclick="closeMediaModal()"
                        class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                        Annuler
                    </button>
                    <button onclick="publishMediaPost()"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                        Publier
                    </button>
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

                <textarea id="pollDescription"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-none mb-4"
                    rows="3" placeholder="Description (optionnel)"></textarea>

                <div id="pollOptions" class="space-y-2 mb-4">
                    <input type="text" class="poll-option-input w-full px-4 py-2 border border-gray-300 rounded-lg"
                        placeholder="Option 1">
                    <input type="text" class="poll-option-input w-full px-4 py-2 border border-gray-300 rounded-lg"
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
                    <button onclick="publishPollPost()"
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
