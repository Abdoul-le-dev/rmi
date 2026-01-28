<div id="mobile-menu" class="fixed inset-0 z-50 hidden">
        <!-- Overlay -->
        <div class="mobile-menu-overlay absolute inset-0" id="mobile-menu-overlay"></div>

        <!-- Menu sidebar -->
        <div class="slide-in absolute left-0 top-0 bottom-0 w-80 bg-white shadow-2xl overflow-y-auto">
            <!-- Header -->
            <div class="gradient-bg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">Menu</h3>
                    <button id="close-mobile-menu" class="p-2 hover:bg-white/20 rounded-lg transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                <div class="flex items-center space-x-3">
                    <img src="https://i.pravatar.cc/150?img=12" alt="Profil"
                        class="w-16 h-16 rounded-full border-3 border-white">
                    <div>
                        <p class="font-bold">CHOUTI Abdoulaye</p>
                        <p class="text-xs text-indigo-100">Modérateur</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="p-4">

                <a href="#"
                    class="group relative flex items-center space-x-3 px-4 py-3 rounded-lg bg-indigo-50 text-indigo-600 mb-2 overflow-hidden transition-all duration-300 hover:bg-indigo-600 hover:text-white hover:shadow-xl hover:scale-105">
                    <!-- Particules animées en arrière-plan -->
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute w-2 h-2 bg-white rounded-full animate-particle-1"></div>
                        <div class="absolute w-1.5 h-1.5 bg-yellow-300 rounded-full animate-particle-2"></div>
                        <div class="absolute w-1 h-1 bg-pink-300 rounded-full animate-particle-3"></div>
                        <div class="absolute w-2 h-2 bg-cyan-300 rounded-full animate-particle-4"></div>
                        <div class="absolute w-1.5 h-1.5 bg-white rounded-full animate-particle-5"></div>
                    </div>

                    <!-- Vague énergétique -->
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-0 group-hover:opacity-30 group-hover:animate-wave">
                    </div>

                    <!-- Icône avec animation -->
                    <i
                        class="fas fa-home text-base w-4 relative z-10 transition-all duration-300 group-hover:rotate-12 group-hover:scale-125"></i>

                    <!-- Texte avec animation -->
                    <p class="font-bold relative z-10 transition-all duration-300 group-hover:tracking-wider">
                        Communautés</p>

                    <!-- Lueur pulsante -->
                    <div
                        class="absolute inset-0 rounded-lg bg-indigo-400 opacity-0 group-hover:opacity-20 group-hover:animate-pulse-glow">
                    </div>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-indigo-50 text-indigo-600 mb-2">
                    <i class="fas fa-home text-base w-4"></i>
                    <p class="font-bold">Communautés</p>
                </a>
                <a href="#"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 mb-2">
                    <i class="fas fa-book text-base w-6"></i>
                    <span class="font-medium">Classrooms</span>
                </a>
                <a href="#"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 mb-2">
                    <i class="fas fa-calendar-alt text-base w-6"></i>
                    <span class="font-medium">Événements</span>
                </a>
                <a href="#"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 mb-2">
                    <i class="fas fa-map-marker-alt text-base w-6"></i>
                    <span class="font-medium">Localisations des Membres</span>
                </a>
                <a href="#"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 mb-2">
                    <i class="fas fa-users text-base w-6"></i>
                    <span class="font-medium">Vos Sessions</span>
                </a>
                <a href="#"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 mb-2">
                    <i class="fas fa-cog text-base w-6"></i>
                    <span class="font-medium">Paramètres</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-question-circle text-base w-6"></i>
                    <span class="font-medium">Centre d'aide</span>
                </a>
            </nav>

            <!-- Stats -->
            <div class="mx-4 my-6 p-4 bg-gray-50 rounded-lg">
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="font-bold text-base">500k</p>
                        <p class="text-xs text-gray-500">Posts</p>
                    </div>
                    <div class="border-x border-gray-300">
                        <p class="font-bold text-base">23.5M</p>
                        <p class="text-xs text-gray-500">Followers</p>
                    </div>
                    <div>
                        <p class="font-bold text-base">50</p>
                        <p class="text-xs text-gray-500">Following</p>
                    </div>
                </div>
            </div>
        </div>
    </div>