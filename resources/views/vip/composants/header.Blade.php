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
                    <img alt="logo" src="{{ asset('assets/vip/logo_rmi.png') }}">
                </div>
            </div>

      

            <!-- Actions utilisateur -->
            <div class="flex items-center space-x-4">
                

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
                <button class="hidden md:flex relative p-2 rounded-lg hover:bg-gray-100 transition-colors" onclick="message_view()">
                    <i class="fas fa-comment-dots text-gray-600 text-lg"></i>
                    <span class="notification-badge pulse-dot">2</span>
                </button>

                <
            </div>
        </div>
    </div>
</header>