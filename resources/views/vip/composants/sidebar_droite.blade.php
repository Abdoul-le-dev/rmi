<aside class="hidden lg:block lg:col-span-3 ">

    <!-- Version améliorée du sidebar sticky -->
    <div class="sticky top-20 space-y-5 group relative  overflow-hidden rounded-r-xl">

        <!-- Card principale avec image hero -->
        <div class="div ">
            <div class="shadow-lg hover:shadow-xl ">

                <!-- Image avec overlay gradient -->
                <div class="h-52 ">
                    <img src="{{ asset('/teams/images/main.jpg') }}" class="w-full h-full object-cover transform "
                        alt="RMClass Trading Academy">
                </div>
            </div>

            <!-- Slogan/Objectif 2026 -->
            <div class="bg-gradient-to-br from-amber-50 via-white to-indigo-50 p-5">
                <div class="flex items-start gap-3">

                    <div>
                        <h4 class="font-bold text-gray-900 text-sm mb-2">Objectif 2026</h4>
                        <p class="text-gray-700 text-sm leading-relaxed">
                            Permettre à chaque étudiant de générer <span class="font-bold text-indigo-600">au
                                minimum 5 000 USD</span> grâce au
                            trading professionnel.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Statistiques améliorées -->
            <div class="bg-white ">
                <div class="grid grid-cols-3 divide-x divide-gray-100">

                    <!-- Posts -->
                    <div class="p-4 text-center group hover:bg-blue-50 transition-colors cursor-pointer">
                        <div class="flex flex-col items-center gap-1">

                            <p class="font-bold text-gray-900 text-lg">500K</p>
                            <p class="text-xs text-gray-500 font-medium">Posts</p>
                        </div>
                    </div>

                    <!-- Étudiants -->
                    <div class="p-4 text-center group hover:bg-indigo-50 transition-colors cursor-pointer">
                        <div class="flex flex-col items-center gap-1">

                            <p class="font-bold text-gray-900 text-lg">23.5M</p>
                            <p class="text-xs text-gray-500 font-medium">Étudiants</p>
                        </div>
                    </div>

                    <!-- En ligne -->
                    <div class="p-4 text-center group hover:bg-green-50 transition-colors cursor-pointer">
                        <div class="flex flex-col items-center gap-1">

                            <p class="font-bold text-gray
                                    -900 text-lg">50</p>
                            <p class="text-xs text-gray-500 font-medium">En ligne</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- Call-to-Action : Inviter des amis -->
        <div
            class="bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-2xl p-6 shadow-lg relative overflow-hidden">

            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>

            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>

                <h4 class="font-bold text-white text-base mb-2">Invitez vos amis</h4>
                <p class="text-blue-100 text-sm mb-4 leading-relaxed">
                    Partagez votre expérience et gagnez des récompenses pour chaque ami qui rejoint RMClass.
                </p>

                <button
                    class="w-full bg-white text-blue-600 font-semibold py-3 px-4 rounded-xl hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                    </svg>
                    Partager maintenant
                </button>
            </div>
        </div>




        <!-- Section Membres actifs / Top traders -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-bold text-gray-900 text-sm">Top Traders</h4>
                <button class="text-blue-600 hover:text-blue-700 text-xs font-semibold">Voir tout</button>
            </div>

            <!-- Liste des membres -->
            <div class="space-y-3">

                <!-- Membre 1 -->
                <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                    <div class="relative">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-amber-400 to-yellow-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                            JD
                        </div>
                        <span
                            class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 text-sm truncate">John Doe</p>
                        <p class="text-xs text-gray-500">+$12,450 ce mois</p>
                    </div>
                    <div class="flex-shrink-0">
                        <span
                            class="inline-flex items-center px-2 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                            Gold
                        </span>
                    </div>
                </div>

                <!-- Membre 2 -->
                <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                    <div class="relative">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-gray-400 to-gray-300 rounded-full flex items-center justify-center text-white font-bold text-sm">
                            SA
                        </div>
                        <span
                            class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 text-sm truncate">Sarah A.</p>
                        <p class="text-xs text-gray-500">+$8,320 ce mois</p>
                    </div>
                    <div class="flex-shrink-0">
                        <span
                            class="inline-flex items-center px-2 py-1 bg-gray-200 text-gray-700 text-xs font-semibold rounded-full">
                            Silver
                        </span>
                    </div>
                </div>

                <!-- Membre 3 -->
                <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                    <div class="relative">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-orange-700 to-orange-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                            MK
                        </div>
                        <span
                            class="absolute bottom-0 right-0 w-3 h-3 bg-gray-400 rounded-full border-2 border-white"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 text-sm truncate">Mike K.</p>
                        <p class="text-xs text-gray-500">+$5,890 ce mois</p>
                    </div>
                    <div class="flex-shrink-0">
                        <span
                            class="inline-flex items-center px-2 py-1 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full">
                            Bronze
                        </span>
                    </div>
                </div>

            </div>
        </div>



        <!-- Badge de certification ou sécurité -->
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 text-sm">Certifié & Sécurisé</p>
                    <p class="text-xs text-gray-500">Formation reconnue</p>
                </div>
            </div>
        </div>

    </div>
</aside>