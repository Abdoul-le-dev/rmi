<aside class="hidden lg:block lg:col-span-3">
    <div class="bg-white rounded-l-xl shadow-sm border border-gray-200 overflow-hidden sticky top-20">

        <!-- Profil utilisateur -->
        <div class="p-6 text-center border-b border-gray-200 gradient-bg">
            <div class="relative inline-block mb-3">
                <img src="https://i.pravatar.cc/150?img=12" alt="Profil"
                    class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg mx-auto">
                <div class="absolute bottom-2 right-2 w-3 h-3 bg-green-500 border-3 border-white rounded-full">
                </div>
            </div>
            <h3 class="font-bold text-white text-base ">{{ $userData['user_name'] }}</h3>
            <p class="text-indigo-100 text-xs change">{{ $userData['user_status'] }}</p>
            <!-- Moderator
            <div class="flex items-center justify-center space-x-1 mt-1">
                <i class="fas fa-check-circle text-white text-xs"></i>
                <span class="text-white text-xs change">Vérifié</span>
            </div>-->
        </div>

        <!-- Tier Progress -->
        <div class=" backdrop-blur-sm rounded-2xl p-5 mb-3">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-300 font-medium">Progression vers Diamond</span>
                <span class="text-sm text-amber-400 font-bold">{{ $userData['montant_restant'] }}%</span>
            </div>

            <!-- Progress Bar -->
            <div class="relative h-3 bg-gray-700/50 rounded-full overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 via-orange-800 to-yellow-500 rounded-full"
                    style="width: {{ $userData['percent'] }}%">
                    <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                </div>
            </div>

            <div class="flex items-center justify-between mt-3 text-xs">
                <span class="text-gray-400">${{ $userData['montant_total'] }} / $100,000</span>
                <span class="text-amber-400 font-medium">+${{ $userData['montant_restant'] }}K restants</span>
            </div>
        </div>



       
        <!-- Tier Badges Grid -->
        <div class="grid grid-cols-4 gap-x-2 mb-6">

            {{-- BADGE BRONZE --}}
            @if($userData['plaque'] == 'bronze')
                <!-- Bronze - Active -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-orange-800 to-orange-600 flex items-center justify-center shadow-lg shadow-orange-500/20 group-hover:scale-110 transition-transform relative">
                        <svg class="w-5 h-5 text-orange-100" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-400 rounded-full animate-pulse"></div>
                    </div>
                    <span class="text-xs text-orange-400 mt-1.5 font-bold">Bronze</span>
                </div>
                <!-- Silver - Locked -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-gray-700 to-gray-600 flex items-center justify-center shadow-lg opacity-40 group-hover:opacity-60 transition-opacity">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <span class="text-xs text-gray-500 mt-1.5 font-medium">Silver</span>
                </div>
                <!-- Gold - Locked -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-gray-700 to-gray-600 flex items-center justify-center shadow-lg opacity-40 group-hover:opacity-60 transition-opacity">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <span class="text-xs text-gray-500 mt-1.5 font-medium">Gold</span>
                </div>
                <!-- Diamond - Locked -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-gray-700 to-gray-600 flex items-center justify-center shadow-lg opacity-40 group-hover:opacity-60 transition-opacity">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <span class="text-xs text-gray-500 mt-1.5 font-medium">Diamond</span>
                </div>

                {{-- BADGE SILVER --}}
            @elseif($userData['plaque'] == 'silver')
                <!-- Bronze - Unlocked -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-orange-800 to-orange-600 flex items-center justify-center shadow-lg shadow-orange-500/20 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-orange-100" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <span class="text-xs text-orange-400 mt-1.5 font-medium">Bronze</span>
                </div>
                <!-- Silver - Active -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-gray-400 to-gray-300 flex items-center justify-center shadow-lg shadow-gray-300/20 group-hover:scale-110 transition-transform relative">
                        <svg class="w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-400 rounded-full animate-pulse"></div>
                    </div>
                    <span class="text-xs text-gray-400 mt-1.5 font-bold">Silver</span>
                </div>
                <!-- Gold - Locked -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-gray-700 to-gray-600 flex items-center justify-center shadow-lg opacity-40 group-hover:opacity-60 transition-opacity">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <span class="text-xs text-gray-500 mt-1.5 font-medium">Gold</span>
                </div>
                <!-- Diamond - Locked -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-gray-700 to-gray-600 flex items-center justify-center shadow-lg opacity-40 group-hover:opacity-60 transition-opacity">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <span class="text-xs text-gray-500 mt-1.5 font-medium">Diamond</span>
                </div>

                {{-- BADGE GOLD --}}
            @elseif($userData['plaque'] == 'gold')
                <!-- Bronze - Unlocked -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-orange-800 to-orange-600 flex items-center justify-center shadow-lg shadow-orange-500/20 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-orange-100" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <span class="text-xs text-orange-400 mt-1.5 font-medium">Bronze</span>
                </div>
                <!-- Silver - Unlocked -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-gray-400 to-gray-300 flex items-center justify-center shadow-lg shadow-gray-300/20 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <span class="text-xs text-gray-400 mt-1.5 font-medium">Silver</span>
                </div>
                <!-- Gold - Active -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-amber-400 to-yellow-500 flex items-center justify-center shadow-lg shadow-amber-500/50 group-hover:scale-110 transition-transform relative">
                        <svg class="w-5 h-5 text-amber-50" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-400 rounded-full animate-pulse"></div>
                    </div>
                    <span class="text-xs text-amber-400 mt-1.5 font-bold">Gold</span>
                </div>
                <!-- Diamond - Locked -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-gray-700 to-gray-600 flex items-center justify-center shadow-lg opacity-40 group-hover:opacity-60 transition-opacity">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <span class="text-xs text-gray-500 mt-1.5 font-medium">Diamond</span>
                </div>

                {{-- BADGE DIAMOND --}}
            @elseif($userData['plaque'] == 'diamond')
                <!-- Bronze - Unlocked -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-orange-800 to-orange-600 flex items-center justify-center shadow-lg shadow-orange-500/20 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-orange-100" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <span class="text-xs text-orange-400 mt-1.5 font-medium">Bronze</span>
                </div>
                <!-- Silver - Unlocked -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-gray-400 to-gray-300 flex items-center justify-center shadow-lg shadow-gray-300/20 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <span class="text-xs text-gray-400 mt-1.5 font-medium">Silver</span>
                </div>
                <!-- Gold - Unlocked -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-amber-400 to-yellow-500 flex items-center justify-center shadow-lg shadow-amber-500/50 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-amber-50" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <span class="text-xs text-amber-400 mt-1.5 font-medium">Gold</span>
                </div>
                <!-- Diamond - Active -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center shadow-lg shadow-cyan-500/50 group-hover:scale-110 transition-transform relative">
                        <svg class="w-5 h-5 text-cyan-50" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-400 rounded-full animate-pulse"></div>
                    </div>
                    <span class="text-xs text-cyan-400 mt-1.5 font-bold">Diamond</span>
                </div>

                {{-- PAS DE BADGE (none) --}}
            @else
                <!-- Tous verrouillés -->
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-gray-700 to-gray-600 flex items-center justify-center shadow-lg opacity-40 group-hover:opacity-60 transition-opacity">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <span class="text-xs text-gray-500 mt-1.5 font-medium">Bronze</span>
                </div>
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-gray-700 to-gray-600 flex items-center justify-center shadow-lg opacity-40 group-hover:opacity-60 transition-opacity">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <span class="text-xs text-gray-500 mt-1.5 font-medium">Silver</span>
                </div>
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-gray-700 to-gray-600 flex items-center justify-center shadow-lg opacity-40 group-hover:opacity-60 transition-opacity">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <span class="text-xs text-gray-500 mt-1.5 font-medium">Gold</span>
                </div>
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-gray-700 to-gray-600 flex items-center justify-center shadow-lg opacity-40 group-hover:opacity-60 transition-opacity">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <span class="text-xs text-gray-500 mt-1.5 font-medium">Diamond</span>
                </div>
            @endif

        </div>



        <!-- Navigation -->
        <nav class="p-3">

            <a href="#"
                class="group relative flex items-center space-x-3 px-4 py-4 rounded-lg bg-indigo-50 text-indigo-600 mb-2 overflow-hidden transition-all duration-300 hover:bg-indigo-600 hover:text-white hover:shadow-xl hover:scale-105">
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

            <div id="sidebar-buttons" class="space-y-3">
                <a href="/vip/session" onclick="moveToTop(this); playRandomClickSound(); return false;"
                    class="sidebar-button group relative flex items-center space-x-3 px-4 py-4 rounded-xl bg-gradient-to-br from-slate-50 via-gray-50 to-slate-100 text-gray-800 overflow-hidden transition-all duration-500 hover:from-indigo-600 hover:via-indigo-500 hover:to-indigo-700 hover:text-white hover:shadow-2xl hover:shadow-indigo-500/40 hover:scale-[1.02] hover:-translate-y-0.5 border border-gray-200/50 hover:border-indigo-300/50">

                    <div
                        class="absolute inset-0 bg-gradient-to-br from-indigo-600/0 via-indigo-500/0 to-indigo-700/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 group-hover:animate-gradient-shift">
                    </div>

                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-indigo-600 via-indigo-500 to-blue-600 rounded-xl opacity-0 group-hover:opacity-30 blur-2xl transition-all duration-700 group-hover:animate-aura-breathe">
                    </div>

                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(255,255,255,0.6),transparent_50%)] opacity-0 group-hover:animate-ripple-expand">
                        </div>
                    </div>

                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-400">
                        <div
                            class="absolute w-1 h-1 bg-white rounded-full animate-rewind-particle-1 shadow-lg shadow-white/50">
                        </div>
                        <div
                            class="absolute w-0.5 h-0.5 bg-indigo-200 rounded-full animate-rewind-particle-2 shadow-lg shadow-indigo-200/50">
                        </div>
                        <div
                            class="absolute w-1 h-1 bg-purple-200 rounded-full animate-rewind-particle-3 shadow-lg shadow-purple-200/50">
                        </div>
                    </div>

                    <div
                        class="absolute top-1.5 right-1.5 opacity-0 group-hover:opacity-100 transition-all duration-300 animate-status-fade-in">
                        <span
                            class="text-[8px] font-black tracking-wider text-white uppercase px-1.5 py-0.5 bg-indigo-600 rounded-sm">REPLAY</span>
                    </div>

                    <div
                        class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        <div class="absolute inset-0 rounded-xl border-2 border-white/40 animate-border-pulse-1">
                        </div>
                    </div>

                    <div
                        class="relative z-10 flex items-center justify-center w-5 h-5 transition-all duration-500 group-hover:scale-110">
                        <div
                            class="absolute inset-0 rounded-lg bg-gradient-to-br from-indigo-400 to-blue-500 opacity-0 group-hover:opacity-20 blur-md transition-all duration-500">
                        </div>
                        <i
                            class="fas fa-video text-sm transition-all duration-500 group-hover:drop-shadow-[0_0_8px_rgba(255,255,255,0.8)]"></i>
                    </div>

                    <span
                        class="relative z-10 font-semibold text-sm tracking-normal transition-all duration-500 group-hover:tracking-wide group-hover:drop-shadow-[0_2px_8px_rgba(255,255,255,0.3)]">
                        Replay Live class
                        <span
                            class="absolute -bottom-0.5 left-0 w-0 h-0.5 bg-gradient-to-r from-white via-blue-200 to-transparent group-hover:w-full transition-all duration-500 shadow-lg shadow-white/50"></span>
                    </span>

                    <div
                        class="absolute bottom-0 left-0 w-full h-1 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        <div
                            class="absolute bottom-0 left-0 h-full w-0 bg-gradient-to-r from-blue-400 via-indigo-400 to-cyan-400 group-hover:w-full transition-all duration-1000 ease-out shadow-lg shadow-indigo-400/50 blur-sm">
                        </div>
                    </div>

                    <div
                        class="absolute inset-0 bg-gradient-to-t from-white/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-xl">
                    </div>
                </a>

                <a href="/vip/live" onclick="moveToTop(this); playRandomClickSound(); return false;"
                    class="group relative flex items-center space-x-3 px-4 py-4 rounded-xl bg-gradient-to-br from-slate-50 via-gray-50 to-slate-100 text-gray-800 overflow-hidden transition-all duration-500 hover:from-indigo-600 hover:via-indigo-500 hover:to-indigo-700 hover:text-white hover:shadow-2xl hover:shadow-indigo-500/40 hover:scale-[1.02] hover:-translate-y-0.5 border border-gray-200/50 hover:border-indigo-300/50">

                    <div
                        class="absolute inset-0 bg-gradient-to-br from-indigo-600/0 via-indigo-500/0 to-indigo-700/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>

                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-indigo-600 via-indigo-500 to-blue-600 rounded-xl opacity-0 group-hover:opacity-30 blur-2xl transition-all duration-700 group-hover:animate-aura-breathe">
                    </div>

                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(255,255,255,0.6),transparent_50%)] opacity-0 group-hover:animate-ripple-expand">
                        </div>
                    </div>

                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-400">
                        <div
                            class="absolute w-1 h-1 bg-white rounded-full animate-particle-1 shadow-lg shadow-white/50">
                        </div>
                        <div
                            class="absolute w-0.5 h-0.5 bg-indigo-200 rounded-full animate-particle-2 shadow-lg shadow-indigo-200/50">
                        </div>
                        <div
                            class="absolute w-1 h-1 bg-purple-200 rounded-full animate-particle-3 shadow-lg shadow-purple-200/50">
                        </div>
                    </div>

                    <div
                        class="absolute top-1.5 right-1.5 opacity-0 group-hover:opacity-100 transition-all duration-300 animate-status-fade-in flex items-center space-x-1">
                        <div class="w-1.5 h-1.5 bg-red-500 rounded-full animate-live-pulse shadow-lg shadow-red-500/60">
                        </div>
                        <span
                            class="text-[8px] font-black tracking-wider text-white uppercase px-1.5 py-0.5 bg-red-600 rounded-sm">LIVE</span>
                    </div>

                    <div
                        class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        <div class="absolute inset-0 rounded-xl border-2 border-white/40 animate-border-pulse-1">
                        </div>
                    </div>

                    <div
                        class="relative z-10 flex items-center justify-center w-5 h-5 transition-all duration-500 group-hover:scale-110">
                        <div
                            class="absolute inset-0 rounded-lg bg-gradient-to-br from-indigo-400 to-blue-500 opacity-0 group-hover:opacity-20 blur-md transition-all duration-500">
                        </div>
                        <i
                            class="fas fa-users text-sm transition-all duration-500 group-hover:drop-shadow-[0_0_8px_rgba(255,255,255,0.8)]"></i>
                    </div>

                    <span
                        class="relative z-10 font-semibold text-sm tracking-normal transition-all duration-500 group-hover:tracking-wide group-hover:drop-shadow-[0_2px_8px_rgba(255,255,255,0.3)]">
                        Live class
                        <span
                            class="absolute -bottom-0.5 left-0 w-0 h-0.5 bg-gradient-to-r from-white via-blue-200 to-transparent group-hover:w-full transition-all duration-500 shadow-lg shadow-white/50"></span>
                    </span>

                    <div
                        class="absolute bottom-0 left-0 w-full h-1 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        <div
                            class="absolute bottom-0 left-0 h-full w-0 bg-gradient-to-r from-blue-400 via-indigo-400 to-cyan-400 group-hover:w-full transition-all duration-1000 ease-out shadow-lg shadow-indigo-400/50 blur-sm">
                        </div>
                    </div>

                    <div
                        class="absolute inset-0 bg-gradient-to-t from-white/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-xl">
                    </div>
                </a>

                <a href="/vip/evenement" onclick="moveToTop(this); playRandomClickSound(); return false;"
                    class="group relative flex items-center space-x-3 px-4 py-4 rounded-xl bg-gradient-to-br from-slate-50 via-gray-50 to-slate-100 text-gray-800 overflow-hidden transition-all duration-500 hover:from-blue-50 hover:via-indigo-50 hover:to-purple-50 hover:text-indigo-700 hover:shadow-xl hover:shadow-indigo-100/50 hover:scale-[1.02] hover:-translate-y-0.5 border border-gray-200/50 hover:border-indigo-200/50">

                    <div
                        class="absolute inset-0 bg-gradient-to-br from-blue-50/0 via-indigo-50/0 to-purple-50/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>

                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-blue-200 via-indigo-200 to-purple-200 rounded-xl opacity-0 group-hover:opacity-20 blur-2xl transition-all duration-700 group-hover:animate-aura-breathe">
                    </div>

                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(99,102,241,0.15),transparent_50%)] opacity-0 group-hover:animate-ripple-expand">
                        </div>
                    </div>

                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-400">
                        <div
                            class="absolute w-1 h-1 bg-indigo-300 rounded-full animate-particle-1 shadow-lg shadow-indigo-300/50">
                        </div>
                        <div
                            class="absolute w-0.5 h-0.5 bg-blue-200 rounded-full animate-particle-2 shadow-lg shadow-blue-200/50">
                        </div>
                        <div
                            class="absolute w-1 h-1 bg-purple-200 rounded-full animate-particle-3 shadow-lg shadow-purple-200/50">
                        </div>
                    </div>

                    <div
                        class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        <div class="absolute inset-0 rounded-xl border-2 border-indigo-200/40 animate-border-pulse">
                        </div>
                    </div>

                    <div
                        class="relative z-10 flex items-center justify-center w-5 h-5 transition-all duration-500 group-hover:scale-110">
                        <div
                            class="absolute inset-0 rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 opacity-0 group-hover:opacity-30 blur-md transition-all duration-500">
                        </div>
                        <i
                            class="fas fa-calendar-alt text-sm transition-all duration-500 group-hover:drop-shadow-[0_0_8px_rgba(99,102,241,0.4)]"></i>
                    </div>

                    <span
                        class="relative z-10 font-semibold text-sm tracking-normal transition-all duration-500 group-hover:tracking-wide group-hover:drop-shadow-[0_2px_8px_rgba(99,102,241,0.2)]">
                        Événements
                        <span
                            class="absolute -bottom-0.5 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-300 via-blue-200 to-transparent group-hover:w-full transition-all duration-500"></span>
                    </span>

                    <div
                        class="absolute bottom-0 left-0 w-full h-1 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        <div
                            class="absolute bottom-0 left-0 h-full w-0 bg-gradient-to-r from-blue-200 via-indigo-200 to-purple-200 group-hover:w-full transition-all duration-1000 ease-out blur-sm">
                        </div>
                    </div>
                </a>

                <a href="/vip/parametre" onclick="moveToTop(this); playRandomClickSound(); return false;"
                    class="group relative flex items-center space-x-3 px-4 py-4 rounded-xl bg-gradient-to-br from-slate-50 via-gray-50 to-slate-100 text-gray-800 overflow-hidden transition-all duration-500 hover:from-blue-50 hover:via-indigo-50 hover:to-purple-50 hover:text-indigo-700 hover:shadow-xl hover:shadow-indigo-100/50 hover:scale-[1.02] hover:-translate-y-0.5 border border-gray-200/50 hover:border-indigo-200/50">

                    <div
                        class="absolute inset-0 bg-gradient-to-br from-blue-50/0 via-indigo-50/0 to-purple-50/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>

                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-blue-200 via-indigo-200 to-purple-200 rounded-xl opacity-0 group-hover:opacity-20 blur-2xl transition-all duration-700 group-hover:animate-aura-breathe">
                    </div>

                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(99,102,241,0.15),transparent_50%)] opacity-0 group-hover:animate-ripple-expand">
                        </div>
                    </div>

                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-400">
                        <div
                            class="absolute w-1 h-1 bg-indigo-300 rounded-full animate-particle-1 shadow-lg shadow-indigo-300/50">
                        </div>
                        <div
                            class="absolute w-0.5 h-0.5 bg-blue-200 rounded-full animate-particle-2 shadow-lg shadow-blue-200/50">
                        </div>
                        <div
                            class="absolute w-1 h-1 bg-purple-200 rounded-full animate-particle-3 shadow-lg shadow-purple-200/50">
                        </div>
                    </div>

                    <div
                        class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        <div class="absolute inset-0 rounded-xl border-2 border-indigo-200/40 animate-border-pulse">
                        </div>
                    </div>

                    <div
                        class="relative z-10 flex items-center justify-center w-5 h-5 transition-all duration-500 group-hover:scale-110">
                        <div
                            class="absolute inset-0 rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 opacity-0 group-hover:opacity-30 blur-md transition-all duration-500">
                        </div>
                        <i
                            class="fas fa-cog text-sm transition-all duration-500 group-hover:drop-shadow-[0_0_8px_rgba(99,102,241,0.4)]"></i>
                    </div>

                    <span
                        class="relative z-10 font-semibold text-sm tracking-normal transition-all duration-500 group-hover:tracking-wide group-hover:drop-shadow-[0_2px_8px_rgba(99,102,241,0.2)]">
                        Paramètres
                        <span
                            class="absolute -bottom-0.5 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-300 via-blue-200 to-transparent group-hover:w-full transition-all duration-500"></span>
                    </span>

                    <div
                        class="absolute bottom-0 left-0 w-full h-1 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        <div
                            class="absolute bottom-0 left-0 h-full w-0 bg-gradient-to-r from-blue-200 via-indigo-200 to-purple-200 group-hover:w-full transition-all duration-1000 ease-out blur-sm">
                        </div>
                    </div>
                </a>

                <a href="/vip/help" onclick="moveToTop(this); playRandomClickSound(); return false;"
                    class="group relative flex items-center space-x-3 px-4 py-4 rounded-xl bg-gradient-to-br from-slate-50 via-gray-50 to-slate-100 text-gray-800 overflow-hidden transition-all duration-500 hover:from-blue-50 hover:via-indigo-50 hover:to-purple-50 hover:text-indigo-700 hover:shadow-xl hover:shadow-indigo-100/50 hover:scale-[1.02] hover:-translate-y-0.5 border border-gray-200/50 hover:border-indigo-200/50">

                    <div
                        class="absolute inset-0 bg-gradient-to-br from-blue-50/0 via-indigo-50/0 to-purple-50/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>

                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-blue-200 via-indigo-200 to-purple-200 rounded-xl opacity-0 group-hover:opacity-20 blur-2xl transition-all duration-700 group-hover:animate-aura-breathe">
                    </div>

                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(99,102,241,0.15),transparent_50%)] opacity-0 group-hover:animate-ripple-expand">
                        </div>
                    </div>

                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-400">
                        <div
                            class="absolute w-1 h-1 bg-indigo-300 rounded-full animate-particle-1 shadow-lg shadow-indigo-300/50">
                        </div>
                        <div
                            class="absolute w-0.5 h-0.5 bg-blue-200 rounded-full animate-particle-2 shadow-lg shadow-blue-200/50">
                        </div>
                        <div
                            class="absolute w-1 h-1 bg-purple-200 rounded-full animate-particle-3 shadow-lg shadow-purple-200/50">
                        </div>
                    </div>

                    <div
                        class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        <div class="absolute inset-0 rounded-xl border-2 border-indigo-200/40 animate-border-pulse">
                        </div>
                    </div>

                    <div
                        class="relative z-10 flex items-center justify-center w-5 h-5 transition-all duration-500 group-hover:scale-110">
                        <div
                            class="absolute inset-0 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 opacity-0 group-hover:opacity-30 blur-md transition-all duration-500">
                        </div>
                        <i
                            class="fas fa-question-circle text-sm transition-all duration-500 group-hover:drop-shadow-[0_0_8px_rgba(99,102,241,0.4)]"></i>
                    </div>

                    <span
                        class="relative z-10 font-semibold text-sm tracking-normal transition-all duration-500 group-hover:tracking-wide group-hover:drop-shadow-[0_2px_8px_rgba(99,102,241,0.2)]">
                        Centre d'aide
                        <span
                            class="absolute -bottom-0.5 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-300 via-blue-200 to-transparent group-hover:w-full transition-all duration-500"></span>
                    </span>

                    <div
                        class="absolute bottom-0 left-0 w-full h-1 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        <div
                            class="absolute bottom-0 left-0 h-full w-0 bg-gradient-to-r from-blue-200 via-indigo-200 to-purple-200 group-hover:w-full transition-all duration-1000 ease-out blur-sm">
                        </div>
                    </div>
                </a>
            </div>
        </nav>
    </div>
</aside>