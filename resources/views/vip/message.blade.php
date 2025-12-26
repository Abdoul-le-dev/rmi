<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>
<body class="bg-white h-screen overflow-hidden">

    <div class="flex h-screen">
        <!-- Sidebar Messages -->
        <div class="w-80 border-r border-gray-200 flex flex-col">
            <!-- Header -->
            <div class="p-4 flex justify-between items-center border-b border-gray-200">
                <h1 class="text-2xl font-bold">Messages</h1>
                <button class="p-2 hover:bg-gray-100 rounded-full">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                    </svg>
                </button>
            </div>

            <!-- Liste des conversations -->
            <div class="flex-1 overflow-y-auto" id="conversationList">
                <!-- Les conversations seront générées ici -->
            </div>
        </div>

        <!-- Zone de chat -->
        <div class="flex-1 flex flex-col">
            <!-- Header Chat -->
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-purple-600 text-white flex items-center justify-center font-bold">
                        HA
                    </div>
                    <div>
                        <h2 class="font-semibold">HOUESSOU Amour</h2>
                        <p class="text-xs text-gray-500">En ligne</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button class="p-2 hover:bg-gray-100 rounded-full">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                    </button>
                    <button class="p-2 hover:bg-gray-100 rounded-full">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                        </svg>
                    </button>
                    <button class="p-2 hover:bg-gray-100 rounded-full">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chatMessages">
                <div class="flex justify-start">
                    <div class="bg-gray-100 rounded-2xl px-4 py-2 max-w-xs">
                        <p class="text-sm">Ou c'est disponible</p>
                        <span class="text-xs text-gray-500">4:30 pm</span>
                    </div>
                </div>

                <div class="flex justify-end">
                    <div class="bg-indigo-600 text-white rounded-2xl px-4 py-2 max-w-xs">
                        <p class="text-sm">Salut, les parts dont tu m'avais parlé, sont dispos ?</p>
                        <span class="text-xs opacity-75">4:55 pm</span>
                    </div>
                </div>

                <div class="flex justify-end">
                    <div class="bg-indigo-600 text-white rounded-2xl px-4 py-2 max-w-xs">
                        <p class="text-sm">Super, puis-je avoir un aperçu ?</p>
                        <span class="text-xs opacity-75">4:55 pm</span>
                    </div>
                </div>

                <div class="flex justify-start">
                    <div class="bg-gray-100 rounded-2xl px-4 py-2 max-w-xs">
                        <p class="text-sm">Bien sûr ! Je t'envoie quelques images !</p>
                        <span class="text-xs text-gray-500">4:30 pm</span>
                    </div>
                </div>

                <div class="flex justify-start">
                    <div class="bg-gray-100 rounded-2xl p-2 max-w-xs">
                        <img src="https://images.unsplash.com/photo-1556656793-08538906a9f8?w=300" alt="Image" class="rounded-lg mb-2">
                        <div class="bg-white rounded-lg p-2 mb-2">
                            <p class="text-xs font-semibold">Sticker Pack Name</p>
                            <div class="grid grid-cols-3 gap-2 mt-2">
                                <div class="w-12 h-12 bg-yellow-400 rounded"></div>
                                <div class="w-12 h-12 bg-orange-400 rounded"></div>
                                <div class="w-12 h-12 bg-red-400 rounded"></div>
                                <div class="w-12 h-12 bg-yellow-500 rounded"></div>
                                <div class="w-12 h-12 bg-orange-500 rounded"></div>
                                <div class="w-12 h-12 bg-red-500 rounded"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">4 Replies</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center">
                    <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">Today</span>
                </div>

                <div class="flex justify-end">
                    <div class="bg-indigo-600 text-white rounded-2xl px-4 py-2 max-w-xs">
                        <p class="text-sm">Merci ! Celle me semble correcte</p>
                        <span class="text-xs opacity-75">4:55 pm</span>
                    </div>
                </div>

                <div class="flex justify-end">
                    <div class="bg-indigo-600 text-white rounded-2xl px-4 py-2 max-w-xs">
                        <p class="text-sm">Je les prends</p>
                        <span class="text-xs opacity-75">4:55 pm</span>
                    </div>
                </div>
            </div>

            <!-- Input Zone -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center gap-2 bg-gray-100 rounded-full px-4 py-2">
                    <button class="p-1 hover:bg-gray-200 rounded-full">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-.464 5.535a1 1 0 10-1.415-1.414 3 3 0 01-4.242 0 1 1 0 00-1.415 1.414 5 5 0 007.072 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <input type="text" placeholder="Type your message..." class="flex-1 bg-transparent outline-none text-sm">
                    <button class="p-1 hover:bg-gray-200 rounded-full">
                        <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <button class="p-1 hover:bg-gray-200 rounded-full">
                        <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <button class="p-1 hover:bg-gray-200 rounded-full">
                        <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset("assets/vip/msg.js") }}"></script>

</body>
</html>