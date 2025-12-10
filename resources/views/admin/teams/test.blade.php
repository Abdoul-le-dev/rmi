
 <div class="bg-white rounded-lg shadow mb-6">
            <div class="border-b border-gray-200">
                <div class="flex">
                    <button id="tabIndividual" class="tab-btn flex-1 px-6 py-4 text-sm font-medium transition flex items-center justify-center gap-2 text-indigo-600 border-b-2 border-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Ajout Individuel
                    </button>
                    <button id="tabBulk" class="tab-btn flex-1 px-6 py-4 text-sm font-medium transition flex items-center justify-center gap-2 text-gray-600 hover:text-gray-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Ajout en Masse
                    </button>
                </div>
            </div>

            <!-- Content Ajout Individuel -->
            <div id="individualContent" class="p-8">
                <div class="space-y-8">
                    <!-- Recherche par email -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Email de l'étudiant</label>
                            <div class="relative">
                                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input id="emailSearch" type="email" placeholder="Rechercher par email..." class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-800">

                               
                            </div>
                        </div>
                    </div>

                   <!-- Info étudiant trouvé -->
                    <div id="studentInfo" class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg p-6 border border-indigo-200 hidden">
                        <div class="flex items-center gap-4">
                            <!-- Avatar -->
                            <div id="studentAvatar" class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-full flex items-center justify-center text-white text-xl font-bold">
                                ?
                            </div>

                            <div class="flex-1">
                                <!-- Nom + email -->
                                <h3 id="studentName" class="font-bold text-gray-900 text-lg">Utilisateur inconnu</h3>
                                <p id="studentEmail" class="text-sm text-gray-600">email@example.com</p>
                                <p id="studentId" class="text-sm text-gray-600 hidden"></p>

                                <!-- Ligne 1 : rôle, statut, connexions -->
                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                    <span id="studentRole" class="text-xs bg-slate-100 text-slate-700 px-3 py-1 rounded-full font-medium">
                                        Rôle: user
                                    </span>
                                    <span id="studentStatus" class="text-xs bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full font-medium">
                                        Statut: Actif
                                    </span>
                                    <span id="studentLogins" class="text-xs text-gray-500">
                                        Connexions: 0
                                    </span>
                                </div>

                                <!-- Ligne 2 : fuseau horaire, date de création -->
                                <div class="flex flex-wrap items-center gap-2 mt-2 text-xs text-gray-500">
                                    <span id="studentTimezone">Fuseau horaire: -</span>
                                    <span id="studentCreatedAt">Inscrit le: -</span>
                                </div>

                                <!-- Ligne 3 : abonnement (si tu veux garder l’idée) -->
                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                    <span id="currentSub" class="text-xs bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full font-medium hidden">
                                        Abonnement actuel: -
                                    </span>
                                    <span id="expiryInfo" class="text-xs text-gray-500 hidden">
                                        Expire dans - jours
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sélection de la durée -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-4">Durée de l'abonnement à ajouter</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <button class="duration-btn p-6 rounded-xl border-2 border-gray-200 hover:border-indigo-300 bg-white transition-all" data-days="30">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-gray-800">30</div>
                                    <div class="text-sm mt-1 text-gray-600">jours</div>
                                </div>
                            </button>
                            
                            <button class="duration-btn p-6 rounded-xl border-2 border-gray-200 hover:border-indigo-300 bg-white transition-all" data-days="90">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-gray-800">90</div>
                                    <div class="text-sm mt-1 text-gray-600">jours</div>
                                </div>
                            </button>
                            <div class="p-6 rounded-xl border-2 border-gray-200 bg-white transition-all">
                                <div class="text-center">
                                    <input id="customDays" type="number" min="1" placeholder="Autre" class="w-full text-3xl font-bold text-center bg-transparent border-none outline-none text-gray-800 placeholder-gray-400">
                                    <div class="text-sm mt-1 text-gray-600">jours</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton d'action -->
                    <div class="flex justify-end">
                        <button id="addSubBtn" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-blue-500 text-white rounded-lg hover:from-indigo-700 hover:to-blue-600 transition font-medium shadow-md flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Ajouter l'abonnement
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Ajout en Masse -->
            <div id="bulkContent" class="p-8 hidden">
                <div class="space-y-8">
                    <!-- Filtre par abonnement -->
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            <h3 class="text-sm font-medium text-gray-700">Filtrer par abonnement actuel</h3>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                           
                            <button class="filter-btn px-4 py-3 rounded-lg border-2 border-gray-200 bg-white text-gray-700 font-medium text-sm hover:border-gray-300 transition" data-filter="1">
                                Abonnés 1 mois
                            </button>
                            <button class="filter-btn px-4 py-3 rounded-lg border-2 border-gray-200 bg-white text-gray-700 font-medium text-sm hover:border-gray-300 transition" data-filter="2">
                                Abonnés 2 mois
                            </button>
                            <button class="filter-btn px-4 py-3 rounded-lg border-2 border-gray-200 bg-white text-gray-700 font-medium text-sm hover:border-gray-300 transition" data-filter="3">
                                Abonnés 3 mois
                            </button>
                        </div>
                    </div>

                    <!-- Durée à ajouter -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-4">Durée à ajouter</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <button class="bulk-duration-btn px-4 py-3 rounded-lg border-2 border-gray-200 bg-white text-gray-700 font-medium text-sm hover:border-gray-300 transition" data-days="30">
                                30 jours
                            </button>
                            <button class="bulk-duration-btn px-4 py-3 rounded-lg border-2 border-gray-200 bg-white text-gray-700 font-medium text-sm hover:border-gray-300 transition" data-days="90">
                                90 jours
                            </button>
                            <input id="bulkCustomDays" type="number" min="1" placeholder="Personnalisé..." class="px-4 py-3 rounded-lg border-2 border-gray-200 text-gray-700 font-medium text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition">
                        </div>
                    </div>

                    <!-- Liste des étudiants -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">
                                        <span id="selectedCount">0</span>
                                    </div>
                                    <span class="text-sm text-gray-600">étudiant(s) sélectionné(s)</span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button id="selectAllBtn" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                    Tout sélectionner
                                </button>
                                <span class="text-gray-300">|</span>
                                <button id="deselectAllBtn" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                    Tout désélectionner
                                </button>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <div class="text-xs font-medium text-gray-500 uppercase mb-2">
                                Liste des étudiants (<span id="listCount">0</span>)
                            </div>
                        </div>

                        <div id="studentsList" class="space-y-2 max-h-96 overflow-y-auto"></div>
                    </div>

                    <!-- Bouton d'action -->
                    <div class="flex justify-end gap-3">
                        <button id="cancelBulkBtn" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                            Annuler la sélection
                        </button>
                        <button id="applyBulkBtn" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-blue-500 text-white rounded-lg hover:from-indigo-700 hover:to-blue-600 transition font-medium shadow-md flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Appliquer aux étudiants
                        </button>
                    </div>
                </div>
            </div>
        </div>
