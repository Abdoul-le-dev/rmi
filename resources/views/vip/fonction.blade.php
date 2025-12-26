<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie - Chat App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media (max-width: 768px) {
            .sidebar { width: 100%; }
            .chat-zone.mobile-hidden { display: none; }
            .sidebar.mobile-hidden { display: none; }
        }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 h-screen overflow-hidden">

    <div class="flex h-screen">
        <!-- Sidebar Navigation (Mobile) -->
        <div class="hidden md:flex flex-col w-20 bg-white border-r border-gray-200">
            <div class="p-4 flex flex-col items-center gap-6">
                <button class="p-3 hover:bg-gray-100 rounded-xl transition">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                </button>
                <button class="p-3 bg-indigo-100 text-indigo-600 rounded-xl">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/><path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                    </svg>
                </button>
                <button class="p-3 hover:bg-gray-100 rounded-xl transition relative">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <button class="p-3 hover:bg-gray-100 rounded-xl transition">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.532 1.532 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.532 1.532 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Liste des conversations -->
        <div class="sidebar w-full md:w-96 bg-white border-r border-gray-200 flex flex-col" id="sidebarConv">
            <!-- Header -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-bold">Messages</h1>
                    <div class="flex gap-2">
                        <button class="p-2 hover:bg-gray-100 rounded-full transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <button class="p-2 hover:bg-gray-100 rounded-full transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- Barre de recherche -->
                <div class="relative">
                    <input type="text" placeholder="Rechercher une conversation..." 
                        class="w-full bg-gray-100 rounded-full pl-10 pr-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            <!-- Liste -->
            <div class="flex-1 overflow-y-auto scrollbar-hide" id="conversationList"></div>
        </div>

        <!-- Zone de chat -->
        <div class="chat-zone flex-1 flex flex-col bg-gray-50" id="chatZone">
            <!-- Header Chat -->
            <div class="bg-white border-b border-gray-200 p-4 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <button class="md:hidden p-2 hover:bg-gray-100 rounded-full" onclick="backToList()">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <div class="relative">
                        <img src="https://ui-avatars.com/api/?name=HOUESSOU+Amour&background=6366f1&color=fff&size=40" 
                            class="w-10 h-10 rounded-full">
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div>
                        <h2 class="font-semibold">HOUESSOU Amour</h2>
                        <p class="text-xs text-green-600">En ligne</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button class="p-2 hover:bg-gray-100 rounded-full transition">
                        <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                    </button>
                    <button class="p-2 hover:bg-gray-100 rounded-full transition">
                        <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                        </svg>
                    </button>
                    <button class="hidden md:block p-2 hover:bg-gray-100 rounded-full transition">
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3" id="chatMessages">
                <!-- Message reçu -->
                <div class="flex items-start gap-2">
                    <img src="https://ui-avatars.com/api/?name=HA&background=6366f1&color=fff&size=32" class="w-8 h-8 rounded-full">
                    <div>
                        <div class="bg-white rounded-2xl rounded-tl-sm px-4 py-2.5 shadow-sm max-w-xs">
                            <p class="text-sm">Ou c'est disponible</p>
                        </div>
                        <span class="text-xs text-gray-500 ml-2 mt-1 block">4:30 PM</span>
                    </div>
                </div>

                <!-- Messages envoyés -->
                <div class="flex justify-end">
                    <div>
                        <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-2xl rounded-tr-sm px-4 py-2.5 shadow-sm max-w-xs">
                            <p class="text-sm">Salut, les parts dont tu m'avais parlé, sont dispos ?</p>
                        </div>
                        <div class="flex items-center justify-end gap-1 mt-1">
                            <span class="text-xs text-gray-500">4:55 PM</span>
                            <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <div>
                        <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-2xl rounded-tr-sm px-4 py-2.5 shadow-sm max-w-xs">
                            <p class="text-sm">Super, puis-je avoir un aperçu ?</p>
                        </div>
                        <div class="flex items-center justify-end gap-1 mt-1">
                            <span class="text-xs text-gray-500">4:55 PM</span>
                            <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Message avec média -->
                <div class="flex items-start gap-2">
                    <img src="https://ui-avatars.com/api/?name=HA&background=6366f1&color=fff&size=32" class="w-8 h-8 rounded-full">
                    <div>
                        <div class="bg-white rounded-2xl rounded-tl-sm p-2 shadow-sm max-w-sm">
                            <p class="text-sm mb-2 px-2">Bien sûr ! Je t'envoie quelques images !</p>
                            <img src="https://images.unsplash.com/photo-1556656793-08538906a9f8?w=300&h=200&fit=crop" 
                                class="rounded-xl w-full mb-2">
                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-xs font-semibold mb-2">Sticker Pack Name</p>
                                <div class="grid grid-cols-3 gap-2">
                                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg"></div>
                                    <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-red-500 rounded-lg"></div>
                                    <div class="w-14 h-14 bg-gradient-to-br from-red-400 to-pink-500 rounded-lg"></div>
                                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-lg"></div>
                                    <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg"></div>
                                    <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-pink-600 rounded-lg"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">🔥 4 Replies</p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-500 ml-2 mt-1 block">4:32 PM</span>
                    </div>
                </div>

                <!-- Séparateur de date -->
                <div class="flex justify-center my-4">
                    <span class="text-xs text-gray-500 bg-white px-4 py-1.5 rounded-full shadow-sm">Aujourd'hui</span>
                </div>

                <!-- Plus de messages -->
                <div class="flex justify-end">
                    <div>
                        <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-2xl rounded-tr-sm px-4 py-2.5 shadow-sm max-w-xs">
                            <p class="text-sm">Merci ! Celle me semble correcte</p>
                        </div>
                        <div class="flex items-center justify-end gap-1 mt-1">
                            <span class="text-xs text-gray-500">4:55 PM</span>
                            <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <div>
                        <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-2xl rounded-tr-sm px-4 py-2.5 shadow-sm max-w-xs">
                            <p class="text-sm">Je les prends</p>
                        </div>
                        <div class="flex items-center justify-end gap-1 mt-1">
                            <span class="text-xs text-gray-500">4:55 PM</span>
                            <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Zone de saisie -->
            <div class="bg-white border-t border-gray-200 p-4">
                <div class="flex items-end gap-2">
                    <button class="p-2.5 text-indigo-600 hover:bg-indigo-50 rounded-full transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-.464 5.535a1 1 0 10-1.415-1.414 3 3 0 01-4.242 0 1 1 0 00-1.415 1.414 5 5 0 007.072 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <div class="flex-1 flex items-center bg-gray-100 rounded-3xl px-4 py-2">
                        <input type="text" placeholder="Type your message..." 
                            class="flex-1 bg-transparent outline-none text-sm">
                        <div class="flex items-center gap-1">
                            <button class="p-1.5 text-gray-500 hover:text-indigo-600 transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <button class="p-1.5 text-gray-500 hover:text-indigo-600 transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button class="p-2.5 bg-indigo-600 text-white hover:bg-indigo-700 rounded-full transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        
    </script>

</body>
</html>