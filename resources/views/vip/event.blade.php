<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RMClass - Calendrier Événements</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* CSS minimal - uniquement pour la grille */
        .calendar-grid {
            display: grid;
            grid-template-columns: 60px repeat(7, 1fr);
            min-width: 900px;
        }
        
        @media (max-width: 768px) {
            .calendar-grid {
                grid-template-columns: 50px repeat(7, minmax(100px, 1fr));
                min-width: 750px;
            }
        }
        
        .time-slot {
            border-right: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
            min-height: 64px;
        }
        
        .day-column {
            position: relative;
        }

        /* Modal */
        .modal-backdrop {
            backdrop-filter: blur(4px);
            transition: opacity 0.2s ease;
        }

        .modal-content {
            transform: scale(0.95);
            opacity: 0;
            transition: all 0.2s ease;
        }

        .modal-backdrop.active .modal-content {
            transform: scale(1);
            opacity: 1;
        }

        /* Animation événement en cours */
        @keyframes pulse-live {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        .live-badge {
            animation: pulse-live 2s ease-in-out infinite;
        }

        /* Layout avec sidebar */
        .app-layout {
            display: grid;
            grid-template-columns: 1fr;
            grid-template-rows: auto 1fr auto;
            min-height: 100vh;
        }

        .app-layout.with-sidebar {
            grid-template-columns: 250px 1fr;
            grid-template-areas: 
                "sidebar header"
                "sidebar main"
                "sidebar footer";
        }

        .app-sidebar {
            grid-area: sidebar;
        }

        .app-header {
            grid-area: header;
        }

        .app-main {
            grid-area: main;
        }

        .app-footer {
            grid-area: footer;
        }

        @media (max-width: 1024px) {
            .app-layout.with-sidebar {
                grid-template-columns: 1fr;
                grid-template-areas: 
                    "header"
                    "main"
                    "footer";
            }
            
            .app-sidebar {
                position: fixed;
                left: -250px;
                top: 0;
                bottom: 0;
                width: 250px;
                z-index: 40;
                transition: left 0.3s ease;
            }
            
            .app-sidebar.open {
                left: 0;
            }
        }

        /* Message vide */
        .empty-state {
            opacity: 0;
            animation: fadeIn 0.3s ease forwards;
        }

        @keyframes fadeIn {
            to { opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Layout principal -->
    <div class="app-layout">
        
        <!-- Sidebar (optionnelle) -->
        <!-- <aside class="app-sidebar bg-white border-r border-gray-200 hidden">
            Contenu sidebar
        </aside> -->

        <!-- Header -->
        <header class="app-header bg-white border-b border-gray-200 px-4 md:px-6 py-3 md:py-4">
            <div class="flex items-center justify-between gap-4">
                <!-- Gauche -->
                <div class="flex items-center gap-2 md:gap-4">
                    <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors md:hidden">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <h1 class="text-lg md:text-xl font-bold text-gray-900">Calendrier</h1>
                    
                    <!-- Navigation temporelle -->
                    <div class="hidden sm:flex items-center gap-2">
                        <!-- Année -->
                        <select id="yearSelector" onchange="changeYear()" class="px-2 py-1 text-sm border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 bg-white">
                            <option value="2026" selected>2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                            <option value="2029">2029</option>
                            <option value="2030">2030</option>
                        </select>
                        
                        <!-- Semaine précédente -->
                        <button onclick="previousPeriod()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        
                        <span id="currentPeriod" class="text-sm font-medium text-gray-700 whitespace-nowrap min-w-[150px] text-center">19 - 25 Janvier 2026</span>
                        
                        <!-- Semaine suivante -->
                        <button onclick="nextPeriod()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        
                        <button onclick="goToToday()" class="px-3 py-1.5 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors ml-2">
                            Aujourd'hui
                        </button>
                    </div>
                </div>
                
                <!-- Droite -->
                <div class="flex items-center gap-2 md:gap-3">
                    <!-- Sélecteur de vue -->
                    <select id="viewSelector" onchange="changeView()" class="hidden md:block px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 bg-white">
                        <option value="day">Jour</option>
                        <option value="week" selected>Semaine</option>
                        <option value="month">Mois</option>
                    </select>
                    
                    <!-- Bouton Nouvel événement -->
                    <button onclick="openEventModal()" class="px-3 md:px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="hidden sm:inline">Nouvel événement</span>
                        <span class="sm:hidden">Nouveau</span>
                    </button>
                    
                    <button class="hidden md:block p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Navigation mobile -->
            <div class="sm:hidden flex items-center justify-between mt-3 pt-3 border-t border-gray-200">
                <button onclick="previousPeriod()" class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <span id="currentPeriodMobile" class="text-sm font-medium text-gray-700">19 - 25 Jan 2026</span>
                <button onclick="nextPeriod()" class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </header>

        <!-- Calendrier -->
        <div class="app-main overflow-auto">
            <div class="h-full" id="calendarContainer">
                <!-- Le calendrier sera généré dynamiquement ici -->
            </div>
        </div>

        <!-- Footer -->
        <div class="app-footer bg-indigo-600 text-white text-center py-3 px-4 text-xs md:text-sm font-medium">
            Synchronisez votre agenda personnel avec RMClass
        </div>

    </div>

    <!-- Modal Nouvel Événement -->
    <div id="eventModal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="modal-content bg-white rounded-xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
            <!-- Header Modal -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">Nouvel événement</h2>
                <button onclick="closeEventModal()" class="p-1 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Corps Modal -->
            <div class="p-6 space-y-4">
                <!-- Titre de l'événement -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Titre de l'événement
                    </label>
                    <input 
                        type="text" 
                        id="eventTitle" 
                        placeholder="Ex: Masterclass Trading"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >
                </div>

                <!-- Affiche de l'événement -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Affiche de l'événement (optionnel)
                    </label>
                    <div class="relative">
                        <input 
                            type="file" 
                            id="eventPoster" 
                            accept="image/*"
                            class="hidden"
                            onchange="previewPoster()"
                        >
                        <button 
                            type="button"
                            onclick="document.getElementById('eventPoster').click()"
                            class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 transition-all text-sm text-gray-600 hover:text-indigo-600 flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span id="posterLabel">Ajouter une affiche</span>
                        </button>
                        
                        <!-- Prévisualisation -->
                        <div id="posterPreview" class="hidden mt-3 relative group">
                            <img id="posterImage" class="w-full h-40 object-cover rounded-lg border border-gray-200">
                            <button 
                                type="button"
                                onclick="removePoster()"
                                class="absolute top-2 right-2 p-1.5 bg-red-500 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Couleur de l'événement -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Couleur de l'événement
                    </label>
                    <div class="flex gap-2 flex-wrap">
                        <button 
                            type="button"
                            onclick="selectEventColor('blue')" 
                            id="colorBlue"
                            class="event-color-btn w-10 h-10 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 border-2 border-gray-300 hover:border-blue-700 hover:scale-110 transition-all"
                            title="Bleu"
                        ></button>
                        
                        <button 
                            type="button"
                            onclick="selectEventColor('purple')" 
                            id="colorPurple"
                            class="event-color-btn w-10 h-10 rounded-lg bg-gradient-to-br from-purple-400 to-purple-600 border-2 border-gray-300 hover:border-purple-700 hover:scale-110 transition-all"
                            title="Violet"
                        ></button>
                        
                        <button 
                            type="button"
                            onclick="selectEventColor('emerald')" 
                            id="colorEmerald"
                            class="event-color-btn w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-400 to-emerald-600 border-2 border-gray-300 hover:border-emerald-700 hover:scale-110 transition-all"
                            title="Vert"
                        ></button>
                        
                        <button 
                            type="button"
                            onclick="selectEventColor('orange')" 
                            id="colorOrange"
                            class="event-color-btn w-10 h-10 rounded-lg bg-gradient-to-br from-orange-400 to-orange-600 border-2 border-gray-300 hover:border-orange-700 hover:scale-110 transition-all"
                            title="Orange"
                        ></button>
                        
                        <button 
                            type="button"
                            onclick="selectEventColor('red')" 
                            id="colorRed"
                            class="event-color-btn w-10 h-10 rounded-lg bg-gradient-to-br from-red-400 to-red-600 border-2 border-gray-300 hover:border-red-700 hover:scale-110 transition-all"
                            title="Rouge"
                        ></button>
                        
                        <button 
                            type="button"
                            onclick="selectEventColor('pink')" 
                            id="colorPink"
                            class="event-color-btn w-10 h-10 rounded-lg bg-gradient-to-br from-pink-400 to-pink-600 border-2 border-gray-300 hover:border-pink-700 hover:scale-110 transition-all"
                            title="Rose"
                        ></button>
                        
                        <button 
                            type="button"
                            onclick="selectEventColor('teal')" 
                            id="colorTeal"
                            class="event-color-btn w-10 h-10 rounded-lg bg-gradient-to-br from-teal-400 to-teal-600 border-2 border-gray-300 hover:border-teal-700 hover:scale-110 transition-all"
                            title="Turquoise"
                        ></button>
                        
                        <button 
                            type="button"
                            onclick="selectEventColor('amber')" 
                            id="colorAmber"
                            class="event-color-btn w-10 h-10 rounded-lg bg-gradient-to-br from-amber-400 to-amber-600 border-2 border-gray-300 hover:border-amber-700 hover:scale-110 transition-all"
                            title="Ambre"
                        ></button>
                    </div>
                </div>

                <!-- Type d'événement -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Type d'événement
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <button 
                            type="button"
                            onclick="selectEventType('live')" 
                            id="typeLive"
                            class="event-type-btn px-4 py-3 border-2 border-gray-300 rounded-lg hover:border-indigo-500 transition-all text-sm font-medium flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Live Class
                        </button>
                        
                        <button 
                            type="button"
                            onclick="selectEventType('presentiel')" 
                            id="typePresentiel"
                            class="event-type-btn px-4 py-3 border-2 border-gray-300 rounded-lg hover:border-indigo-500 transition-all text-sm font-medium flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Présentiel
                        </button>
                    </div>
                </div>

                <!-- Date et heure -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Date
                        </label>
                        <input 
                            type="date" 
                            id="eventDate" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Heure
                        </label>
                        <input 
                            type="time" 
                            id="eventTime" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >
                    </div>
                </div>

                <!-- Durée -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Durée (heures)
                    </label>
                    <select 
                        id="eventDuration" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >
                        <option value="0.5">30 minutes</option>
                        <option value="1">1 heure</option>
                        <option value="1.5">1h30</option>
                        <option value="2" selected>2 heures</option>
                        <option value="2.5">2h30</option>
                        <option value="3">3 heures</option>
                        <option value="4">4 heures</option>
                    </select>
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="flex gap-3 p-6 border-t border-gray-200">
                <button 
                    onclick="closeEventModal()" 
                    class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                >
                    Annuler
                </button>
                <button 
                    onclick="createEvent()" 
                    class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium"
                >
                    Créer l'événement
                </button>
            </div>
        </div>
    </div>

    <!-- Tooltip Preview Affiche -->
    <div id="posterPreviewTooltip" class="fixed hidden z-[100] pointer-events-none">
        <div class="bg-white rounded-lg shadow-2xl border-2 border-gray-200 overflow-hidden" style="width: 250px;">
            <img id="posterPreviewImage" class="w-full h-auto" alt="Aperçu affiche">
            <div class="p-3 bg-gray-50 border-t border-gray-200">
                <h4 id="posterPreviewTitle" class="text-sm font-bold text-gray-900 mb-1 truncate"></h4>
                <p id="posterPreviewInfo" class="text-xs text-gray-600"></p>
            </div>
        </div>
    </div>

    <script>
        // Variables globales
        let currentDate = new Date(2026, 0, 25); // 25 janvier 2026 (dimanche)
        let currentView = 'week';
        let selectedEventType = null;
        let selectedEventColor = 'blue';
        let eventPosterData = null;
        let events = [
            { id: 1, title: 'Réunion Trading', type: 'live', date: '2026-01-19', time: '08:00', duration: 2.5, color: 'blue', poster: null },
            { id: 2, title: 'Workshop Crypto', type: 'presentiel', date: '2026-01-19', time: '14:00', duration: 2, color: 'purple', poster: null },
            { id: 3, title: 'Masterclass IA', type: 'live', date: '2026-01-21', time: '09:00', duration: 2.5, color: 'emerald', poster: null },
            { id: 4, title: 'Conférence VIP', type: 'presentiel', date: '2026-01-22', time: '19:00', duration: 2, color: 'orange', poster: null },
            { id: 5, title: 'Session Live', type: 'live', date: '2026-01-25', time: '15:00', duration: 2, color: 'red', isLive: true, poster: null }
        ];

        // Initialisation
        document.addEventListener('DOMContentLoaded', () => {
            renderCalendar();
        });

        // Fonction pour obtenir les événements d'une période donnée
        function getEventsForPeriod(startDate, endDate) {
            return events.filter(event => {
                const eventDate = new Date(event.date);
                return eventDate >= startDate && eventDate <= endDate;
            });
        }

        // Fonction pour vérifier si un événement est en cours
        function isEventLive(event) {
            const now = new Date(2026, 0, 25, 15, 30); // Simule l'heure actuelle
            const eventDate = new Date(event.date);
            const [hours, minutes] = event.time.split(':').map(Number);
            const eventStart = new Date(eventDate.setHours(hours, minutes));
            const eventEnd = new Date(eventStart.getTime() + event.duration * 60 * 60 * 1000);
            
            return now >= eventStart && now <= eventEnd;
        }

        // Rendu du calendrier
        function renderCalendar() {
            const container = document.getElementById('calendarContainer');
            const startOfWeek = getStartOfWeek(currentDate);
            const endOfWeek = new Date(startOfWeek);
            endOfWeek.setDate(startOfWeek.getDate() + 6);
            
            const weekEvents = getEventsForPeriod(startOfWeek, endOfWeek);
            
            // Générer l'en-tête des jours
            const daysHeader = generateDaysHeader(startOfWeek);
            
            // Générer la grille
            const calendarGrid = generateCalendarGrid(startOfWeek, weekEvents);
            
            container.innerHTML = daysHeader + calendarGrid;
            
            // Réinitialiser les tooltips
            setTimeout(setupAgendaTooltips, 100);
        }

        // Générer l'en-tête des jours
        function generateDaysHeader(startDate) {
            const days = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
            const today = new Date(2026, 0, 25);
            
            let html = '<div class="calendar-grid bg-white border-b border-gray-200 sticky top-0 z-20">';
            html += '<div class="p-3 bg-gray-50"></div>';
            
            for (let i = 0; i < 7; i++) {
                const currentDay = new Date(startDate);
                currentDay.setDate(startDate.getDate() + i);
                const isToday = currentDay.toDateString() === today.toDateString();
                const isSunday = i === 6;
                
                html += `<div class="p-3 text-center border-r ${isSunday ? 'bg-indigo-50 border-indigo-200' : 'border-gray-200'}">`;
                html += `<div class="text-xs font-medium ${isSunday ? 'text-indigo-600 font-semibold' : 'text-gray-500'} mb-1 uppercase tracking-wide">${days[i]}</div>`;
                html += `<div class="text-lg font-semibold ${isSunday ? 'text-indigo-600' : 'text-gray-900'} ${isToday ? 'bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center mx-auto' : ''}">${currentDay.getDate()}</div>`;
                html += '</div>';
            }
            
            html += '</div>';
            return html;
        }

        // Générer la grille du calendrier
        function generateCalendarGrid(startDate, weekEvents) {
            let html = '<div class="calendar-grid bg-white relative">';
            
            // Colonne des heures
            html += '<div class="bg-gray-50">';
            for (let hour = 0; hour < 24; hour++) {
                html += `<div class="time-slot px-2 py-2 text-right text-xs text-gray-500 font-medium">${hour.toString().padStart(2, '0')}:00</div>`;
            }
            html += '</div>';
            
            // Colonnes des jours
            for (let dayIndex = 0; dayIndex < 7; dayIndex++) {
                const currentDay = new Date(startDate);
                currentDay.setDate(startDate.getDate() + dayIndex);
                const dateString = currentDay.toISOString().split('T')[0];
                const today = new Date(2026, 0, 25);
                const isToday = currentDay.toDateString() === today.toDateString();
                const isSunday = dayIndex === 6;
                
                // Événements du jour
                const dayEvents = weekEvents.filter(e => e.date === dateString);
                
                html += `<div class="day-column ${isSunday ? 'bg-indigo-50' : ''}">`;
                
                for (let hour = 0; hour < 24; hour++) {
                    const hourEvents = dayEvents.filter(e => {
                        const [eventHour] = e.time.split(':').map(Number);
                        return eventHour === hour;
                    });
                    
                    html += `<div class="time-slot ${isSunday ? 'border-indigo-100' : ''} relative">`;
                    
                    // Ajouter les événements
                    hourEvents.forEach(event => {
                        const isLive = isEventLive(event);
                        html += generateEventHTML(event, isLive);
                    });
                    
                    html += '</div>';
                }
                
                html += '</div>';
            }
            
            html += '</div>';
            return html;
        }

        // Générer le HTML d'un événement
        function generateEventHTML(event, isLive = false) {
            const colorClasses = {
                'blue': 'bg-blue-500',
                'purple': 'bg-purple-500',
                'emerald': 'bg-emerald-500',
                'orange': 'bg-orange-500',
                'red': 'bg-red-500',
                'pink': 'bg-pink-500',
                'teal': 'bg-teal-500',
                'amber': 'bg-amber-500'
            };
            
            const hoverColors = {
                'blue': 'hover:bg-blue-600',
                'purple': 'hover:bg-purple-600',
                'emerald': 'hover:bg-emerald-600',
                'orange': 'hover:bg-orange-600',
                'red': 'hover:bg-red-600',
                'pink': 'hover:bg-pink-600',
                'teal': 'hover:bg-teal-600',
                'amber': 'hover:bg-amber-600'
            };
            
            const endTime = calculateEndTime(event.time, event.duration);
            
            let html = `<div class="absolute top-1 left-1 right-1 ${colorClasses[event.color]} text-white rounded-lg p-2 shadow-sm hover:shadow-md cursor-pointer transition-all ${isLive ? 'border-2 border-' + event.color + '-600' : ''} group">`;
            html += '<div class="flex items-start justify-between gap-2">';
            html += '<div class="flex-1 min-w-0">';
            
            // Titre avec indicateur affiche si présent
            html += '<div class="flex items-center gap-1.5 mb-1">';
            html += `<div class="font-semibold text-xs">${event.title}</div>`;
            if (event.poster) {
                html += '<svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Affiche disponible"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>';
            }
            html += '</div>';
            
            html += `<div class="text-xs opacity-90">${event.time} - ${endTime}</div>`;
            
            // Badge type avec animation si en cours
            if (isLive) {
                html += `<div class="live-badge text-xs bg-${event.color}-600 px-1.5 py-0.5 rounded mt-1 inline-flex items-center gap-1">`;
                html += '<span class="w-1.5 h-1.5 bg-white rounded-full"></span>';
                html += 'En cours';
                html += '</div>';
            } else {
                html += `<div class="text-xs bg-${event.color}-600 bg-opacity-50 px-1.5 py-0.5 rounded mt-1 inline-block">${event.type === 'live' ? 'Live Class' : 'Présentiel'}</div>`;
            }
            
            html += '</div>';
            
            // Boutons d'action
            html += '<div class="flex flex-col gap-1">';
            
            // Bouton Voir l'affiche (uniquement si affiche existe)
            if (event.poster) {
                html += `<button onmouseenter="showPosterTooltip(this, ${event.id})" onmouseleave="hidePosterTooltip()" class="opacity-0 group-hover:opacity-100 transition-opacity p-1 ${hoverColors[event.color]} rounded" title="Voir l'affiche">`;
                html += '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>';
                html += '</button>';
            }
            
            // Bouton Ajouter à l'agenda
            html += `<button data-agenda-tooltip class="opacity-0 group-hover:opacity-100 transition-opacity p-1 ${hoverColors[event.color]} rounded">`;
            html += '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>';
            html += '</button>';
            
            html += '</div>';
            html += '</div>';
            html += '</div>';
            
            return html;
        }

        // Calculer l'heure de fin
        function calculateEndTime(startTime, duration) {
            const [hours, minutes] = startTime.split(':').map(Number);
            const totalMinutes = hours * 60 + minutes + duration * 60;
            const endHours = Math.floor(totalMinutes / 60) % 24;
            const endMinutes = totalMinutes % 60;
            return `${endHours.toString().padStart(2, '0')}:${endMinutes.toString().padStart(2, '0')}`;
        }

        // Obtenir le début de la semaine
        function getStartOfWeek(date) {
            const result = new Date(date);
            const day = result.getDay();
            const diff = result.getDate() - day + (day === 0 ? -6 : 1);
            result.setDate(diff);
            result.setHours(0, 0, 0, 0);
            return result;
        }

        // Modal événement
        function openEventModal() {
            const modal = document.getElementById('eventModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'active');
            document.body.style.overflow = 'hidden';
            
            // Réinitialiser
            document.getElementById('eventTitle').value = '';
            document.getElementById('eventDate').value = '';
            document.getElementById('eventTime').value = '';
            selectedEventType = null;
            selectedEventColor = 'blue';
            eventPosterData = null;
            
            document.querySelectorAll('.event-type-btn').forEach(btn => {
                btn.classList.remove('border-indigo-600', 'bg-indigo-50', 'text-indigo-600');
                btn.classList.add('border-gray-300');
            });
            
            document.querySelectorAll('.event-color-btn').forEach(btn => {
                btn.classList.remove('border-4', 'ring-4', 'ring-offset-2', 'ring-blue-300', 'ring-purple-300', 'ring-emerald-300', 'ring-orange-300', 'ring-red-300', 'ring-pink-300', 'ring-teal-300', 'ring-amber-300');
                btn.classList.add('border-2');
            });
            
            const blueBtn = document.getElementById('colorBlue');
            blueBtn.classList.remove('border-2');
            blueBtn.classList.add('border-4', 'ring-4', 'ring-offset-2', 'ring-blue-300');
            
            document.getElementById('posterPreview').classList.add('hidden');
            document.getElementById('posterLabel').textContent = 'Ajouter une affiche';
        }

        function previewPoster() {
            const input = document.getElementById('eventPoster');
            const preview = document.getElementById('posterPreview');
            const image = document.getElementById('posterImage');
            const label = document.getElementById('posterLabel');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    eventPosterData = e.target.result;
                    image.src = e.target.result;
                    preview.classList.remove('hidden');
                    label.textContent = '✓ Affiche ajoutée';
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removePoster() {
            eventPosterData = null;
            document.getElementById('eventPoster').value = '';
            document.getElementById('posterPreview').classList.add('hidden');
            document.getElementById('posterLabel').textContent = 'Ajouter une affiche';
        }

        function showEventPoster(eventId) {
            const event = events.find(e => e.id === eventId);
            if (!event) return;
            
            const tooltip = document.getElementById('posterPreviewTooltip');
            const image = document.getElementById('posterPreviewImage');
            const title = document.getElementById('posterPreviewTitle');
            const info = document.getElementById('posterPreviewInfo');
            
            if (!event.poster) {
                const canvas = document.createElement('canvas');
                canvas.width = 400;
                canvas.height = 500;
                const ctx = canvas.getContext('2d');
                
                const colorMap = {
                    'blue': ['#3b82f6', '#1e40af'],
                    'purple': ['#a855f7', '#7e22ce'],
                    'emerald': ['#10b981', '#047857'],
                    'orange': ['#f97316', '#c2410c'],
                    'red': ['#ef4444', '#b91c1c'],
                    'pink': ['#ec4899', '#be185d'],
                    'teal': ['#14b8a6', '#0f766e'],
                    'amber': ['#f59e0b', '#d97706']
                };
                
                const [color1, color2] = colorMap[event.color] || ['#3b82f6', '#1e40af'];
                const gradient = ctx.createLinearGradient(0, 0, 400, 500);
                gradient.addColorStop(0, color1);
                gradient.addColorStop(1, color2);
                ctx.fillStyle = gradient;
                ctx.fillRect(0, 0, 400, 500);
                
                ctx.fillStyle = 'rgba(255, 255, 255, 0.1)';
                ctx.font = 'bold 80px Inter, sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText('RM', 200, 150);
                
                ctx.fillStyle = '#ffffff';
                ctx.font = 'bold 40px Inter, sans-serif';
                ctx.fillText(event.title, 200, 280);
                
                ctx.font = '24px Inter, sans-serif';
                ctx.fillText(event.type === 'live' ? 'Live Class' : 'Présentiel', 200, 330);
                
                ctx.font = '20px Inter, sans-serif';
                ctx.fillText(`${event.time} • ${event.duration}h`, 200, 380);
                
                image.src = canvas.toDataURL();
            } else {
                image.src = event.poster;
            }
            
            title.textContent = event.title;
            info.textContent = `${event.type === 'live' ? 'Live Class' : 'Présentiel'} • ${event.time}`;
        }

        function showPosterTooltip(button, eventId) {
            showEventPoster(eventId);
            const tooltip = document.getElementById('posterPreviewTooltip');
            const rect = button.getBoundingClientRect();
            
            tooltip.style.left = (rect.right + 15) + 'px';
            tooltip.style.top = (rect.top - 100) + 'px';
            
            const tooltipRect = tooltip.getBoundingClientRect();
            if (tooltipRect.right > window.innerWidth) {
                tooltip.style.left = (rect.left - 265) + 'px';
            }
            
            tooltip.classList.remove('hidden');
        }

        function hidePosterTooltip() {
            const tooltip = document.getElementById('posterPreviewTooltip');
            tooltip.classList.add('hidden');
        }

        function closeEventModal() {
            const modal = document.getElementById('eventModal');
            modal.classList.remove('active');
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 200);
        }

        function selectEventType(type) {
            selectedEventType = type;
            document.querySelectorAll('.event-type-btn').forEach(btn => {
                btn.classList.remove('border-indigo-600', 'bg-indigo-50', 'text-indigo-600');
                btn.classList.add('border-gray-300');
            });
            
            const selectedBtn = document.getElementById(`type${type.charAt(0).toUpperCase() + type.slice(1)}`);
            selectedBtn.classList.remove('border-gray-300');
            selectedBtn.classList.add('border-indigo-600', 'bg-indigo-50', 'text-indigo-600');
        }

        function selectEventColor(color) {
            selectedEventColor = color;
            document.querySelectorAll('.event-color-btn').forEach(btn => {
                btn.classList.remove('border-4', 'ring-4', 'ring-offset-2');
                btn.classList.add('border-2');
            });
            
            const selectedBtn = document.getElementById(`color${color.charAt(0).toUpperCase() + color.slice(1)}`);
            selectedBtn.classList.remove('border-2');
            selectedBtn.classList.add('border-4', 'ring-4', 'ring-offset-2');
            
            const colorRings = {
                'blue': 'ring-blue-300',
                'purple': 'ring-purple-300',
                'emerald': 'ring-emerald-300',
                'orange': 'ring-orange-300',
                'red': 'ring-red-300',
                'pink': 'ring-pink-300',
                'teal': 'ring-teal-300',
                'amber': 'ring-amber-300'
            };
            
            selectedBtn.classList.add(colorRings[color]);
        }

        function createEvent() {
            const title = document.getElementById('eventTitle').value.trim();
            const date = document.getElementById('eventDate').value;
            const time = document.getElementById('eventTime').value;
            const duration = parseFloat(document.getElementById('eventDuration').value);

            if (!title || !selectedEventType || !date || !time) {
                alert('Veuillez remplir tous les champs obligatoires');
                return;
            }

            const newEvent = {
                id: Date.now(),
                title,
                type: selectedEventType,
                date,
                time,
                duration,
                color: selectedEventColor,
                isLive: false,
                poster: eventPosterData
            };

            events.push(newEvent);
            closeEventModal();
            renderCalendar();
            
            showNotification(eventPosterData ? 'Événement créé avec affiche !' : 'Événement créé avec succès !');
        }

        function changeYear() {
            const year = parseInt(document.getElementById('yearSelector').value);
            currentDate.setFullYear(year);
            renderCalendar();
            updatePeriodDisplay();
        }

        function previousPeriod() {
            if (currentView === 'week') {
                currentDate.setDate(currentDate.getDate() - 7);
            } else if (currentView === 'day') {
                currentDate.setDate(currentDate.getDate() - 1);
            } else if (currentView === 'month') {
                currentDate.setMonth(currentDate.getMonth() - 1);
            }
            renderCalendar();
            updatePeriodDisplay();
        }

        function nextPeriod() {
            if (currentView === 'week') {
                currentDate.setDate(currentDate.getDate() + 7);
            } else if (currentView === 'day') {
                currentDate.setDate(currentDate.getDate() + 1);
            } else if (currentView === 'month') {
                currentDate.setMonth(currentDate.getMonth() + 1);
            }
            renderCalendar();
            updatePeriodDisplay();
        }

        function goToToday() {
            currentDate = new Date(2026, 0, 25);
            document.getElementById('yearSelector').value = '2026';
            renderCalendar();
            updatePeriodDisplay();
        }

        function changeView() {
            currentView = document.getElementById('viewSelector').value;
            renderCalendar();
            updatePeriodDisplay();
        }

        function updatePeriodDisplay() {
            const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            const monthsShort = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
            
            if (currentView === 'week') {
                const startOfWeek = getStartOfWeek(currentDate);
                const endOfWeek = new Date(startOfWeek);
                endOfWeek.setDate(startOfWeek.getDate() + 6);
                
                const periodText = `${startOfWeek.getDate()} - ${endOfWeek.getDate()} ${months[endOfWeek.getMonth()]} ${endOfWeek.getFullYear()}`;
                const periodTextMobile = `${startOfWeek.getDate()} - ${endOfWeek.getDate()} ${monthsShort[endOfWeek.getMonth()]} ${endOfWeek.getFullYear()}`;
                
                document.getElementById('currentPeriod').textContent = periodText;
                document.getElementById('currentPeriodMobile').textContent = periodTextMobile;
            }
        }

        function showNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            notification.style.animation = 'fadeIn 0.3s ease';
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                notification.style.transition = 'all 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        document.getElementById('eventModal').addEventListener('click', (e) => {
            if (e.target.id === 'eventModal') {
                closeEventModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeEventModal();
            }
        });

        function setupAgendaTooltips() {
            document.querySelectorAll('[data-agenda-tooltip]').forEach(el => {
                el.addEventListener('mouseenter', (e) => {
                    const tooltip = document.createElement('div');
                    tooltip.className = 'fixed bg-gray-900 text-white text-xs px-3 py-2 rounded-lg z-50 pointer-events-none';
                    tooltip.textContent = 'Bientôt disponible';
                    tooltip.id = 'agenda-tooltip';
                    
                    const rect = el.getBoundingClientRect();
                    tooltip.style.top = (rect.top - 40) + 'px';
                    tooltip.style.left = (rect.left + rect.width / 2) + 'px';
                    tooltip.style.transform = 'translateX(-50%)';
                    
                    document.body.appendChild(tooltip);
                });
                
                el.addEventListener('mouseleave', () => {
                    const tooltip = document.getElementById('agenda-tooltip');
                    if (tooltip) tooltip.remove();
                });
            });
        }
    </script>

</body>
</html>