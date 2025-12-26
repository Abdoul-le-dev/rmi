<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sessions - Groupes Trading</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes slideRight {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-slide-in { animation: slideIn 0.4s ease-out; }
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
        .animate-scale-in { animation: scaleIn 0.3s ease-out; }
        .animate-slide-right { animation: slideRight 0.3s ease-out; }
        
        @media (max-width: 768px) {
            .sidebar { width: 100%; }
            .chat-zone.mobile-hidden { display: none; }
            .sidebar.mobile-hidden { display: none; }
        }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        
        .comment-input:focus-within {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        .like-btn:active {
            transform: scale(0.9);
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 h-screen overflow-hidden">

    <div class="flex h-screen">
        <!-- Sidebar Navigation (Desktop) -->
        <div class="hidden md:flex flex-col w-20 bg-white border-r border-gray-200 shadow-sm">
            <div class="p-4 flex flex-col items-center gap-6">
                <button class="p-3 hover:bg-gradient-to-br hover:from-indigo-50 hover:to-purple-50 rounded-xl transition-all duration-300 group">
                    <svg class="w-6 h-6 text-gray-600 group-hover:text-indigo-600 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                </button>
                <button class="p-3 hover:bg-gradient-to-br hover:from-indigo-50 hover:to-purple-50 rounded-xl transition-all duration-300 group">
                    <svg class="w-6 h-6 text-gray-600 group-hover:text-indigo-600 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/><path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                    </svg>
                </button>
                <button class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl relative shadow-lg shadow-indigo-200">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                    </svg>
                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full animate-pulse"></span>
                </button>
                <button class="p-3 hover:bg-gradient-to-br hover:from-indigo-50 hover:to-purple-50 rounded-xl transition-all duration-300 relative group">
                    <svg class="w-6 h-6 text-gray-600 group-hover:text-indigo-600 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                </button>
                <button class="p-3 hover:bg-gradient-to-br hover:from-indigo-50 hover:to-purple-50 rounded-xl transition-all duration-300 group mt-auto">
                    <svg class="w-6 h-6 text-gray-600 group-hover:text-indigo-600 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.532 1.532 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.532 1.532 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Liste des Sessions -->
        <div class="sidebar w-full md:w-96 bg-white border-r border-gray-200 flex flex-col shadow-sm" id="sidebarSessions">
            <!-- Header -->
            <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-white to-gray-50">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-bold gradient-text">Sessions</h1>
                    <button class="p-2 hover:bg-gradient-to-br hover:from-indigo-50 hover:to-purple-50 rounded-full transition-all duration-300">
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                        </svg>
                    </button>
                </div>
                <!-- Barre de recherche -->
                <div class="relative group">
                    <input type="text" placeholder="Rechercher une session..." 
                        class="w-full bg-gray-100 rounded-full pl-10 pr-4 py-3 text-sm outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all duration-300">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3 group-focus-within:text-indigo-500 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            <!-- Liste -->
            <div class="flex-1 overflow-y-auto scrollbar-hide" id="sessionsList"></div>
        </div>

        <!-- Zone vide (état initial) -->
        <div class="chat-zone flex-1 flex items-center justify-center" id="chatZone">
            <div class="text-center max-w-md px-4 animate-fade-in">
                <div class="mb-6 flex justify-center">
                    <div class="w-32 h-32 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-16 h-16 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                        </svg>
                    </div>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-3">Bienvenue sur vos Sessions</h2>
                <p class="text-gray-500">
                    Sélectionnez une session pour découvrir les publications et participer aux discussions
                </p>
            </div>
        </div>
    </div>

    

</body>
</html>