<header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <!-- Logo et titre -->
            <div class="flex items-center space-x-3">
                <!-- Bouton menu mobile -->
                <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-bars text-gray-600 text-lg"></i>
                </button>

                <!-- Logo -->
                <div class="flex items-center space-x-2 cursor-pointer">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-lg">
                        <i class="fas fa-play text-white text-xs"></i>
                    </div>
                    <span class="text-lg font-bold">
                        Trade <span class="text-indigo-600">Hub</span>
                    </span>
                </div>
            </div>

            <!-- Barre de recherche (desktop uniquement) -->
            <div class="hidden md:flex flex-1 max-w-md mx-8">
                <div class="relative w-full">
                    <input type="text" placeholder="Rechercher..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
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
                <button class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors" onclick="toggleNotif()">
                    <i class="fas fa-bell text-gray-600 text-lg"></i>
                    <span class="notification-badge pulse-dot" id="badge">3</span>
                </button>

                <div class="hidden absolute top-16 right-5 w-96 max-h-[500px] bg-white rounded-lg shadow-xl flex-col z-50"
                    id="notifPanel">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-2xl font-bold">Notifications</h3>
                    </div>
                    <div class="overflow-y-auto max-h-96 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100"
                        id="notifList"></div>
                </div>

                <!-- Messages -->
                <button class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors" onclick="message_view()">
                    <i class="fas fa-comment-dots text-gray-600 text-lg"></i>
                    <span class="notification-badge pulse-dot">2</span>
                </button>

                <!-- Avatar utilisateur -->
                <div class="relative">
                    <button id="user-menu-btn"
                        class="flex items-center space-x-2 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                        <img src="https://i.pravatar.cc/150?img=12" alt="Avatar"
                            class="w-7 h-7 rounded-full object-cover ring-2 ring-indigo-500">
                    </button>

                    <!-- Dropdown menu (caché par défaut) -->
                    <div id="user-dropdown"
                        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2">
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">Mon Profil</a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">Paramètres</a>
                        <hr class="my-2">
                        <a href="#" class="block px-4 py-2 text-xs text-red-600 hover:bg-gray-100">Déconnexion</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>