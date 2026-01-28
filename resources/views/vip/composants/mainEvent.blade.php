<!-- Calendrier -->
<div class="flex-1 overflow-auto">
    <div class="h-full">

        <!-- En-têtes des jours -->
        <div class="calendar-grid bg-white border-b border-gray-200 sticky top-0 z-20">
            <div class="p-3 bg-gray-50"></div>

            <div class="p-3 text-center border-r border-gray-200">
                <div class="text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Lun</div>
                <div class="text-lg font-semibold text-gray-900">19</div>
            </div>

            <div class="p-3 text-center border-r border-gray-200">
                <div class="text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Mar</div>
                <div class="text-lg font-semibold text-gray-900">20</div>
            </div>

            <div class="p-3 text-center border-r border-gray-200">
                <div class="text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Mer</div>
                <div class="text-lg font-semibold text-gray-900">21</div>
            </div>

            <div class="p-3 text-center border-r border-gray-200">
                <div class="text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Jeu</div>
                <div class="text-lg font-semibold text-gray-900">22</div>
            </div>

            <div class="p-3 text-center border-r border-gray-200">
                <div class="text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Ven</div>
                <div class="text-lg font-semibold text-gray-900">23</div>
            </div>

            <div class="p-3 text-center border-r border-gray-200">
                <div class="text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Sam</div>
                <div class="text-lg font-semibold text-gray-900">24</div>
            </div>

            <div class="p-3 text-center bg-indigo-50 border-r border-indigo-200">
                <div class="text-xs font-semibold text-indigo-600 mb-1 uppercase tracking-wide">Dim</div>
                <div class="text-lg font-semibold text-indigo-600">25</div>
            </div>
        </div>

        <!-- Grille horaire -->
        <div class="calendar-grid bg-white relative">

            <!-- Heures (colonne gauche) -->
            <div class="bg-gray-50">
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">00:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">01:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">02:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">03:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">04:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">05:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">06:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">07:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">08:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">09:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">10:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">11:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">12:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">13:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">14:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">15:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">16:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">17:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">18:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">19:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">20:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">21:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">22:00</div>
                <div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">23:00</div>
            </div>

            <!-- Lundi -->
            <div class="day-column">
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot relative">
                    <!-- Événement -->
                    <div
                        class="absolute top-1 left-1 right-1 bg-blue-500 text-white rounded-lg p-2 shadow-sm hover:shadow-md cursor-pointer transition-all group">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <div class="font-semibold text-xs">Réunion Trading</div>
                                    <!-- Indicateur affiche -->
                                    <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" title="Affiche disponible">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="text-xs opacity-90">08:00 - 10:30</div>
                                <div class="text-xs bg-blue-600 bg-opacity-50 px-1.5 py-0.5 rounded mt-1 inline-block">
                                    Live Class</div>
                            </div>
                            <div class="flex flex-col gap-1">
                                <!-- Bouton Voir l'affiche -->
                                <button onmouseenter="showPosterTooltip(this, 1)" onmouseleave="hidePosterTooltip()"
                                    class="opacity-0 group-hover:opacity-100 transition-opacity p-1 hover:bg-blue-600 rounded"
                                    title="Voir l'affiche">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <!-- Bouton Ajouter à l'agenda -->
                                <button data-agenda-tooltip
                                    class="opacity-0 group-hover:opacity-100 transition-opacity p-1 hover:bg-blue-600 rounded">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot relative">
                    <!-- Événement -->
                    <div
                        class="absolute top-1 left-1 right-1 bg-purple-500 text-white rounded-lg p-2 shadow-sm hover:shadow-md cursor-pointer transition-all group">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-xs">Workshop Crypto</div>
                                <div class="text-xs opacity-90 mt-0.5">14:00 - 16:00</div>
                                <div
                                    class="text-xs bg-purple-600 bg-opacity-50 px-1.5 py-0.5 rounded mt-1 inline-block">
                                    Présentiel</div>
                            </div>
                            <div class="flex flex-col gap-1">
                                <button onmouseenter="showPosterTooltip(this, 2)" onmouseleave="hidePosterTooltip()"
                                    class="opacity-0 group-hover:opacity-100 transition-opacity p-1 hover:bg-purple-600 rounded"
                                    title="Voir l'affiche">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <button data-agenda-tooltip
                                    class="opacity-0 group-hover:opacity-100 transition-opacity p-1 hover:bg-purple-600 rounded">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
            </div>

            <!-- Mardi -->
            <div class="day-column">
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
            </div>

            <!-- Mercredi -->
            <div class="day-column">
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot relative">
                    <!-- Événement -->
                    <div
                        class="absolute top-1 left-1 right-1 bg-emerald-500 text-white rounded-lg p-2 shadow-sm hover:shadow-md cursor-pointer transition-all group">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-xs">Masterclass IA</div>
                                <div class="text-xs opacity-90 mt-0.5">09:00 - 11:30</div>
                                <div
                                    class="text-xs bg-emerald-600 bg-opacity-50 px-1.5 py-0.5 rounded mt-1 inline-block">
                                    Live Class</div>
                            </div>
                            <div class="flex flex-col gap-1">
                                <button onmouseenter="showPosterTooltip(this, 3)" onmouseleave="hidePosterTooltip()"
                                    class="opacity-0 group-hover:opacity-100 transition-opacity p-1 hover:bg-emerald-600 rounded"
                                    title="Voir l'affiche">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <button data-agenda-tooltip
                                    class="opacity-0 group-hover:opacity-100 transition-opacity p-1 hover:bg-emerald-600 rounded">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
            </div>

            <!-- Jeudi -->
            <div class="day-column">
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot relative">
                    <!-- Événement -->
                    <div
                        class="absolute top-1 left-1 right-1 bg-orange-500 text-white rounded-lg p-2 shadow-sm hover:shadow-md cursor-pointer transition-all group">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-xs">Conférence VIP</div>
                                <div class="text-xs opacity-90 mt-0.5">19:00 - 21:00</div>
                                <div
                                    class="text-xs bg-orange-600 bg-opacity-50 px-1.5 py-0.5 rounded mt-1 inline-block">
                                    Présentiel</div>
                            </div>
                            <div class="flex flex-col gap-1">
                                <button onmouseenter="showPosterTooltip(this, 4)" onmouseleave="hidePosterTooltip()"
                                    class="opacity-0 group-hover:opacity-100 transition-opacity p-1 hover:bg-orange-600 rounded"
                                    title="Voir l'affiche">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <button data-agenda-tooltip
                                    class="opacity-0 group-hover:opacity-100 transition-opacity p-1 hover:bg-orange-600 rounded">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
            </div>

            <!-- Vendredi -->
            <div class="day-column">
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
            </div>

            <!-- Samedi -->
            <div class="day-column">
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
                <div class="time-slot"></div>
            </div>

            <!-- Dimanche (jour actuel) -->
            <div class="day-column bg-indigo-50">
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100 relative">
                    <!-- Événement en cours -->
                    <div
                        class="absolute top-1 left-1 right-1 bg-red-500 text-white rounded-lg p-2 shadow-md hover:shadow-lg cursor-pointer transition-all border-2 border-red-600 group">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="font-semibold text-xs">Session Live</div>
                                    <div
                                        class="live-badge text-xs bg-red-600 px-1.5 py-0.5 rounded flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                                        En cours
                                    </div>
                                </div>
                                <div class="text-xs opacity-90">15:00 - 17:00</div>
                                <div class="text-xs bg-red-600 bg-opacity-50 px-1.5 py-0.5 rounded mt-1 inline-block">
                                    Live Class</div>
                            </div>
                            <div class="flex flex-col gap-1">
                                <button onmouseenter="showPosterTooltip(this, 5)" onmouseleave="hidePosterTooltip()"
                                    class="opacity-0 group-hover:opacity-100 transition-opacity p-1 hover:bg-red-600 rounded"
                                    title="Voir l'affiche">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <button data-agenda-tooltip
                                    class="opacity-0 group-hover:opacity-100 transition-opacity p-1 hover:bg-red-600 rounded">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
                <div class="time-slot border-indigo-100"></div>
            </div>

        </div>
    </div>
</div>